<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi - Simple POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col justify-between">
    <!-- Navbar Tamu -->
    <header class="bg-slate-900 text-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-white shadow">
                    POS
                </div>
                <span class="font-bold text-lg tracking-tight">Simple POS</span>
            </div>
            <nav class="flex items-center gap-4">
                <a href="{{ route('info') }}" class="text-sm font-medium text-blue-400 hover:text-white transition">
                    Informasi
                </a>
                <a href="{{ route('login') }}" class="text-sm bg-blue-600 hover:bg-blue-500 text-white font-medium px-4 py-2 rounded-md transition shadow">
                    Masuk ke Sistem
                </a>
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="max-w-6xl mx-auto px-4 py-12 flex-grow">
        <!-- Hero Section -->
        <section class="text-center py-10">
            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                Halaman Informasi Tamu
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                Sistem Kasir Modern & Efisien
            </h1>
            <p class="max-w-2xl mx-auto text-base md:text-lg text-slate-600 mb-8 leading-relaxed">
                Simple POS dirancang untuk mempermudah operasional transaksi penjualan ritel, manajemen katalog produk, serta pemantauan riwayat transaksi bisnis Anda.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-slate-900 text-white font-semibold text-sm hover:bg-slate-800 transition shadow">
                    Mulai Sekarang & Masuk
                </a>
            </div>
        </section>

        <!-- Fitur Unggulan -->
        <section class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold mb-4">
                    ⚡
                </div>
                <h2 class="text-lg font-semibold text-slate-900 mb-2">Transaksi Kasir Cepat</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pencatatan transaksi dengan pemilihan produk yang intuitif, kalkulasi otomatis, dan validasi sisi server yang aman.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center font-bold mb-4">
                    📦
                </div>
                <h2 class="text-lg font-semibold text-slate-900 mb-2">Katalog & Kategori</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pengelolaan produk terstruktur dengan kategori, informasi harga, pengelolaan stok, dan kode SKU yang rapi.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center font-bold mb-4">
                    🛡️
                </div>
                <h2 class="text-lg font-semibold text-slate-900 mb-2">Hak Akses Multi-Role</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Sistem keamanan role-based untuk Admin, Manager, dan Kasir yang memastikan setiap pengguna hanya mengakses fitur yang diizinkan.
                </p>
            </div>
        </section>

        <!-- Info Akun Demo -->
        <section class="mt-12 bg-white rounded-xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Informasi Akun Demo</h2>
            <p class="text-sm text-slate-600 mb-6">
                Silakan gunakan kredensial berikut untuk menguji sistem sesuai peran masing-masing:
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-sm">
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800 mb-2">Admin</span>
                    <p class="font-medium text-slate-800">admin@pos.test</p>
                    <p class="text-xs text-slate-500 mt-1">Sandi: <code>password</code></p>
                </div>
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-sm">
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 mb-2">Manager</span>
                    <p class="font-medium text-slate-800">manager@pos.test</p>
                    <p class="text-xs text-slate-500 mt-1">Sandi: <code>password</code></p>
                </div>
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-sm">
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800 mb-2">Kasir</span>
                    <p class="font-medium text-slate-800">kasir@pos.test</p>
                    <p class="text-xs text-slate-500 mt-1">Sandi: <code>password</code></p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Simple POS - Kelompok 02. Dilindungi Hak Cipta.</p>
    </footer>
</body>
</html>
