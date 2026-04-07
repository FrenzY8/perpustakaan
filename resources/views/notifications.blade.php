<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "1rem",
                        xl: "1.5rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>

    <style>
        .glass {
            background: rgba(16, 25, 34, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-white">
    <div class="relative flex min-h-screen flex-col overflow-x-hidden">

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

                        <a href="/dashboard"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('dashboard*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Dashboard
                        </a>

                        <a href="/chat"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('dashboard*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
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

        <main class="flex-1 pt-28 pb-12 px-4 relative">
            <div class="mx-auto max-w-[850px]">

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                    <div>
                        <h1 class="text-4xl font-black tracking text-white mb-2">Notifikasi</h1>
                        <p class="text-slate-400 text-sm">Kamu punya {{ $notifications->where('is_read', 0)->count() }}
                            pesan yang belum dibaca.</p>
                    </div>
                </div>

                <div class="grid gap-4">
                    @forelse($notifications as $notif)
                        <div class="{{ $notif->is_read == 0 ? 'bg-primary/5' : 'opacity-60' }}">
                            @if($notif->is_read == 0)
                                <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                            @endif
                        </div>
                        @php
                            $isUnread = !$notif->is_read;
                            $isWarning = str_contains(strtolower($notif->title), 'terlambat') || str_contains(strtolower($notif->title), 'tempo');
                        @endphp

                        <div onclick="window.location.href='{{ $notif->link }}'"
                            class="group relative glass rounded-2xl p-1 transition-all duration-300 hover:scale-[1.01] cursor-pointer {{ $isUnread ? 'opacity-100' : 'opacity-60 hover:opacity-100' }}">

                            <div
                                class="bg-background-dark/40 rounded-[14px] p-5 flex gap-5 {{ $isWarning && $isUnread ? 'border-l-4 border-l-amber-500' : ($isUnread ? 'border-l-4 border-l-primary' : '') }}">

                                <div class="relative shrink-0">
                                    <div
                                        class="size-14 rounded-2xl flex items-center justify-center border 
                                            {{ $isWarning ? 'bg-amber-500/10 text-amber-500 border-amber-500/20' : 'bg-primary/10 text-primary border-primary/20' }}">
                                        <span
                                            class="material-symbols-outlined text-3xl">{{ $notif->icon ?? 'notifications' }}</span>
                                    </div>
                                    @if($isUnread)
                                        <div
                                            class="absolute -top-1 -right-1 size-4 bg-primary rounded-full border-4 border-[#101922] animate-pulse">
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-4 mb-1">
                                        <h3
                                            class="font-bold {{ $isUnread ? 'text-white' : 'text-slate-400' }} truncate group-hover:text-primary transition-colors">
                                            {{ $notif->title }}
                                        </h3>
                                        <span
                                            class="shrink-0 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p
                                        class="text-sm {{ $isUnread ? 'text-slate-400' : 'text-slate-500' }} leading-relaxed line-clamp-2">
                                        {!! $notif->message !!}
                                    </p>
                                </div>

                                <div class="flex flex-col justify-center">
                                    <span
                                        class="material-symbols-outlined text-slate-600 group-hover:text-white transition-colors">chevron_right</span>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="glass rounded-3xl p-20 text-center">
                            <div class="size-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="material-symbols-outlined text-5xl text-slate-600">notifications_off</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">No notifications yet</h3>
                            <p class="text-slate-500 max-w-xs mx-auto">When you receive updates about your books, they will
                                appear here.</p>
                        </div>
                    @endforelse
                </div>

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
                            <li><a href="/chat"
                                    class="text-slate-400 hover:text-primary text-sm transition-colors">Chat</a>
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