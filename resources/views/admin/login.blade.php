<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - BAPPERIDA Kabupaten Belitung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">

        <div class="bg-slate-900 text-white px-8 py-8 text-center">
            <h1 class="text-2xl font-extrabold">BAPPERIDA</h1>
            <p class="text-sm text-slate-300 mt-1">Kabupaten Belitung</p>
            <div class="border-t border-slate-700 mt-5 pt-5">
                <h2 class="font-bold text-lg">Login Administrator</h2>
                <p class="text-xs text-slate-300 mt-2">
                    Sistem Informasi Agenda & Disposisi
                </p>
            </div>
        </div>

        <div class="p-8">

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <input type="hidden" name="role" value="admin">

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email Admin
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600"
                        placeholder="admin1@bapperida.go.id">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600"
                        placeholder="Masukkan password">
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold py-3">
                    Masuk sebagai Admin
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('home') }}"
                   class="text-sm text-slate-500 hover:text-blue-700">
                    Kembali ke halaman agenda
                </a>
            </div>
        </div>

        <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 text-center">
            <p class="text-xs text-slate-500">
                BAPPERIDA Kabupaten Belitung
            </p>
        </div>
    </div>

</body>
</html>
