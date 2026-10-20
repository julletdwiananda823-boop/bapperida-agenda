<?php

namespace App\Exports;

use App\Models\Agenda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AgendaMonthSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithStyles,
    WithColumnWidths,
    WithCustomStartCell,
    WithEvents
{
    private int $nomor = 0;
    private ?string $tanggalTerakhir = null;

    public function __construct(
        private int $bulan,
        private int $tahun,
        private ?string $bidang = null,
        private ?string $search = null,
        private ?string $jenis = 'semua'
    ) {}

    /**
     * Mengambil data berdasarkan tahun, bulan, bidang,
     * pencarian, dan jenis laporan.
     */
    public function collection()
    {
        $query = Agenda::query()
            ->whereNotNull('tanggal_kegiatan')
            ->whereYear('tanggal_kegiatan', $this->tahun)
            ->whereMonth('tanggal_kegiatan', $this->bulan);

        // Filter bidang: cocokkan dengan bidang atau disposisi.
        if ($this->bidang !== null && trim($this->bidang) !== '') {
            $bidang = trim($this->bidang);

            $query->where(function ($q) use ($bidang) {
                $q->where('bidang', 'like', '%' . $bidang . '%')
                    ->orWhere('disposisi', 'like', '%' . $bidang . '%');
            });
        }

        // Filter pencarian.
        if ($this->search !== null && trim($this->search) !== '') {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('acara', 'like', '%' . $search . '%')
                    ->orWhere('ruang', 'like', '%' . $search . '%')
                    ->orWhere('bidang', 'like', '%' . $search . '%')
                    ->orWhere('disposisi', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_kegiatan', 'like', '%' . $search . '%');
            });
        }

        // Filter khusus laporan disposisi.
        if ($this->jenis === 'disposisi') {
            $query->whereNotNull('disposisi')
                ->where('disposisi', '<>', '')
                ->where('disposisi', '<>', '-');
        }

        return $query
            ->orderBy('tanggal_kegiatan')
            ->orderBy('id')
            ->get();
    }

    public function title(): string
    {
        $bulan = [
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER',
        ];

        return ucfirst(strtolower($bulan[$this->bulan] ?? 'BULAN'))
            . ' ' . $this->tahun;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'HARI',
            'WAKTU',
            'RUANG RAPAT',
            'ACARA',
            'BIDANG',
            'EKSTERNAL',
            'DISPOSISI',
            'KETERANGAN',
        ];
    }

    public function map($agenda): array
    {
        $tanggal = date('Y-m-d', strtotime($agenda->tanggal_kegiatan));
        $tanggalBaru = $tanggal !== $this->tanggalTerakhir;

        if ($tanggalBaru) {
            $this->nomor++;
            $this->tanggalTerakhir = $tanggal;
        }

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tanggalIndonesia = date('d', strtotime($tanggal))
            . ' ' . ($namaBulan[(int) date('n', strtotime($tanggal))] ?? '')
            . ' ' . date('Y', strtotime($tanggal));

        return [
            $tanggalBaru ? $this->nomor : '',
            $tanggalBaru ? $tanggalIndonesia : '',
            $tanggalBaru ? ($agenda->hari ?? '') : '',
            $agenda->waktu ?? '',
            $agenda->ruang ?? '',
            $agenda->acara ?? '',
            $agenda->bidang ?? '',
            '',
            $agenda->disposisi ?? '',
            '',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 19,
            'C' => 14,
            'D' => 15,
            'E' => 28,
            'F' => 48,
            'G' => 24,
            'H' => 25,
            'I' => 27,
            'J' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Format header tabel.
        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9EAD3'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $lastRow = max(4, $sheet->getHighestRow());

        // Format isi tabel.
        if ($lastRow >= 5) {
            $sheet->getStyle("A5:J{$lastRow}")->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            // Warna selang-seling untuk baris data.
            for ($row = 5; $row <= $lastRow; $row++) {
                $sheet->getRowDimension($row)->setRowHeight(-1);

                if (($row - 5) % 2 === 0) {
                    $sheet->getStyle("A{$row}:J{$row}")
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID);

                    $sheet->getStyle("A{$row}:J{$row}")
                        ->getFill()
                        ->getStartColor()
                        ->setRGB('D9F2E6');
                }
            }

            $sheet->getStyle("A5:C{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->freezePane('A5');

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');

                $sheet->setCellValue(
                    'A1',
                    'AGENDA KEGIATAN BAPPERIDA'
                );

                $namaBulan = [
                    1 => 'JANUARI',
                    2 => 'FEBRUARI',
                    3 => 'MARET',
                    4 => 'APRIL',
                    5 => 'MEI',
                    6 => 'JUNI',
                    7 => 'JULI',
                    8 => 'AGUSTUS',
                    9 => 'SEPTEMBER',
                    10 => 'OKTOBER',
                    11 => 'NOVEMBER',
                    12 => 'DESEMBER',
                ];

                $namaBulanTerpilih = $namaBulan[$this->bulan] ?? 'BULAN';

                $sheet->setCellValue(
                    'A2',
                    'BULAN ' . $namaBulanTerpilih
                    . ' TAHUN ' . $this->tahun
                );

                foreach (['A1:J1', 'A2:J2'] as $range) {
                    $sheet->getStyle($range)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 12,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }

                $sheet->getRowDimension(1)->setRowHeight(26);
                $sheet->getRowDimension(2)->setRowHeight(24);
                $sheet->getRowDimension(4)->setRowHeight(34);

                $lastRow = $sheet->getHighestRow();

                if ($lastRow >= 4) {
                    $sheet->setAutoFilter("A4:J{$lastRow}");
                }
            },
        ];
    }
}
