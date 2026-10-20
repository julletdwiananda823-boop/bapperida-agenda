
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BookingAuthController;
use App\Http\Controllers\RoomBookingController;
use App\Http\Middleware\EnsureBookingAuthenticated;
use App\Exports\AgendaExport;
use Maatwebsite\Excel\Facades\Excel;

// ========================================
// HALAMAN UTAMA
// ========================================
Route::get('/', [AgendaController::class, 'index'])
    ->name('home');

// ========================================
// AGENDA
// Wajib login PIN
// ========================================
Route::post('/agenda/save', [AgendaController::class, 'storeOrUpdate'])
    ->middleware(EnsureBookingAuthenticated::class)
    ->name('agenda.save');

Route::delete('/agenda/delete/{id}', [AgendaController::class, 'destroy'])
    ->middleware(EnsureBookingAuthenticated::class)
    ->whereNumber('id')
    ->name('agenda.delete');

// ========================================
// JADWAL PEGAWAI: APEL / PIKET
// Wajib login PIN
// ========================================
Route::post('/apel/save', [AgendaController::class, 'saveApel'])
    ->middleware(EnsureBookingAuthenticated::class)
    ->name('apel.save');

Route::delete('/apel/delete/{id}', [AgendaController::class, 'deleteApel'])
    ->middleware(EnsureBookingAuthenticated::class)
    ->whereNumber('id')
    ->name('apel.delete');

// ========================================
// LAPORAN
// ========================================
Route::get('/api/reports', [AgendaController::class, 'getReportData'])
    ->name('reports.data');

// Ekspor Excel berdasarkan filter laporan
Route::get('/reports/export-excel', function (Request $request) {
    $bulan = $request->query('bulan');
    $tahun = $request->query('tahun');
    $jenis = $request->query('jenis', 'semua');
    $bidang = $request->query('bidang');
    $search = $request->query('search');

    // Validasi jenis laporan
    if (!is_string($jenis) ||
        !in_array($jenis, ['semua', 'agenda', 'disposisi'], true)) {
        $jenis = 'semua';
    }

    // Validasi tahun: harus empat digit
    if (
        !is_string($tahun) ||
        !preg_match('/^\d{4}$/', $tahun) ||
        (int) $tahun < 1900 ||
        (int) $tahun > 2200
    ) {
        $tahun = null;
    }

    // Batasi panjang input filter
    $bulan = is_string($bulan) ? substr($bulan, 0, 20) : null;
    $bidang = is_string($bidang) ? substr($bidang, 0, 100) : null;
    $search = is_string($search) ? substr($search, 0, 200) : null;

    return Excel::download(
        new AgendaExport(
            $bulan,
            $tahun,
            $jenis,
            $bidang,
            $search
        ),
        'laporan-agenda-bapperida.xlsx'
    );
})->name('reports.export-excel');

// ========================================
// LOGIN DAN LOGOUT BOOKING DENGAN PIN
// ========================================
Route::post('/booking/login', [BookingAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('booking.login');

Route::post('/booking/logout', [BookingAuthController::class, 'logout'])
    ->name('booking.logout');

// ========================================
// BOOKING RUANGAN RAPAT
// ========================================

// Melihat daftar booking tanpa login
Route::get('/bookings', [RoomBookingController::class, 'index'])
    ->name('bookings.index');

// Membuat booking
Route::post('/bookings', [RoomBookingController::class, 'store'])
    ->name('bookings.store');

// Membatalkan booking
Route::delete('/bookings/{id}', [RoomBookingController::class, 'destroy'])
    ->whereNumber('id')
    ->name('bookings.destroy');
