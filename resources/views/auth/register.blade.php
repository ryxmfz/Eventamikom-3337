<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun User - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } .glass { background: rgba(255, 255, 255, 0.82); backdrop-filter: blur(16px); } </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <div class="max-w-md w-full relative z-10 my-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-slate-900">Buat Akun Pembeli 🎟️</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">Daftar untuk memesan dan mengelola tiket event Amikom</p>
        </div>
        <div class="glass rounded-[2rem] p-8 shadow-2xl border border-white/80">
            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="John Doe" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase">Email Address</label>
                    <input type="email" name="email" required placeholder="user@gmail.com" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 uppercase">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold">
                </div>
                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition">Daftar Sekarang</button>
            </form>
            <div class="mt-6 text-center text-xs text-slate-500">
                Sudah punya akun? <a href="{{ route('admin.login') }}" class="font-bold text-indigo-600">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
