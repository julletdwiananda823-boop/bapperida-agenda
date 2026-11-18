<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Informasi Agenda & Disposisi Pimpinan Bapperida Kab. Belitung</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-bapperida.jpeg') }}">
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
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Pengaturan dokumen cetak */
    @page {
        size: A4 landscape;
        margin: 12mm;
    }

    @media print {
        html,
        body {
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            color: #000 !important;
            overflow: visible !important;
        }

        /* Sembunyikan seluruh halaman selain laporan */
        body * {
            visibility: hidden !important;
        }

        #print-area,
        #print-area * {
            visibility: visible !important;
        }

        #print-area {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: #fff !important;
            overflow: visible !important;
        }

        /* Kop resmi BAPPERIDA */
        #pdf-kop {
            display: block !important;
            visibility: visible !important;
            color: #000 !important;
            margin: 0 0 14px 0 !important;
        }

        #pdf-kop h2,
        #pdf-kop h3,
        #pdf-kop h4,
        #pdf-kop p {
            color: #000 !important;
        }

        /* Hilangkan scroll horizontal tabel */
        #print-area .overflow-x-auto {
            overflow: visible !important;
        }

        /* Format tabel laporan */
        #print-area table {
            width: 100% !important;
            min-width: 0 !important;
            border-collapse: collapse !important;
            table-layout: auto !important;
            font-size: 9pt !important;
        }

        #print-area thead {
            display: table-header-group !important;
        }

        #print-area tfoot {
            display: table-footer-group !important;
        }

        #print-area tr {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        #print-area th,
        #print-area td {
            border: 1px solid #555 !important;
            padding: 6px 7px !important;
            color: #000 !important;
            vertical-align: top !important;
            overflow-wrap: anywhere !important;
        }

        #print-area th {
            background: #e5e7eb !important;
            font-weight: 700 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #print-area .text-slate-400,
        #print-area .text-slate-500,
        #print-area .text-slate-600,
        #print-area .text-slate-700,
        #print-area .text-slate-800,
        #print-area .text-slate-900 {
            color: #000 !important;
        }

        /* Jangan cetak elemen interaktif di dalam area laporan */
        #print-area button,
        #print-area .no-print {
            display: none !important;
        }

        /* Hindari bayangan dan dekorasi layar */
        #print-area * {
            box-shadow: none !important;
            text-shadow: none !important;
        }
    }
</style>

