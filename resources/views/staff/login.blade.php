@extends('layouts.app', ['title' => 'Login Pegawai - Bapperida Belitung'])

@section('content')
<script>
    window.location.href = "{{ route('home', ['action' => 'login_staff']) }}";
</script>
<div class="min-h-screen flex items-center justify-center bg-slate-100 p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-6 text-center space-y-4">
        <h2 class="text-xl font-bold text-slate-800">Login Pegawai BAPPERIDA</h2>
        <p class="text-sm text-slate-500">Mengarahkan ke halaman login pegawai...</p>
        <a href="{{ route('home', ['action' => 'login_staff']) }}" class="inline-block px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition">
            Buka Form Login Pegawai
        </a>
    </div>
</div>
@endsection
