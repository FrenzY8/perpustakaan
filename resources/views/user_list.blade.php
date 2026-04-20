<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>List Buku - Jokopus</title>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .glass-card {
            background-color: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass {
            background: rgba(25, 38, 51, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        }

        .fill-1 {
            font-variation-settings: "FILL" 1;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <header class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-[1200px]">
            <div class="glass border border-white/10 rounded-2xl px-6 py-3 shadow-2xl shadow-black/20">
                <div class="flex items-center justify-between">

                    <div onclick="window.location.href='/'" class="flex items-center gap-3 cursor-pointer group">
                        <div
                            class="size-9 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                            <svg viewBox="0 0 48 48" fill="currentColor" class="size-6">
                                <path
                                    d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z" />
                            </svg>
                        </div>
                        <h2
                            class="text-xl font-black tracking-tighter bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                            JOKOPUS
                        </h2>
                    </div>

                    <nav
                        class="hidden md:flex items-center bg-white/5 rounded-full px-2 py-1 border border-white/5 shadow-inner">
                        <a href="/"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('/') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Home
                        </a>

                        <a href="/buku"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('buku') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Book
                        </a>

                        <a href="/chat"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('chat*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Chat
                        </a>
                    </nav>

                    <div class="flex items-center gap-4">
                        @if (session()->has('user'))
                            @php
                                $userData = DB::table('users')->where('id', session('user.id'))->first();
                                $photo = $userData->profile_photo ?? null;
                                if ($photo && (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://'))) {
                                    $displayPhoto = $photo;
                                } elseif ($photo && file_exists(storage_path('app/public/avatars/' . $photo))) {
                                    $displayPhoto = asset('storage/avatars/' . $photo);
                                } else {
                                    $displayPhoto = "https://ui-avatars.com/api/?name=" . urlencode(session('user.name')) . "&background=137fec&color=fff";
                                }
                              @endphp

                            <div class="flex items-center gap-3 pl-4 border-l border-white/10">
                                <div class="hidden sm:block text-right">
                                    <p class="text-[10px] font-black text-primary uppercase tracking-widest">
                                        {{ session('user.role') == 1 ? 'Admin' : 'Member' }}
                                    </p>
                                    <p class="text-xs font-bold text-white truncate max-w-[100px]">
                                        {{ session('user.name') }}
                                    </p>
                                </div>
                                <div onclick="window.location.href='/dashboard'"
                                    class="h-10 w-10 rounded-xl border-2 border-white/10 bg-center bg-cover hover:border-primary hover:scale-105 transition-all cursor-pointer shadow-lg"
                                    style="background-image: url('{{ $displayPhoto }}');">
                                </div>
                            </div>
                        @else
                            <a href="/daftar"
                                class="h-10 px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-widest flex items-center justify-center hover:shadow-[0_0_20px_rgba(19,127,236,0.4)] hover:scale-105 active:scale-95 transition-all">
                                Sign In
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </header>

        <main class="flex-1 px-4 lg:px-40 py-8 max-w-[1200px] mx-auto w-full">
            <div class="flex pt-28 flex-col md:flex-row items-center justify-between gap-6 mb-12 px-2">
                <div class="flex flex-col gap-2">
                    <h1 class="text-4xl font-black tracking-tight text-white @[480px]:text-6xl">
                        Community <span class="text-primary">Members</span>
                    </h1>
                    <p class="text-base text-slate-400">
                        Menampilkan semua pembaca yang terdaftar di Jokopus.
                    </p>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="w-full md:w-auto">
                    <div class="relative group">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary transition-colors z-10 pointer-events-none"
                            style="font-family: 'Material Symbols Outlined';">
                            search
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari member..."
                            class="w-full md:w-80 bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-sm text-white focus:border-primary/50 focus:ring-1 focus:ring-primary/20 transition-all glass">
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 px-2">
                @foreach ($users as $u)
                    @php
                        $photo = $u->profile_photo ?? null;
                        if ($photo && (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://'))) {
                            $displayPhoto = $photo;
                        } elseif ($photo && file_exists(storage_path('app/public/avatars/' . $photo))) {
                            $displayPhoto = asset('storage/avatars/' . $photo);
                        } else {
                            $displayPhoto = "https://ui-avatars.com/api/?name=" . urlencode($u->name) . "&background=137fec&color=fff";
                        }
                    @endphp

                    <div onclick="window.location.href='/profile/{{ $u->id }}'"
                        class="glass-card p-6 rounded-[2rem] flex flex-col items-center text-center group hover:translate-y-[-8px] transition-all duration-500 cursor-pointer border border-white/5 hover:border-primary/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)]">

                        <div class="relative mb-4">
                            <div
                                class="size-20 rounded-2xl border-2 border-white/10 p-1 group-hover:border-primary transition-all duration-500 overflow-hidden">
                                <div class="w-full h-full rounded-xl bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
                                    style="background-image: url('{{ $displayPhoto }}')"></div>
                            </div>
                            @if($u->role == 1)
                                <div
                                    class="absolute -top-2 -right-2 bg-primary text-white text-[8px] font-black px-2 py-1 rounded-lg uppercase tracking-tighter shadow-lg">
                                    Admin
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col gap-1 w-full">
                            <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors truncate">
                                {{ $u->name }}
                            </h3>
                            <p class="text-[10px] text-slate-500 font-medium truncate">
                                Joined {{ $u->created_at ? $u->created_at->format('M Y') : 'Unknown' }}
                            </p>
                        </div>

                        <div class="mt-5 w-full pt-4 border-t border-white/5 flex items-center justify-between">
                            <div class="flex flex-col items-start">
                                <span class="text-[9px] text-slate-500 uppercase font-black tracking-widest">ID</span>
                                <span
                                    class="text-xs text-white/70 font-mono">#{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div
                                class="size-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($users->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <span class="material-symbols-outlined text-6xl text-white/10 mb-4">person_search</span>
                    <p class="text-white/40 font-bold uppercase tracking-widest text-xs">User tidak ditemukan.</p>
                </div>
            @endif

            <div class="flex flex-col items-center justify-center p-12 gap-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </main>

        <footer
            class="relative mt-20 border-t border-white/5 bg-background-dark/50 px-4 pb-12 pt-20 md:px-20 overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent">
            </div>
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 size-48 bg-primary/10 blur-[100px] rounded-full">
            </div>

            <div class="mx-auto max-w-[1200px]">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-8 bg-primary/20 rounded-lg flex items-center justify-center text-primary">
                                <svg viewBox="0 0 48 48" fill="currentColor" class="size-5">
                                    <path
                                        d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z" />
                                </svg>
                            </div>
                            <span class="text-xl font-black tracking-tighter text-white">JOKOPUS</span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                            Website peminjaman buku buat tugas akhir
                        </p>
                    </div>

                    <div>
                        <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-6">Navigation</h4>
                        <ul class="space-y-4">
                            <li><a href="/" class="text-slate-400 hover:text-primary text-sm transition-colors">Home</a>
                            </li>
                            <li><a href="/buku" class="text-slate-400 hover:text-primary text-sm transition-colors">List
                                    Buku</a>
                            </li>
                            <li><a href="/dashboard"
                                    class="text-slate-400 hover:text-primary text-sm transition-colors">Dashboard</a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-6">Connect</h4>
                        <div class="flex gap-4">
                            <a href="#"
                                class="size-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:border-primary hover:text-primary transition-all group">
                                <span class="material-symbols-outlined text-xl">language</span>
                            </a>
                            <a href="mailto:akunvgesport@gmail.com"
                                class="size-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:border-primary hover:text-primary transition-all"
                                title="Kirim Email">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-slate-500 font-medium">
                        © 2026 <span class="text-slate-300">Jokopus</span>.
                    </p>
                    <div class="flex gap-6">
                        <a href="#"
                            class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Privacy
                            Policy</a>
                        <a href="#"
                            class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Terms of
                            Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>