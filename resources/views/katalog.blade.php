<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Event - EventAmikom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <nav class="bg-white shadow-sm p-4 sticky top-0 z-50 flex justify-center gap-8 font-medium border-b border-slate-100">
        <a href="/" class="hover:text-indigo-600 transition">Home</a>
        <a href="/profil" class="hover:text-indigo-600 transition">Profil</a>
        <a href="/katalog" class="text-indigo-600 font-bold">Katalog</a>
        <a href="/bantuan" class="hover:text-indigo-600 transition">Bantuan</a>
        <a href="/kontak" class="hover:text-indigo-600 transition">Kontak</a>
    </nav>

    <div class="max-w-6xl mx-auto py-16 px-4">
        <header class="mb-12">
            <h1 class="text-4xl font-black text-slate-900 mb-2">Eksplor Katalog.</h1>
            <p class="text-slate-500">Temukan event yang sesuai dengan minat Anda di Amikom Yogyakarta.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-2 transition duration-300">
                <div class="h-48 bg-indigo-500 flex items-center justify-center text-5xl">⚡</div>
                <div class="p-6">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Seminar</span>
                    <h3 class="text-xl font-bold mt-2 mb-3">Seminar Nasional IT</h3>
                    <p class="text-slate-500 text-sm mb-6">Masa depan AI dan pengaruhnya bagi lulusan Sistem Informasi di era 2026.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">📍 Cinema Amikom</span>
                        <button class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Daftar</button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-2 transition duration-300">
                <div class="h-48 bg-purple-500 flex items-center justify-center text-5xl">🎨</div>
                <div class="p-6">
                    <span class="text-xs font-bold text-purple-600 uppercase tracking-widest">Workshop</span>
                    <h3 class="text-xl font-bold mt-2 mb-3">UI/UX Design Basic</h3>
                    <p class="text-slate-500 text-sm mb-6">Pelajari cara membuat desain aplikasi yang user-friendly menggunakan Figma.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">📍 Lab ICT 2</span>
                        <button class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Daftar</button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-2 transition duration-300">
                <div class="h-48 bg-emerald-500 flex items-center justify-center text-5xl">📈</div>
                <div class="p-6">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Finance</span>
                    <h3 class="text-xl font-bold mt-2 mb-3">Crypto & Stock 101</h3>
                    <p class="text-slate-500 text-sm mb-6">Workshop strategi investasi cerdas bagi mahasiswa di pasar modal & kripto.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">📍 Aula BSC</span>
                        <button class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Daftar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>