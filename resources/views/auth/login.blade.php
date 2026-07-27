<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Background Glass Decorative Blobs (Serasi dengan Landing Page) -->
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse pointer-events-none"></div>
    <div class="absolute -bottom-10 right-10 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10">

        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group mb-3">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-indigo-200 group-hover:scale-105 transition-transform">
                    AH
                </div>
                <span class="text-2xl font-black tracking-tight text-slate-900">AmikomEventHub</span>
            </a>
            <p class="text-sm font-medium text-slate-500">Masuk untuk melanjutkan pesanan tiketmu</p>
        </div>

        <!-- Glassmorphism Card -->
        <div class="glass rounded-[2rem] p-8 shadow-2xl shadow-indigo-100/50 border border-white/80">

            {{-- Alert Notifikasi Sukses --}}
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl mb-6 font-bold text-sm text-center border border-emerald-200/80">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Alert Notifikasi Error --}}
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 font-bold text-sm text-center border border-red-200/80">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Alert Validasi --}}
            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 font-bold text-sm border border-red-200/80 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="text-center">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form Login Manual -->
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" required placeholder="admin@amikom.ac.id"
                        class="w-full px-5 py-3.5 bg-white/90 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-5 py-3.5 bg-white/90 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition shadow-2xs">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all cursor-pointer">
                    Masuk Akun
                </button>
            </form>

            {{-- Pembatas / Divider --}}
            <div class="relative my-6 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <span class="relative px-4 bg-slate-50/50 text-xs font-bold text-slate-400 uppercase tracking-wider">Atau</span>
            </div>

            {{-- Tombol Login Google SSO --}}
            <a href="{{ route('auth.google') }}"
               class="w-full py-3.5 px-5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300 rounded-2xl font-bold text-sm flex items-center justify-center gap-3 transition shadow-2xs hover:shadow-md cursor-pointer">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Continue with Google
            </a>

            <!-- Link Kembali ke Beranda -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>

</body>
</html>
