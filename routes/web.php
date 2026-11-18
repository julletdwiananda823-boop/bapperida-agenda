<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaAttachmentController;
use App\Http\Controllers\BookingAuthController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\StaffLoginController;
use App\Http\Controllers\RoomBookingController;
use App\Http\Controllers\AccountManagementController;
use App\Http\Middleware\EnsureBookingAuthenticated;
use App\Exports\AgendaExport;
use Maatwebsite\Excel\Facades\Excel;

// LOGIN PIMPINAN DAN ADMIN
Route::redirect('/login', '/admin/login')->name('login');
Route::get('/admin/login', [AdminLoginController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

// LOGIN PEGAWAI
Route::get('/staff/login', [StaffLoginController::class, 'showLogin'])
    ->name('staff.login');

Route::post('/staff/login', [StaffLoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('staff.login.submit');

// Pegawai menggunakan sesi khusus, bukan middleware auth
Route::post('/staff/logout', [StaffLoginController::class, 'logout'])
    ->name('staff.logout');

// HALAMAN UTAMA
Route::get('/', [AgendaController::class, 'index'])
    ->name('home');

// FITUR PENGELOLAAN PIMPINAN DAN ADMIN
Route::middleware('management')->group(function () {

    Route::post('/agenda/save', [AgendaController::class, 'storeOrUpdate'])
        ->name('agenda.save');

    Route::delete('/agenda/delete/{id}', [AgendaController::class, 'destroy'])
        ->name('agenda.delete');

    Route::post('/agenda/{agendaId}/attachments', [AgendaAttachmentController::class, 'store']);

    Route::delete('/agenda/attachments/{id}', [AgendaAttachmentController::class, 'destroy']);

    Route::post('/apel/save', [AgendaController::class, 'saveApel'])
        ->name('apel.save');

    Route::delete('/apel/delete/{id}', [AgendaController::class, 'deleteApel'])
        ->name('apel.delete');

    Route::get('/api/reports', [AgendaController::class, 'getReportData'])
        ->name('reports.data');

    Route::get('/reports/export-excel', function (Request $request) {
        return Excel::download(
            new AgendaExport(
                $request->query('bulan'),
                $request->query('tahun'),
                $request->query('jenis', 'semua'),
                $request->query('bidang'),
                $request->query('search')
            ),
            'laporan-agenda-bapperida.xlsx'
        );
    })->name('reports.export-excel');
});

// AKSES LAMPIRAN DOKUMEN AGENDA (Pemeriksaan Hak Akses Publik & Internal di Controller)
Route::get('/agenda/attachments/{id}/download', [AgendaAttachmentController::class, 'download'])
    ->name('agenda.attachments.download');
Route::get('/agenda/attachments/{id}/preview', [AgendaAttachmentController::class, 'preview'])
    ->name('agenda.attachments.preview');

// MANAJEMEN AKUN KHUSUS PIMPINAN
Route::middleware(['auth'])
    ->prefix('management')
    ->name('management.')
    ->group(function () {

        Route::get('/accounts', [AccountManagementController::class, 'index'])
            ->name('accounts.index');

        Route::post('/accounts/admin', [AccountManagementController::class, 'storeAdmin'])
            ->name('accounts.admin.store');

        Route::put('/accounts/admin/{id}/password', [AccountManagementController::class, 'updateAdminPassword'])
            ->name('accounts.admin.password');

        Route::delete('/accounts/admin/{id}', [AccountManagementController::class, 'destroyAdmin'])
            ->name('accounts.admin.destroy');

        Route::put('/accounts/staff-pin', [AccountManagementController::class, 'updateStaffPin'])
            ->name('accounts.staff-pin');
    });

// BOOKING RUANGAN
// Tetap terpisah dari login Pegawai
Route::post('/booking/login', [BookingAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('booking.login');

Route::post('/booking/logout', [BookingAuthController::class, 'logout'])
    ->name('booking.logout');

Route::get('/bookings', [RoomBookingController::class, 'index'])
    ->name('bookings.index');

Route::middleware(EnsureBookingAuthenticated::class)->group(function () {

    Route::post('/bookings', [RoomBookingController::class, 'store'])
        ->name('bookings.store');

    Route::delete('/bookings/{id}', [RoomBookingController::class, 'destroy'])
        ->name('bookings.destroy');
});