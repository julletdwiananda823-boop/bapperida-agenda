<?php

namespace App\Exports;

use App\Models\Agenda;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AgendaExport implements WithMultipleSheets
{
    public function __construct(
        private ?string $bulan = null,
        private ?string $tahun = null,
        private ?string $jenis = 'semua',
        private ?string $bidang = null,
        private ?string $search = null
    ) {}

    public function sheets(): array
    {
        $query = Agenda::query()
            ->whereNotNull('tanggal_kegiatan');

        if ($this->tahun !== null && $this->tahun !== '') {
            $query->whereYear('tanggal_kegiatan', (int) $this->tahun);
        }

        $nomorBulan = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        if ($this->bulan !== null && $this->bulan !== '') {
            $angkaBulan = $nomorBulan[strtolower($this->bulan)] ?? null;

            if ($angkaBulan !== null) {
                $query->whereMonth('tanggal_kegiatan', $angkaBulan);
            }
        }

        if ($this->bidang !== null && trim($this->bidang) !== '') {
            $bidang = trim($this->bidang);

            $query->where(function ($q) use ($bidang) {
                $q->where('bidang', 'like', "%{$bidang}%")
                    ->orWhere('disposisi', 'like', "%{$bidang}%");
            });
        }

        if ($this->search !== null && trim($this->search) !== '') {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('acara', 'like', "%{$search}%")
                    ->orWhere('ruang', 'like', "%{$search}%")
                    ->orWhere('bidang', 'like', "%{$search}%")
                    ->orWhere('disposisi', 'like', "%{$search}%")
                    ->orWhere('tanggal_kegiatan', 'like', "%{$search}%");
            });
        }

        if ($this->jenis === 'disposisi') {
            $query->whereNotNull('disposisi')
                ->where('disposisi', '<>', '')
                ->where('disposisi', '<>', '-');
        }

        $periode = $query
            ->selectRaw(
                'YEAR(tanggal_kegiatan) as tahun, MONTH(tanggal_kegiatan) as bulan'
            )
            ->groupByRaw(
                'YEAR(tanggal_kegiatan), MONTH(tanggal_kegiatan)'
            )
            ->orderByRaw(
                'YEAR(tanggal_kegiatan), MONTH(tanggal_kegiatan)'
            )
            ->get();

        $sheets = [];

        foreach ($periode as $item) {
            $sheets[] = new AgendaMonthSheet(
                (int) $item->bulan,
                (int) $item->tahun,
                $this->bidang,
                $this->search,
                $this->jenis
            );
        }

        // Jika data tidak ditemukan, tetap buat satu sheet kosong
        // dengan bulan dan tahun yang benar.
        if (empty($sheets)) {
            $bulanKosong = ($this->bulan !== null && $this->bulan !== '')
                ? ($nomorBulan[strtolower($this->bulan)] ?? (int) date('n'))
                : (int) date('n');

            $tahunKosong = ($this->tahun !== null && $this->tahun !== '')
                ? (int) $this->tahun
                : (int) date('Y');

            $sheets[] = new AgendaMonthSheet(
                $bulanKosong,
                $tahunKosong,
                $this->bidang,
                $this->search,
                $this->jenis
            );
        }

        return $sheets;
    }
}