</head>
<body class="bg-slate-50 text-slate-800 h-full flex flex-col">

    <!-- Top Navbar -->
    <header class="bg-navy-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center space-x-2.5">
                <div class="bg-white p-1 rounded-xl shadow-inner flex items-center justify-center shrink-0">
                    <div role="img" aria-label="Logo Bapperida" style="width:36px;height:48px;background:url('{{ asset('images/logo-bapperida.jpeg') }}') no-repeat;background-size:44.5px 92.9px;background-position:0 -22px;"></div>
                </div>
                <!-- TAMBAHAN: Logo Kabupaten Belitung -->
                <div class="bg-white p-1 rounded-xl shadow-inner flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-belitung.png') }}"
                         alt="Logo Kabupaten Belitung"
                         class="h-10 sm:h-12 w-auto object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-sm sm:text-lg tracking-tight leading-tight">BAPPERIDA KAB. BELITUNG</h1>
                    <p class="text-[10px] sm:text-xs text-blue-200">Sistem Informasi Agenda & Disposisi Pimpinan</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <div id="user-status-container" class="flex items-center gap-2">
                    <button type="button"
                            onclick="openLoginModal()"
                            class="px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login</span>
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
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'pimpinan'], true) && auth()->user()->is_active)
                    <button type="button" onclick="switchTab('laporan')" id="nav-laporan" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <i class="fa-solid fa-file-lines w-4 text-center text-blue-600"></i>
                        <span>Laporan</span>
                    </button>
                    @endif
                    @if(auth()->check() && auth()->user()->role === 'pimpinan' && auth()->user()->is_active)
                    <button type="button" onclick="switchTab('manajemen-akun')" id="nav-manajemen-akun" class="flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <i class="fa-solid fa-user-shield w-4 text-center text-emerald-600"></i>
                        <span>Manajemen Akun</span>
                    </button>
                    @endif
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
                        <p class="text-xs font-medium text-slate-500">Total Agenda Kegiatan</p>
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

                <!-- DASHBOARD: 3 HALAMAN AGENDA -->
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm">

                    <!-- HALAMAN 1: HARI INI -->
                    <section id="dashboard-page-today">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-4">
                            <div>
                                <h3 class="font-bold text-slate-800">
                                    <i class="fa-solid fa-calendar-day text-blue-600 mr-2"></i>Hari Ini
                                </h3>
                                <p id="dashboard-today-date" class="text-xs text-slate-500 mt-1">Memuat tanggal...</p>
                            </div>
                            <span id="dashboard-today-count" class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full">0 kegiatan</span>
                        </div>
                        <div id="dashboard-today-list" class="space-y-3">
                            <p class="text-xs text-slate-400 text-center py-4">Memuat agenda...</p>
                        </div>
                    </section>

                    <!-- HALAMAN 2: AKAN DATANG -->
                    <section id="dashboard-page-upcoming" class="hidden">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-4">
                            <div>
                                <h3 class="font-bold text-slate-800">
                                    <i class="fa-solid fa-calendar-plus text-emerald-600 mr-2"></i>Akan Datang
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Agenda setelah hari ini</p>
                            </div>
                            <span id="dashboard-upcoming-count" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">0 kegiatan</span>
                        </div>
                        <div id="dashboard-upcoming-list" class="space-y-3">
                            <p class="text-xs text-slate-400 text-center py-4">Memuat agenda...</p>
                        </div>
                    </section>

                    <!-- HALAMAN 3: ARSIP -->
                    <section id="dashboard-page-archive" class="hidden">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-4">
                            <div>
                                <h3 class="font-bold text-slate-800">
                                    <i class="fa-solid fa-box-archive text-slate-500 mr-2"></i>Arsip
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Agenda yang sudah berlalu</p>
                            </div>
                            <span id="dashboard-archive-count" class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full">0 kegiatan</span>
                        </div>
                        <div id="dashboard-archive-list" class="space-y-3">
                            <p class="text-xs text-slate-400 text-center py-4">Memuat arsip...</p>
                        </div>
                    </section>

                    <!-- NAVIGASI HALAMAN DASHBOARD -->
                    <div id="dashboard-pagination" class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-5 pt-4 border-t border-slate-100">
                        <p id="dashboard-pagination-info" class="text-xs text-slate-500">Menampilkan agenda...</p>
                        <div class="flex items-center gap-2">
                            <button type="button" id="dashboard-prev-button" onclick="pindahHalamanDashboard(-1)"
                                class="px-3 py-2 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                Sebelumnya
                            </button>
                            <span id="dashboard-page-number" class="text-xs font-semibold text-slate-700 whitespace-nowrap">1 / 3</span>
                            <button type="button" id="dashboard-next-button" onclick="pindahHalamanDashboard(1)"
                                class="px-3 py-2 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                Berikutnya
                            </button>
                        </div>
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
                                    <input type="text" id="search-agenda" oninput="renderAgendaTable(); renderAgendaCalendar();" placeholder="Cari acara, tempat..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            </div>
                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Bulan</label>
                                <select id="filter-bulan" onchange="renderAgendaTable(); renderAgendaCalendar();" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none">
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
                                <select id="filter-tahun" onchange="renderAgendaTable(); renderAgendaCalendar();" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none">
                                    <option value="">Semua Tahun</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-1 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <p class="text-[11px] text-slate-400">Semua Bulan dan Semua Tahun juga tetap tersedia sebagai pilihan.</p>
                            <select id="filter-bidang" onchange="renderAgendaTable(); renderAgendaCalendar();" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 focus:outline-none">
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

                    <!-- PILIHAN TAMPILAN AGENDA -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                        <p class="text-xs text-slate-500">
                            Pilih tampilan agenda:
                        </p>
                        <div class="flex gap-2">
                            <button type="button"
                                id="btn-view-table"
                                onclick="setAgendaView('table')"
                                class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white cursor-pointer">
                                <i class="fa-solid fa-table-list mr-1"></i> Tabel
                            </button>
                            <button type="button"
                                id="btn-view-calendar"
                                onclick="setAgendaView('calendar')"
                                class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 cursor-pointer">
                                <i class="fa-solid fa-calendar-days mr-1"></i> Kalender
                            </button>
                        </div>
                    </div>

                    <!-- AREA KALENDER BULANAN -->
                    <div id="agenda-calendar-view" class="hidden border border-slate-200 rounded-2xl p-3 sm:p-5 mb-4">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div>
                                <h3 id="calendar-month-title" class="text-lg font-bold text-slate-800">
                                    Kalender Agenda
                                </h3>
                                <p class="text-xs text-slate-500">Pilih tanggal untuk melihat kegiatan.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="changeAgendaCalendarMonth(-1)"
                                    class="w-9 h-9 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer"
                                    aria-label="Bulan sebelumnya">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <button type="button" onclick="goToAgendaCalendarToday()"
                                    class="px-3 h-9 rounded-lg border border-slate-200 text-xs font-semibold hover:bg-slate-50 cursor-pointer">
                                    Hari Ini
                                </button>
                                <button type="button" onclick="changeAgendaCalendarMonth(1)"
                                    class="w-9 h-9 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer"
                                    aria-label="Bulan berikutnya">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-7 gap-1 text-center mb-1">
                            <div class="py-2 text-xs font-semibold text-slate-500">Min</div>
                            <div class="py-2 text-xs font-semibold text-slate-500">Sen</div>
                            <div class="py-2 text-xs font-semibold text-slate-500">Sel</div>
                            <div class="py-2 text-xs font-semibold text-slate-500">Rab</div>
                            <div class="py-2 text-xs font-semibold text-slate-500">Kam</div>
                            <div class="py-2 text-xs font-semibold text-slate-500">Jum</div>
                            <div class="py-2 text-xs font-semibold text-slate-500">Sab</div>
                        </div>

                        <div id="agenda-calendar-grid" class="grid grid-cols-7 gap-1"></div>

                        <!-- TAMPILAN KHUSUS HP (Disembunyikan) -->
                        <div id="agenda-calendar-mobile" class="hidden"></div>

                        <p class="text-xs text-slate-500 mt-3">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-600 mr-1"></span>
                            Ada agenda pada tanggal tersebut.
                        </p>
                    </div>

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle px-4 sm:px-0">
                            <table class="min-w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                        <th class="py-2.5 px-3">Tanggal</th>
                                        <th class="py-2.5 px-3">Acara & Lokasi</th>
                                        <th class="py-2.5 px-3">Bidang</th>
                                        <th id="agenda-disposisi-header" class="py-2.5 px-3">Disposisi</th>
                                    </tr>
                                </thead>
                                <tbody id="agenda-table-body" class="divide-y divide-slate-100 text-xs"></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="agenda-pagination"
                         class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 px-2">
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
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'pimpinan'], true) && auth()->user()->is_active)
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

                    <!-- Panel Filter Laporan -->
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
                        <div id="pdf-kop" class="hidden mb-4 text-black">

                            <div class="flex items-center gap-4 pb-3">

                                <!-- LOGO KABUPATEN BELITUNG -->
                                <div class="w-20 shrink-0 flex items-center justify-center">
                                    <img
                                        src="{{ asset('images/logo-belitung.png') }}"
                                        alt="Logo Kabupaten Belitung"
                                        class="w-16 h-20 object-contain"
                                    >
                                </div>

                                <!-- IDENTITAS INSTANSI -->
                                <div class="flex-1 text-center leading-tight">
                                    <p class="text-sm font-medium">
                                        PEMERINTAH KABUPATEN BELITUNG
                                    </p>

                                    <h2 class="text-base font-extrabold uppercase mt-1">
                                        BADAN PERENCANAAN PEMBANGUNAN, RISET
                                        <br>
                                        DAN INOVASI DAERAH
                                    </h2>

                                    <p class="text-[10px] mt-1">
                                        Jl. Jend. A. Yani No. 99 Tanjungpandan Belitung, 33412
                                    </p>

                                    <p class="text-[10px]">
                                        Telp/Fax. (0719) 21666, 21066
                                        E-mail: bpd.belitung@yahoo.com
                                        Laman: belitung.go.id
                                    </p>
                                </div>

                                <!-- PENYEIMBANG LOGO -->
                                <div class="w-20 shrink-0"></div>

                            </div>

                            <!-- GARIS KOP -->
                            <div class="border-t-[3px] border-black mb-3"></div>

                            <!-- JUDUL LAPORAN -->
                            <div class="text-center">
                                <h4 class="font-bold text-sm underline uppercase">
                                    LAPORAN AGENDA & DISPOSISI PIMPINAN
                                </h4>

                                <p id="pdf-periode" class="text-xs mt-1">
                                    Periode: Semua Riwayat
                                </p>
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
            @endif

            <!-- TAB MANAJEMEN AKUN KHUSUS PIMPINAN -->
            @if(auth()->check() && auth()->user()->role === 'pimpinan' && auth()->user()->is_active)
            <div id="tab-manajemen-akun" class="hidden space-y-5">

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <i class="fa-solid fa-user-shield text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Manajemen Akun</h2>
                            <p class="text-xs text-slate-500">Pengelolaan akun Admin dan PIN Pegawai BAPPERIDA</p>
                        </div>
                    </div>
                </div>

                <!-- JUMLAH ADMIN -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <p class="text-sm text-slate-500 font-semibold">Jumlah Akun Admin</p>
                    <div class="flex items-center justify-between mt-2">
                        <h3 id="management-admin-total" class="text-3xl font-bold text-blue-700">0</h3>
                        <i class="fa-solid fa-users text-3xl text-blue-200"></i>
                    </div>
                </div>

                <!-- TAMBAH ADMIN -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-1">
                        <i class="fa-solid fa-user-plus text-emerald-600 mr-2"></i>
                        Tambah Akun Admin
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">
                        Pimpinan dapat membuat akun Admin baru dan melihat password yang sedang diketik sebelum disimpan.
                    </p>

                    <form id="management-create-admin-form" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="management-admin-name" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Nama Admin <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="management-admin-name" required maxlength="100"
                                    placeholder="Contoh: Admin Bidang PPEPD"
                                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                            </div>

                            <div>
                                <label for="management-admin-email" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Email Admin <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="management-admin-email" required maxlength="255"
                                    placeholder="Contoh: ppepd@bapperida.go.id"
                                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="management-admin-password" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Password Admin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="management-admin-password"
                                        required
                                        minlength="6"
                                        maxlength="100"
                                        placeholder="Minimal 6 karakter"
                                        autocomplete="new-password"
                                        class="w-full border border-slate-300 rounded-xl pl-4 pr-11 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                                    <button type="button"
                                        onclick="togglePasswordVisibility('management-admin-password', 'management-admin-password-eye')"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                        title="Tampilkan / Sembunyikan Password">
                                        <i id="management-admin-password-eye" class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="management-admin-password-confirmation" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Konfirmasi Password Admin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="management-admin-password-confirmation"
                                        required
                                        minlength="6"
                                        maxlength="100"
                                        placeholder="Ketik ulang password"
                                        autocomplete="new-password"
                                        class="w-full border border-slate-300 rounded-xl pl-4 pr-11 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                                    <button type="button"
                                        onclick="togglePasswordVisibility('management-admin-password-confirmation', 'management-admin-password-confirm-eye')"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                        title="Tampilkan / Sembunyikan Password">
                                        <i id="management-admin-password-confirm-eye" class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                            <label class="inline-flex items-center gap-2 text-xs text-slate-600 cursor-pointer select-none">
                                <input type="checkbox" id="management-create-toggle-all"
                                    onchange="toggleAllPasswords(['management-admin-password', 'management-admin-password-confirmation'], ['management-admin-password-eye', 'management-admin-password-confirm-eye'], this.checked)"
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Lihat password yang sedang diketik</span>
                            </label>

                            <button type="submit" id="management-create-admin-button"
                                class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl px-5 py-2.5 text-sm font-bold shadow-sm transition-all cursor-pointer">
                                <i class="fa-solid fa-plus mr-1"></i> Buat Akun Admin
                            </button>
                        </div>
                    </form>
                </div>

                <!-- DAFTAR ADMIN -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800">
                                <i class="fa-solid fa-users-gear text-blue-600 mr-2"></i>
                                Daftar Akun Admin
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Pimpinan dapat mereset password akun Admin kapan pun diperlukan.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openResetAdminPasswordModal()"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3.5 py-2 rounded-xl transition-colors cursor-pointer">
                                <i class="fa-solid fa-key text-blue-600"></i>
                                <span>Reset Password Admin</span>
                            </button>
                            <button type="button" onclick="loadAccountManagement()"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-xl transition-colors cursor-pointer">
                                <i class="fa-solid fa-rotate"></i>
                                <span>Muat Ulang</span>
                            </button>
                        </div>
                    </div>

                    <div id="management-admin-list" class="space-y-3">
                        <p class="text-sm text-slate-500">Data Admin belum dimuat.</p>
                    </div>
                </div>

                <!-- GANTI PIN PEGAWAI -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-2">
                        <i class="fa-solid fa-key text-amber-600 mr-2"></i>
                        Ganti PIN Pegawai
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">
                        PIN baru akan digunakan bersama oleh Pegawai untuk masuk ke aplikasi.
                    </p>

                    <form id="management-staff-pin-form" class="space-y-3">
                        @csrf
                        <input type="text"
                            id="management-staff-pin"
                            required
                            minlength="4"
                            maxlength="20"
                            placeholder="Masukkan PIN Pegawai baru"
                            autocomplete="off"
                            autocapitalize="off"
                            spellcheck="false"
                            style="-webkit-text-security: disc;"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">

                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white rounded-xl px-5 py-3 text-sm font-bold">
                            <i class="fa-solid fa-lock mr-1"></i> Simpan PIN Baru
                        </button>
                    </form>
                </div>

            </div>
            @endif

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
                @csrf
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
                    <!-- Info ketersediaan ruangan -->
                    <div id="booking-info" class="hidden mt-2"></div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1"><i class="fa-solid fa-pen-nib text-emerald-600 mr-1"></i> Disposisi Pimpinan</label>
                    <textarea id="form-disposisi" rows="3" placeholder="Tuliskan catatan disposisi pimpinan di sini..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"></textarea>
                </div>

                <!-- LAMPIRAN DOKUMEN AGENDA (DRAG & DROP, PILIHAN AKSES PUBLIK / INTERNAL) -->
                <div class="border border-slate-200 rounded-2xl p-3.5 bg-slate-50/70 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-bold text-slate-700 text-xs">
                            <i class="fa-solid fa-paperclip text-blue-600 mr-1.5"></i>
                            Lampiran Dokumen
                        </label>
                        <span class="text-[10px] text-slate-400 font-medium">Maks. 5 file, maks. 10 MB/file</span>
                    </div>

                    <!-- AREA DRAG & DROP -->
                    <div id="agenda-drop-zone"
                        class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-white rounded-xl p-4 text-center cursor-pointer transition-all duration-200">
                        <input
                            type="file"
                            id="form-agenda-attachments"
                            name="attachments[]"
                            multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                            class="hidden"
                            onchange="handleAgendaFileSelect(this.files)"
                        >
                        <div class="flex flex-col items-center justify-center pointer-events-none">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-1.5 text-lg">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-700">
                                Tarik & lepaskan file di sini, atau <span class="text-blue-600 underline">pilih file</span>
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                Format: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG
                            </p>
                        </div>
                    </div>

                    <!-- DAFTAR FILE LAMA (Saat Edit Agenda) -->
                    <div id="existing-attachments-container" class="hidden space-y-1.5">
                        <p class="text-[11px] font-semibold text-slate-600 flex items-center gap-1">
                            <i class="fa-solid fa-folder-closed text-slate-400"></i>
                            Lampiran Tersimpan:
                        </p>
                        <div id="existing-attachments-list" class="space-y-1.5"></div>
                    </div>

                    <!-- DAFTAR FILE BARU YANG AKAN DIUNGGAH -->
                    <div id="staged-attachments-container" class="hidden space-y-1.5">
                        <p class="text-[11px] font-semibold text-slate-600 flex items-center gap-1">
                            <i class="fa-solid fa-file-circle-plus text-emerald-600"></i>
                            File Baru yang Dipilih:
                        </p>
                        <div id="staged-attachments-list" class="space-y-1.5"></div>
                    </div>
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
                @csrf
                <input type="hidden" id="form-apel-index">
                <div class="space-y-3">
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Tanggal (Pilih Kalender)</label>
                        <input type="date" id="form-apel-tanggal" onchange="onApelTanggalChange()" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Hari (Otomatis)</label>
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
    <div id="login-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-3 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl border border-slate-100 relative my-auto">

            <button type="button" onclick="closeLoginModal()" class="absolute right-4 top-4 text-slate-400 hover:text-slate-700 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="text-center mb-5">
                <div class="w-11 h-11 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center mx-auto mb-2 text-lg">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Login Sistem BAPPERIDA</h3>
                <p class="text-xs text-slate-500 mt-1">Pilih akses sesuai kewenangan Anda.</p>
            </div>

            <!-- PILIHAN AKSES -->
            <div id="login-option-menu" class="space-y-3">

                <button type="button" onclick="showLoginOption('pimpinan')" class="w-full flex items-center gap-3 p-4 border border-slate-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 text-left cursor-pointer">
                    <span class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                        <i class="fa-solid fa-user-tie"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-slate-800">Pimpinan</span>
                        <span class="block text-xs text-slate-500">Login menggunakan email dan password</span>
                    </span>
                    <i class="fa-solid fa-chevron-right ml-auto text-slate-400"></i>
                </button>

                <button type="button" onclick="showLoginOption('admin')" class="w-full flex items-center gap-3 p-4 border border-slate-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 text-left cursor-pointer">
                    <span class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        <i class="fa-solid fa-user-shield"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-slate-800">Admin</span>
                        <span class="block text-xs text-slate-500">Kelola agenda, disposisi, dan booking ruangan</span>
                    </span>
                    <i class="fa-solid fa-chevron-right ml-auto text-slate-400"></i>
                </button>

                <button type="button" onclick="showLoginOption('staff')" class="w-full flex items-center gap-3 p-4 border border-slate-200 rounded-xl hover:bg-emerald-50 hover:border-emerald-300 text-left cursor-pointer">
                    <span class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i class="fa-solid fa-users"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-slate-800">Pegawai</span>
                        <span class="block text-xs text-slate-500">Login menggunakan satu PIN bersama</span>
                    </span>
                    <i class="fa-solid fa-chevron-right ml-auto text-slate-400"></i>
                </button>
            </div>

            <!-- FORM PIMPINAN / ADMIN -->
            <div id="login-admin-form-wrapper" class="hidden">
                <h4 id="login-admin-title" class="font-bold text-slate-800 text-sm mb-3">Login Admin</h4>

                <div id="admin-login-error"
                     class="hidden mb-3 rounded-lg bg-red-50 border border-red-200 p-3 text-xs text-red-700">
                </div>

                <form id="admin-login-form"
                      method="POST"
                      action="{{ route('admin.login.submit') }}"
                      class="space-y-3">
                    @csrf
                    <input type="hidden" name="role" id="login-role" value="admin">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@bapperida.go.id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" id="login-password" name="password" required
                                   autocomplete="current-password" placeholder="Masukkan password"
                                   class="w-full pl-3 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            <button type="button"
                                    onclick="togglePasswordVisibility('login-password', 'login-password-eye')"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    title="Tampilkan / Sembunyikan Password"
                                    aria-label="Tampilkan atau sembunyikan password">
                                <i id="login-password-eye" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <button id="login-admin-submit" type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-xl text-sm cursor-pointer">
                        Masuk sebagai Admin
                    </button>
                </form>

                <button type="button" onclick="showLoginOption('menu')" class="w-full mt-3 py-2 text-xs text-slate-500 hover:text-slate-800 cursor-pointer">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </button>
            </div>

            <!-- FORM PEGAWAI -->
            <div id="login-staff-form" class="hidden">
                <h4 class="font-bold text-slate-800 text-sm mb-3">Login Pegawai</h4>

                <div id="staff-login-error"
                     class="hidden mb-3 rounded-lg bg-red-50 border border-red-200 p-3 text-xs text-red-700">
                </div>

                <form id="staff-login-form"
                      method="POST"
                      action="{{ route('staff.login.submit') }}"
                      class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">PIN Pegawai</label>
                        <div class="relative">
                            <input type="password" id="login-staff-pin" name="pin" required inputmode="numeric"
                                   autocomplete="current-password" placeholder="Masukkan PIN bersama"
                                   class="w-full pl-3 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                            <button type="button"
                                    onclick="togglePasswordVisibility('login-staff-pin', 'login-staff-pin-eye')"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    title="Tampilkan / Sembunyikan PIN"
                                    aria-label="Tampilkan atau sembunyikan PIN">
                                <i id="login-staff-pin-eye" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm cursor-pointer">
                        Masuk sebagai Pegawai
                    </button>
                </form>

                <button type="button" onclick="showLoginOption('menu')" class="w-full mt-3 py-2 text-xs text-slate-500 hover:text-slate-800 cursor-pointer">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </button>
            </div>

        </div>
    </div>

    <!-- Notifikasi aplikasi BAPPERIDA -->
    <div id="app-toast"
         class="hidden fixed top-5 right-5 z-[9999] max-w-sm w-[calc(100%-2rem)] rounded-xl border bg-white p-4 shadow-xl">
        <div class="flex items-start gap-3">
            <div id="app-toast-icon"
                 class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold">
                ✓
            </div>
            <div class="flex-1">
                <p id="app-toast-title" class="font-bold text-slate-800 text-sm">Berhasil</p>
                <p id="app-toast-message" class="mt-1 text-sm text-slate-600 whitespace-pre-line"></p>
            </div>
            <button type="button"
                    onclick="hideAppToast()"
                    class="text-slate-400 hover:text-slate-700 text-xl leading-none cursor-pointer"
                    aria-label="Tutup notifikasi">&times;</button>
        </div>
    </div>

    <!-- MODAL KONFIRMASI BAPPERIDA -->
    <div id="bapperida-confirm-modal"
         class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">

            <div class="px-6 pt-6 pb-4">
                <div class="flex items-center gap-3 mb-4">
                    <div id="bapperida-confirm-icon"
                         class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-xl"></i>
                    </div>

                    <div>
                        <h3 id="bapperida-confirm-title"
                            class="text-lg font-bold text-slate-800">
                            Konfirmasi Tindakan
                        </h3>
                        <p class="text-xs text-slate-500">
                            Sistem Informasi BAPPERIDA
                        </p>
                    </div>
                </div>

                <p id="bapperida-confirm-message"
                   class="text-sm text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin melanjutkan tindakan ini?
                </p>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                <button type="button"
                        id="bapperida-confirm-cancel"
                        class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-100 cursor-pointer">
                    Batal
                </button>

                <button type="button"
                        id="bapperida-confirm-yes"
                        class="px-5 py-2.5 rounded-xl bg-blue-700 text-white text-sm font-semibold hover:bg-blue-800 cursor-pointer">
                    Ya, Lanjutkan
                </button>
            </div>

        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'pimpinan' && auth()->user()->is_active)
    <!-- MODAL RESET PASSWORD ADMIN OLEH PIMPINAN -->
    <div id="bapperida-reset-password-modal"
         class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <i class="fa-solid fa-key text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">
                            Reset Password Admin
                        </h3>
                        <p class="text-xs text-slate-500">
                            Atur ulang password akun Admin BAPPERIDA
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeResetAdminPasswordModal()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none font-bold p-1 cursor-pointer" aria-label="Tutup">&times;</button>
            </div>

            <form id="bapperida-reset-password-form" onsubmit="submitResetAdminPassword(event)" class="p-6 space-y-4 overflow-y-auto">
                <div class="bg-blue-50/80 border border-blue-200/80 rounded-xl p-3.5 text-xs text-blue-900 leading-relaxed flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info text-blue-600 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <strong>Catatan Keamanan:</strong> Password lama tersimpan dalam bentuk hash satu arah (one-way encryption) Laravel sehingga tidak dapat dikembalikan ke teks asli. Untuk mengetahui dan mengganti password yang digunakan oleh Admin, silakan buat password baru di bawah ini. Anda dapat melihat password sebelum menyimpannya.
                    </div>
                </div>

                <div>
                    <label for="reset-modal-admin-id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Pilih Akun Admin <span class="text-red-500">*</span>
                    </label>
                    <select id="reset-modal-admin-id" required onchange="updateResetModalAdminInfo()"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih Akun Admin --</option>
                    </select>
                    <p id="reset-modal-admin-info" class="text-xs text-slate-500 mt-1.5 hidden"></p>
                </div>

                <div>
                    <label for="reset-modal-password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                            id="reset-modal-password"
                            required
                            minlength="6"
                            maxlength="100"
                            placeholder="Masukkan password baru (minimal 6 karakter)"
                            autocomplete="new-password"
                            class="w-full border border-slate-300 rounded-xl pl-4 pr-11 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <button type="button"
                            onclick="togglePasswordVisibility('reset-modal-password', 'reset-modal-password-eye')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                            title="Tampilkan / Sembunyikan Password">
                            <i id="reset-modal-password-eye" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="reset-modal-password-confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                            id="reset-modal-password-confirmation"
                            required
                            minlength="6"
                            maxlength="100"
                            placeholder="Ketik ulang password baru"
                            autocomplete="new-password"
                            class="w-full border border-slate-300 rounded-xl pl-4 pr-11 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <button type="button"
                            onclick="togglePasswordVisibility('reset-modal-password-confirmation', 'reset-modal-password-confirm-eye')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                            title="Tampilkan / Sembunyikan Password">
                            <i id="reset-modal-password-confirm-eye" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-xs">
                    <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" id="reset-modal-toggle-all"
                            onchange="toggleAllPasswords(['reset-modal-password', 'reset-modal-password-confirmation'], ['reset-modal-password-eye', 'reset-modal-password-confirm-eye'], this.checked)"
                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>Lihat teks password yang sedang diketik</span>
                    </label>

                    <button type="button" onclick="generateRandomResetPassword()"
                        class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Buat Password Acak</span>
                    </button>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button"
                        onclick="closeResetAdminPasswordModal()"
                        class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-100 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        id="reset-modal-submit-button"
                        class="px-5 py-2.5 rounded-xl bg-blue-700 text-white text-sm font-bold hover:bg-blue-800 shadow-sm transition-all cursor-pointer">
                        <i class="fa-solid fa-check mr-1.5"></i> Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SUKSES RESET PASSWORD ADMIN -->
    <div id="bapperida-reset-success-modal"
         class="hidden fixed inset-0 z-[10000] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Password Berhasil Diubah!
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Password akun Admin telah berhasil diperbarui di sistem BAPPERIDA.
                </p>

                <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl text-left space-y-2.5">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Nama Akun Admin</span>
                        <span id="reset-success-name" class="text-sm font-bold text-slate-800">-</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Email Login Admin</span>
                        <span id="reset-success-email" class="text-xs font-semibold text-slate-600">-</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Password Baru</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="reset-success-password" class="flex-1 bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono font-bold text-slate-900 select-all tracking-wider break-all">-</span>
                            <button type="button"
                                onclick="copyResetPasswordToClipboard()"
                                class="bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 px-3 py-2 rounded-lg text-xs font-semibold inline-flex items-center gap-1 cursor-pointer transition-colors"
                                title="Salin password ke clipboard">
                                <i id="reset-copy-icon" class="fa-regular fa-copy"></i>
                                <span id="reset-copy-text">Salin</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl p-3 text-left flex items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xs mt-0.5 shrink-0"></i>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Harap berikan password baru ini kepada Admin terkait agar dapat segera digunakan untuk login ke aplikasi.
                    </p>
                </div>

                <div class="mt-5">
                    <button type="button"
                        onclick="closeResetSuccessModal()"
                        class="w-full bg-slate-800 hover:bg-slate-900 text-white rounded-xl py-2.5 text-sm font-bold cursor-pointer">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL PREVIEW DOKUMEN / GAMBAR INTERAKTIF -->
    <div id="attachment-preview-modal" class="hidden fixed inset-0 z-[10001] flex items-center justify-center bg-slate-950/75 backdrop-blur-sm p-2 sm:p-4">
        <div class="bg-white w-full max-w-4xl h-[88vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-scale-in border border-slate-200">
            <!-- HEADER PREVIEW -->
            <div class="px-5 py-3.5 bg-slate-900 text-white flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div id="preview-modal-icon" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h4 id="preview-modal-title" class="font-bold text-sm truncate max-w-xs sm:max-w-md md:max-w-xl text-white">Nama Dokumen</h4>
                        <div class="flex items-center gap-2 text-[11px] text-slate-300">
                            <span id="preview-modal-meta">Ukuran File</span>
                            <span id="preview-modal-badge" class="px-2 py-0.2 rounded-full font-bold uppercase text-[9px]"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a id="preview-modal-download-btn" href="#" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                        <i class="fa-solid fa-download"></i>
                        <span class="hidden sm:inline">Download</span>
                    </a>
                    <button type="button" onclick="closeAttachmentPreviewModal()"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center text-lg transition cursor-pointer"
                        title="Tutup Pratinjau">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- ISI PREVIEW -->
            <div id="preview-modal-body" class="flex-1 bg-slate-100 flex items-center justify-center overflow-auto p-2 relative">
                <!-- Iframe / Gambar / Loading akan dirender di sini via JavaScript -->
            </div>
        </div>
    </div>

    <!-- JavaScript Controller -->
    <script>
        let globalAgendaData = @json($formattedAgendas ?? []);
        let globalApelData = @json($apels ?? []);
        let globalReportData = [];

        @php
            $isManagementLoggedIn = auth()->check()
                && in_array(auth()->user()->role, ['admin', 'pimpinan'], true)
                && auth()->user()->is_active;
        @endphp

        let isLoggedIn = @json($isManagementLoggedIn);
        let canViewDisposisi = isLoggedIn || @json(session('staff_authenticated') === true);
        let isStaffLoggedIn = @json(session('staff_authenticated') === true);
        let bookingPemesan = @json((string) session('booking_pemesan', ''));
        let isBookingLoggedIn = @json((bool) session('booking_authenticated', false));

        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let toastTimeout = null;

        // Fungsi Pengendali Pilihan Login Modal
        function showLoginOption(option) {
            const menu = document.getElementById('login-option-menu');
            const adminForm = document.getElementById('login-admin-form-wrapper');
            const staffForm = document.getElementById('login-staff-form');
            const adminTitle = document.getElementById('login-admin-title');
            const adminSubmit = document.getElementById('login-admin-submit');
            const roleInput = document.getElementById('login-role');
            const errorBox = document.getElementById('admin-login-error');
            const staffErrorBox = document.getElementById('staff-login-error');

            if (!menu || !adminForm || !staffForm) return;

            // Selalu sembunyikan kembali password/PIN (ikon mata kembali ke awal)
            toggleAllPasswords(
                ['login-password', 'login-staff-pin'],
                ['login-password-eye', 'login-staff-pin-eye'],
                false
            );

            menu.classList.add('hidden');
            adminForm.classList.add('hidden');
            staffForm.classList.add('hidden');
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }
            if (staffErrorBox) {
                staffErrorBox.classList.add('hidden');
                staffErrorBox.textContent = '';
            }

            if (option === 'menu') {
                menu.classList.remove('hidden');
            } else if (option === 'pimpinan') {
                adminForm.classList.remove('hidden');
                if (adminTitle) adminTitle.textContent = 'Login Pimpinan';
                if (adminSubmit) {
                    adminSubmit.textContent = 'Masuk sebagai Pimpinan';
                    adminSubmit.className = 'w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-xl text-sm cursor-pointer';
                }
                if (roleInput) roleInput.value = 'pimpinan';
            } else if (option === 'admin') {
                adminForm.classList.remove('hidden');
                if (adminTitle) adminTitle.textContent = 'Login Admin';
                if (adminSubmit) {
                    adminSubmit.textContent = 'Masuk sebagai Admin';
                    adminSubmit.className = 'w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm cursor-pointer';
                }
                if (roleInput) roleInput.value = 'admin';
            } else if (option === 'staff') {
                staffForm.classList.remove('hidden');
            }
        }

        // LOGIN ADMIN DAN PIMPINAN TANPA PINDAH HALAMAN
        const adminLoginForm = document.getElementById('admin-login-form');

        if (adminLoginForm) {
            adminLoginForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const errorBox = document.getElementById('admin-login-error');
                const submitButton = document.getElementById('login-admin-submit');
                const passwordInput = adminLoginForm.querySelector('input[name="password"]');
                const originalText = submitButton.textContent;

                errorBox.classList.add('hidden');
                errorBox.textContent = '';

                submitButton.disabled = true;
                submitButton.textContent = 'Memeriksa...';

                try {
                    const response = await fetch(adminLoginForm.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: new FormData(adminLoginForm)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        window.location.href = result.redirect;
                        return;
                    }

                    let pesan = result.message || 'Email atau password salah. Silakan coba kembali.';

                    if (result.errors) {
                        const firstError = Object.values(result.errors)[0];
                        if (firstError && firstError.length) {
                            pesan = firstError[0];
                        }
                    }

                    errorBox.textContent = pesan;
                    errorBox.classList.remove('hidden');

                    if (passwordInput) {
                        passwordInput.value = '';
                        passwordInput.focus();
                    }

                } catch (error) {
                    errorBox.textContent = 'Terjadi gangguan koneksi. Silakan coba kembali.';
                    errorBox.classList.remove('hidden');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            });
        }

        // LOGIN PEGAWAI TANPA PINDAH HALAMAN
        const staffLoginForm = document.getElementById('staff-login-form');

        if (staffLoginForm) {
            staffLoginForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const errorBox = document.getElementById('staff-login-error');
                const submitButton = staffLoginForm.querySelector('button[type="submit"]');
                const pinInput = staffLoginForm.querySelector('input[name="pin"]');
                const originalText = submitButton.textContent;

                errorBox.classList.add('hidden');
                errorBox.textContent = '';

                submitButton.disabled = true;
                submitButton.textContent = 'Memeriksa...';

                try {
                    const response = await fetch(staffLoginForm.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: new FormData(staffLoginForm)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        window.location.href = result.redirect;
                        return;
                    }

                    let pesan = result.message || 'PIN Pegawai salah. Silakan coba kembali.';

                    if (result.errors) {
                        const firstError = Object.values(result.errors)[0];

                        if (firstError && firstError.length) {
                            pesan = firstError[0];
                        }
                    }

                    errorBox.textContent = pesan;
                    errorBox.classList.remove('hidden');

                    if (pinInput) {
                        pinInput.value = '';
                        pinInput.focus();
                    }

                } catch (error) {
                    errorBox.textContent = 'Terjadi gangguan koneksi. Silakan coba kembali.';
                    errorBox.classList.remove('hidden');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            });
        }

        // MODAL KONFIRMASI KHUSUS BAPPERIDA
        function showBapperidaConfirm(options = {}) {
            return new Promise((resolve) => {
                const modal = document.getElementById('bapperida-confirm-modal');
                const title = document.getElementById('bapperida-confirm-title');
                const message = document.getElementById('bapperida-confirm-message');
                const icon = document.getElementById('bapperida-confirm-icon');
                const cancelButton = document.getElementById('bapperida-confirm-cancel');
                const yesButton = document.getElementById('bapperida-confirm-yes');

                if (!modal) {
                    resolve(false);
                    return;
                }

                title.textContent = options.title || 'Konfirmasi Tindakan';
                message.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan?';
                yesButton.textContent = options.confirmText || 'Ya, Lanjutkan';

                if (options.type === 'danger') {
                    icon.className = 'w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center';
                    icon.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-xl"></i>';
                    yesButton.className = 'px-5 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 cursor-pointer';
                } else {
                    icon.className = 'w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center';
                    icon.innerHTML = '<i class="fa-solid fa-shield-halved text-xl"></i>';
                    yesButton.className = 'px-5 py-2.5 rounded-xl bg-blue-700 text-white text-sm font-semibold hover:bg-blue-800 cursor-pointer';
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                let selesai = false;

                function tutup(hasil) {
                    if (selesai) return;
                    selesai = true;

                    modal.classList.add('hidden');
                    document.body.style.overflow = '';

                    cancelButton.removeEventListener('click', batal);
                    yesButton.removeEventListener('click', setuju);

                    resolve(hasil);
                }

                function batal() {
                    tutup(false);
                }

                function setuju() {
                    tutup(true);
                }

                cancelButton.addEventListener('click', batal);
                yesButton.addEventListener('click', setuju);

                cancelButton.focus();
            });
        }

        async function changeAdminPassword(id) {
            const admin = (currentAdminList || []).find(a => Number(a.id) === Number(id));
            if (admin) {
                openResetAdminPasswordModal(admin.id, admin.name, admin.email);
            } else {
                openResetAdminPasswordModal(id);
            }
        }

        async function deleteAdminAccount(id, nama) {
            const konfirmasi = await showBapperidaConfirm({
                title: 'Hapus Akun Admin',
                message: `Apakah Anda yakin ingin menghapus akun Admin "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
                confirmText: 'Ya, Hapus Admin',
                type: 'danger'
            });

            if (!konfirmasi) return;

            try {
                const response = await apiFetch(`/management/accounts/admin/${id}`, {
                    method: 'DELETE'
                });

                const data = await managementReadResponse(response);

                showAppToast(data.message || 'Akun Admin berhasil dihapus.');
                await loadAccountManagement();

            } catch (error) {
                showAppToast(error.message, 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {

            const createForm = document.getElementById('management-create-admin-form');

            if (createForm) {
                createForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const button = document.getElementById('management-create-admin-button');
                    const name = document.getElementById('management-admin-name').value.trim();
                    const email = document.getElementById('management-admin-email').value.trim();
                    const password = document.getElementById('management-admin-password').value;
                    const passwordConfirmInput = document.getElementById('management-admin-password-confirmation');
                    const password_confirmation = passwordConfirmInput ? passwordConfirmInput.value : '';

                    if (password.length < 6) {
                        showAppToast('Password Admin minimal 6 karakter.', 'error');
                        document.getElementById('management-admin-password').focus();
                        return;
                    }

                    if (password !== password_confirmation) {
                        showAppToast('Konfirmasi password tidak cocok dengan password yang dimasukkan.', 'error');
                        if (passwordConfirmInput) passwordConfirmInput.focus();
                        return;
                    }

                    const konfirmasi = await showBapperidaConfirm({
                        title: 'Buat Akun Admin Baru',
                        message: `Apakah Anda yakin ingin membuat akun Admin "${name}" (${email})? Password yang Anda tentukan akan langsung aktif.`,
                        confirmText: 'Ya, Buat Akun'
                    });

                    if (!konfirmasi) return;

                    button.disabled = true;
                    button.textContent = 'Membuat akun...';

                    try {
                        const response = await apiFetch('/management/accounts/admin', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ name, email, password, password_confirmation })
                        });

                        const data = await managementReadResponse(response);

                        createForm.reset();
                        // Kembalikan visibilitas password ke mode tersembunyi
                        toggleAllPasswords(
                            ['management-admin-password', 'management-admin-password-confirmation'],
                            ['management-admin-password-eye', 'management-admin-password-confirm-eye'],
                            false
                        );
                        const chk = document.getElementById('management-create-toggle-all');
                        if (chk) chk.checked = false;

                        showAppToast(data.message || 'Akun Admin berhasil dibuat.');
                        showResetPasswordSuccess(name, email, password);
                        await loadAccountManagement();

                    } catch (error) {
                        showAppToast(error.message, 'error');
                    } finally {
                        button.disabled = false;
                        button.innerHTML = '<i class="fa-solid fa-plus mr-1"></i> Buat Akun Admin';
                    }
                });
            }

            const pinForm = document.getElementById('management-staff-pin-form');

            if (pinForm) {
                pinForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const pinInput = document.getElementById('management-staff-pin');
                    const pin = pinInput.value;

                    if (pin.length < 4) {
                        showAppToast('PIN Pegawai minimal 4 karakter.', 'error');
                        return;
                    }

                    const konfirmasi = await showBapperidaConfirm({
                        title: 'Ganti PIN Pegawai',
                        message: 'Apakah Anda yakin ingin mengganti PIN Pegawai? PIN lama tidak akan berlaku setelah perubahan ini.',
                        confirmText: 'Ya, Ganti PIN'
                    });

                    if (!konfirmasi) return;

                    try {
                        const response = await apiFetch('/management/accounts/staff-pin', {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ pin })
                        });

                        const data = await managementReadResponse(response);

                        pinForm.reset();
                        showAppToast(data.message || 'PIN Pegawai berhasil diganti.');

                    } catch (error) {
                        showAppToast(error.message, 'error');
                    }
                });
            }
        });

        function formatTanggalLengkap(inputStr) {
            if (!inputStr) return '';
            let dateObj = null;

            if (/^\d{4}-\d{2}-\d{2}$/.test(inputStr)) {
                dateObj = new Date(inputStr + 'T12:00:00');
            } else {
                dateObj = new Date(inputStr);
            }

            if (!dateObj || isNaN(dateObj.getTime())) {
                return inputStr;
            }

            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' };
            return dateObj.toLocaleDateString('id-ID', options);
        }

        function showAppToast(message, type = 'success') {
            const toast = document.getElementById('app-toast');
            const titleEl = document.getElementById('app-toast-title');
            const messageEl = document.getElementById('app-toast-message');
            const iconEl = document.getElementById('app-toast-icon');

            if (!toast || !titleEl || !messageEl || !iconEl) return;

            if (toastTimeout) {
                clearTimeout(toastTimeout);
            }

            messageEl.textContent = message;

            if (type === 'error') {
                titleEl.textContent = 'Perhatian';
                iconEl.className = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700 font-bold';
                iconEl.textContent = '✕';
                toast.className = 'fixed top-5 right-5 z-[9999] max-w-sm w-[calc(100%-2rem)] rounded-xl border border-red-200 bg-white p-4 shadow-xl transition-all duration-300';
            } else {
                titleEl.textContent = 'Berhasil';
                iconEl.className = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold';
                iconEl.textContent = '✓';
                toast.className = 'fixed top-5 right-5 z-[9999] max-w-sm w-[calc(100%-2rem)] rounded-xl border border-emerald-200 bg-white p-4 shadow-xl transition-all duration-300';
            }

            toast.classList.remove('hidden');

            toastTimeout = setTimeout(() => {
                hideAppToast();
            }, 4000);
        }

        function hideAppToast() {
            const toast = document.getElementById('app-toast');
            if (toast) {
                toast.classList.add('hidden');
            }
            if (toastTimeout) {
                clearTimeout(toastTimeout);
            }
        }

        function setCsrfToken(token) {
            if (!token) return;

            csrfToken = token;

            // Perbarui token pada meta tag.
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) {
                meta.setAttribute('content', token);
            }

            // Perbarui token pada seluruh form yang menggunakan CSRF.
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = token;
            });
        }

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

            const sekarang = new Date();
            const tanggalWIB = new Intl.DateTimeFormat('en-CA', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).format(sekarang);

            const [tahunSekarang, bulanSekarang] = tanggalWIB.split('-');

            const daftarBulan = [
                'januari', 'februari', 'maret', 'april',
                'mei', 'juni', 'juli', 'agustus',
                'september', 'oktober', 'november', 'desember'
            ];

            const filterBulan = document.getElementById('filter-bulan');
            const filterTahun = document.getElementById('filter-tahun');

            if (filterBulan) {
                filterBulan.value = daftarBulan[Number(bulanSekarang) - 1];
            }

            if (filterTahun) {
                filterTahun.value = tahunSekarang;
            }

            renderDashboardList();
            renderAgendaTable();
            renderApelTable();
            renderPiketHariIni();

            ['form-tanggal-picker', 'form-jam-mulai', 'form-jam-selesai'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', perbaruiInfoBooking);
            });
            const ruangEl = document.getElementById('form-ruang');
            if (ruangEl) ruangEl.addEventListener('input', perbaruiInfoBooking);

            fetchRoomBookings();

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'login_staff') {
                openLoginModal();
                showLoginOption('staff');
            }

            const activeTab = sessionStorage.getItem('bapperida_active_tab');

            if (activeTab) {
                sessionStorage.removeItem('bapperida_active_tab');
                switchTab(activeTab);
            }
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

            tanggalEl.textContent = formatTanggalLengkap(tanggalHariIni);

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

            tbody.innerHTML = globalApelData.map((r, idx) => {
                const tanggalBersih = String(r.tanggal_asli || r.tanggal || '').slice(0, 10);
                const tampilanTanggal = tanggalBersih ? formatTanggalLengkap(tanggalBersih) : (r.tanggal || '-');
                return `
                <tr class="hover:bg-slate-50">
                    <td class="py-2.5 px-3 font-semibold">${idx + 1}</td>
                    <td class="py-2.5 px-3 text-blue-700 font-medium">${tampilanTanggal}</td>
                    <td class="py-2.5 px-3">${r.pembina}</td>
                    <td class="py-2.5 px-3">${r.pemimpin}</td>
                    <td class="py-2.5 px-3 whitespace-nowrap">${r.ket || '-'}</td>
                    ${isLoggedIn ? `
                        <td class="py-2.5 px-3 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button type="button" onclick='openEditApel(${idx})'
                                    class="w-8 h-8 inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs cursor-pointer shadow-sm transition"
                                    title="Edit jadwal" aria-label="Edit jadwal">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" onclick='deleteApel(${Number(r.id)})'
                                    class="w-8 h-8 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs cursor-pointer shadow-sm transition"
                                    title="Hapus jadwal" aria-label="Hapus jadwal">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        ` : ''}
                </tr>
            `;
            }).join('');
        }

        function openLoginModal() {
            document.getElementById('login-modal').classList.remove('hidden');
            showLoginOption('menu');
        }

        function closeLoginModal() { 
            document.getElementById('login-modal').classList.add('hidden');
            // Sembunyikan kembali password/PIN saat modal ditutup
            toggleAllPasswords(
                ['login-password', 'login-staff-pin'],
                ['login-password-eye', 'login-staff-pin-eye'],
                false
            );
        }

        function updateAuthUI() {
            const container = document.getElementById('user-status-container');
            const addBtn = document.getElementById('admin-add-button-container');
            const addApelBtn = document.getElementById('admin-add-apel-container');

            const managementName = @json(auth()->user()?->name ?? '');
            const managementRole = @json(auth()->user()?->role ?? '');

            if (isLoggedIn) {
                let bookingStatus = '';

                if (['admin', 'pimpinan'].includes(managementRole) && isBookingLoggedIn) {
                    bookingStatus = `
                        <div class="text-xs bg-amber-100 text-amber-800 px-2.5 py-1.5 rounded-xl font-semibold">
                            <i class="fa-solid fa-calendar-check mr-1"></i>
                            Booking Aktif
                        </div>
                    `;
                }

                if (container) {
                    container.innerHTML = `
                        <div class="flex items-center gap-2 flex-wrap">
                            <div class="flex items-center gap-1.5 bg-blue-800 px-2.5 py-1.5 rounded-xl text-white font-semibold text-xs">
                                <i class="fa-solid fa-user-shield"></i>
                                ${managementName || 'Pengguna'}
                                <span class="opacity-70">(${managementRole})</span>
                            </div>

                            ${bookingStatus}

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-2.5 py-1.5 rounded-xl cursor-pointer">
                                    <i class="fa-solid fa-right-from-bracket mr-1"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    `;
                }

                if (addBtn) {
                    addBtn.innerHTML = `
                        <button type="button" onclick="openFormAgendaModal()"
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl cursor-pointer">
                            <i class="fa-solid fa-plus"></i> Tambah Agenda
                        </button>`;
                }

                if (addApelBtn) {
                    addApelBtn.innerHTML = `
                        <button type="button" onclick="openFormApelModal()"
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl cursor-pointer">
                            <i class="fa-solid fa-plus"></i> Tambah Jadwal
                        </button>`;
                }
            } else if (isStaffLoggedIn) {
                if (container) {
                    container.innerHTML = `
                        <div class="flex items-center gap-2 flex-wrap">
                            <div class="text-xs bg-emerald-100 text-emerald-800 px-2.5 py-1.5 rounded-xl font-semibold">
                                <i class="fa-solid fa-users mr-1"></i>
                                Pegawai
                            </div>

                            <form method="POST" action="{{ route('staff.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-2.5 py-1.5 rounded-xl cursor-pointer">
                                    <i class="fa-solid fa-right-from-bracket mr-1"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    `;
                }

                if (addBtn) addBtn.innerHTML = '';
                if (addApelBtn) addApelBtn.innerHTML = '';

            } else {
                if (container) {
                    container.innerHTML = `
                        <button type="button" onclick="openLoginModal()"
                            class="px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl cursor-pointer">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </button>
                    `;
                }

                if (addBtn) addBtn.innerHTML = '';
                if (addApelBtn) addApelBtn.innerHTML = '';
            }
        }

        // ==========================================
        // NAVIGASI TAB
        // ==========================================
        function switchTab(tabId) {
            const tabs = ['dashboard', 'agenda', 'pegawai', 'layanan', 'laporan', 'manajemen-akun'];

            if (!tabs.includes(tabId)) return;

            if (tabId === 'manajemen-akun' && @json(auth()->check() && auth()->user()->role === 'pimpinan' && auth()->user()->is_active)) {
                // Akses Manajemen Akun hanya untuk Pimpinan.
            } else if (tabId === 'manajemen-akun') {
                return;
            }

            const targetContent = document.getElementById('tab-' + tabId);
            const targetNav = document.getElementById('nav-' + tabId);

            if (!targetContent || !targetNav) return;

            tabs.forEach(t => {
                const content = document.getElementById('tab-' + t);
                const nav = document.getElementById('nav-' + t);

                if (content) content.classList.add('hidden');

                if (nav) {
                    nav.className = "flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer";
                }
            });

            targetContent.classList.remove('hidden');

            targetNav.className = "flex items-center justify-center md:justify-start space-x-2 px-3 py-2.5 rounded-xl text-xs md:text-sm font-semibold transition-all bg-blue-50 text-blue-700 cursor-pointer";

            sessionStorage.setItem('bapperida_active_tab', tabId);

            if (tabId === 'laporan' && globalReportData.length === 0) {
                loadReportData();
            }

            if (tabId === 'manajemen-akun' && typeof loadAccountManagement === 'function') {
                loadAccountManagement();
            }
        }

        // ==========================================
        // MANAJEMEN AKUN KHUSUS PIMPINAN
        // ==========================================

        function managementEscapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[char]);
        }

        async function managementReadResponse(response) {
            let data = {};

            try {
                data = await response.json();
            } catch (error) {
                throw new Error('Server memberikan respons yang tidak valid.');
            }

            if (!response.ok || data.success === false) {
                let message = data.message || 'Permintaan gagal diproses.';

                if (data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    if (firstError && firstError.length) {
                        message = firstError[0];
                    }
                }

                throw new Error(message);
            }

            return data;
        }

        let currentAdminList = [];
        let lastResetPassword = '';

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }

        function toggleAllPasswords(inputIds, iconIds, show) {
            inputIds.forEach((id, index) => {
                const input = document.getElementById(id);
                const icon = document.getElementById(iconIds[index]);
                if (!input) return;
                input.type = show ? 'text' : 'password';
                if (icon) {
                    if (show) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        }

        function generateRandomResetPassword() {
            const prefix = 'Bapp';
            const num = Math.floor(1000 + Math.random() * 9000);
            const symbols = '!@#$%&*';
            const sym = symbols[Math.floor(Math.random() * symbols.length)];
            const pass = `${prefix}${num}${sym}`;

            const pwdInput = document.getElementById('reset-modal-password');
            const pwdConf = document.getElementById('reset-modal-password-confirmation');
            if (pwdInput && pwdConf) {
                pwdInput.value = pass;
                pwdConf.value = pass;
                toggleAllPasswords(
                    ['reset-modal-password', 'reset-modal-password-confirmation'],
                    ['reset-modal-password-eye', 'reset-modal-password-confirm-eye'],
                    true
                );
                const chk = document.getElementById('reset-modal-toggle-all');
                if (chk) chk.checked = true;
            }
        }

        function populateResetModalAdminSelect(selectedId = null) {
            const select = document.getElementById('reset-modal-admin-id');
            if (!select) return;

            select.innerHTML = '<option value="">-- Pilih Akun Admin --</option>' +
                currentAdminList.map(admin => {
                    const isSel = selectedId && Number(admin.id) === Number(selectedId) ? 'selected' : '';
                    return `<option value="${admin.id}" ${isSel}>${managementEscapeHtml(admin.name)} (${managementEscapeHtml(admin.email)})</option>`;
                }).join('');

            updateResetModalAdminInfo();
        }

        function updateResetModalAdminInfo() {
            const select = document.getElementById('reset-modal-admin-id');
            const info = document.getElementById('reset-modal-admin-info');
            if (!select || !info) return;

            const adminId = Number(select.value);
            const admin = currentAdminList.find(a => Number(a.id) === adminId);
            if (admin) {
                info.innerHTML = `<span class="inline-flex items-center gap-1.5 text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg"><i class="fa-solid fa-user-check text-xs"></i> Admin terpilih: <strong>${managementEscapeHtml(admin.name)}</strong> (${managementEscapeHtml(admin.email)})</span>`;
                info.classList.remove('hidden');
            } else {
                info.classList.add('hidden');
                info.textContent = '';
            }
        }

        function openResetAdminPasswordModal(adminId = null, adminName = '', adminEmail = '') {
            const modal = document.getElementById('bapperida-reset-password-modal');
            if (!modal) return;

            populateResetModalAdminSelect(adminId);

            const select = document.getElementById('reset-modal-admin-id');
            if (adminId && select) {
                select.value = String(adminId);
            }

            const pwd = document.getElementById('reset-modal-password');
            const pwdConf = document.getElementById('reset-modal-password-confirmation');
            if (pwd) pwd.value = '';
            if (pwdConf) pwdConf.value = '';

            toggleAllPasswords(
                ['reset-modal-password', 'reset-modal-password-confirmation'],
                ['reset-modal-password-eye', 'reset-modal-password-confirm-eye'],
                false
            );
            const chk = document.getElementById('reset-modal-toggle-all');
            if (chk) chk.checked = false;

            updateResetModalAdminInfo();

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (pwd) pwd.focus();
        }

        function closeResetAdminPasswordModal() {
            const modal = document.getElementById('bapperida-reset-password-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        async function submitResetAdminPassword(event) {
            if (event) event.preventDefault();

            const select = document.getElementById('reset-modal-admin-id');
            const pwdInput = document.getElementById('reset-modal-password');
            const pwdConfInput = document.getElementById('reset-modal-password-confirmation');
            const submitBtn = document.getElementById('reset-modal-submit-button');

            if (!select || !pwdInput || !pwdConfInput) return;

            const adminId = Number(select.value);
            if (!adminId) {
                showAppToast('Silakan pilih akun Admin yang akan direset.', 'error');
                select.focus();
                return;
            }

            const admin = currentAdminList.find(a => Number(a.id) === adminId);
            const adminName = admin ? admin.name : 'Admin';
            const adminEmail = admin ? admin.email : '';

            const password = pwdInput.value;
            const password_confirmation = pwdConfInput.value;

            if (password.length < 6) {
                showAppToast('Password baru minimal 6 karakter.', 'error');
                pwdInput.focus();
                return;
            }

            if (password !== password_confirmation) {
                showAppToast('Konfirmasi password tidak cocok dengan password baru.', 'error');
                pwdConfInput.focus();
                return;
            }

            const konfirmasi = await showBapperidaConfirm({
                title: 'Konfirmasi Reset Password Admin',
                message: `Apakah Anda yakin ingin mereset password akun Admin "${adminName}" (${adminEmail})? Password baru ini akan langsung aktif.`,
                confirmText: 'Ya, Reset Password'
            });

            if (!konfirmasi) return;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...';
            }

            try {
                const response = await apiFetch(`/management/accounts/admin/${adminId}/password`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ password, password_confirmation })
                });

                const data = await managementReadResponse(response);

                closeResetAdminPasswordModal();
                showResetPasswordSuccess(adminName, adminEmail, password);
                await loadAccountManagement();

            } catch (error) {
                showAppToast(error.message, 'error');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-check mr-1.5"></i> Simpan Password Baru';
                }
            }
        }

        function showResetPasswordSuccess(adminName, adminEmail, newPassword) {
            lastResetPassword = newPassword;
            const modal = document.getElementById('bapperida-reset-success-modal');
            const nameEl = document.getElementById('reset-success-name');
            const emailEl = document.getElementById('reset-success-email');
            const pwdEl = document.getElementById('reset-success-password');

            if (nameEl) nameEl.textContent = adminName;
            if (emailEl) emailEl.textContent = adminEmail;
            if (pwdEl) pwdEl.textContent = newPassword;

            const copyIcon = document.getElementById('reset-copy-icon');
            const copyText = document.getElementById('reset-copy-text');
            if (copyIcon) copyIcon.className = 'fa-regular fa-copy';
            if (copyText) copyText.textContent = 'Salin';

            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeResetSuccessModal() {
            const modal = document.getElementById('bapperida-reset-success-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        async function copyResetPasswordToClipboard() {
            if (!lastResetPassword) return;

            const copyIcon = document.getElementById('reset-copy-icon');
            const copyText = document.getElementById('reset-copy-text');

            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(lastResetPassword);
                } else {
                    const ta = document.createElement('textarea');
                    ta.value = lastResetPassword;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                }

                if (copyIcon) copyIcon.className = 'fa-solid fa-check text-emerald-600';
                if (copyText) copyText.textContent = 'Tersalin!';
                showAppToast('Password baru berhasil disalin ke clipboard.');
            } catch (err) {
                showAppToast('Gagal menyalin otomatis, silakan salin teks password secara manual.', 'error');
            }
        }

        async function loadAccountManagement() {
            const list = document.getElementById('management-admin-list');
            const total = document.getElementById('management-admin-total');

            if (!list || !total) return;

            list.innerHTML = '<p class="text-sm text-slate-500">Memuat daftar Admin...</p>';

            try {
                const response = await apiFetch('/management/accounts', {
                    method: 'GET'
                });

                const data = await managementReadResponse(response);

                total.textContent = data.total ?? 0;
                currentAdminList = Array.isArray(data.admins) ? data.admins : [];

                if (currentAdminList.length === 0) {
                    list.innerHTML = '<p class="text-sm text-slate-500">Belum ada akun Admin.</p>';
                    return;
                }

                list.innerHTML = currentAdminList.map(admin => {
                    const id = Number(admin.id);
                    const nama = managementEscapeHtml(admin.name);
                    const email = managementEscapeHtml(admin.email);
                    const rawNama = String(admin.name ?? '').replace(/'/g, "\\'");
                    const rawEmail = String(admin.email ?? '').replace(/'/g, "\\'");
                    const status = admin.is_active
                        ? '<span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-lg text-xs font-semibold"><i class="fa-solid fa-circle text-[8px]"></i> Aktif</span>'
                        : '<span class="inline-flex items-center gap-1 text-red-700 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-lg text-xs font-semibold"><i class="fa-solid fa-circle text-[8px]"></i> Nonaktif</span>';

                    return `
                        <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                                        <i class="fa-solid fa-user-shield text-base"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-slate-800 text-sm md:text-base">${nama}</h4>
                                            <span class="text-[11px] text-slate-400 font-mono">(ID: ${id})</span>
                                        </div>
                                        <p class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                            <i class="fa-regular fa-envelope text-slate-400"></i>
                                            <span>${email}</span>
                                        </p>
                                        <div class="mt-2">
                                            ${status}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200">
                                    <button
                                        type="button"
                                        onclick="openResetAdminPasswordModal(${id}, '${rawNama}', '${rawEmail}')"
                                        class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-3.5 py-2 text-xs font-bold shadow-sm transition-all cursor-pointer"
                                        title="Reset password akun Admin ini">
                                        <i class="fa-solid fa-key"></i>
                                        <span>Reset Password</span>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="deleteAdminAccount(${id}, '${rawNama}')"
                                        class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl px-3 py-2 text-xs font-semibold transition-all cursor-pointer"
                                        title="Hapus akun admin">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

            } catch (error) {
                list.innerHTML = '<p class="text-sm text-red-600">Gagal memuat daftar Admin.</p>';
                showAppToast(error.message, 'error');
            }
        }

        // ==========================================
        // FILTER TAHUN LAPORAN
        // ==========================================
        function populateReportYears() {
            const select = document.getElementById('lap-tahun');
            if (!select) return;

            const selectedYear = select.value;

            const tahunWIB = new Intl.DateTimeFormat('en', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric'
            }).format(new Date());

            const years = new Set([tahunWIB]);

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

        // ==========================================
        // MEMUAT DATA LAPORAN
        // ==========================================
        function loadReportData() {
            fetch('/api/reports', {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Gagal mengambil data laporan.');
                }
                return res.json();
            })
            .then(data => {
                globalReportData = Array.isArray(data) ? data : [];

                populateReportYears();

                const periodeWIB = new Intl.DateTimeFormat('en-CA', {
                    timeZone: 'Asia/Jakarta',
                    year: 'numeric',
                    month: '2-digit'
                }).format(new Date());

                const [tahun, bulan] = periodeWIB.split('-');

                const namaBulan = [
                    'januari', 'februari', 'maret', 'april',
                    'mei', 'juni', 'juli', 'agustus',
                    'september', 'oktober', 'november', 'desember'
                ];

                const filterBulan = document.getElementById('lap-bulan');
                const filterTahun = document.getElementById('lap-tahun');

                if (filterBulan) {
                    filterBulan.value = namaBulan[Number(bulan) - 1];
                }

                if (filterTahun) {
                    filterTahun.value = tahun;
                }

                renderReportTable();
            })
            .catch(err => {
                console.error('Gagal memuat laporan:', err);
                showAppToast(err.message || 'Gagal memuat laporan.', 'error');
            });
        }

        // ==========================================
        // MENAMPILKAN TABEL LAPORAN
        // ==========================================
        function renderReportTable() {
            const jenis = document.getElementById('lap-jenis')?.value || 'semua';
            const bulan = (document.getElementById('lap-bulan')?.value || '').toLowerCase();
            const tahun = document.getElementById('lap-tahun')?.value || '';
            const bidang = (document.getElementById('lap-bidang')?.value || '').toLowerCase();
            const search = (document.getElementById('lap-search')?.value || '').toLowerCase();

            const tbody = document.getElementById('report-table-body');
            const totalEl = document.getElementById('lap-stat-total');
            const dispEl = document.getElementById('lap-stat-disposisi');

            if (!tbody) return;

            const filtered = (globalReportData || []).filter(item => {
                const tglLower = String(item.tanggal || '').toLowerCase();
                const acaraLower = String(item.acara || '').toLowerCase();
                const ruangLower = String(item.ruang || '').toLowerCase();
                const bidangLower = String(item.bidang || '').toLowerCase();
                const dispLower = String(item.disposisi || '').toLowerCase();

                const matchJenis = jenis !== 'disposisi' ||
                    (dispLower !== '-' && dispLower !== '');

                const matchBulan = !bulan || tglLower.includes(bulan);
                const matchTahun = !tahun || tglLower.includes(tahun);
                const matchBidang = !bidang || bidangLower.includes(bidang);

                const matchSearch = !search ||
                    tglLower.includes(search) ||
                    acaraLower.includes(search) ||
                    ruangLower.includes(search) ||
                    bidangLower.includes(search) ||
                    dispLower.includes(search);

                return matchJenis && matchBulan && matchTahun &&
                    matchBidang && matchSearch;
            });

            if (totalEl) totalEl.innerText = filtered.length;

            if (dispEl) {
                dispEl.innerText = filtered.filter(item => {
                    const disposisi = String(item.disposisi || '');
                    return disposisi !== '-' && disposisi !== '';
                }).length;
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400">
                            Tidak ada data laporan yang sesuai filter.
                        </td>
                    </tr>`;
                return;
            }

            tbody.innerHTML = filtered.map((item, idx) => {
                const rawDate = item.tanggal_asli || item.tanggal || '';
                const cleanDate = String(rawDate).slice(0, 10);

                const tampilanTgl = /^\d{4}-\d{2}-\d{2}$/.test(cleanDate)
                    ? formatTanggalLengkap(cleanDate)
                    : (item.tanggal || '-');

                return `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-2 px-3 font-semibold text-slate-600">${idx + 1}</td>
                        <td class="py-2 px-3 font-medium text-blue-800 whitespace-nowrap">${tampilanTgl}</td>
                        <td class="py-2 px-3">
                            <strong>${item.waktu || '-'}</strong><br>
                            <span class="text-[10px] text-slate-500">${item.ruang || '-'}</span>
                        </td>
                        <td class="py-2 px-3 text-slate-900 font-medium">
                            <i class="fa-solid fa-file-lines text-blue-600 mr-1"></i>${item.acara || '-'}
                        </td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-bold">
                                ${item.bidang && item.bidang !== '-' ? item.bidang : 'Umum'}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-slate-700">
                            ${item.disposisi && item.disposisi !== '-' ? item.disposisi : '-'}
                        </td>
                    </tr>`;
            }).join('');
        }

        // ==========================================
        // RESET FILTER LAPORAN
        // ==========================================
        function resetReportFilter() {
            const jenis = document.getElementById('lap-jenis');
            const bidang = document.getElementById('lap-bidang');
            const search = document.getElementById('lap-search');
            const bulanEl = document.getElementById('lap-bulan');
            const tahunEl = document.getElementById('lap-tahun');

            if (jenis) jenis.value = 'semua';

            const periodeWIB = new Intl.DateTimeFormat('en-CA', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit'
            }).format(new Date());

            const [tahun, bulan] = periodeWIB.split('-');

            const namaBulan = [
                'januari', 'februari', 'maret', 'april',
                'mei', 'juni', 'juli', 'agustus',
                'september', 'oktober', 'november', 'desember'
            ];

            if (bulanEl) bulanEl.value = namaBulan[Number(bulan) - 1];
            if (tahunEl) tahunEl.value = tahun;
            if (bidang) bidang.value = '';
            if (search) search.value = '';

            renderReportTable();
        }

        // ==========================================
        // EKSPOR EXCEL
        // ==========================================
        function exportToExcel() {
            const jenis = document.getElementById('lap-jenis')?.value || 'semua';
            const bulan = document.getElementById('lap-bulan')?.value || '';
            const tahun = document.getElementById('lap-tahun')?.value || '';
            const bidang = document.getElementById('lap-bidang')?.value || '';
            const search = document.getElementById('lap-search')?.value.trim() || '';

            const params = new URLSearchParams();

            params.set('jenis', jenis);

            if (bulan) params.set('bulan', bulan);
            if (tahun) params.set('tahun', tahun);
            if (bidang) params.set('bidang', bidang);
            if (search) params.set('search', search);

            window.location.href =
                '{{ url("/reports/export-excel") }}?' + params.toString();
        }

        // ==========================================
        // EKSPOR PDF / CETAK
        // ==========================================
        function exportToPDF() {
            const bulan = document.getElementById('lap-bulan')?.value || '';
            const tahun = document.getElementById('lap-tahun')?.value || '';

            const periode = [
                bulan ? 'Bulan: ' + bulan.toUpperCase() : 'Semua Bulan',
                tahun ? 'Tahun: ' + tahun : 'Semua Tahun'
            ];

            const pdfPeriode = document.getElementById('pdf-periode');
            const pdfKop = document.getElementById('pdf-kop');

            if (pdfPeriode) {
                pdfPeriode.innerText = 'Periode: ' + periode.join(' | ');
            }

            if (pdfKop) pdfKop.classList.remove('hidden');

            window.print();

            if (pdfKop) pdfKop.classList.add('hidden');
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

            if (!mulai.value) mulai.value = '09:00';
            if (!selesai.value) selesai.value = '10:00';
        }

        function aturPilihanWaktu(waktu) {
            isiPilihanJam();

            const mulai = document.getElementById('form-jam-mulai');
            const selesai = document.getElementById('form-jam-selesai');

            if (!mulai || !selesai) return;

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
                showAppToast(
                    'Silakan pilih jam mulai dan jam selesai terlebih dahulu.',
                    'error'
                );
                return null;
            }

            if (selesai <= mulai) {
                showAppToast(
                    'Jam selesai harus lebih besar dari jam mulai. Silakan periksa kembali pilihan waktu Anda.',
                    'error'
                );
                return null;
            }

            return `${mulai.replace(':', '.')} - ${selesai.replace(':', '.')} WIB`;
        }

        function jamKeMenit(jam, menit) {
            return parseInt(jam, 10) * 60 + parseInt(menit, 10);
        }

        function menitDariSelect(id) {
            const el = document.getElementById(id);
            if (!el || !el.value) return null;
            const [j, m] = el.value.split(':');
            return jamKeMenit(j, m);
        }

        function parseRentangMenit(waktu) {
            const teks = String(waktu || '').trim();

            const rentang = teks.match(
                /(\d{1,2})[.:](\d{2})\s*(?:-|–|—|sampai)\s*(\d{1,2})[.:](\d{2})/i
            );

            if (rentang) {
                const jamMulai = Number(rentang[1]);
                const menitMulai = Number(rentang[2]);
                const jamSelesai = Number(rentang[3]);
                const menitSelesai = Number(rentang[4]);

                if (
                    jamMulai > 23 || jamSelesai > 23 ||
                    menitMulai > 59 || menitSelesai > 59
                ) {
                    return null;
                }

                const mulai = jamKeMenit(jamMulai, menitMulai);
                const selesai = jamKeMenit(jamSelesai, menitSelesai);

                if (selesai <= mulai) return null;

                return { mulai, selesai };
            }

            return null;
        }

        let globalRoomBookings = [];

        async function fetchRoomBookings() {
            try {
                const res = await fetch('/bookings');
                if (res.ok) {
                    const json = await res.json();
                    globalRoomBookings = Array.isArray(json.data) ? json.data : [];
                    perbaruiInfoBooking();
                }
            } catch (err) {
                console.warn('Gagal memuat jadwal booking ruangan:', err);
            }
        }

        function agendaPadaRuang(tanggal, ruang, excludeId) {
            const kunciRuang = String(ruang || '').trim().toLowerCase();
            if (!tanggal || !kunciRuang) return [];

            const semua = (globalAgendaData || []).flatMap(g => g.subEvents || [g]);

            const listAgenda = semua
                .filter(evt => {
                    if (excludeId && String(evt.id) === String(excludeId)) return false;
                    if (String(evt.tanggal_kegiatan || '').slice(0, 10) !== tanggal) return false;
                    if (String(evt.ruang || '').trim().toLowerCase() !== kunciRuang) return false;
                    return parseRentangMenit(evt.waktu) !== null;
                })
                .map(evt => ({
                    waktu: evt.waktu,
                    acara: evt.acara,
                    ruang: evt.ruang,
                    id: evt.id,
                    sumber: 'agenda'
                }));

            const listBooking = (globalRoomBookings || [])
                .filter(b => {
                    if (String(b.tanggal || '').slice(0, 10) !== tanggal) return false;
                    if (String(b.ruang || '').trim().toLowerCase() !== kunciRuang) return false;
                    return true;
                })
                .map(b => {
                    const mulaiStr = (b.jam_mulai || '').slice(0, 5).replace(':', '.');
                    const selesaiStr = (b.jam_selesai || '').slice(0, 5).replace(':', '.');
                    return {
                        waktu: `${mulaiStr} - ${selesaiStr} WIB`,
                        acara: `[Booking] ${b.acara} (${b.pemesan || 'Pemohon'})`,
                        ruang: b.ruang,
                        id: 'booking-' + b.id,
                        sumber: 'booking'
                    };
                });

            return [...listAgenda, ...listBooking]
                .sort((a, b) => {
                    const ra = parseRentangMenit(a.waktu);
                    const rb = parseRentangMenit(b.waktu);
                    return (ra ? ra.mulai : 0) - (rb ? rb.mulai : 0);
                });
        }

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

            const tombolSimpan = document.getElementById('btn-save-agenda');

            if (tombolSimpan) {
                tombolSimpan.disabled = bentrok.length > 0;
                tombolSimpan.classList.toggle('opacity-50', bentrok.length > 0);
                tombolSimpan.classList.toggle('cursor-not-allowed', bentrok.length > 0);
                tombolSimpan.title = bentrok.length > 0
                    ? 'Pilih jam atau ruangan yang tidak bentrok.'
                    : '';
            }

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

        function closeFormAgendaModal() {
            const modal = document.getElementById('form-agenda-modal');

            if (modal) {
                modal.classList.add('hidden');
            }

            resetAgendaAttachmentsState();
        }

        function openFormAgendaModal() {
            document.getElementById('form-agenda-modal').classList.remove('hidden');
            fetchRoomBookings();
            document.getElementById('form-modal-title').innerText = 'Tambah Agenda Baru';
            document.getElementById('form-row-index').value = '';

            document.getElementById('form-tanggal-picker').value = '';
            
            isiPilihanJam();
            document.getElementById('form-jam-mulai').value = '09:00';
            document.getElementById('form-jam-selesai').value = '10:00';

            document.getElementById('form-acara').value = '';
            document.getElementById('form-disposisi').value = '';
            document.getElementById('form-pelaksana-manual').value = '';
            
            resetAgendaAttachmentsState();

            document.querySelectorAll('.bidang-checkbox').forEach(cb => cb.checked = false);
            
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
            fetchRoomBookings();
            document.getElementById('form-modal-title').innerText = 'Edit Agenda';
            document.getElementById('form-row-index').value = evt.id;

            document.getElementById('form-tanggal-picker').value = evt.tanggal_kegiatan;
            aturPilihanWaktu(evt.waktu || '');
            document.getElementById('form-acara').value = evt.acara;
            document.getElementById('form-ruang').value = evt.ruang;
            document.getElementById('form-disposisi').value = evt.disposisi !== '-' ? evt.disposisi : '';
            document.getElementById('form-pelaksana-manual').value = '';

            // Render lampiran lama pada form edit
            resetAgendaAttachmentsState();
            if (Array.isArray(evt.attachments) && evt.attachments.length > 0) {
                existingAttachmentsData = [...evt.attachments];
                renderExistingAttachments();
            }

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

        function hitungHariDariTanggal(tanggalStr) {
            if (!tanggalStr) return '';
            const tglObj = new Date(tanggalStr + 'T12:00:00');
            if (isNaN(tglObj.getTime())) return '';
            const namaHari = tglObj.toLocaleDateString('id-ID', { weekday: 'long', timeZone: 'Asia/Jakarta' });
            return namaHari.charAt(0).toUpperCase() + namaHari.slice(1);
        }

        function onApelTanggalChange() {
            const tanggalInput = document.getElementById('form-apel-tanggal').value;
            const hariInput = document.getElementById('form-apel-hari');
            if (hariInput && tanggalInput) {
                hariInput.value = hitungHariDariTanggal(tanggalInput);
            }
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

            const tglHariIni = `${yyyy}-${mm}-${dd}`;
            document.getElementById('form-apel-tanggal').value = tglHariIni;
            document.getElementById('form-apel-hari').value = hitungHariDariTanggal(tglHariIni);
            document.getElementById('form-apel-pembina').value = '';
            document.getElementById('form-apel-pemimpin').value = '';
            document.getElementById('form-apel-ket').value = 'Apel Pagi';
        }

        function openEditApel(index) {
            const item = globalApelData[index];
            if (!item) return;
            document.getElementById('form-apel-modal').classList.remove('hidden');
            document.getElementById('form-apel-modal-title').innerText = 'Edit Jadwal Pegawai';
            document.getElementById('form-apel-index').value = item.id;

            const tglSimpan = item.tanggal_asli || '';
            document.getElementById('form-apel-tanggal').value = tglSimpan;
            document.getElementById('form-apel-hari').value = item.hari || hitungHariDariTanggal(tglSimpan);
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

            const id = document.getElementById('form-apel-index').value;
            const payload = {
                id: id !== "" ? parseInt(id) : null,
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
                    showAppToast(res.message, 'success');

                    sessionStorage.setItem('bapperida_active_tab', 'pegawai');

                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAppToast(res.message || "Gagal menyimpan jadwal pegawai.", 'error');
                }
            } catch (error) {
                console.error('Kesalahan menyimpan jadwal pegawai:', error);
                showAppToast('Gagal menyimpan jadwal pegawai:\n' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = "Simpan";
            }
        }

        function konfirmasiHapusJadwal() {
            return new Promise(resolve => {
                const dialog = document.createElement('div');

                dialog.className =
                    'fixed inset-0 z-[99999] flex items-center justify-center p-4';

                dialog.innerHTML = `
                    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
                         data-cancel></div>

                    <div class="relative w-full max-w-md overflow-hidden
                                rounded-2xl bg-white shadow-2xl">

                        <div class="h-1.5 bg-red-600"></div>

                        <div class="p-6 text-center">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center
                                        justify-center rounded-full bg-red-50">
                                <i class="fa-solid fa-calendar-xmark
                                          text-2xl text-red-600"></i>
                            </div>

                            <h3 class="text-lg font-bold text-slate-800">
                                Hapus Jadwal Pegawai?
                            </h3>

                            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                                Apakah Anda yakin ingin menghapus jadwal ini?
                                Data yang telah dihapus tidak dapat dikembalikan.
                            </p>

                            <div class="mt-6 flex justify-center gap-3">
                                <button type="button" data-cancel
                                    class="rounded-xl border border-slate-300
                                           px-5 py-2.5 text-sm font-semibold
                                           text-slate-600 hover:bg-slate-100 cursor-pointer">
                                    Batal
                                </button>

                                <button type="button" data-confirm
                                    class="rounded-xl bg-red-600 px-5 py-2.5
                                           text-sm font-semibold text-white
                                           hover:bg-red-700 cursor-pointer">
                                    <i class="fa-solid fa-trash-can mr-1"></i>
                                    Ya, Hapus
                                </button>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 bg-slate-50
                                    px-4 py-3 text-center">
                            <span class="text-xs font-semibold text-slate-400">
                                BAPPERIDA KABUPATEN BELITUNG
                            </span>
                        </div>
                    </div>
                `;

                document.body.appendChild(dialog);

                let selesai = false;

                const tutup = hasil => {
                    if (selesai) return;
                    selesai = true;
                    dialog.remove();
                    resolve(hasil);
                };

                dialog.querySelectorAll('[data-cancel]').forEach(el => {
                    el.addEventListener('click', () => tutup(false));
                });

                dialog.querySelector('[data-confirm]')
                    .addEventListener('click', () => tutup(true));

                const tombolBatal = dialog.querySelector('[data-cancel]');
                if (tombolBatal) tombolBatal.focus();
            });
        }

        async function deleteApel(id) {
            if (!Number.isInteger(Number(id)) || Number(id) <= 0) {
                showAppToast('ID jadwal tidak valid.', 'error');
                return;
            }

            const disetujui = await konfirmasiHapusJadwal();

            if (!disetujui) {
                return;
            }

            try {
                const response = await apiFetch(`/apel/delete/${Number(id)}`, {
                    method: 'DELETE'
                });

                const res = await response.json();

                if (response.ok && res.success) {
                    showAppToast('Jadwal pegawai berhasil dihapus!', 'success');

                    sessionStorage.setItem('bapperida_active_tab', 'pegawai');

                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAppToast(res.message || 'Gagal menghapus jadwal pegawai.', 'error');
                }
            } catch (error) {
                console.error('Gagal menghapus jadwal:', error);
                showAppToast('Terjadi kesalahan saat menghapus jadwal. Silakan coba lagi.', 'error');
            }
        }

        async function handleFormAgendaSubmit(e) {
            e.preventDefault();

            const bentrok = ambilBentrokForm();

            if (bentrok.length > 0) {
                perbaruiInfoBooking();
                return;
            }

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
                const fileInput = document.getElementById('form-agenda-attachments');
                // Gunakan daftar staged files jika ada, atau fallback ke fileInput
                const filesToUpload = stagedAgendaFiles.length > 0
                    ? stagedAgendaFiles.map(item => item.file)
                    : (fileInput ? Array.from(fileInput.files) : []);

                if (!pickerVal) throw new Error('Tanggal kegiatan wajib diisi.');
                if (!acara) throw new Error('Uraian kegiatan wajib diisi.');
                if (!ruang) throw new Error('Lokasi kegiatan wajib dipilih.');

                if (filesToUpload.length > 5) {
                    throw new Error('Maksimal 5 file lampiran dalam sekali simpan.');
                }

                const maxSize = 10 * 1024 * 1024;
                if (filesToUpload.some(file => file.size > maxSize)) {
                    throw new Error('Ukuran setiap file maksimal 10 MB.');
                }

                const selectedBidang = [];
                document.querySelectorAll('.bidang-checkbox:checked').forEach(cb => {
                    selectedBidang.push(cb.value);
                });

                const inputManual = document.getElementById('form-pelaksana-manual');
                const pelaksanaManual = inputManual ? inputManual.value.trim() : '';

                if (pelaksanaManual) selectedBidang.push(pelaksanaManual);

                const elMulai = document.getElementById('form-jam-mulai');
                const elSelesai = document.getElementById('form-jam-selesai');

                const payload = {
                    id: document.getElementById('form-row-index').value || null,
                    tanggal_kegiatan: pickerVal,
                    hari: new Date(pickerVal + 'T12:00:00').toLocaleDateString('id-ID', {
                        weekday: 'long',
                        timeZone: 'Asia/Jakarta'
                    }),
                    waktu: rentangWaktu,
                    jam_mulai: elMulai ? elMulai.value : null,
                    jam_selesai: elSelesai ? elSelesai.value : null,
                    bidang: selectedBidang.length ? selectedBidang.join(', ') : '-',
                    acara: acara,
                    ruang: ruang,
                    disposisi: disposisi
                };

                const response = await apiFetch('/agenda/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                let result;
                try {
                    result = await response.json();
                } catch {
                    throw new Error(`Respons server tidak valid (HTTP ${response.status}).`);
                }

                if (!response.ok || !result.success) {
                    let message = result.message || 'Agenda gagal disimpan.';
                    if (result.errors) {
                        message += '\n' + Object.values(result.errors).flat().join('\n');
                    }
                    throw new Error(message);
                }

                const agendaId = result.data?.id;

                if (!agendaId) {
                    throw new Error('Agenda tersimpan, tetapi ID agenda tidak diterima server.');
                }

                if (filesToUpload.length > 0) {
                    btn.innerText = 'Mengunggah lampiran...';

                    const formData = new FormData();
                    if (stagedAgendaFiles.length > 0) {
                        stagedAgendaFiles.forEach(item => {
                            formData.append('attachments[]', item.file);
                            formData.append('access_types[]', item.access_type);
                        });
                    } else {
                        filesToUpload.forEach(file => {
                            formData.append('attachments[]', file);
                            formData.append('access_types[]', 'public');
                        });
                    }

                    const uploadResponse = await apiFetch(`/agenda/${agendaId}/attachments`, {
                        method: 'POST',
                        body: formData
                    });

                    let uploadResult;
                    try {
                        uploadResult = await uploadResponse.json();
                    } catch {
                        throw new Error('Agenda tersimpan, tetapi respons unggah lampiran tidak valid.');
                    }

                    if (!uploadResponse.ok || !uploadResult.success) {
                        throw new Error(
                            'Agenda berhasil disimpan, tetapi lampiran gagal diunggah: ' +
                            (uploadResult.message || 'Kesalahan server.')
                        );
                    }
                }

                showAppToast(
                    filesToUpload.length > 0
                        ? 'Agenda dan lampiran berhasil disimpan!'
                        : (result.message || 'Agenda berhasil disimpan!'),
                    'success'
                );

                closeFormAgendaModal();
                closeAgendaModal();

                sessionStorage.setItem('bapperida_active_tab', 'agenda');

                setTimeout(() => window.location.reload(), 500);

            } catch (error) {
                console.error('Kesalahan menyimpan agenda:', error);
                showAppToast(error.message, 'error');

            } finally {
                btn.disabled = false;
                btn.innerText = 'Simpan';
            }
        }

        function deleteAgenda(id) {
            const oldDialog = document.getElementById('bapperida-delete-dialog');
            if (oldDialog) oldDialog.remove();

            const dialog = document.createElement('div');
            dialog.id = 'bapperida-delete-dialog';
            dialog.className = 'fixed inset-0 z-[99999] flex items-center justify-center p-4';
            
            dialog.innerHTML = `
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
                     data-delete-cancel></div>

                <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl
                            border border-slate-200 animate-[fadeIn_.2s_ease-out]">

                    <div class="h-2 bg-gradient-to-r from-amber-500 to-red-600"></div>

                    <div class="p-6 sm:p-7">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center
                                    rounded-full bg-red-50 text-red-600">
                            <i class="fa-solid fa-trash-can text-2xl"></i>
                        </div>

                        <div class="text-center">
                            <h3 class="text-xl font-bold text-slate-800">
                                Konfirmasi Hapus Agenda
                            </h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-500">
                                Apakah Anda yakin ingin menghapus agenda ini?
                                Data yang telah dihapus tidak dapat dikembalikan.
                            </p>
                        </div>

                        <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                            <button type="button" data-delete-cancel
                                class="rounded-xl border border-slate-300 bg-white px-6 py-3
                                       text-sm font-semibold text-slate-600 transition
                                       hover:bg-slate-100 cursor-pointer">
                                Batal
                            </button>

                            <button type="button" id="confirm-delete-agenda"
                                class="rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold
                                       text-white shadow-md transition hover:bg-red-700 cursor-pointer">
                                <i class="fa-solid fa-trash-can mr-2"></i>
                                Ya, Hapus
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 bg-slate-50 px-6 py-3 text-center">
                        <span class="text-xs font-medium tracking-wide text-slate-400">
                            BAPPERIDA KABUPATEN BELITUNG
                        </span>
                    </div>
                </div>
            `;

            document.body.appendChild(dialog);

            const closeDialog = () => dialog.remove();

            dialog.querySelectorAll('[data-delete-cancel]').forEach(button => {
                button.addEventListener('click', closeDialog);
            });

            const confirmButton = document.getElementById('confirm-delete-agenda');

            confirmButton.addEventListener('click', async () => {
                confirmButton.disabled = true;
                confirmButton.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menghapus...';

                try {
                    const response = await fetch(`/agenda/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        closeDialog();
                        closeAgendaModal();

                        sessionStorage.setItem('bapperida_active_tab', 'agenda');

                        location.reload();
                    } else {
                        confirmButton.disabled = false;
                        confirmButton.innerHTML =
                            '<i class="fa-solid fa-trash-can mr-2"></i> Ya, Hapus';

                        alert(result.message || 'Gagal menghapus agenda.');
                    }
                } catch (error) {
                    confirmButton.disabled = false;
                    confirmButton.innerHTML =
                        '<i class="fa-solid fa-trash-can mr-2"></i> Ya, Hapus';

                    alert('Terjadi kesalahan saat menghapus agenda.');
                }
            });
        }

        // ==========================================
        // PAGINATION DASHBOARD: HARI INI / AKAN DATANG / ARSIP
        // ==========================================
        let dashboardCurrentPage = 1;

        function pindahHalamanDashboard(arah) {
            dashboardCurrentPage = Math.max(1, Math.min(3, dashboardCurrentPage + arah));
            renderDashboardList();
        }

        function renderDashboardList() {
            const todayDateEl = document.getElementById('dashboard-today-date');
            const todayCountEl = document.getElementById('dashboard-today-count');
            const todayListEl = document.getElementById('dashboard-today-list');
            const upcomingCountEl = document.getElementById('dashboard-upcoming-count');
            const upcomingListEl = document.getElementById('dashboard-upcoming-list');
            const archiveCountEl = document.getElementById('dashboard-archive-count');
            const archiveListEl = document.getElementById('dashboard-archive-list');
            const statEl = document.getElementById('stat-total-agenda');

            if (!todayListEl || !upcomingListEl || !archiveListEl) return;

            const tanggalHariIni = new Intl.DateTimeFormat('en-CA', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).format(new Date());

            if (todayDateEl) todayDateEl.textContent = formatTanggalLengkap(tanggalHariIni);

            const semuaAgenda = [];
            (globalAgendaData || []).forEach(item => {
                const tanggal = String(item.isoDate || item.tanggal_kegiatan || '').slice(0, 10);
                if (!/^\d{4}-\d{2}-\d{2}$/.test(tanggal)) return;

                const daftarKegiatan = Array.isArray(item.subEvents) && item.subEvents.length
                    ? item.subEvents
                    : [item];

                daftarKegiatan.forEach(evt => {
                    semuaAgenda.push({
                        tanggal,
                        waktu: evt.waktu || '-',
                        acara: evt.acara || '-',
                        ruang: evt.ruang || '-',
                        bidang: evt.bidang || '-',
                        disposisi: evt.disposisi || ''
                    });
                });
            });

            semuaAgenda.sort((a, b) => {
                const bedaTanggal = a.tanggal.localeCompare(b.tanggal);
                return bedaTanggal || String(a.waktu).localeCompare(String(b.waktu));
            });

            const agendaHariIni = semuaAgenda.filter(item => item.tanggal === tanggalHariIni);
            const agendaAkanDatang = semuaAgenda.filter(item => item.tanggal > tanggalHariIni);
            const agendaArsip = semuaAgenda.filter(item => item.tanggal < tanggalHariIni).reverse();

            if (statEl) statEl.textContent = semuaAgenda.length;
            if (todayCountEl) todayCountEl.textContent = `${agendaHariIni.length} kegiatan`;
            if (upcomingCountEl) upcomingCountEl.textContent = `${agendaAkanDatang.length} kegiatan`;
            if (archiveCountEl) archiveCountEl.textContent = `${agendaArsip.length} kegiatan`;

            function buatKartuAgenda(item, tampilkanTanggal = false) {
                const tanggalHtml = tampilkanTanggal
                    ? `<p class="text-blue-700 font-bold mb-2"><i class="fa-solid fa-calendar-day mr-1"></i>${escapePiket(formatTanggalLengkap(item.tanggal))}</p>`
                    : '';

                return `
                    <div class="bg-white border-l-4 border-blue-600 rounded-xl p-4 shadow-sm border border-slate-200 text-xs space-y-2">
                        ${tanggalHtml}
                        <div class="flex flex-wrap items-start justify-between gap-x-3 gap-y-1.5 font-bold text-blue-800 bg-blue-50 p-2 rounded">
                            <span class="flex items-center gap-1 shrink-0"><i class="fa-solid fa-clock"></i>${escapePiket(item.waktu)}</span>
                            <span class="uppercase flex-1 min-w-[10rem] text-left sm:text-right break-words leading-snug">${escapePiket(item.bidang !== '-' ? item.bidang : 'Umum')}</span>
                        </div>
                        <h4 class="font-bold text-slate-900"><i class="fa-solid fa-file-lines text-blue-600 mr-1"></i>${escapePiket(item.acara)}</h4>
                        <p class="text-slate-500"><i class="fa-solid fa-location-dot text-red-500"></i>${escapePiket(item.ruang)}</p>
                        ${item.disposisi && canViewDisposisi ? `<p class="text-emerald-700 bg-emerald-50 p-2 rounded"><b>Disposisi:</b> ${escapePiket(item.disposisi)}</p>` : ''}
                    </div>
                `;
            }

            todayListEl.innerHTML = agendaHariIni.length
                ? agendaHariIni.map(item => buatKartuAgenda(item)).join('')
                : `<div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-500">Belum ada agenda untuk hari ini.</div>`;

            upcomingListEl.innerHTML = agendaAkanDatang.length
                ? agendaAkanDatang.map(item => buatKartuAgenda(item, true)).join('')
                : `<div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-500">Belum ada agenda mendatang.</div>`;

            archiveListEl.innerHTML = agendaArsip.length
                ? agendaArsip.map(item => buatKartuAgenda(item, true)).join('')
                : `<div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-500">Belum ada agenda yang masuk arsip.</div>`;

            // Tampilkan satu kategori sesuai nomor halaman yang dipilih.
            document.getElementById('dashboard-page-today')?.classList.toggle('hidden', dashboardCurrentPage !== 1);
            document.getElementById('dashboard-page-upcoming')?.classList.toggle('hidden', dashboardCurrentPage !== 2);
            document.getElementById('dashboard-page-archive')?.classList.toggle('hidden', dashboardCurrentPage !== 3);

            const daftarAktif = dashboardCurrentPage === 1
                ? agendaHariIni
                : dashboardCurrentPage === 2
                    ? agendaAkanDatang
                    : agendaArsip;
            const namaHalaman = dashboardCurrentPage === 1
                ? 'Hari Ini'
                : dashboardCurrentPage === 2
                    ? 'Akan Datang'
                    : 'Arsip';

            const pageInfo = document.getElementById('dashboard-pagination-info');
            const pageNumber = document.getElementById('dashboard-page-number');
            const prevButton = document.getElementById('dashboard-prev-button');
            const nextButton = document.getElementById('dashboard-next-button');

            if (pageInfo) {
                pageInfo.textContent = `Menampilkan ${daftarAktif.length ? `1–${daftarAktif.length}` : '0'} dari ${daftarAktif.length} kegiatan`;
            }
            if (pageNumber) pageNumber.textContent = `${dashboardCurrentPage} / 3`;
            if (prevButton) prevButton.disabled = dashboardCurrentPage === 1;
            if (nextButton) nextButton.disabled = dashboardCurrentPage === 3;
        }

        let agendaCurrentPage = 1;
        const agendaPerPage = 10;

        function renderAgendaTable() {
            const searchInput = document.getElementById('search-agenda');
            const bulanSelect = document.getElementById('filter-bulan');
            const tahunSelect = document.getElementById('filter-tahun');
            const bidangSelect = document.getElementById('filter-bidang');
            const tbody = document.getElementById('agenda-table-body');
            const pagination = document.getElementById('agenda-pagination');

            if (!tbody) return;

            const disposisiHeader = document.getElementById('agenda-disposisi-header');
            if (disposisiHeader) {
                disposisiHeader.classList.toggle('hidden', !canViewDisposisi);
            }

            const query = searchInput ? searchInput.value.toLowerCase() : '';
            const bulanFilter = bulanSelect ? bulanSelect.value.toLowerCase() : '';
            const tahunFilter = tahunSelect ? tahunSelect.value.toLowerCase() : '';
            const bidangFilter = bidangSelect ? bidangSelect.value.toLowerCase() : '';

            const filtered = globalAgendaData.filter(item => {
                const tgl = (item.tanggal || '').toLowerCase();

                let translatedTgl = tgl
                    .replace('january', 'januari')
                    .replace('february', 'februari')
                    .replace('march', 'maret')
                    .replace('may', 'mei')
                    .replace('june', 'juni')
                    .replace('july', 'juli')
                    .replace('august', 'agustus')
                    .replace('october', 'oktober')
                    .replace('december', 'desember');

                const subEvents = Array.isArray(item.subEvents) ? item.subEvents : [];

                const matchBulan = !bulanFilter || translatedTgl.includes(bulanFilter);
                const matchTahun = !tahunFilter || translatedTgl.includes(tahunFilter);
                const matchBidang = !bidangFilter ||
                    (item.bidang || '').toLowerCase().includes(bidangFilter) ||
                    subEvents.some(se => (se.bidang || '').toLowerCase().includes(bidangFilter));

                const matchQuery = !query ||
                    translatedTgl.includes(query) ||
                    (item.acara || '').toLowerCase().includes(query) ||
                    (item.ruang || '').toLowerCase().includes(query);

                return matchBulan && matchTahun && matchBidang && matchQuery;
            });

            const totalPages = Math.max(1, Math.ceil(filtered.length / agendaPerPage));

            if (agendaCurrentPage > totalPages) {
                agendaCurrentPage = totalPages;
            }

            const startIndex = (agendaCurrentPage - 1) * agendaPerPage;
            const pageItems = filtered.slice(startIndex, startIndex + agendaPerPage);

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${canViewDisposisi ? 4 : 3}" class="text-center py-6 text-slate-400">Tidak ada agenda ditemukan.</td></tr>`;
                if (pagination) pagination.innerHTML = '';
                return;
            }

            tbody.innerHTML = pageItems.map(item => {
                const rawIso = item.isoDate || item.tanggal_kegiatan || '';
                const displayDate = /^\d{4}-\d{2}-\d{2}$/.test(rawIso)
                    ? formatTanggalLengkap(rawIso)
                    : item.tanggal;

                return `
                    <tr class="hover:bg-slate-50 cursor-pointer"
                        onclick="openAgendaModal(${globalAgendaData.indexOf(item)})">
                        <td class="py-3 px-3 font-semibold text-blue-700 whitespace-nowrap">${escapePiket(displayDate)}</td>
                        <td class="py-3 px-3">
                            <p class="font-bold text-slate-900">${escapePiket(item.acara || '-')}</p>
                            <p class="text-[11px] text-slate-500">${escapePiket(item.ruang || '-')}</p>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-bold">${escapePiket(item.bidang || '-')}</span>
                        </td>
                        ${canViewDisposisi ? `<td class="py-3 px-3 text-slate-600">${item.disposisi && item.disposisi !== '-' ? escapePiket(item.disposisi) : '-'}</td>` : ''}
                    </tr>`;
            }).join('');

            if (pagination) {
                const firstItem = startIndex + 1;
                const lastItem = Math.min(startIndex + agendaPerPage, filtered.length);

                pagination.innerHTML = `
                    <p class="text-xs text-slate-500 text-center sm:text-left">
                        Menampilkan ${firstItem}–${lastItem} dari ${filtered.length} agenda
                    </p>
                    <div class="flex items-center gap-2">
                        <button type="button"
                            onclick="changeAgendaPage(-1)"
                            ${agendaCurrentPage === 1 ? 'disabled' : ''}
                            class="px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 disabled:opacity-40">
                            Sebelumnya
                        </button>
                        <span class="text-xs font-semibold text-slate-600">
                            ${agendaCurrentPage} / ${totalPages}
                        </span>
                        <button type="button"
                            onclick="changeAgendaPage(1)"
                            ${agendaCurrentPage === totalPages ? 'disabled' : ''}
                            class="px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 disabled:opacity-40">
                            Berikutnya
                        </button>
                    </div>`;
            }
        }

        function changeAgendaPage(direction) {
            agendaCurrentPage += direction;
            renderAgendaTable();
        }

        let agendaCalendarDate = new Date();
        agendaCalendarDate.setDate(1);

        function setAgendaView(view) {
            const table = document.getElementById('agenda-table-body')?.closest('.overflow-x-auto');
            const calendar = document.getElementById('agenda-calendar-view');
            const btnTable = document.getElementById('btn-view-table');
            const btnCalendar = document.getElementById('btn-view-calendar');

            if (!table || !calendar) return;

            table.classList.toggle('hidden', view !== 'table');
            calendar.classList.toggle('hidden', view !== 'calendar');

            btnTable?.classList.toggle('bg-blue-600', view === 'table');
            btnTable?.classList.toggle('text-white', view === 'table');
            btnTable?.classList.toggle('bg-slate-100', view !== 'table');
            btnTable?.classList.toggle('text-slate-600', view !== 'table');

            btnCalendar?.classList.toggle('bg-blue-600', view === 'calendar');
            btnCalendar?.classList.toggle('text-white', view === 'calendar');
            btnCalendar?.classList.toggle('bg-slate-100', view !== 'calendar');
            btnCalendar?.classList.toggle('text-slate-600', view !== 'calendar');

            if (view === 'calendar') renderAgendaCalendar();
        }

        function changeAgendaCalendarMonth(offset) {
            agendaCalendarDate.setMonth(agendaCalendarDate.getMonth() + offset);

            const bulan = [
                'januari', 'februari', 'maret', 'april',
                'mei', 'juni', 'juli', 'agustus',
                'september', 'oktober', 'november', 'desember'
            ];

            const filterBulan = document.getElementById('filter-bulan');
            const filterTahun = document.getElementById('filter-tahun');

            if (filterBulan) {
                filterBulan.value = bulan[agendaCalendarDate.getMonth()];
            }

            if (filterTahun) {
                filterTahun.value = String(agendaCalendarDate.getFullYear());
            }

            agendaCurrentPage = 1;
            renderAgendaTable();
            renderAgendaCalendar();
        }

        function goToAgendaCalendarToday() {
            const today = new Date();

            agendaCalendarDate = new Date(
                today.getFullYear(),
                today.getMonth(),
                1
            );

            const bulan = [
                'januari', 'februari', 'maret', 'april',
                'mei', 'juni', 'juli', 'agustus',
                'september', 'oktober', 'november', 'desember'
            ];

            const filterBulan = document.getElementById('filter-bulan');
            const filterTahun = document.getElementById('filter-tahun');

            if (filterBulan) {
                filterBulan.value = bulan[today.getMonth()];
            }

            if (filterTahun) {
                filterTahun.value = String(today.getFullYear());
            }

            agendaCurrentPage = 1;
            renderAgendaTable();
            renderAgendaCalendar();
        }

        function renderAgendaCalendar() {
            const title = document.getElementById('calendar-month-title');
            const grid = document.getElementById('agenda-calendar-grid');

            if (!title || !grid) return;

            const year = agendaCalendarDate.getFullYear();
            const month = agendaCalendarDate.getMonth();

            title.textContent = new Date(year, month, 1).toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric'
            });

            const eventsByDate = {};
            const search = (document.getElementById('search-agenda')?.value || '').toLowerCase().trim();
            const monthFilter = (document.getElementById('filter-bulan')?.value || '').toLowerCase();
            const yearFilter = document.getElementById('filter-tahun')?.value || '';
            const fieldFilter = (document.getElementById('filter-bidang')?.value || '').toLowerCase();

            const monthNames = [
                'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
            ];

            globalAgendaData.forEach((item, index) => {
                const iso = item.isoDate || item.tanggal_kegiatan || '';

                if (!/^\d{4}-\d{2}-\d{2}$/.test(iso)) return;

                const [y, m] = iso.split('-').map(Number);

                if (y !== year || m !== month + 1) return;
                if (monthFilter && monthNames[month] !== monthFilter) return;
                if (yearFilter && String(y) !== yearFilter) return;

                const events = item.subEvents || [item];

                const matches = events.filter(evt => {
                    const text = [
                        item.tanggal || '',
                        evt.acara || '',
                        evt.ruang || '',
                        evt.bidang || '',
                        evt.disposisi || ''
                    ].join(' ').toLowerCase();

                    return (!search || text.includes(search)) &&
                        (!fieldFilter ||
                            (evt.bidang || '').toLowerCase().includes(fieldFilter) ||
                            (evt.disposisi || '').toLowerCase().includes(fieldFilter));
                });

                if (matches.length) {
                    eventsByDate[iso] = { index, events: matches };
                }
            });

            // KALENDER
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const today = new Date();
            const todayIso = [
                today.getFullYear(),
                String(today.getMonth() + 1).padStart(2, '0'),
                String(today.getDate()).padStart(2, '0')
            ].join('-');

            let html = '';

            for (let i = 0; i < firstDay; i++) {
                html += '<div class="min-h-20 sm:min-h-24 rounded-lg bg-slate-50/70"></div>';
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const iso = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const record = eventsByDate[iso];
                const isToday = iso === todayIso;

                html += `
                    <div class="min-w-0 min-h-[54px] sm:min-h-24 rounded-lg border ${
                        isToday
                            ? 'border-blue-500 bg-blue-50'
                            : 'border-slate-200 bg-white'
                    } p-1 sm:p-2">

                        <div class="text-[11px] sm:text-xs font-bold ${
                            isToday ? 'text-blue-700' : 'text-slate-700'
                        }">
                            ${day}
                        </div>

                        ${record ? `
                            <button
                                type="button"
                                onclick="openAgendaModal(${record.index})"
                                class="mt-1 w-full text-center sm:text-left rounded-md bg-blue-100 text-blue-800 px-0.5 sm:px-1.5 py-1 text-[9px] sm:text-xs hover:bg-blue-200 cursor-pointer">

                                <span class="font-bold">
                                    ${record.events.length} agenda
                                </span>

                                <span class="hidden sm:block truncate">
                                    ${escapePiket(record.events[0].acara || 'Kegiatan')}
                                </span>

                                ${record.events.length > 1 ? `
                                    <span class="hidden sm:block">
                                        +${record.events.length - 1} lainnya
                                    </span>
                                ` : ''}
                            </button>
                        ` : ''}
                    </div>`;
            }

            grid.innerHTML = html;
        }

        // ==========================================
        // PENGELOLAAN LAMPIRAN AGENDA (UPLOAD & PREVIEW)
        // ==========================================
        let stagedAgendaFiles = []; // { file, access_type }
        let existingAttachmentsData = []; // [{ id, original_name, file_size, access_type, mime_type }]

        function formatFileSize(bytes) {
            bytes = Number(bytes) || 0;
            if (bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function getFileExtension(filename) {
            if (!filename) return '';
            return filename.split('.').pop().toLowerCase();
        }

        function getFileIconClass(filename) {
            const ext = getFileExtension(filename);
            switch (ext) {
                case 'pdf':
                    return 'fa-solid fa-file-pdf text-red-500';
                case 'doc':
                case 'docx':
                    return 'fa-solid fa-file-word text-blue-600';
                case 'xls':
                case 'xlsx':
                    return 'fa-solid fa-file-excel text-emerald-600';
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'webp':
                    return 'fa-solid fa-file-image text-purple-600';
                default:
                    return 'fa-solid fa-file-lines text-slate-500';
            }
        }

        function resetAgendaAttachmentsState() {
            stagedAgendaFiles = [];
            existingAttachmentsData = [];
            const fileInput = document.getElementById('form-agenda-attachments');
            if (fileInput) fileInput.value = '';
            renderStagedFiles();
            renderExistingAttachments();
        }

        function handleAgendaFileSelect(files) {
            if (!files || files.length === 0) return;

            const allowedExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
            const maxSizeBytes = 10 * 1024 * 1024; // 10 MB

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const ext = getFileExtension(file.name);

                if (!allowedExts.includes(ext)) {
                    showAppToast(`Format file "${file.name}" tidak didukung. Format diizinkan: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG.`, 'error');
                    continue;
                }

                if (file.size > maxSizeBytes) {
                    showAppToast(`Ukuran file "${file.name}" melebihi batas 10 MB.`, 'error');
                    continue;
                }

                // Cek batas total file (staged + existing)
                if (stagedAgendaFiles.length + existingAttachmentsData.length >= 5) {
                    showAppToast('Maksimal 5 file lampiran untuk setiap agenda.', 'error');
                    break;
                }

                // Hindari duplikasi nama
                const isDuplicate = stagedAgendaFiles.some(f => f.file.name === file.name && f.file.size === file.size);
                if (!isDuplicate) {
                    stagedAgendaFiles.push({
                        file: file,
                        access_type: 'public' // Default akses publik
                    });
                }
            }

            renderStagedFiles();
        }

        function renderStagedFiles() {
            const container = document.getElementById('staged-attachments-container');
            const list = document.getElementById('staged-attachments-list');
            if (!container || !list) return;

            if (stagedAgendaFiles.length === 0) {
                container.classList.add('hidden');
                list.innerHTML = '';
                return;
            }

            container.classList.remove('hidden');
            list.innerHTML = stagedAgendaFiles.map((item, idx) => {
                const file = item.file;
                const icon = getFileIconClass(file.name);
                const sizeStr = formatFileSize(file.size);
                const extUpper = getFileExtension(file.name).toUpperCase();

                return `
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-2.5 bg-white border border-slate-200 rounded-xl text-xs shadow-sm">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-sm shrink-0">
                                <i class="${icon}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 truncate max-w-[200px] sm:max-w-[260px] md:max-w-[320px]">${escapePiket(file.name)}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">${sizeStr} &bull; ${extUpper}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 justify-end shrink-0">
                            <!-- PILIHAN AKSES PUBLIK / INTERNAL -->
                            <div class="flex items-center bg-slate-100 p-0.5 rounded-lg text-[11px] font-semibold">
                                <button type="button" onclick="setStagedFileAccess(${idx}, 'public')"
                                    class="px-2 py-1 rounded-md transition cursor-pointer ${item.access_type === 'public' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'}">
                                    <i class="fa-solid fa-globe mr-1"></i> Publik
                                </button>
                                <button type="button" onclick="setStagedFileAccess(${idx}, 'internal')"
                                    class="px-2 py-1 rounded-md transition cursor-pointer ${item.access_type === 'internal' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'}">
                                    <i class="fa-solid fa-lock mr-1"></i> Internal
                                </button>
                            </div>

                            <!-- TOMBOL HAPUS DARI LIST SEBELUM DIUNGGAH -->
                            <button type="button" onclick="removeStagedFile(${idx})"
                                class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                title="Batal unggah file ini">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function setStagedFileAccess(index, accessType) {
            if (stagedAgendaFiles[index]) {
                stagedAgendaFiles[index].access_type = accessType;
                renderStagedFiles();
            }
        }

        function removeStagedFile(index) {
            stagedAgendaFiles.splice(index, 1);
            renderStagedFiles();
        }

        function renderExistingAttachments() {
            const container = document.getElementById('existing-attachments-container');
            const list = document.getElementById('existing-attachments-list');
            if (!container || !list) return;

            if (existingAttachmentsData.length === 0) {
                container.classList.add('hidden');
                list.innerHTML = '';
                return;
            }

            container.classList.remove('hidden');
            list.innerHTML = existingAttachmentsData.map(att => {
                const icon = getFileIconClass(att.original_name);
                const sizeStr = formatFileSize(att.file_size);
                const extUpper = getFileExtension(att.original_name).toUpperCase();
                const isInternal = att.access_type === 'internal';

                const badge = isInternal
                    ? '<span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-200 text-amber-800 px-2 py-0.5 rounded text-[10px] font-bold"><i class="fa-solid fa-lock text-[9px]"></i> INTERNAL</span>'
                    : '<span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-200 text-emerald-800 px-2 py-0.5 rounded text-[10px] font-bold"><i class="fa-solid fa-globe text-[9px]"></i> PUBLIK</span>';

                return `
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-2.5 bg-white border border-slate-200 rounded-xl text-xs shadow-sm">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-sm shrink-0">
                                <i class="${icon}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 truncate max-w-[200px] sm:max-w-[260px] md:max-w-[320px]">${escapePiket(att.original_name)}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-[10px] text-slate-400">${sizeStr} &bull; ${extUpper}</span>
                                    ${badge}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 justify-end shrink-0">
                            <!-- TOMBOL PREVIEW -->
                            <button type="button" onclick="openAttachmentPreview(${att.id}, '${escapePiket(att.original_name)}', '${sizeStr}', '${att.access_type}')"
                                class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition cursor-pointer">
                                <i class="fa-regular fa-eye mr-1"></i> Preview
                            </button>

                            <!-- TOMBOL DOWNLOAD -->
                            <a href="/agenda/attachments/${att.id}/download" target="_blank"
                                class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition">
                                <i class="fa-solid fa-download mr-1"></i> Download
                            </a>

                            <!-- TOMBOL HAPUS LAMPIRAN -->
                            <button type="button" onclick="deleteExistingAttachment(${att.id}, '${escapePiket(att.original_name)}')"
                                class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                title="Hapus lampiran ini">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function deleteExistingAttachment(attachmentId, filename) {
            const confirmDelete = await showBapperidaConfirm({
                title: 'Hapus Lampiran Dokumen',
                message: `Apakah Anda yakin ingin menghapus lampiran "${filename}"? File akan dihapus permanen dari server.`,
                confirmText: 'Ya, Hapus File',
                type: 'danger'
            });

            if (!confirmDelete) return;

            try {
                const response = await apiFetch(`/agenda/attachments/${attachmentId}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Gagal menghapus lampiran.');
                }

                existingAttachmentsData = existingAttachmentsData.filter(att => att.id !== attachmentId);
                renderExistingAttachments();
                showAppToast('Lampiran berhasil dihapus.');

                // Perbarui juga data global agenda di memori agar popup detail sinkron
                globalAgendaData.forEach(day => {
                    const subs = day.subEvents || [day];
                    subs.forEach(ev => {
                        if (Array.isArray(ev.attachments)) {
                            ev.attachments = ev.attachments.filter(a => a.id !== attachmentId);
                        }
                    });
                });

            } catch (err) {
                showAppToast(err.message, 'error');
            }
        }

        // ==========================================
        // FITUR PREVIEW DOKUMEN MODAL
        // ==========================================
        function openAttachmentPreview(id, name, sizeStr, accessType) {
            const modal = document.getElementById('attachment-preview-modal');
            const titleEl = document.getElementById('preview-modal-title');
            const metaEl = document.getElementById('preview-modal-meta');
            const badgeEl = document.getElementById('preview-modal-badge');
            const bodyEl = document.getElementById('preview-modal-body');
            const downloadBtn = document.getElementById('preview-modal-download-btn');
            const iconEl = document.getElementById('preview-modal-icon');

            if (!modal || !titleEl || !bodyEl) return;

            titleEl.textContent = name;
            metaEl.textContent = sizeStr || '';

            if (iconEl) {
                iconEl.innerHTML = `<i class="${getFileIconClass(name)}"></i>`;
            }

            if (badgeEl) {
                if (accessType === 'internal') {
                    badgeEl.className = 'px-2 py-0.5 rounded-full font-bold uppercase text-[9px] bg-amber-500/20 text-amber-300 border border-amber-500/40';
                    badgeEl.innerHTML = '<i class="fa-solid fa-lock text-[8px] mr-1"></i> Internal';
                } else {
                    badgeEl.className = 'px-2 py-0.5 rounded-full font-bold uppercase text-[9px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                    badgeEl.innerHTML = '<i class="fa-solid fa-globe text-[8px] mr-1"></i> Publik';
                }
            }

            if (downloadBtn) {
                downloadBtn.href = `/agenda/attachments/${id}/download`;
            }

            const previewUrl = `/agenda/attachments/${id}/preview`;
            const ext = getFileExtension(name);

            // Tampilkan loading spinner
            bodyEl.innerHTML = `
                <div class="flex flex-col items-center justify-center p-8 text-slate-400">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-3 text-blue-600"></i>
                    <p class="text-xs font-semibold">Memuat pratinjau dokumen...</p>
                </div>
            `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Jika gambar -> render <img>
            if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                const img = new Image();
                img.onload = () => {
                    bodyEl.innerHTML = `
                        <div class="w-full h-full flex items-center justify-center p-4">
                            <img src="${previewUrl}" alt="${escapePiket(name)}" class="max-w-full max-h-full object-contain rounded-xl shadow-lg border border-slate-200">
                        </div>
                    `;
                };
                img.onerror = () => {
                    renderPreviewError(bodyEl, 'Gagal memuat gambar atau file tidak dapat diakses.');
                };
                img.src = previewUrl;
            } else {
                // Dokumen PDF, Word, Excel -> render via <iframe>
                const iframe = document.createElement('iframe');
                iframe.className = 'w-full h-full border-0 rounded-xl bg-white shadow-sm';
                iframe.src = previewUrl;
                iframe.onload = () => {
                    // Iframe selesai dimuat
                };
                iframe.onerror = () => {
                    renderPreviewError(bodyEl, 'Gagal memuat pratinjau dokumen.');
                };

                // Bersihkan dan masukkan iframe
                bodyEl.innerHTML = '';
                bodyEl.appendChild(iframe);
            }
        }

        function renderPreviewError(container, message) {
            container.innerHTML = `
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md text-center max-w-sm">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3 text-xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h5 class="font-bold text-slate-800 text-sm mb-1">Pratinjau Tidak Tersedia</h5>
                    <p class="text-xs text-slate-500 mb-4">${escapePiket(message)}</p>
                    <button type="button" onclick="closeAttachmentPreviewModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                        Tutup
                    </button>
                </div>
            `;
        }

        function closeAttachmentPreviewModal() {
            const modal = document.getElementById('attachment-preview-modal');
            const bodyEl = document.getElementById('preview-modal-body');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
            if (bodyEl) {
                bodyEl.innerHTML = ''; // Hentikan pemutaran/loading iframe
            }
        }

        // Inisialisasi Drag & Drop pada Area Upload
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('agenda-drop-zone');
            const fileInput = document.getElementById('form-agenda-attachments');

            if (dropZone && fileInput) {
                dropZone.addEventListener('click', () => {
                    fileInput.click();
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.add('border-blue-500', 'bg-blue-50/50');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.remove('border-blue-500', 'bg-blue-50/50');
                    });
                });

                dropZone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    if (dt && dt.files && dt.files.length > 0) {
                        handleAgendaFileSelect(dt.files);
                    }
                });
            }
        });

        // ==========================================
        // MODAL DETAIL AGENDA & LAMPIRAN
        // ==========================================
        function openAgendaModal(index) {
            const item = globalAgendaData[index];
            if (!item) return;

            const modal = document.getElementById('agenda-modal');
            const tanggalEl = document.getElementById('modal-tanggal');
            const container = document.getElementById('modal-events-container');

            if (!modal || !tanggalEl || !container) return;

            const rawIso = item.isoDate || item.tanggal_kegiatan || '';
            const displayDate = /^\d{4}-\d{2}-\d{2}$/.test(rawIso)
                ? formatTanggalLengkap(rawIso)
                : (item.tanggal || '-');

            tanggalEl.textContent = displayDate;

            const events = Array.isArray(item.subEvents) && item.subEvents.length > 0
                ? item.subEvents
                : [item];

            container.innerHTML = events.map(evt => {
                let attachmentsHtml = '';
                if (Array.isArray(evt.attachments) && evt.attachments.length > 0) {
                    const cards = evt.attachments.map(att => {
                        const icon = getFileIconClass(att.original_name);
                        const sizeStr = formatFileSize(att.file_size);
                        const extUpper = getFileExtension(att.original_name).toUpperCase();
                        const isInternal = att.access_type === 'internal';

                        const badge = isInternal
                            ? '<span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[10px]"><i class="fa-solid fa-lock text-[8px]"></i> INTERNAL</span>'
                            : '<span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]"><i class="fa-solid fa-globe text-[8px]"></i> PUBLIK</span>';

                        const safeName = escapePiket(att.original_name || 'Lampiran');

                        return `
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 p-3 bg-white border border-slate-200/90 rounded-xl shadow-xs hover:border-blue-300 transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-lg shrink-0">
                                        <i class="${icon}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-slate-800 text-xs truncate max-w-[180px] sm:max-w-[240px] md:max-w-[300px]" title="${safeName}">${safeName}</p>
                                            ${badge}
                                        </div>
                                        <p class="text-[11px] text-slate-400 mt-0.5 font-medium">${sizeStr} &bull; ${extUpper}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 justify-end shrink-0 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                    <button type="button"
                                        onclick="openAttachmentPreview(${att.id}, '${safeName.replace(/'/g, "\\'")}', '${sizeStr}', '${att.access_type}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg text-xs transition cursor-pointer">
                                        <i class="fa-regular fa-eye"></i>
                                        <span>Preview</span>
                                    </button>
                                    <a href="/agenda/attachments/${att.id}/download"
                                        target="_blank"
                                        rel="noopener"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition cursor-pointer">
                                        <i class="fa-solid fa-download"></i>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        `;
                    }).join('');

                    attachmentsHtml = `
                        <div class="mt-3 pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-paperclip text-blue-600"></i>
                                    <span>Lampiran Dokumen</span>
                                </p>
                                <span class="text-[10px] text-slate-400 font-medium">${evt.attachments.length} file terlampir</span>
                            </div>
                            <div class="space-y-2">${cards}</div>
                        </div>
                    `;
                }

                let actionButtons = '';
                if (isLoggedIn) {
                    actionButtons = `
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type='button' onclick='openEditForm(${JSON.stringify(evt)})' class='px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold text-xs cursor-pointer shadow-sm'>
                                <i class="fa-solid fa-pen mr-1"></i> Edit
                            </button>
                            <button type='button' onclick='deleteAgenda(${evt.id})' class='px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-xs cursor-pointer shadow-sm'>
                                <i class="fa-solid fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    `;
                }

                return `
                    <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/80 text-xs space-y-2">
                        <div class="flex flex-wrap items-center justify-between gap-2 font-bold text-blue-800 bg-blue-50/70 p-2 rounded-lg">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-clock"></i> ${escapePiket(evt.waktu || '-')}</span>
                            <span class="uppercase text-slate-700">${escapePiket(evt.bidang || 'Umum')}</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">${escapePiket(evt.acara || '-')}</p>
                            <p class="text-slate-500 mt-1"><i class="fa-solid fa-location-dot text-red-500 mr-1"></i> ${escapePiket(evt.ruang || '-')}</p>
                        </div>
                        ${evt.disposisi && evt.disposisi !== '-' && canViewDisposisi ? `
                            <div class="bg-emerald-50 border border-emerald-100 p-2.5 rounded-lg text-emerald-900">
                                <span class="font-bold block mb-0.5"><i class="fa-solid fa-pen-nib mr-1"></i> Disposisi Pimpinan:</span>
                                <p class="text-slate-700 whitespace-pre-line">${escapePiket(evt.disposisi)}</p>
                            </div>
                        ` : ''}
                        ${attachmentsHtml}
                        ${actionButtons}
                    </div>
                `;
            }).join('');

            modal.classList.remove('hidden');
        }

        function closeAgendaModal() {
            document.getElementById('agenda-modal').classList.add('hidden');
        }
    </script>
</body>
</html>