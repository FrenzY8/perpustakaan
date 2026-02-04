<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>List Buku - Jokopus</title>
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

                        <a href="/dashboard"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('dashboard*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Dashboard
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
            <div class="flex pt-28 flex-col md:flex-row md:items-center justify-between gap-6 mb-12 px-2">
                <div>
                    <h1 class="text-white text-5xl font-bold tracking-tight mb-2">Temukan Buku</h1>
                    <p class="text-slate-400 text-md">Temukan koleksi bacaan terbaik kami yang dikurasi khusus untukmu.
                    </p>
                </div>

                <form action="/buku" method="GET" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">

                    <div class="relative group">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary transition-colors">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau penulis..."
                            class="w-full sm:w-64 bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-sm text-white focus:border-primary focus:ring-0 transition-all outline-none">
                        <button id="clearSearch"
                            class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>

                    <div class="relative">
                        <select name="sort" onchange="this.form.submit()"
                            class="appearance-none h-10 pl-4 pr-10 bg-white/5 border border-white/10 rounded-xl text-[11px] font-bold uppercase tracking-wider focus:border-primary/50 focus:ring-0 text-slate-400">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru (Input)
                            </option>

                            {{-- Sort by Year --}}
                            <option value="year_new" {{ request('sort') == 'year_new' ? 'selected' : '' }}>Tahun: Terbaru
                            </option>
                            <option value="year_old" {{ request('sort') == 'year_old' ? 'selected' : '' }}>Tahun: Terlama
                            </option>

                            {{-- Sort by Author --}}
                            <option value="author_asc" {{ request('sort') == 'author_asc' ? 'selected' : '' }}>Penulis:
                                A-Z</option>

                            {{-- Sort by Others --}}
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul: A-Z
                            </option>
                            <option value="pages" {{ request('sort') == 'pages' ? 'selected' : '' }}>Halaman: Terbanyak
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="hidden"></button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 px-2">
                @foreach ($books as $item)
                    <div onclick="window.location.href='/detail/{{ $item->id }}'"
                        class="flex flex-col gap-6 glass-card p-6 rounded-[2.5rem] hover:translate-y-[-12px] transition-all duration-500 group cursor-pointer border border-white/5 hover:border-primary/30 hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)]">

                        <div class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-[2rem] shadow-2xl relative overflow-hidden group-hover:shadow-primary/20 transition-all duration-500"
                            style='background-image: url("{{ $item->gambar_sampul ?? asset('images/default-cover.jpg') }} ");'>

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center">
                                <div
                                    class="bg-white text-black px-6 py-3 rounded-full font-black text-sm uppercase tracking-widest translate-y-4 group-hover:translate-y-0 transition-transform duration-500 flex items-center gap-2 shadow-xl">
                                    Explore
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </div>
                            </div>

                            <div class="absolute top-4 left-4">
                                <span
                                    class="bg-black/40 backdrop-blur-xl text-[10px] font-bold px-4 py-1.5 rounded-full text-white border border-white/10 uppercase tracking-tighter">
                                    {{ $item->format ?? 'E-Book' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col flex-1 px-2 pb-2">
                            <p class="text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                                {{ $item->kategori->nama ?? 'General' }}
                            </p>

                            <h3 class="text-white text-2xl font-black leading-[1.1] line-clamp-2 group-hover:text-primary transition-colors tracking-tight"
                                title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </h3>

                            <p class="text-slate-400 text-base font-semibold mt-3 flex items-center gap-2">
                                <span class="w-6 h-[1px] bg-slate-600"></span>
                                {{ $item->penulis->nama ?? 'Unknown Author' }}
                            </p>

                            <div class="flex items-center justify-between mt-6 pt-5 border-t border-white/5">
                                <div class="flex items-center gap-2 text-[#92adc9]">
                                    <span class="material-symbols-outlined text-lg">auto_stories</span>
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ $item->jumlah_halaman }}
                                        Halaman</span>
                                </div>

                                <span
                                    class="material-symbols-outlined text-slate-600 group-hover:text-primary transition-colors">
                                    bookmark_add
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($books->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <span class="material-symbols-outlined text-6xl text-white/10 mb-4">search_off</span>
                    <p class="text-white/40">Belum ada buku yang tersedia.</p>
                </div>
            @endif

            <div class="flex items-center justify-center p-12">
                <nav class="flex items-center gap-2">
                    <button
                        class="flex h-10 w-10 items-center justify-center text-white hover:bg-white/10 rounded-lg transition-colors cursor-not-allowed opacity-50">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <span
                        class="text-sm font-bold flex h-10 w-10 items-center justify-center text-white rounded-lg bg-primary">1</span>
                    <button
                        class="flex h-10 w-10 items-center justify-center text-white hover:bg-white/10 rounded-lg transition-colors cursor-not-allowed opacity-50">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </nav>
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