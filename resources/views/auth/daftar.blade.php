<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar di Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#137fec",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(25, 38, 51, 0.6);
            backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.05), 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        .bg-image {
            background-image: linear-gradient(rgba(16, 25, 34, 0.85), rgba(16, 25, 34, 0.85)),
                url('https://media.istockphoto.com/id/1339845062/photo/reading-room-or-library-interior-with-leather-armchair-bookshelf-and-floor-lamp.jpg?s=612x612&w=0&k=20&c=2ghOW2DCvb49Up3D0eFeVzv1kbSMjUq-_psohUYeZB0=');
        }
    </style>
</head>

<body
    class="bg-background-dark font-display text-white min-h-screen flex items-center justify-center px-4 bg-image bg-cover bg-center bg-no-repeat">

    <main class="w-full max-w-[420px] z-10 my-10">
        <div class="glass-card rounded-2xl p-8 shadow-2xl">

            <div class="mb-8 text-center">
                <h2 class="text-4xl font-bold tracking-tight mb-2">Daftar Jokopus</h2>
                <p class="text-sm text-slate-400">
                    <span class="flex items-center justify-center gap-1.5 italic opacity-80">
                        <span class="material-symbols-outlined text-[16px]">database</span>
                        {{ $dbStatus }}
                    </span>
                </p>
            </div>

            @if(session('success'))
                <div
                    class="mb-5 text-sm text-green-400 bg-green-400/10 border border-green-400/20 p-3 rounded-lg text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 text-sm text-red-400 bg-red-400/10 border border-red-400/20 p-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/users/store" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Nama Lengkap</label>
                    <input name="name" type="text" required
                        class="w-full h-11 rounded-xl bg-slate-900/50 border border-slate-700 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                        placeholder="Contoh: Budi Santoso">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Email</label>
                    <input name="email" type="email" required
                        class="w-full h-11 rounded-xl bg-slate-900/50 border border-slate-700 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                        placeholder="email@example.com">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Password</label>
                    <input name="password" type="password" required
                        class="w-full h-11 rounded-xl bg-slate-900/50 border border-slate-700 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                        placeholder="Minimal 8 karakter">
                </div>

                <button type="submit"
                    class="w-full h-11 mt-4 rounded-xl bg-primary font-bold text-white hover:bg-blue-600 active:scale-[0.98] transition-all shadow-lg shadow-primary/20">
                    Daftar Sekarang
                </button>

                <div class="pt-4 flex flex-col gap-3 text-center">
                    <a href="/login" class="text-sm text-slate-400 hover:text-white transition">
                        Sudah punya akun? <span class="text-primary font-semibold">Login</span>
                    </a>

                    <div class="flex items-center gap-4 my-1">
                        <div class="h-[1px] w-full bg-white/10"></div>
                        <span class="text-xs text-slate-500 uppercase tracking-widest">atau</span>
                        <div class="h-[1px] w-full bg-white/10"></div>
                    </div>

                    <a href="/"
                        class="text-sm font-medium text-slate-400 hover:text-white flex items-center justify-center gap-2 transition">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-xs text-slate-500">
            &copy; 2026 Jokopus
        </p>
    </main>

</body>

</html>