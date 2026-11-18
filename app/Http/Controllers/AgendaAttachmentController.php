<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Html as SpreadsheetHtmlWriter;
use ZipArchive;

class AgendaAttachmentController extends Controller
{
    /**
     * Memeriksa apakah pengguna memiliki hak akses internal (Pimpinan, Admin, atau Pegawai).
     */
    public static function canAccessInternal(Request $request): bool
    {
        $user = Auth::user();

        // 1. Cek Admin / Pimpinan aktif
        if ($user && in_array($user->role, ['admin', 'pimpinan'], true) && $user->is_active) {
            return true;
        }

        // 2. Cek Sesi Pegawai BAPPERIDA
        if ($request->hasSession() && $request->session()->get('staff_authenticated') === true) {
            return true;
        }

        // Cek juga helper session() jika request belum di-bind session store
        if (function_exists('session') && session('staff_authenticated') === true) {
            return true;
        }

        return false;
    }

    /**
     * Memeriksa apakah request boleh mengakses attachment tertentu.
     */
    protected function authorizeAttachmentAccess(Request $request, AgendaAttachment $attachment): void
    {
        // Jika file berstatus internal, pastikan pengguna adalah Pegawai, Admin, atau Pimpinan
        if ($attachment->access_type === 'internal') {
            if (!$this->canAccessInternal($request)) {
                abort(403, 'Akses ditolak. Lampiran ini bersifat INTERNAL untuk Pegawai BAPPERIDA.');
            }
        }
    }

    /**
     * Mengunggah lampiran baru untuk agenda tertentu (Khusus Admin / Pimpinan).
     */
    public function store(Request $request, $agendaId)
    {
        $request->validate([
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'max:10240', // 10 MB
            ],
            'access_types' => ['nullable', 'array'],
            'access_types.*' => ['nullable', 'in:public,internal'],
        ], [
            'attachments.max' => 'Maksimal 5 file lampiran dalam satu kali unggah.',
            'attachments.*.mimes' => 'Format file yang diperbolehkan hanya PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, dan PNG.',
            'attachments.*.max' => 'Ukuran setiap file maksimal 10 MB.',
        ]);

        $agenda = Agenda::findOrFail($agendaId);

        // Periksa total file yang sudah ada
        $currentCount = $agenda->attachments()->count();
        $incomingCount = count($request->file('attachments'));

        if ($currentCount + $incomingCount > 10) { // Toleransi wajar atau 5 jika agenda baru
            // Jika ada lebih dari batas 5, batasi sesuai aturan
        }

        $accessTypes = $request->input('access_types', []);
        $files = $request->file('attachments');

        $uploaded = [];

        foreach ($files as $index => $file) {
            $originalName = basename($file->getClientOriginalName());
            $extension = strtolower($file->getClientOriginalExtension());
            $storedName = Str::uuid() . '.' . $extension;

            $path = $file->storeAs(
                'agenda-attachments/' . $agenda->id,
                $storedName,
                'local'
            );

            // Tentukan status akses untuk file ini (default: public)
            $accessType = isset($accessTypes[$index]) && in_array($accessTypes[$index], ['public', 'internal'], true)
                ? $accessTypes[$index]
                : 'public';

            $att = $agenda->attachments()->create([
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'access_type' => $accessType,
                'uploaded_by' => Auth::user()?->name ?? $request->session()->get('booking_pemesan', 'Administrator'),
            ]);

            $uploaded[] = $att;
        }

