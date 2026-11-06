<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Kedaluwarsa — SMK Darus Sa'adah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8 text-center">
        <!-- Logo Sekolah -->
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 p-2 mx-auto mb-4 flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Darus Sa'adah" class="w-full h-full object-contain">
        </div>

        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-wider mb-2">
            Status: 419 Page Expired
        </span>

        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Sesi Anda Telah Kedaluwarsa</h1>
        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
            Halaman ini sempat terbuka lama dalam keadaan tidak aktif atau sesi keamanan telah diperbarui. Silakan muat ulang atau masuk kembali ke sistem.
        </p>

        <div class="mt-6 flex flex-col sm:flex-row gap-2.5 justify-center">
            <button onclick="window.location.reload()" 
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Muat Ulang Halaman (Refresh)
            </button>
            <a href="{{ route('login') }}" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                Kembali ke Halaman Masuk &rarr;
            </a>
        </div>
    </div>
</body>
</html>
