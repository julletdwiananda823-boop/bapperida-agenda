<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountManagementController extends Controller
{
    /**
     * Pastikan hanya Pimpinan aktif yang dapat mengakses.
     */
    private function isPimpinan(Request $request): bool
    {
        $user = $request->user();

        return $user !== null
            && $user->role === 'pimpinan'
            && (bool) $user->is_active;
    }

    /**
     * Menampilkan daftar dan jumlah akun Admin.
     */
    public function index(Request $request)
    {
        if (!$this->isPimpinan($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses khusus Pimpinan.',
            ], 403);
        }

        $admins = User::where('role', 'admin')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'is_active', 'created_at']);

        return response()->json([
            'success' => true,
            'total' => $admins->count(),
            'admins' => $admins,
        ]);
    }

    /**
     * Membuat akun Admin baru.
     */
    public function storeAdmin(Request $request)
    {
        if (!$this->isPimpinan($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses khusus Pimpinan.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
        ], [
            'name.required' => 'Nama Admin wajib diisi.',
            'name.max' => 'Nama Admin maksimal 100 karakter.',
            'email.required' => 'Email Admin wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email Admin sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password maksimal 100 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'admin',
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun Admin berhasil dibuat.',
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    /**
     * Mengganti password akun Admin.
     */
    public function updateAdminPassword(Request $request, $id)
    {
        if (!$this->isPimpinan($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses khusus Pimpinan.',
            ], 403);
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.max' => 'Password baru maksimal 100 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->password = $validated['password'];
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => "Password Admin {$admin->name} berhasil diperbarui.",
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    /**
     * Menghapus akun Admin.
     */
    public function destroyAdmin(Request $request, $id)
    {
        if (!$this->isPimpinan($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses khusus Pimpinan.',
            ], 403);
        }

        $admin = User::where('role', 'admin')->findOrFail($id);

        if ($admin->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri.',
            ], 403);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun Admin berhasil dihapus.',
        ]);
    }

    /**
     * Mengganti PIN bersama Pegawai.
     */
    public function updateStaffPin(Request $request)
    {
        if (!$this->isPimpinan($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses khusus Pimpinan.',
            ], 403);
        }

        $validated = $request->validate([
            'pin' => ['required', 'string', 'min:4', 'max:20'],
        ]);

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'staff_pin_hash'],
            [
                'value' => Hash::make($validated['pin']),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'PIN Pegawai berhasil diganti.',
        ]);
    }
}