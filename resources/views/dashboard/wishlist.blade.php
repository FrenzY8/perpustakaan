<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Wishlist - Jokopus</title>

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
                        <h2 class="text-3xl font-bold">Koleksi Favorit</h2>
                        <p class="text-[#92adc9] mt-1">Kamu punya {{ $wishlist->count() }} buku di wishlist.</p>
                    </div>
                </div>

                @if($wishlist->isEmpty())
                    <div class="glass-card rounded-3xl p-12 text-center flex flex-col items-center">
                        <span class="material-symbols-outlined text-64px text-primary/30 mb-4 scale-[2]">heart_broken</span>
                        <h3 class="text-xl font-bold mt-4">Belum ada buku favorit</h3>
                        <p class="text-[#92adc9] mb-6">Jelajahi perpustakaan dan temukan buku yang kamu suka.</p>
                        <a href="/buku"
                            class="bg-primary px-8 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-primary/30 transition-all">Cari
                            Buku</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($wishlist as $item)
                            <div
                                class="glass-card p-3 rounded-2xl group relative hover:-translate-y-2 transition-all duration-300">
                                <form action="/wishlist/{{ $item->buku->id }}" method="POST"
                                    class="absolute top-5 right-5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    <button type="submit"
                                        class="bg-red-500 size-8 rounded-lg flex items-center justify-center shadow-xl">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>

                                <div class="aspect-[3/4] rounded-xl overflow-hidden mb-4 shadow-lg">
                                    <img src="{{ $item->buku->gambar_sampul }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        alt="{{ $item->buku->judul }}">
                                </div>

                                <div class="px-2 pb-2">
                                    <h4 class="font-bold text-sm truncate">{{ $item->buku->judul }}</h4>
                                    <p class="text-[11px] text-[#92adc9] mt-1">{{ $item->buku->penulis->nama ?? 'Anonim' }}</p>

                                    <a href="/detail/{{ $item->buku->id }}"
                                        class="mt-4 block w-full text-center py-2 bg-white/5 hover:bg-primary rounded-lg text-xs font-bold transition-colors">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </main>

            <footer class="border-t border-white/5 bg-background-dark px-4 pb-8 pt-16 md:px-20">
                <div class="mx-auto max-w-[1200px]">
                    <p class="text-center text-xs text-[#92adc9]">
                        © 2026 Jokopus Management System. All rights reserved.
                    </p>
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