        return response()->json([
            'success' => true,
            'message' => 'Lampiran agenda berhasil diunggah.',
            'attachments' => $agenda->attachments()->latest()->get(),
        ]);
    }

    /**
     * Download lampiran dengan pemeriksaan hak akses publik / internal.
     */
    public function download(Request $request, $id)
    {
        $attachment = AgendaAttachment::findOrFail($id);

        $this->authorizeAttachmentAccess($request, $attachment);

        abort_unless(
            Storage::disk('local')->exists($attachment->file_path),
            404,
            'File lampiran tidak ditemukan di penyimpanan server.'
        );

        return Storage::disk('local')->download(
            $attachment->file_path,
            $attachment->original_name
        );
    }

    /**
     * Preview lampiran secara inline / interaktif di browser.
     */
    public function preview(Request $request, $id)
    {
        $attachment = AgendaAttachment::findOrFail($id);

        $this->authorizeAttachmentAccess($request, $attachment);

        abort_unless(
            Storage::disk('local')->exists($attachment->file_path),
            404,
            'File lampiran tidak ditemukan di penyimpanan server.'
        );

        $filePath = Storage::disk('local')->path($attachment->file_path);
        $extension = strtolower(pathinfo($attachment->original_name, PATHINFO_EXTENSION));

        // 1. PDF -> Tampilkan langsung inline dalam browser PDF viewer
        if ($extension === 'pdf') {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . addslashes($attachment->original_name) . '"',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        // 2. GAMBAR (JPG, JPEG, PNG) -> Tampilkan langsung inline
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $mime = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                default => 'image/jpeg',
            };

            return response()->file($filePath, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . addslashes($attachment->original_name) . '"',
            ]);
        }

        // 3. EXCEL (XLS, XLSX) -> Render HTML preview menggunakan PhpSpreadsheet
        if (in_array($extension, ['xls', 'xlsx'], true)) {
            try {
                $spreadsheet = IOFactory::load($filePath);
                $writer = new SpreadsheetHtmlWriter($spreadsheet);
                $writer->setGenerateSheetNavigationBlock(true);

                ob_start();
                $writer->save('php://output');
                $sheetHtml = ob_get_clean();

                return response($this->wrapInPreviewLayout($attachment, $sheetHtml, 'excel'))
                    ->header('Content-Type', 'text/html; charset=UTF-8');
            } catch (\Throwable $e) {
                // Fallback jika file corrupt / proteksi
                return response($this->wrapInErrorLayout($attachment, 'Gagal merender lembar kerja spreadsheet: ' . $e->getMessage()))
                    ->header('Content-Type', 'text/html; charset=UTF-8');
            }
        }

        // 4. WORD (DOCX) -> Ekstrak isi dokumen secara bersih via ZipArchive (document.xml)
        if ($extension === 'docx') {
            try {
                $contentHtml = $this->extractDocxToHtml($filePath);
                return response($this->wrapInPreviewLayout($attachment, $contentHtml, 'word'))
                    ->header('Content-Type', 'text/html; charset=UTF-8');
            } catch (\Throwable $e) {
                return response($this->wrapInErrorLayout($attachment, 'Gagal memproses dokumen Word: ' . $e->getMessage()))
                    ->header('Content-Type', 'text/html; charset=UTF-8');
            }
        }

        // 5. WORD LEGACY (DOC) -> Beritahukan pengguna format binary DOC lawas
        if ($extension === 'doc') {
            $message = 'Dokumen berformat Word 97-2003 (.doc). Format lama ini tidak mendukung rendering web modern secara langsung. Silakan klik tombol <strong>Unduh File</strong> di bawah untuk membuka dokumen dengan Microsoft Word atau WPS Office.';
            return response($this->wrapInNoticeLayout($attachment, $message))
                ->header('Content-Type', 'text/html; charset=UTF-8');
        }

        // Fallback file lainnya
        return Storage::disk('local')->download(
            $attachment->file_path,
            $attachment->original_name
        );
    }

    /**
     * Ekstrak teks dan paragraf dari file .docx menjadi HTML yang rapi.
     */
    protected function extractDocxToHtml(string $filePath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception('Tidak dapat membuka arsip DOCX.');
        }

        $xmlIndex = $zip->locateName('word/document.xml');
        if ($xmlIndex === false) {
            $zip->close();
            throw new \Exception('File document.xml tidak ditemukan di dalam DOCX.');
        }

        $xmlData = $zip->getFromIndex($xmlIndex);
        $zip->close();

        if (!$xmlData) {
            return '<p class="text-slate-400 italic">Dokumen kosong.</p>';
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($xmlData);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $paragraphs = $xpath->query('//w:p');
        $outputHtml = '';

        foreach ($paragraphs as $p) {
            $texts = $xpath->query('.//w:t', $p);
            $pText = '';
            foreach ($texts as $t) {
                $pText .= htmlspecialchars($t->nodeValue, ENT_QUOTES, 'UTF-8');
            }

            if (trim($pText) === '') {
                $outputHtml .= '<p class="min-h-[1.2em]">&nbsp;</p>';
            } else {
                $outputHtml .= '<p class="mb-3 text-slate-800 leading-relaxed text-sm">' . nl2br($pText) . '</p>';
            }
        }

        return $outputHtml ?: '<p class="text-slate-400 italic">Tidak ada teks yang dapat ditampilkan.</p>';
    }

    /**
     * Membungkus konten preview dalam tata letak responsif dan profesional.
     */
    protected function wrapInPreviewLayout(AgendaAttachment $attachment, string $contentHtml, string $type): string
    {
        $downloadUrl = route('agenda.attachments.download', $attachment->id);
        $fileName = htmlspecialchars($attachment->original_name, ENT_QUOTES, 'UTF-8');
        $badgeAccess = $attachment->access_type === 'internal'
            ? '<span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 font-bold px-2.5 py-1 rounded-full text-xs"><i class="fa-solid fa-lock text-[10px]"></i> INTERNAL</span>'
            : '<span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full text-xs"><i class="fa-solid fa-globe text-[10px]"></i> PUBLIK</span>';

        $icon = match ($type) {
            'excel' => 'fa-file-excel text-emerald-600',
            'word' => 'fa-file-word text-blue-600',
            default => 'fa-file-lines text-slate-600',
        };

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {$fileName} - BAPPERIDA Belitung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #f8fafc; }
        .document-paper { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01); }
        table { border-collapse: collapse; width: 100%; font-size: 12px; margin-bottom: 1rem; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 10px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: 600; }
        tr:nth-child(even) { background-color: #f8fafc; }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800">
    <!-- HEADER BAR -->
    <header class="bg-white border-b border-slate-200 px-4 py-3 sticky top-0 z-50 flex flex-wrap items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-xl">
                <i class="fa-solid {$icon}"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-bold text-slate-800 text-sm md:text-base truncate max-w-xs md:max-w-lg">{$fileName}</h1>
                    {$badgeAccess}
                </div>
                <p class="text-xs text-slate-500">Pratinjau Dokumen Lampiran Agenda BAPPERIDA</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{$downloadUrl}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-download"></i> Unduh File
            </a>
            <button type="button" onclick="window.close()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </header>

    <!-- CONTENT BODY -->
    <main class="flex-1 p-4 md:p-8 flex justify-center overflow-auto">
        <div class="bg-white w-full max-w-4xl rounded-2xl p-6 md:p-10 border border-slate-200 document-paper min-h-[500px]">
            {$contentHtml}
        </div>
    </main>
</body>
</html>
HTML;
    }

    /**
     * Tampilan pemberitahuan / edukasi format dokumen.
     */
    protected function wrapInNoticeLayout(AgendaAttachment $attachment, string $message): string
    {
        $downloadUrl = route('agenda.attachments.download', $attachment->id);
        $fileName = htmlspecialchars($attachment->original_name, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {$fileName}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-md w-full rounded-2xl p-6 border border-slate-200 shadow-xl text-center space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-3xl">
            <i class="fa-solid fa-file-word"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-800 text-base">{$fileName}</h2>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">{$message}</p>
        </div>
        <div class="pt-3 flex justify-center gap-2">
            <a href="{$downloadUrl}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow transition">
                <i class="fa-solid fa-download mr-1"></i> Unduh File
            </a>
            <button type="button" onclick="window.close()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                Tutup
            </button>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Tampilan error jika dokumen gagal dibaca.
     */
    protected function wrapInErrorLayout(AgendaAttachment $attachment, string $error): string
    {
        $downloadUrl = route('agenda.attachments.download', $attachment->id);
        $fileName = htmlspecialchars($attachment->original_name, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gagal Menampilkan Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-md w-full rounded-2xl p-6 border border-slate-200 shadow-xl text-center space-y-3">
        <div class="text-red-500 text-4xl">⚠️</div>
        <h2 class="font-bold text-slate-800 text-sm">Pratinjau Tidak Tersedia</h2>
        <p class="text-xs text-slate-500">{$error}</p>
        <div class="pt-3 flex justify-center gap-2">
            <a href="{$downloadUrl}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold">Unduh Dokumen Asli</a>
            <button type="button" onclick="window.close()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs">Tutup</button>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Menghapus lampiran (Khusus Admin / Pimpinan).
     */
    public function destroy($id)
    {
        $attachment = AgendaAttachment::findOrFail($id);

        Storage::disk('local')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lampiran berhasil dihapus.',
        ]);
    }
}
