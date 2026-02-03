<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pinjaman - Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
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
                    fontFamily: { display: ["Inter"] }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(25, 38, 51, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        body {
            scrollbar-width: thin;
            scrollbar-color: #233648 #101922;
        }
    </style>
</head>

<body class="bg-background-dark font-display text-white min-h-screen">
    <div class="flex min-h-screen relative">
        <aside class="w-72 bg-background-dark border-r border-[#233648] hidden lg:flex flex-col sticky top-0 h-screen">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex pt-3 items-center gap-3">
                    <div class="size-6 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>

                    <h2 onclick="window.location.href='/'" class="text-3xl font-bold tracking-light text-white">
                        Jokopus
                    </h2>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 mt-4 space-y-1">
                <a href="/dashboard"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-[#92adc9] hover:bg-[#233648] hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('dashboard') ? 'fill-1' : '' }}">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="/dashboard/wishlist"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('dashboard/wishlist') ? 'bg-primary/10 text-primary font-bold' : 'text-[#92adc9] hover:bg-[#233648] hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('dashboard/wishlist') ? 'fill-1' : '' }}">bookmark</span>
                    <span>Wishlist</span>
                </a>
                <a href="/dashboard/pinjaman"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('dashboard/pinjaman') ? 'bg-primary/10 text-primary font-bold' : 'text-[#92adc9] hover:bg-[#233648] hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('dashboard/pinjaman') ? 'fill-1' : '' }}">payments</span>
                    <span>Pinjaman</span>
                </a>
                <a href="/dashboard/history"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('dashboard/history') ? 'bg-primary/10 text-primary font-bold' : 'text-[#92adc9] hover:bg-[#233648] hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('dashboard/history') ? 'fill-1' : '' }}">history</span>
                    <span>History</span>
                </a>

                <!-- Settings -->
                <div class="pt-4 mt-4 border-t border-[#233648]">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
                        href="/profile">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="font-medium">Settings</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
                        href="/admin/panel">
                        <span class="material-symbols-outlined">shield</span>
                        <span class="font-medium">Admin Panel</span>
                    </a>
                </div>
            </nav>

            <!-- Browse Library Button -->
            <div class="p-4">
                <a href="/"
                    class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-bold flex items-center justify-center gap-2 transition-all">
                    <span class="material-symbols-outlined text-sm">search</span>
                    Browse Library
                </a>
            </div>

            <div class="p-4">
                <a href="/"
                    class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-bold flex items-center justify-center gap-2 transition-all">
                    <span class="material-symbols-outlined text-sm">home</span>
                    Back to Landing
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">

            <main class="p-8 flex-1">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold">Pinjaman Aktif</h2>
                        <p class="text-[#92adc9] mt-1">Kamu sedang meminjam {{ $pinjaman->count() }} buku.</p>
                    </div>
                </div>

                @if($pinjaman->isEmpty())
                    <div class="glass-card rounded-3xl p-12 text-center flex flex-col items-center">
                        <span class="material-symbols-outlined text-64px text-primary/30 mb-4 scale-[2]">book_5</span>
                        <h3 class="text-xl font-bold mt-4">Belum ada pinjaman</h3>
                        <p class="text-[#92adc9] mb-6">Kamu tidak sedang meminjam buku apapun saat ini.</p>
                        <a href="/" class="bg-primary px-8 py-3 rounded-xl font-bold hover:shadow-lg transition-all">Cari
                            Buku</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($pinjaman as $item)
                            @php
                                $due = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo);
                                $diff = now()->diffInDays($due, false); // false biar bisa minus kalau telat
                                $isUrgent = $diff <= 1; // Merah kalau 1 hari lagi atau sudah lewat
                            @endphp

                            <div
                                class="glass-card p-4 rounded-2xl group relative transition-all duration-300 {{ $isUrgent ? 'border-red-500/50 shadow-[0_0_20px_rgba(239,68,68,0.1)]' : '' }}">
                                <div class="absolute top-6 right-6 z-10">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $isUrgent ? 'bg-red-500 text-white animate-pulse' : 'bg-primary/20 text-primary border border-primary/30' }}">
                                        {{ $isUrgent ? 'Urgent / Telat' : 'Dipinjam' }}
                                    </span>
                                </div>

                                <div class="aspect-[3/4] rounded-xl overflow-hidden mb-4 shadow-lg">
                                    <img src="{{ $item->buku->gambar_sampul }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ $item->buku->judul }}">
                                </div>

                                <div class="px-1">
                                    <h4 class="font-bold text-sm truncate">{{ $item->buku->judul }}</h4>
                                    <p class="text-[11px] text-[#92adc9] mb-4">{{ $item->buku->penulis->nama ?? 'Anonim' }}</p>

                                    <div class="space-y-2 bg-black/20 p-3 rounded-xl border border-white/5">
                                        <div class="flex justify-between text-[10px]">
                                            <span class="text-[#92adc9]">Sisa Waktu:</span>
                                            <span class="{{ $isUrgent ? 'text-red-400 font-bold' : 'text-primary font-bold' }}">
                                                @if($diff < 0)
                                                    Telat {{ abs($diff) }} Hari
                                                @elseif($diff == 0)
                                                    Hari Ini!
                                                @else
                                                    {{ $diff }} Hari Lagi
                                                @endif
                                            </span>
                                        </div>
                                        <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                            @php
                                                $start = \Carbon\Carbon::parse($item->tanggal_pinjam);
                                                $total = $start->diffInDays($due) ?: 1;
                                                $elapsed = $start->diffInDays(now());
                                                $percent = min(100, ($elapsed / $total) * 100);
                                            @endphp
                                            <div class="h-full rounded-full {{ $isUrgent ? 'bg-red-500' : 'bg-primary' }}"
                                                style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>

                                    <form action="/dashboard/kembalikan/{{ $item->id }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-2.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 {{ $isUrgent ? 'bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/20' : 'bg-white/5 hover:bg-white/10 text-white border border-white/10' }}">
                                            <span class="material-symbols-outlined text-sm">keyboard_return</span>
                                            Kembalikan Buku
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        @if(session('success'))
                            <div id="toast">
                                <div
                                    class="fixed bottom-8 right-8 z-[100] transform translate-y-0 opacity-100 transition-all duration-300">
                                    <div
                                        class="bg-slate-900 dark:bg-[#233648] border border-primary/30 rounded-xl px-5 py-4 shadow-2xl flex items-center gap-4">
                                        <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm font-bold">Buku dikembalikan!</p>
                                            <p class="text-[#92adc9] text-xs">Bukunya udah dikembaliin ya...</p>
                                        </div>
                                        <button class="ml-4 text-slate-400 hover:text-white">
                                            <span class="material-symbols-outlined text-lg">close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <script>setTimeout(() => document.getElementById('toast')?.remove(), 2500);</script>
                        @endif
                    </div>
                @endif
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
                                <div
                                    class="size-8 bg-primary/20 rounded-lg flex items-center justify-center text-primary">
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
                                <li><a href="/"
                                        class="text-slate-400 hover:text-primary text-sm transition-colors">Home</a>
                                </li>
                                <li><a href="/buku"
                                        class="text-slate-400 hover:text-primary text-sm transition-colors">List
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

                    <div
                        class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-xs text-slate-500 font-medium">
                            © 2026 <span class="text-slate-300">Jokopus</span>.
                        </p>
                        <div class="flex gap-6">
                            <a href="#"
                                class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Privacy
                                Policy</a>
                            <a href="#"
                                class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Terms
                                of
                                Service</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        const btn = document.getElementById('profileBtn');
        const dropdown = document.getElementById('profileDropdown');

        btn.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', e => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>