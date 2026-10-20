<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Informasi Agenda & Disposisi Bapperida Belitung</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Inter & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 800: '#1e3a8a', 900: '#0f172a' },
                        emerald: { 600: '#059669', 700: '#047857' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-full flex flex-col">

    <!-- Top Navbar -->
    <header class="bg-navy-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center space-x-2.5">
                <div class="bg-blue-600 p-2 rounded-xl shadow-inner flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-building-columns text-xl sm:text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="font-bold text-sm sm:text-lg tracking-tight leading-tight">BAPPERIDA KAB. BELITUNG</h1>
                    <p class="text-[10px] sm:text-xs text-blue-200">Sistem Informasi Agenda & Disposisi Pimpinan</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <div id="user-status-container">
                    <button type="button" onclick="openLoginModal()" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-lock"></i>
                        <span>Login Pegawai</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row gap-4">

        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-2.5 md:p-4 sticky top-16 md:top-28 z-40">
                <nav class="grid grid-cols-2 md:grid-cols-1 gap-1.5">
                    <button type="button" onclick="switchTab('dashboard')" id="nav-dashboard" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all bg-blue-50 text-blue-700 cursor-pointer">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                        <span>Dashboard</span>
                    </button>
                    <button type="button" onclick="switchTab('agenda')" id="nav-agenda" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <i class="fa-solid fa-calendar-days w-4 text-center"></i>
                        <span>Agenda</span>
                    </button>
                    <button type="button" onclick="switchTab('pegawai')" id="nav-pegawai" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <i class="fa-solid fa-users-gear w-4 text-center"></i>
                        <span>Jadwal Pegawai</span>
                    </button>
                    <button type="button" onclick="switchTab('layanan')" id="nav-layanan" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                        <span>Jam Layanan</span>
                    </button>
                    <button type="button" onclick="switchTab('laporan')" id="nav-laporan" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <i class="fa-solid fa-file-lines w-4 text-center text-blue-600"></i>
                        <span>Laporan</span>
                    </button>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="flex-1 min-w-0">

            <!-- TAB 1: DASHBOARD -->
            <div id="tab-dashboard" class="space-y-4">
                <div class="bg-gradient-to-r from-navy-900 to-blue-800 rounded-2xl p-5 sm:p-8 text-white shadow-md relative overflow-hidden">
                    <div class="relative z-10 max-w-2xl">
                        <span class="bg-blue-500/30 text-blue-200 text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-400/20">Arsip Resmi</span>
                        <h2 class="text-xl sm:text-3xl font-bold mt-2">Monitoring Agenda & Disposisi</h2>
                        <p class="text-slate-200 text-xs sm:text-sm mt-1.5 leading-relaxed">
                            Pusat data kegiatan kedinasan serta catatan disposisi pimpinan Bapperida Belitung.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Total Tanggal Terjadwal</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5" id="stat-total-agenda">0</h3>
                        <span class="text-[11px] text-blue-600 font-medium flex items-center mt-0.5"><i class="fa-solid fa-calendar-check mr-1"></i> Database Aktif</span>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg sm:text-xl">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                </div>

                <!-- KARTU PETUGAS PIKET HARI INI -->
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">
                                Petugas Piket Hari Ini
                            </h3>
                            <p id="dashboard-piket-tanggal"
                               class="text-xs text-slate-500">
                                Memuat tanggal...
                            </p>
                        </div>
                    </div>

                    <div id="dashboard-piket-hari-ini">
                        <p class="text-sm text-slate-400">
                            Memuat jadwal piket...
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="text-center border-b border-slate-100 pb-3">
                        <div class="inline-block bg-blue-50 text-blue-700 px-4 py-1 rounded-full text-xs font-bold border border-blue-100">
                            <i class="fa-solid fa-calendar-day mr-1"></i> <span id="dashboard-header-tanggal">Memuat tanggal...</span>
                        </div>
                        <h3 id="dashboard-header-count" class="font-bold text-slate-800 text-sm mt-2">Memuat kegiatan...</h3>
                    </div>
                    <div id="dashboard-agenda-list" class="space-y-3">
                        <p class="text-xs text-slate-400 text-center py-4">Memuat data agenda...</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: AGENDA -->
            <div id="tab-agenda" class="space-y-4 hidden">
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Agenda & Disposisi</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar agenda kedinasan lengkap per bulan.</p>
                        </div>
                        <div id="admin-add-button-container"></div>
                    </div>

                    <!-- Panel Filter (Bulan & Tahun Terpisah) -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 mb-4 shadow-sm space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Filter Pencarian Teks -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Kegiatan</label>
                                <div class="relative">
                                    <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400 text-xs"></i>
                                    <input type="text" id="search-agenda" oninput="renderAgendaTable()" placeholder="Cari acara, tempat..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            </div>
                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Bulan</label>
                                <select id="filter-bulan" onchange="renderAgendaTable()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none">
                                    <option value="">Semua Bulan</option>
                                    <option value="januari">Januari</option>
                                    <option value="februari">Februari</option>
                                    <option value="maret">Maret</option>
                                    <option value="april">April</option>
                                    <option value="mei">Mei</option>
                                    <option value="juni">Juni</option>
                                    <option value="juli">Juli</option>
                                    <option value="agustus">Agustus</option>
                                    <option value="september">September</option>
                                    <option value="oktober">Oktober</option>
                                    <option value="november">November</option>
                                    <option value="desember">Desember</option>
                                </select>
                            </div>
                            <!-- Filter Tahun -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Tahun</label>
                                <select id="filter-tahun" onchange="renderAgendaTable()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none">
                                    <option value="">Semua Tahun</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-1 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <p class="text-[11px] text-slate-400">Semua Bulan dan Semua Tahun juga tetap tersedia sebagai pilihan.</p>
                            <select id="filter-bidang" onchange="renderAgendaTable()" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 focus:outline-none">
                                <option value="">Semua Bidang / Disposisi</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Kabid PPEPD">Kabid PPEPD</option>
                                <option value="Kabid PSDA">Kabid PSDA</option>
                                <option value="Kabid PPM">Kabid PPM</option>
                                <option value="Kabid IK">Kabid IK</option>
                                <option value="Kabid RIDA">Kabid RIDA</option>
                                <option value="Sub Bag: Kepeg & Umum">Sub Bag: Kepeg &amp; Umum</option>
                                <option value="Jabatan Fungsional">Jabatan Fungsional</option>
                                <option value="Seluruh Pegawai">Seluruh Pegawai</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle px-4 sm:px-0">
                            <table class="min-w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                        <th class="py-2.5 px-3">Tanggal</th>
                                        <th class="py-2.5 px-3">Acara & Lokasi</th>
                                        <th class="py-2.5 px-3">Bidang</th>
                                        <th class="py-2.5 px-3">Disposisi</th>
                                    </tr>
                                </thead>
                                <tbody id="agenda-table-body" class="divide-y divide-slate-100 text-xs"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: PEGAWAI -->
            <div id="tab-pegawai" class="space-y-4 hidden">
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Jadwal & Piket Pegawai</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar petugas pembina dan pemimpin apel pegawai Bapperida.</p>
                        </div>
                        <div id="admin-add-apel-container"></div>
                    </div>
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle px-4 sm:px-0">
                            <table class="min-w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                        <th class="py-2.5 px-3">No</th>
                                        <th class="py-2.5 px-3">Tanggal</th>
                                        <th class="py-2.5 px-3">Pembina</th>
                                        <th class="py-2.5 px-3">Pemimpin</th>
                                        <th class="py-2.5 px-3">Ket</th>
                                        <th class="py-2.5 px-3 text-right" id="th-aksi-apel" style="display:none;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="apel-table-body" class="divide-y divide-slate-100 text-xs"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: LAYANAN -->
            <div id="tab-layanan" class="space-y-4 hidden">
                <div class="bg-white rounded-2xl p-4 sm:p-8 border border-slate-200/80 shadow-sm">
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-clock text-blue-600"></i> Jam Layanan & Operasional Kantor
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Jadwal jam kerja resmi Bapperida Kabupaten Belitung.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4">
                            <span class="text-[10px] font-bold uppercase text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full">Senin – Kamis</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-2">07.30 – 16.00 <span class="text-xs font-semibold text-slate-500">WIB</span></h3>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4">
                            <span class="text-[10px] font-bold uppercase text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Jumat</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-2">07.30 – 16.30 <span class="text-xs font-semibold text-slate-500">WIB</span></h3>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4">
                            <span class="text-[10px] font-bold uppercase text-red-700 bg-red-100 px-2.5 py-0.5 rounded-full">Sabtu – Minggu</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-red-600 mt-2">TUTUP</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: LAPORAN -->
            <div id="tab-laporan" class="space-y-4 hidden">
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-file-lines text-blue-600"></i> Laporan Agenda & Disposisi Pimpinan
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pusat rekapitulasi riwayat lengkap dari seluruh data database.</p>
                        </div>
                       
                        <div class="flex items-center gap-2">
                            <a href="{{ url('/reports/export-excel') }}"
                               onclick="exportToExcel(); return false;"
                               class="px-3.5 py-2 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer no-underline">
                                <i class="fa-solid fa-file-excel"></i> Ekspor Excel
                            </a>

                            <button type="button" onclick="exportToPDF()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
                            </button>
                        </div>
                    </div>

                    <!-- Panel Filter Laporan (Diubah menjadi lg:grid-cols-6) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-2.5 bg-slate-50 p-3.5 rounded-xl border border-slate-200/70 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Jenis Laporan</label>
                            <select id="lap-jenis" onchange="renderReportTable()" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                                <option value="semua">Semua Laporan</option>
                                <option value="agenda">Laporan Agenda Saja</option>
                                <option value="disposisi">Laporan Disposisi Saja</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Bulan</label>
                            <select id="lap-bulan" onchange="renderReportTable()" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                                <option value="">Semua Bulan</option>
                                <option value="januari">Januari</option>
                                <option value="februari">Februari</option>
                                <option value="maret">Maret</option>
                                <option value="april">April</option>
                                <option value="mei">Mei</option>
                                <option value="juni">Juni</option>
                                <option value="juli">Juli</option>
                                <option value="agustus">Agustus</option>
                                <option value="september">September</option>
                                <option value="oktober">Oktober</option>
                                <option value="november">November</option>
                                <option value="desember">Desember</option>
                            </select>
                        </div>
                        <!-- Dropdown Tahun Ditambahkan di Sini -->
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Tahun</label>
                            <select id="lap-tahun"
                                onchange="renderReportTable()"
                                class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                                <option value="">Semua Tahun</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Bidang / OPD</label>
                            <select id="lap-bidang" onchange="renderReportTable()" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                                <option value="">Semua Bidang</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Kabid PPEPD">Kabid PPEPD</option>
                                <option value="Kabid PSDA">Kabid PSDA</option>
                                <option value="Kabid PPM">Kabid PPM</option>
                                <option value="Kabid IK">Kabid IK</option>
                                <option value="Kabid RIDA">Kabid RIDA</option>
                                <option value="Sub Bag: Kepeg & Umum">Sub Bag: Kepeg &amp; Umum</option>
                                <option value="Jabatan Fungsional">Jabatan Fungsional</option>
                                <option value="Seluruh Pegawai">Seluruh Pegawai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Pencarian Teks</label>
                            <input type="text" id="lap-search" oninput="renderReportTable()" placeholder="Cari kegiatan/tempat..." class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="resetReportFilter()" class="w-full py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-lg transition">Reset Filter</button>
                        </div>
                    </div>

                    <!-- Ringkasan Statistik Laporan -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl">
                            <p class="text-[11px] text-blue-600 font-bold uppercase">Total Agenda Filter</p>
                            <h4 id="lap-stat-total" class="text-lg font-extrabold text-blue-900 mt-0.5">0</h4>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-100 p-3 rounded-xl">
                            <p class="text-[11px] text-emerald-600 font-bold uppercase">Dengan Disposisi</p>
                            <h4 id="lap-stat-disposisi" class="text-lg font-extrabold text-emerald-900 mt-0.5">0</h4>
                        </div>
                        <div class="col-span-2 sm:col-span-1 bg-slate-100 border border-slate-200 p-3 rounded-xl">
                            <p class="text-[11px] text-slate-600 font-bold uppercase">Status Data</p>
                            <h4 class="text-xs font-bold text-slate-800 mt-1"><i class="fa-solid fa-database text-blue-600 mr-1"></i> Riwayat Lengkap Database</h4>
                        </div>
                    </div>

                    <!-- Area Tabel / Cetak PDF -->
                    <div id="print-area" class="bg-white p-2 rounded-xl">
                        <div id="pdf-kop" class="hidden text-center pb-4 mb-4 border-b-2 border-slate-800">
                            <h3 class="font-bold text-sm tracking-wide">PEMERINTAH KABUPATEN BELITUNG</h3>
                            <h2 class="font-extrabold text-base uppercase">BADAN PERENCANAAN PEMBANGUNAN, RISET DAN PENELITIAN DAERAH</h2>
                            <p class="text-[10px] text-slate-600 mt-0.5">Jl. A. Yani No. 5 Telp. (0719) 21121 Tanjungpandan - Belitung</p>
                            <div class="mt-3">
                                <h4 class="font-bold text-sm underline uppercase">LAPORAN AGENDA & DISPOSISI PIMPINAN</h4>
                                <p id="pdf-periode" class="text-xs text-slate-600 mt-0.5">Periode: Semua Riwayat</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-300">
                                        <th class="py-2.5 px-3">No</th>
                                        <th class="py-2.5 px-3">Tanggal / Hari</th>
                                        <th class="py-2.5 px-3">Waktu & Tempat</th>
                                        <th class="py-2.5 px-3">Uraian Kegiatan</th>
                                        <th class="py-2.5 px-3">Bidang</th>
                                        <th class="py-2.5 px-3">Disposisi Pimpinan</th>
                                    </tr>
                                </thead>
                                <tbody id="report-table-body" class="divide-y divide-slate-200"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL FORM TAMBAH / EDIT AGENDA -->
    <div id="form-agenda-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center hidden p-3 sm:p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-5 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeFormAgendaModal()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
            <h3 id="form-modal-title" class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2 mb-3">Form Agenda & Disposisi</h3>
            <form onsubmit="handleFormAgendaSubmit(event)" class="space-y-3.5 text-xs">
                <input type="hidden" id="form-row-index">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1"><i class="fa-solid fa-calendar-days text-blue-600 mr-1"></i> Hari / Tanggal</label>
                        <input type="date" id="form-tanggal-picker" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Jam Mulai</label>
                            <select id="form-jam-mulai" required class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Jam Selesai</label>
                            <select id="form-jam-selesai" required class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1"><i class="fa-solid fa-users text-blue-600 mr-1"></i> Pelaksana / Disposisi Bidang</label>
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 max-h-36 overflow-y-auto space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Sekretaris"><span>Sekretaris</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Kabid PPEPD"><span>Kabid PPEPD</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Kabid PSDA"><span>Kabid PSDA</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Kabid PPM"><span>Kabid PPM</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Kabid IK"><span>Kabid IK</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Kabid RIDA"><span>Kabid RIDA</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Sub Bag: Kepeg & Umum"><span>Sub Bag: Kepeg &amp; Umum</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Jabatan Fungsional"><span>Jabatan Fungsional</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input class="bidang-checkbox" type="checkbox" value="Seluruh Pegawai"><span>Seluruh Pegawai</span></label>
                    </div>
                    <div class="mt-2">
                        <input type="text" id="form-pelaksana-manual" placeholder="Atau ketik pelaksana lainnya..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1"><i class="fa-solid fa-file-lines text-blue-600 mr-1"></i> Uraian Kegiatan</label>
                    <textarea id="form-acara" rows="2.5" placeholder="Tuliskan uraian nama kegiatan di sini..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1"><i class="fa-solid fa-location-dot text-red-500 mr-1"></i> Lokasi / Tempat</label>
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pilihan_ruang" value="Ruang Rapat Batu Dinding Bappeda" onchange="setRuang(this.value)" class="ruang-radio">
                            <span>Ruang Rapat Batu Dinding Bappeda</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pilihan_ruang" value="Ruang Rapat Linsum Kawai Bappeda" onchange="setRuang(this.value)" class="ruang-radio">
                            <span>Ruang Rapat Linsum Kawai Bappeda</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pilihan_ruang" value="Ruang Rapat Batu Bedil Bappeda" onchange="setRuang(this.value)" class="ruang-radio">
                            <span>Ruang Rapat Batu Bedil Bappeda</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pilihan_ruang" value="manual" onchange="setRuang('manual')" class="ruang-radio">
                            <span>Lokasi / Tempat Lainnya (Ketik Manual)</span>
                        </label>
                    </div>
                    <div class="mt-2">
                        <input type="text" id="form-ruang" placeholder="Contoh: Ruang Rapat Bupati Belitung" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <!-- Info ketersediaan ruangan (sistem booking) -->
                    <div id="booking-info" class="hidden mt-2"></div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1"><i class="fa-solid fa-pen-nib text-emerald-600 mr-1"></i> Disposisi Pimpinan</label>
                    <textarea id="form-disposisi" rows="3" placeholder="Tuliskan catatan disposisi pimpinan di sini..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeFormAgendaModal()" class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl cursor-pointer">Batal</button>
                    <button type="submit" id="btn-save-agenda" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl cursor-pointer shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FORM TAMBAH / EDIT APEL / JADWAL PEGAWAI -->
    <div id="form-apel-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center hidden p-3 sm:p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeFormApelModal()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
            <h3 id="form-apel-modal-title" class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2 mb-3">Tambah Jadwal Pegawai</h3>
            <form onsubmit="handleFormApelSubmit(event)" class="space-y-3 text-xs">
                <input type="hidden" id="form-apel-index">
                <div class="space-y-3">
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Tanggal (Pilih Kalender)</label>
                        <input type="date" id="form-apel-tanggal" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Hari (Opsional / Otomatis)</label>
                        <input type="text" id="form-apel-hari" placeholder="Contoh: Senin" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Pembina Apel / Petugas</label>
                        <input type="text" id="form-apel-pembina" placeholder="Nama Pembina..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Pemimpin Apel / Petugas</label>
                        <input type="text" id="form-apel-pemimpin" placeholder="Nama Pemimpin..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Keterangan / Status</label>
                        <input type="text" id="form-apel-ket" placeholder="Contoh: Apel Pagi / Petugas Piket" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeFormApelModal()" class="px-3 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl cursor-pointer">Batal</button>
                    <button type="submit" id="btn-save-apel" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POPUP DETAIL AGENDA -->
    <div id="agenda-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-3 sm:p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-4 sm:p-6 shadow-2xl border border-slate-100 relative max-h-[90vh] flex flex-col">
            <button type="button" onclick="closeAgendaModal()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
            <div class="mb-3 pr-6 border-b border-slate-100 pb-2.5">
                <span id="modal-tanggal" class="text-[11px] font-bold text-blue-700 bg-blue-50 px-3 py-0.5 rounded-full border border-blue-100"></span>
                <h3 class="font-bold text-slate-900 text-base mt-1.5">Susunan Acara Jam demi Jam</h3>
            </div>
            <div id="modal-events-container" class="space-y-3 overflow-y-auto pr-1 flex-1"></div>
            <div class="mt-4 text-right pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeAgendaModal()" class="px-4 py-2 bg-slate-800 text-white font-semibold rounded-xl text-xs cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL LOGIN -->
    <div id="login-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-3">
        <div class="bg-white rounded-2xl max-w-sm w-full p-5 shadow-2xl border border-slate-100 relative">
            <button type="button" onclick="closeLoginModal()" class="absolute right-3 top-3 text-slate-400 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            <div class="text-center mb-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center mx-auto mb-2 text-lg"><i class="fa-solid fa-user-shield"></i></div>
                <h3 class="font-bold text-slate-800 text-base">Akses Khusus Pegawai</h3>
            </div>
            <form onsubmit="handleLogin(event)" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Nama Pegawai
                    </label>
                    <input
                        type="text"
                        id="login-nama"
                        maxlength="150"
                        autocomplete="name"
                        required
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        PIN / Kode Akses
                    </label>
                    <input
                        type="password"
                        id="login-pin"
                        maxlength="100"
                        autocomplete="current-password"
                        required
                        placeholder="Masukkan PIN"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"
                    >
                    <p id="login-error" class="text-xs text-red-500 mt-1 hidden">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i>
                        PIN atau nama pegawai tidak valid.
                    </p>
                </div>

                <button
                    type="submit"
                    id="login-submit"
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs cursor-pointer"
                >
                    Buka Akses
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript Controller -->
    <script>
        let globalAgendaData = @json($formattedAgendas ?? []);
        let globalApelData = @json($apels ?? []);
        let globalReportData = [];
        // Status login dibaca dari session Laravel, jadi tidak hilang setelah halaman reload
        let isLoggedIn = @json((bool) session('booking_authenticated', false));
        let bookingPemesan = @json((string) session('booking_pemesan', ''));

        // ===== CSRF: token disimpan di variabel yang bisa diperbarui =====
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function setCsrfToken(token) {
            if (!token) return;
            csrfToken = token;
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) meta.setAttribute('content', token);
        }

        // Ambil token terbaru dari server (tanpa perlu mengubah controller).
        // Dipakai setelah login/logout, karena Laravel mengganti token session saat itu.
        async function refreshCsrfToken() {
            try {
                const res = await fetch(window.location.pathname, {
                    method: 'GET',
                    credentials: 'same-origin',
                    cache: 'no-store',
                    headers: { 'Accept': 'text/html' }
                });
                const html = await res.text();
                const match = html.match(/<meta\s+name="csrf-token"\s+content="([^"]+)"/i);
                if (match) setCsrfToken(match[1]);
            } catch (err) {
                console.warn('Gagal memperbarui token CSRF:', err);
            }
        }

        // fetch bawaan yang otomatis menyertakan token CSRF dan
        // mengulang SEKALI dengan token baru jika server menjawab 419.
        async function apiFetch(url, options = {}) {
            const kirim = () => fetch(url, {
                ...options,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    ...(options.headers || {}),
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            let response = await kirim();

            if (response.status === 419) {
                await refreshCsrfToken();
                response = await kirim();
            }

            return response;
        }

        window.addEventListener('DOMContentLoaded', () => {
            updateAuthUI();
            const statEl = document.getElementById('stat-total-agenda');
            if (statEl) statEl.innerText = globalAgendaData.reduce((acc, item) => acc + (item.subEvents ? item.subEvents.length : 1), 0);
            renderDashboardList();
            renderAgendaTable();
            renderApelTable();
            renderPiketHariIni();

            // Cek ketersediaan ruangan setiap kali tanggal / jam / ruangan berubah
            ['form-tanggal-picker', 'form-jam-mulai', 'form-jam-selesai'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', perbaruiInfoBooking);
            });
            const ruangEl = document.getElementById('form-ruang');
            if (ruangEl) ruangEl.addEventListener('input', perbaruiInfoBooking);
        });

        function renderPiketHariIni() {
            const tanggalEl = document.getElementById('dashboard-piket-tanggal');
            const isiEl = document.getElementById('dashboard-piket-hari-ini');

            if (!tanggalEl || !isiEl) return;

            const sekarang = new Date();
            const tanggalHariIni = new Intl.DateTimeFormat('en-CA', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).format(sekarang);

            tanggalEl.textContent = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(sekarang);

            const jadwal = (globalApelData || []).filter(item => {
                const tanggal = String(item.tanggal || '').slice(0, 10);
                return tanggal === tanggalHariIni;
            });

            if (jadwal.length === 0) {
                isiEl.innerHTML = `
                    <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
                        <i class="fa-regular fa-calendar-xmark mr-2"></i>
                        Belum ada jadwal piket untuk hari ini.
                    </div>
                `;
                return;
            }

            isiEl.innerHTML = jadwal.map(item => `
                <div class="rounded-xl border border-slate-100 p-4 mb-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Pembina</p>
                            <p class="font-semibold text-slate-800">
                                ${escapePiket(item.pembina || '-')}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Pemimpin</p>
                            <p class="font-semibold text-slate-800">
                                ${escapePiket(item.pemimpin || '-')}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Keterangan</p>
                        <p class="text-sm text-slate-700">
                            ${escapePiket(item.ket || '-')}
                        </p>
                    </div>
                </div>
            `).join('');
        }

        function escapePiket(value) {
            const div = document.createElement('div');
            div.textContent = String(value);
            return div.innerHTML;
        }

        function renderApelTable() {
            const tbody = document.getElementById('apel-table-body');
            const thAksi = document.getElementById('th-aksi-apel');
            if (!tbody) return;

            if (thAksi) {
                thAksi.style.display = isLoggedIn ? '' : 'none';
            }

            if (!globalApelData || globalApelData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${isLoggedIn ? 6 : 5}" class="text-center py-6 text-slate-400">Tidak ada jadwal pegawai ditemukan.</td></tr>`;
                return;
            }

            tbody.innerHTML = globalApelData.map((r, idx) => `
                <tr class="hover:bg-slate-50">
                    <td class="py-2.5 px-3 font-semibold">${idx + 1}</td>
                    <td class="py-2.5 px-3 text-blue-700 font-medium">${r.hari ? r.hari + ', ' : ''}${r.tanggal}</td>
                    <td class="py-2.5 px-3">${r.pembina}</td>
                    <td class="py-2.5 px-3">${r.pemimpin}</td>
                    <td class="py-2.5 px-3">${r.ket || '-'}</td>
                    ${isLoggedIn ? `
                        <td class="py-2.5 px-3 text-right">
                            <button type="button" onclick='openEditApel(${idx})' class="px-2 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded font-semibold text-[10px] mr-1 cursor-pointer">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" onclick='deleteApel(${Number(r.id)})' class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded font-semibold text-[10px] cursor-pointer">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                        ` : ''}
                </tr>
            `).join('');
        }

        function openLoginModal() {
            document.getElementById('login-modal').classList.remove('hidden');
            document.getElementById('login-pin').value = '';
            document.getElementById('login-nama').value = '';
            document.getElementById('login-error').classList.add('hidden');
            document.getElementById('login-nama').focus();
        }

        function closeLoginModal() { 
            document.getElementById('login-modal').classList.add('hidden'); 
        }

        async function handleLogin(e) {
            e.preventDefault();

            const namaInput = document.getElementById('login-nama');
            const pinInput = document.getElementById('login-pin');
            const errorEl = document.getElementById('login-error');
            const submitBtn = document.getElementById('login-submit');

            const nama = namaInput.value.trim();
            const pin = pinInput.value.trim();

            errorEl.classList.add('hidden');

            if (!nama || !pin) {
                errorEl.textContent = 'Nama pegawai dan PIN wajib diisi.';
                errorEl.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Memeriksa...';

            try {
                const response = await apiFetch('/booking/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama, pin })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Login gagal.');
                }

                isLoggedIn = true;

                // Token session berganti setelah login -> ambil token terbaru
                setCsrfToken(result.csrf_token);
                await refreshCsrfToken();

                bookingPemesan = result.pemesan || nama;

                closeLoginModal();
                updateAuthUI();
                renderAgendaTable();
                renderDashboardList();
                renderApelTable();

                alert('Login berhasil. Selamat datang, ' + bookingPemesan + '.');
            } catch (error) {
                errorEl.textContent = error.message || 'Tidak dapat menghubungi server.';
                errorEl.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Buka Akses';
            }
        }

        async function handleLogout() {
            try {
                const response = await apiFetch('/booking/logout', {
                    method: 'POST'
                });

                if (!response.ok) {
                    throw new Error('Logout gagal.');
                }

                const result = await response.json().catch(() => ({}));
                setCsrfToken(result.csrf_token);

                // Token session berganti setelah logout -> ambil token terbaru
                await refreshCsrfToken();

                isLoggedIn = false;
                bookingPemesan = '';

                updateAuthUI();
                renderAgendaTable();
                renderDashboardList();
                renderApelTable();
            } catch (error) {
                alert(error.message || 'Tidak dapat menghubungi server.');
            }
        }

        function switchTab(tabId) {
            const tabs = ['dashboard', 'agenda', 'pegawai', 'layanan', 'laporan'];
            tabs.forEach(t => {
                const content = document.getElementById('tab-' + t);
                const nav = document.getElementById('nav-' + t);
                if (content) content.classList.add('hidden');
                if (nav) nav.className = "flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer";
            });

            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('nav-' + tabId).className = "flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all bg-blue-50 text-blue-700 cursor-pointer";

            if (tabId === 'laporan' && globalReportData.length === 0) {
                loadReportData();
            }
        }

        function populateReportYears() {
            const select = document.getElementById('lap-tahun');
            if (!select) return;

            const selectedYear = select.value;
            const years = new Set();

            (globalReportData || []).forEach(item => {
                const tanggal = String(item.tanggal || '');
                const matches = tanggal.match(/\b(?:19|20)\d{2}\b/g);

                if (matches) {
                    matches.forEach(year => years.add(year));
                }
            });

            const sortedYears = [...years].sort((a, b) => Number(b) - Number(a));

            select.innerHTML =
                '<option value="">Semua Tahun</option>' +
                sortedYears.map(year =>
                    `<option value="${year}">${year}</option>`
                ).join('');

            if (sortedYears.includes(selectedYear)) {
                select.value = selectedYear;
            }
        }

        function loadReportData() {
            fetch('/api/reports', {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    globalReportData = data || [];
                    populateReportYears();
                    renderReportTable();
                })
                .catch(err => console.error('Gagal memuat laporan:', err));
        }

        function renderReportTable() {
            const jenis = document.getElementById('lap-jenis').value;
            const bulan = document.getElementById('lap-bulan').value.toLowerCase();
            const tahun = document.getElementById('lap-tahun')?.value || '';
            const bidang = document.getElementById('lap-bidang').value.toLowerCase();
            const search = document.getElementById('lap-search').value.toLowerCase();
            const tbody = document.getElementById('report-table-body');
            const totalEl = document.getElementById('lap-stat-total');
            const dispEl = document.getElementById('lap-stat-disposisi');

            if (!tbody) return;

            const filtered = globalReportData.filter(item => {
                const tglLower = item.tanggal.toLowerCase();
                const acaraLower = item.acara.toLowerCase();
                const ruangLower = item.ruang.toLowerCase();
                const bidangLower = item.bidang.toLowerCase();
                const dispLower = item.disposisi.toLowerCase();

                let matchJenis = true;
                if (jenis === 'disposisi') {
                    matchJenis = item.disposisi !== '-' && item.disposisi !== '';
                }

                let matchBulan = !bulan || tglLower.includes(bulan);
                let matchTahun = !tahun || tglLower.includes(tahun);
                let matchBidang = !bidang || bidangLower.includes(bidang);
                let matchSearch = !search || (tglLower.includes(search) || acaraLower.includes(search) || ruangLower.includes(search) || bidangLower.includes(search) || dispLower.includes(search));

                return matchJenis && matchBulan && matchTahun && matchBidang && matchSearch;
            });

            if (totalEl) totalEl.innerText = filtered.length;
            if (dispEl) dispEl.innerText = filtered.filter(i => i.disposisi && i.disposisi !== '-' && i.disposisi !== '').length;

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-slate-400">Tidak ada data laporan yang sesuai filter.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map((item, idx) => `
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-2 px-3 font-semibold text-slate-600">${idx + 1}</td>
                    <td class="py-2 px-3 font-medium text-blue-800 whitespace-nowrap">${item.tanggal}<br><span class="text-[10px] text-slate-400">${item.hari}</span></td>
                    <td class="py-2 px-3"><strong>${item.waktu}</strong><br><span class="text-[10px] text-slate-500">${item.ruang}</span></td>
                    <td class="py-2 px-3 text-slate-900 font-medium"><i class="fa-solid fa-file-lines text-blue-600 mr-1"></i>${item.acara}</td>
                    <td class="py-2 px-3"><span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-bold">${item.bidang !== '-' ? item.bidang : 'Umum'}</span></td>
                    <td class="py-2 px-3 text-slate-700">${item.disposisi !== '-' && item.disposisi !== '' ? item.disposisi : '-'}</td>
                </tr>
            `).join('');
        }

        function resetReportFilter() {
            document.getElementById('lap-jenis').value = 'semua';
            document.getElementById('lap-bulan').value = '';
            document.getElementById('lap-tahun').value = '';
            document.getElementById('lap-bidang').value = '';
            document.getElementById('lap-search').value = '';
            renderReportTable();
        }

        function exportToExcel() {
            const jenis = document.getElementById('lap-jenis')?.value || 'semua';
            const bulan = document.getElementById('lap-bulan')?.value || '';
            const tahun = document.getElementById('lap-tahun')?.value || '';
            const bidang = document.getElementById('lap-bidang')?.value || '';
            const search = document.getElementById('lap-search')?.value.trim() || '';

            const params = new URLSearchParams();

            params.set('jenis', jenis);

            if (bulan) {
                params.set('bulan', bulan);
            }

            if (tahun) {
                params.set('tahun', tahun);
            }

            if (bidang) {
                params.set('bidang', bidang);
            }

            if (search) {
                params.set('search', search);
            }

            window.location.href =
                '{{ url("/reports/export-excel") }}?' + params.toString();
        }

        function exportToPDF() {
            const bulan = document.getElementById('lap-bulan').value;
            const tahun = document.getElementById('lap-tahun')?.value || '';

            const periode = [
                bulan ? 'Bulan: ' + bulan.toUpperCase() : 'Semua Bulan',
                tahun ? 'Tahun: ' + tahun : 'Semua Tahun'
            ];

            document.getElementById('pdf-periode').innerText =
                'Periode: ' + periode.join(' | ');

            document.getElementById('pdf-kop').classList.remove('hidden');
            window.print();
            document.getElementById('pdf-kop').classList.add('hidden');
        }

        function openAgendaModal(index) {
            const selectedItem = globalAgendaData[index];
            if (!selectedItem) return;
            const sameDayEvents = selectedItem.subEvents || [selectedItem];
            document.getElementById('modal-tanggal').innerText = `${selectedItem.tanggal} (${selectedItem.hari})`;
            const container = document.getElementById('modal-events-container');
            container.innerHTML = sameDayEvents.map((evt) => `
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-blue-800 bg-blue-100 px-2 py-0.5 rounded"><i class="fa-solid fa-clock"></i> ${evt.waktu}</span>
                        ${isLoggedIn ? `
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick='openEditForm(${JSON.stringify(evt)})' class="px-2 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded font-semibold cursor-pointer"><i class="fa-solid fa-pen"></i> Edit</button>
                                <button type="button" onclick='deleteAgenda(${evt.id})' class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded font-semibold cursor-pointer"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </div>
                        ` : ''}
                    </div>
                    <h4 class="font-bold text-slate-900"><i class="fa-solid fa-file-lines text-blue-600 mr-1"></i>${evt.acara}</h4>
                    <p class="text-slate-500"><i class="fa-solid fa-location-dot text-red-500"></i> ${evt.ruang}</p>
                    <p class="text-slate-600"><b>Bidang:</b> ${evt.bidang !== '-' ? evt.bidang : 'Umum'}</p>
                    ${evt.disposisi && evt.disposisi !== '-' ? `<div class="pt-1.5 border-t border-emerald-200 bg-emerald-50 p-2 rounded text-emerald-900"><b>Disposisi:</b> ${evt.disposisi}</div>` : ''}
                </div>
            `).join('');
            document.getElementById('agenda-modal').classList.remove('hidden');
        }

        function closeAgendaModal() { 
            document.getElementById('agenda-modal').classList.add('hidden'); 
        }

        function updateAuthUI() {
            const container = document.getElementById('user-status-container');
            const addBtn = document.getElementById('admin-add-button-container');
            const addApelBtn = document.getElementById('admin-add-apel-container');

            if (isLoggedIn) {
                if (container) container.innerHTML = `<div class="flex items-center gap-1.5 bg-blue-800 px-2.5 py-1 rounded-xl text-emerald-300 font-semibold text-xs"><i class="fa-solid fa-user-check"></i> ${bookingPemesan || 'Pegawai'} <button type="button" onclick="handleLogout()" class="ml-1 text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded cursor-pointer">Keluar</button></div>`;
                if (addBtn) addBtn.innerHTML = `<button type="button" onclick="openFormAgendaModal()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl cursor-pointer"><i class="fa-solid fa-plus"></i> Tambah Agenda</button>`;
                if (addApelBtn) addApelBtn.innerHTML = `<button type="button" onclick="openFormApelModal()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl cursor-pointer"><i class="fa-solid fa-plus"></i> Tambah Jadwal</button>`;
            } else {
                if (container) container.innerHTML = `<button type="button" onclick="openLoginModal()" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer"><i class="fa-solid fa-lock"></i><span>Login Pegawai</span></button>`;
                if (addBtn) addBtn.innerHTML = '';
                if (addApelBtn) addApelBtn.innerHTML = '';
            }
        }

        function setRuang(val) {
            const ruangInput = document.getElementById('form-ruang');
            if (val === 'manual') {
                ruangInput.value = '';
                ruangInput.placeholder = 'Ketik lokasi ruangan di sini...';
                ruangInput.focus();
            } else {
                ruangInput.value = val;
            }
            perbaruiInfoBooking();
        }

        function isiPilihanJam() {
            const mulai = document.getElementById('form-jam-mulai');
            const selesai = document.getElementById('form-jam-selesai');

            if (!mulai || !selesai) return;

            // Buat pilihan jam jika belum tersedia.
            if (mulai.options.length === 0 || selesai.options.length === 0) {
                mulai.replaceChildren();
                selesai.replaceChildren();

                for (let menit = 0; menit < 24 * 60; menit += 15) {
                    const jam = String(Math.floor(menit / 60)).padStart(2, '0');
                    const menitJam = String(menit % 60).padStart(2, '0');
                    const nilai = `${jam}:${menitJam}`;
                    const label = `${jam}.${menitJam}`;

                    mulai.add(new Option(label, nilai));
                    selesai.add(new Option(label, nilai));
                }
            }

            // Gunakan waktu default hanya jika belum ada pilihan.
            if (!mulai.value) mulai.value = '09:00';
            if (!selesai.value) selesai.value = '10:00';
        }

        function aturPilihanWaktu(waktu) {
            isiPilihanJam();

            const mulai = document.getElementById('form-jam-mulai');
            const selesai = document.getElementById('form-jam-selesai');

            if (!mulai || !selesai) return;

            // Mendukung format 09.00 - 10.00 WIB atau 09:00 - 10:00.
            const cocok = String(waktu || '').match(
                /(\d{1,2})[.:](\d{2})\s*(?:-|–|—|sampai)\s*(\d{1,2})[.:](\d{2})/i
            );

            if (cocok) {
                const nilaiMulai =
                    `${cocok[1].padStart(2, '0')}:${cocok[2]}`;
                const nilaiSelesai =
                    `${cocok[3].padStart(2, '0')}:${cocok[4]}`;

                if ([...mulai.options].some(o => o.value === nilaiMulai)) {
                    mulai.value = nilaiMulai;
                }

                if ([...selesai.options].some(o => o.value === nilaiSelesai)) {
                    selesai.value = nilaiSelesai;
                }
            }
        }

        function ambilRentangWaktu() {
            isiPilihanJam();

            const elemenMulai = document.getElementById('form-jam-mulai');
            const elemenSelesai = document.getElementById('form-jam-selesai');

            if (!elemenMulai || !elemenSelesai) {
                console.error('Elemen pilihan jam tidak ditemukan.');
                return null;
            }

            const mulai = elemenMulai.value;
            const selesai = elemenSelesai.value;

            if (!mulai || !selesai) {
                alert('Pilihan jam belum tersedia. Silakan pilih jam mulai dan jam selesai.');
                return null;
            }

            if (selesai <= mulai) {
                alert('Jam selesai harus lebih besar dari jam mulai.');
                return null;
            }

            return `${mulai.replace(':', '.')} - ${selesai.replace(':', '.')} WIB`;
        }

        // ===== SISTEM BOOKING RUANGAN: cek bentrok jadwal =====
        function jamKeMenit(jam, menit) {
            return parseInt(jam, 10) * 60 + parseInt(menit, 10);
        }

        function menitDariSelect(id) {
            const el = document.getElementById(id);
            if (!el || !el.value) return null;
            const [j, m] = el.value.split(':');
            return jamKeMenit(j, m);
        }

        // Membaca teks waktu agenda, mis. "09.00 - 10.00 WIB".
        // Jika hanya satu jam (mis. "08.00 WIB"), dianggap memakai ruangan 1 jam.
        function parseRentangMenit(waktu) {
            const teks = String(waktu || '');
            const rentang = teks.match(/(\d{1,2})[.:](\d{2})\s*(?:-|–|—|sampai)\s*(\d{1,2})[.:](\d{2})/i);
            if (rentang) {
                return { mulai: jamKeMenit(rentang[1], rentang[2]), selesai: jamKeMenit(rentang[3], rentang[4]) };
            }
            const tunggal = teks.match(/(\d{1,2})[.:](\d{2})/);
            if (tunggal) {
                const m = jamKeMenit(tunggal[1], tunggal[2]);
                return { mulai: m, selesai: m + 60 };
            }
            return null;
        }

        // Semua agenda pada tanggal + ruangan yang sama (kecuali agenda yang sedang diedit)
        function agendaPadaRuang(tanggal, ruang, excludeId) {
            const kunciRuang = String(ruang || '').trim().toLowerCase();
            if (!tanggal || !kunciRuang) return [];

            const semua = (globalAgendaData || []).flatMap(g => g.subEvents || [g]);

            return semua
                .filter(evt => {
                    if (excludeId && String(evt.id) === String(excludeId)) return false;
                    if (String(evt.tanggal_kegiatan || '').slice(0, 10) !== tanggal) return false;
                    if (String(evt.ruang || '').trim().toLowerCase() !== kunciRuang) return false;
                    return parseRentangMenit(evt.waktu) !== null;
                })
                .sort((a, b) => parseRentangMenit(a.waktu).mulai - parseRentangMenit(b.waktu).mulai);
        }

        // Bentrok bila jam saling tumpang tindih. Jam sambung (10.00 selesai, 10.00 mulai) tetap boleh.
        function cariBentrok(tanggal, ruang, mulai, selesai, excludeId) {
            return agendaPadaRuang(tanggal, ruang, excludeId).filter(evt => {
                const r = parseRentangMenit(evt.waktu);
                return mulai < r.selesai && selesai > r.mulai;
            });
        }

        function ambilBentrokForm() {
            const tanggal = document.getElementById('form-tanggal-picker').value;
            const ruang = document.getElementById('form-ruang').value;
            const excludeId = document.getElementById('form-row-index').value;
            const mulai = menitDariSelect('form-jam-mulai');
            const selesai = menitDariSelect('form-jam-selesai');

            if (mulai === null || selesai === null || selesai <= mulai) return [];

            return cariBentrok(tanggal, ruang, mulai, selesai, excludeId);
        }

        function perbaruiInfoBooking() {
            const box = document.getElementById('booking-info');
            if (!box) return;

            const tanggal = document.getElementById('form-tanggal-picker').value;
            const ruang = document.getElementById('form-ruang').value.trim();
            const excludeId = document.getElementById('form-row-index').value;

            if (!tanggal || !ruang) {
                box.className = 'hidden mt-2';
                box.innerHTML = '';
                return;
            }

            const terpakai = agendaPadaRuang(tanggal, ruang, excludeId);
            const bentrok = ambilBentrokForm();

            const daftar = terpakai.map(evt => `
                <li class="flex gap-2">
                    <span class="font-bold whitespace-nowrap">${escapePiket(evt.waktu)}</span>
                    <span class="break-words">${escapePiket(evt.acara)}</span>
                </li>
            `).join('');

            if (bentrok.length > 0) {
                box.className = 'mt-2 p-3 rounded-xl border border-red-200 bg-red-50 text-red-800 text-xs space-y-1.5';
                box.innerHTML = `
                    <p class="font-bold"><i class="fa-solid fa-circle-exclamation mr-1"></i> Ruangan sudah dibooking pada jam tersebut</p>
                    <ul class="space-y-1">${daftar}</ul>
                    <p>Silakan pilih jam atau ruangan lain.</p>
                `;
            } else if (terpakai.length > 0) {
                box.className = 'mt-2 p-3 rounded-xl border border-amber-200 bg-amber-50 text-amber-900 text-xs space-y-1.5';
                box.innerHTML = `
                    <p class="font-bold"><i class="fa-solid fa-circle-check mr-1"></i> Jam yang Anda pilih masih kosong</p>
                    <p>Jadwal lain di ruangan ini pada tanggal tersebut:</p>
                    <ul class="space-y-1">${daftar}</ul>
                `;
            } else {
                box.className = 'mt-2 p-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs';
                box.innerHTML = `<p class="font-bold"><i class="fa-solid fa-circle-check mr-1"></i> Ruangan tersedia sepanjang hari pada tanggal ini</p>`;
            }
        }

        function openFormAgendaModal() {
            document.getElementById('form-agenda-modal').classList.remove('hidden');
            document.getElementById('form-modal-title').innerText = 'Tambah Agenda Baru';
            document.getElementById('form-row-index').value = '';

            document.getElementById('form-tanggal-picker').value = '';
            
            isiPilihanJam();
            document.getElementById('form-jam-mulai').value = '09:00';
            document.getElementById('form-jam-selesai').value = '10:00';

            document.getElementById('form-acara').value = '';
            document.getElementById('form-disposisi').value = '';
            document.getElementById('form-pelaksana-manual').value = '';

            document.querySelectorAll('.bidang-checkbox').forEach(cb => cb.checked = false);
            
            // Default terpilih: Ruang Rapat Batu Dinding Bappeda
            document.querySelectorAll('.ruang-radio').forEach(rb => {
                if(rb.value === 'Ruang Rapat Batu Dinding Bappeda') {
                    rb.checked = true;
                    setRuang(rb.value);
                } else {
                    rb.checked = false;
                }
            });
        }

        function openEditForm(evt) {
            document.getElementById('form-agenda-modal').classList.remove('hidden');
            document.getElementById('form-modal-title').innerText = 'Edit Agenda';
            document.getElementById('form-row-index').value = evt.id;

            document.getElementById('form-tanggal-picker').value = evt.tanggal_kegiatan;
            aturPilihanWaktu(evt.waktu || '');
            document.getElementById('form-acara').value = evt.acara;
            document.getElementById('form-ruang').value = evt.ruang;
            document.getElementById('form-disposisi').value = evt.disposisi !== '-' ? evt.disposisi : '';
            document.getElementById('form-pelaksana-manual').value = '';

            const bidangList = (evt.bidang || '').split(', ').map(b => b.trim());
            document.querySelectorAll('.bidang-checkbox').forEach(cb => {
                cb.checked = bidangList.includes(cb.value);
            });

            let matchRuang = false;
            document.querySelectorAll('.ruang-radio').forEach(rb => {
                if (rb.value === evt.ruang) {
                    rb.checked = true;
                    matchRuang = true;
                }
            });
            if (!matchRuang) {
                document.querySelectorAll('.ruang-radio').forEach(rb => {
                    if (rb.value === 'manual') rb.checked = true;
                });
            }

            perbaruiInfoBooking();
        }

        function closeFormAgendaModal() { 
            document.getElementById('form-agenda-modal').classList.add('hidden'); 
        }

        function openFormApelModal() {
            document.getElementById('form-apel-modal').classList.remove('hidden');
            document.getElementById('form-apel-modal-title').innerText = 'Tambah Jadwal Pegawai';
            document.getElementById('form-apel-index').value = '';

            const now = new Date();
            const jakartaDate = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
            const yyyy = jakartaDate.getFullYear();
            const mm = String(jakartaDate.getMonth() + 1).padStart(2, '0');
            const dd = String(jakartaDate.getDate()).padStart(2, '0');

            document.getElementById('form-apel-tanggal').value = `${yyyy}-${mm}-${dd}`;
            document.getElementById('form-apel-hari').value = 'Senin';
            document.getElementById('form-apel-pembina').value = '';
            document.getElementById('form-apel-pemimpin').value = '';
            document.getElementById('form-apel-ket').value = 'Apel Pagi';
        }

        function openEditApel(index) {
            const item = globalApelData[index];
            if (!item) return;
            document.getElementById('form-apel-modal').classList.remove('hidden');
            document.getElementById('form-apel-modal-title').innerText = 'Edit Jadwal Pegawai';
            document.getElementById('form-apel-index').value = index;

            document.getElementById('form-apel-tanggal').value = item.tanggal_asli || '';
            document.getElementById('form-apel-hari').value = item.hari || '';
            document.getElementById('form-apel-pembina').value = item.pembina || '';
            document.getElementById('form-apel-pemimpin').value = item.pemimpin || '';
            document.getElementById('form-apel-ket').value = item.ket || '';
        }

        function closeFormApelModal() {
            document.getElementById('form-apel-modal').classList.add('hidden');
        }

        async function handleFormApelSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-apel');
            btn.disabled = true;
            btn.innerText = "Menyimpan...";

            const index = document.getElementById('form-apel-index').value;
            const payload = {
                index: index !== "" ? parseInt(index) : null,
                tanggal: document.getElementById('form-apel-tanggal').value,
                hari: document.getElementById('form-apel-hari').value,
                pembina: document.getElementById('form-apel-pembina').value,
                pemimpin: document.getElementById('form-apel-pemimpin').value,
                ket: document.getElementById('form-apel-ket').value
            };

            try {
                const response = await apiFetch('/apel/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                let res;
                try {
                    res = await response.json();
                } catch {
                    throw new Error(`Server mengirim respons yang tidak valid (HTTP ${response.status}).`);
                }

                if (response.ok && res.success) {
                    closeFormApelModal();
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message || "Gagal menyimpan jadwal pegawai.");
                }
            } catch (error) {
                console.error('Kesalahan menyimpan jadwal pegawai:', error);
                alert('Gagal menyimpan jadwal pegawai:\n' + error.message);
            } finally {
                btn.disabled = false;
                btn.innerText = "Simpan";
            }
        }

        async function deleteApel(id) {
            if (!Number.isInteger(Number(id)) || Number(id) <= 0) {
                alert('ID jadwal tidak valid.');
                return;
            }

            if (!confirm('Yakin ingin menghapus jadwal pegawai ini?')) {
                return;
            }

            try {
                const response = await apiFetch(`/apel/delete/${Number(id)}`, {
                    method: 'DELETE'
                });

                const res = await response.json();

                if (response.ok && res.success) {
                    alert('Jadwal pegawai berhasil dihapus!');
                    location.reload();
                } else {
                    alert(res.message || 'Gagal menghapus jadwal pegawai.');
                }
            } catch (error) {
                console.error('Gagal menghapus jadwal:', error);
                alert('Terjadi kesalahan saat menghapus jadwal. Silakan coba lagi.');
            }
        }

        async function handleFormAgendaSubmit(e) {
            e.preventDefault();

            const btn = document.getElementById('btn-save-agenda');

            if (!btn || btn.disabled) return;

            btn.disabled = true;
            btn.innerText = 'Menyimpan...';

            try {
                const rentangWaktu = ambilRentangWaktu();

                if (!rentangWaktu) {
                    throw new Error('Silakan pilih jam mulai dan jam selesai.');
                }

                const pickerVal = document.getElementById('form-tanggal-picker').value;
                const acara = document.getElementById('form-acara').value.trim();
                const ruang = document.getElementById('form-ruang').value;
                const disposisi = document.getElementById('form-disposisi').value;

                if (!pickerVal) {
                    throw new Error('Tanggal kegiatan wajib diisi.');
                }

                if (!acara) {
                    throw new Error('Uraian kegiatan wajib diisi.');
                }

                if (!ruang) {
                    throw new Error('Lokasi kegiatan wajib dipilih.');
                }

                const bentrok = ambilBentrokForm();

                if (bentrok.length > 0) {
                    throw new Error(
                        'Ruangan sudah dibooking pada jam tersebut:\n' +
                        bentrok.map(e => `- ${e.waktu}: ${e.acara}`).join('\n') +
                        '\n\nSilakan pilih jam atau ruangan lain.'
                    );
                }

                const selectedBidang = [];

                document.querySelectorAll('.bidang-checkbox:checked').forEach(cb => {
                    selectedBidang.push(cb.value);
                });

                const inputManual = document.getElementById('form-pelaksana-manual');
                const pelaksanaManual = inputManual ? inputManual.value.trim() : '';

                if (pelaksanaManual) {
                    selectedBidang.push(pelaksanaManual);
                }

                const bidangString = selectedBidang.length
                    ? selectedBidang.join(', ')
                    : '-';

                const payload = {
                    id: document.getElementById('form-row-index').value || null,
                    tanggal_kegiatan: pickerVal,
                    hari: new Date(pickerVal + 'T12:00:00').toLocaleDateString('id-ID', {
                        weekday: 'long',
                        timeZone: 'Asia/Jakarta'
                    }),
                    waktu: rentangWaktu,
                    bidang: bidangString,
                    acara: acara,
                    ruang: ruang,
                    disposisi: disposisi
                };

                // apiFetch otomatis menyertakan token CSRF terbaru dan
                // mengulang sekali bila server menjawab 419.
                const response = await apiFetch('/agenda/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (response.status === 419) {
                    throw new Error('Sesi habis. Silakan muat ulang halaman (Ctrl + F5) lalu login kembali.');
                }

                let result;

                try {
                    result = await response.json();
                } catch {
                    throw new Error(
                        `Server mengirim respons yang tidak valid (HTTP ${response.status}). Periksa log Laravel.`
                    );
                }

                if (!response.ok || !result.success) {
                    let message = result.message || 'Agenda gagal disimpan.';

                    if (result.errors) {
                        message += '\n' + Object.values(result.errors).flat().join('\n');
                    }

                    throw new Error(message);
                }

                alert(result.message || 'Agenda berhasil disimpan!');
                closeFormAgendaModal();
                closeAgendaModal();
                window.location.reload();

            } catch (error) {
                console.error('Kesalahan menyimpan agenda:', error);
                alert('Gagal menyimpan agenda:\n' + error.message);

            } finally {
                btn.disabled = false;
                btn.innerText = 'Simpan';
            }
        }

        async function deleteAgenda(id) {
            if (!confirm("Yakin ingin menghapus agenda ini?")) return;

            try {
                const response = await apiFetch(`/agenda/delete/${id}`, { method: 'DELETE' });
                const res = await response.json();

                if (response.ok && res.success) {
                    closeAgendaModal();
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message || "Gagal menghapus agenda.");
                }
            } catch (error) {
                console.error('Kesalahan menghapus agenda:', error);
                alert('Gagal menghapus agenda:\n' + error.message);
            }
        }

        function renderDashboardList() {
            const container = document.getElementById('dashboard-agenda-list');
            const headerTgl = document.getElementById('dashboard-header-tanggal');
            const headerCount = document.getElementById('dashboard-header-count');
            if (!container) return;

            if (!globalAgendaData || globalAgendaData.length === 0) {
                if (headerTgl) headerTgl.innerText = "Arsip Agenda";
                if (headerCount) headerCount.innerText = "0 agenda kegiatan";
                container.innerHTML = `<div class="p-6 bg-slate-50 border rounded-xl text-center text-xs text-slate-500">Belum ada data agenda di database.</div>`;
                return;
            }

            const tanggalHariIni = new Date().toLocaleDateString('en-CA', {
                timeZone: 'Asia/Jakarta'
            });

            const todayGroup =
                globalAgendaData.find(item => item.isoDate === tanggalHariIni) ||
                globalAgendaData.find(item => item.isoDate >= tanggalHariIni) ||
                globalAgendaData[0];

            if (todayGroup && todayGroup.subEvents) {
                if (headerTgl) headerTgl.innerText = `${todayGroup.tanggal} (${todayGroup.hari})`;
                if (headerCount) headerCount.innerText = `${todayGroup.subEvents.length} agenda kegiatan terjadwal`;
                container.innerHTML = todayGroup.subEvents.map(evt => `
                    <div class="bg-white border-l-4 border-blue-600 rounded-xl p-4 shadow-sm border border-slate-200 text-xs space-y-2">
                        <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1.5 font-bold text-blue-800 bg-blue-50 p-2 rounded">
                            <span class="flex items-center gap-1 shrink-0 whitespace-nowrap"><i class="fa-solid fa-clock"></i> ${evt.waktu}</span>
                            <span class="uppercase flex-1 min-w-[10rem] text-left sm:text-right break-words leading-snug">${evt.bidang !== '-' ? evt.bidang : 'Umum'}</span>
                        </div>
                        <h4 class="font-bold text-slate-900"><i class="fa-solid fa-file-lines text-blue-600 mr-1"></i>${evt.acara}</h4>
                        <p class="text-slate-500"><i class="fa-solid fa-location-dot text-red-500"></i> ${evt.ruang}</p>
                        ${evt.disposisi && evt.disposisi !== '-' ? `<p class="text-emerald-700 bg-emerald-50 p-2 rounded"><b>Disposisi:</b> ${evt.disposisi}</p>` : ''}
                    </div>
                `).join('');
            }
        }

        function renderAgendaTable() {
            const searchInput = document.getElementById('search-agenda');
            const bulanSelect = document.getElementById('filter-bulan');
            const tahunSelect = document.getElementById('filter-tahun');
            const bidangSelect = document.getElementById('filter-bidang');
            const tbody = document.getElementById('agenda-table-body');
            if (!tbody) return;

            const query = searchInput ? searchInput.value.toLowerCase() : '';
            const bulanFilter = bulanSelect ? bulanSelect.value.toLowerCase() : '';
            const tahunFilter = tahunSelect ? tahunSelect.value.toLowerCase() : '';
            const bidangFilter = bidangSelect ? bidangSelect.value.toLowerCase() : '';

            const filtered = globalAgendaData.filter(item => {
                const tgl = item.tanggal.toLowerCase();
                
                let translatedTgl = tgl
                    .replace('january', 'januari')
                    .replace('february', 'februari')
                    .replace('march', 'maret')
                    .replace('april', 'april')
                    .replace('may', 'mei')
                    .replace('june', 'juni')
                    .replace('july', 'juli')
                    .replace('august', 'agustus')
                    .replace('september', 'september')
                    .replace('october', 'oktober')
                    .replace('november', 'november')
                    .replace('december', 'desember');

                const matchBulan = !bulanFilter || translatedTgl.includes(bulanFilter);
                const matchTahun = !tahunFilter || translatedTgl.includes(tahunFilter);
                const matchBidang = !bidangFilter || item.bidang.toLowerCase().includes(bidangFilter) || item.subEvents.some(se => se.bidang.toLowerCase().includes(bidangFilter));
                const matchQuery = !query || translatedTgl.includes(query) || item.acara.toLowerCase().includes(query) || item.ruang.toLowerCase().includes(query);
                
                return matchBulan && matchTahun && matchBidang && matchQuery;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-slate-400">Tidak ada agenda ditemukan.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map((item) => `
                <tr class="hover:bg-slate-50 cursor-pointer" onclick="openAgendaModal(${globalAgendaData.indexOf(item)})">
                    <td class="py-3 px-3 font-semibold text-blue-700 whitespace-nowrap">${item.tanggal}</td>
                    <td class="py-3 px-3"><p class="font-bold text-slate-900"><i class="fa-solid fa-file-lines text-blue-600 mr-1"></i>${item.acara}</p><p class="text-[11px] text-slate-500"><i class="fa-solid fa-location-dot text-red-500"></i> ${item.ruang}</p></td>
                    <td class="py-3 px-3"><span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-bold">${item.bidang}</span></td>
                    <td class="py-3 px-3 text-slate-600">${isLoggedIn ? (item.disposisi !== '-' ? item.disposisi : '-') : '<span class="text-slate-400 italic">Login untuk lihat</span>'}</td>
                </tr>
            `).join('');
        }
    </script>
</body>
</html>