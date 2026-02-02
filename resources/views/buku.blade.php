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
        <header class="fixed top-0 z-50 w-full glass border-b border-white/10 px-4 py-3 md:px-20">
            <div class="mx-auto flex max-w-[1200px] items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-8 text-primary">
                        <svg viewBox="0 0 48 48" fill="currentColor">
                            <path
                                d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z" />
                        </svg>
                    </div>
                    <h2 onclick="window.location.href='/'" class="text-xl font-bold tracking-tight">Jokopus</h2>
                </div>

                <nav class="hidden items-center gap-8 md:flex">
                    <a href="/" class="text-sm font-medium hover:text-primary">Home</a>
                    <a href="/buku" class="text-sm font-medium hover:text-primary">Book</a>
                    <a href="/dashboard" class="text-sm font-medium hover:text-primary">Dashboard</a>
                </nav>

                @if (session()->has('user'))
                    <!-- Profile Button -->
                    <div class="flex-1 max-w-md mx-4 hidden sm:block">
                    </div>
                    <div class="flex items-center gap-3">

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
                        <div onclick="window.location.href='/dashboard'"
                            class="h-10 w-10 rounded-full border-2 border-primary/20 bg-center bg-cover"
                            data-alt="User profile avatar portrait" style="background-image: url('{{ $displayPhoto }}');">
                        </div>

                    </div>
                @else
                    <!-- Sign In Button -->
                    <a href="/daftar"
                        class="hidden h-10 min-w-[100px] items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition hover:scale-105 active:scale-95 sm:flex">
                        Sign In
                    </a>
                @endif

            </div>
        </header>

        <main class="flex-1 px-4 lg:px-40 py-8 max-w-[1200px] mx-auto w-full">
            <div class="flex pt-20 flex-col md:flex-row md:items-end justify-between mb-8 px-2">
                <div>
                    <h1 class="text-white text-3xl font-bold tracking-tight mb-2">Browse Books</h1>
                    <p class="text-slate-400 text-sm">Temukan koleksi bacaan terbaik kami yang dikurasi khusus untukmu.
                    </p>
                </div>
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

        <footer class="border-t border-white/5 bg-background-dark px-4 pb-8 pt-16 md:px-20">
            <div class="mx-auto max-w-[1200px]">
                <p class="text-center text-xs text-[#92adc9]">
                    © 2026 Jokopus Management System. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>

</html>