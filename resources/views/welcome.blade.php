<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amikom Event Hub - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-900">
    <nav class="bg-white/80 backdrop-blur-md shadow-sm p-4 sticky top-0 z-50 border-b border-slate-200">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <span class="text-xl font-bold text-indigo-600">EventHub.</span>
            <div class="hidden md:flex gap-6 font-medium">
                <a href="/" class="text-indigo-600 border-b-2 border-indigo-600 pb-1">Home</a>
                <a href="/profil" class="hover:text-indigo-600 transition">Profil</a>
                <a href="/katalog" class="hover:text-indigo-600 transition">Katalog</a>
                <a href="/bantuan" class="hover:text-indigo-600 transition">Bantuan</a>
                <a href="/kontak" class="hover:text-indigo-600 transition">Kontak</a>
            </div>
        </div>
    </nav>

    <header class="relative bg-white pt-20 pb-32 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="bg-indigo-100 text-indigo-700 px-4 py-1.5 rounded-full text-sm font-bold tracking-wide uppercase mb-6 inline-block">Platform Event Kampus #1</span>
                <h1 class="text-6xl font-black text-slate-900 leading-tight mb-8">Eksplorasi Kreativitas Tanpa Batas di <span class="text-indigo-600 italic">Amikom.</span></h1>
                <p class="text-lg text-slate-500 mb-10 leading-relaxed">Jangan lewatkan momen berharga! Amikom Event Hub membantu Anda menemukan workshop, seminar, dan kompetisi terbaik untuk meningkatkan skill Anda.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/katalog" class="bg-indigo-600 text-white px-10 py-4 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition duration-300">Cari Event Sekarang</a>
                    <a href="/profil" class="bg-white border-2 border-slate-200 text-slate-700 px-10 py-4 rounded-xl font-bold hover:bg-slate-50 transition duration-300">Tentang Pengembang</a>
                </div>
            </div>
        </div>
    </header>

    <section class="max-w-6xl mx-auto px-4 -mt-16 relative z-20">
        <div class="bg-indigo-900 rounded-3xl p-10 grid grid-cols-1 md:grid-cols-3 gap-8 shadow-2xl text-center">
            <div>
                <p class="text-indigo-300 mb-2 font-medium">Event Aktif</p>
                <h3 class="text-4xl font-bold text-white tracking-tight">50+</h3>
            </div>
            <div class="border-y md:border-y-0 md:border-x border-indigo-800 py-6 md:py-0">
                <p class="text-indigo-300 mb-2 font-medium">Mahasiswa Terdaftar</p>
                <h3 class="text-4xl font-bold text-white tracking-tight">1.200+</h3>
            </div>
            <div>
                <p class="text-indigo-300 mb-2 font-medium">Penyelenggara</p>
                <h3 class="text-4xl font-bold text-white tracking-tight">25</h3>
            </div>
        </div>
    </section>

    <footer class="mt-32 border-t border-slate-200 py-12 text-center text-slate-400 text-sm">
        <p>© 2026 Reyhan Amin Afrizal - Tugas Pemasaram Digital</p>
    </footer>
</body>
</html>