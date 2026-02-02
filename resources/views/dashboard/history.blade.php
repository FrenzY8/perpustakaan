<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My History - Jokopus</title>

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

            <main class="p-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-white">History Pengembalian</h2>
                    <p class="text-[#92adc9] mt-1">Rekam jejak literasi kamu di Jokopus.</p>
                </div>

                <div class="glass-card rounded-2xl overflow-hidden border border-white/5">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 text-[#92adc9] text-xs uppercase tracking-wider">
                                    <th class="px-6 py-4 font-bold">Buku</th>
                                    <th class="px-6 py-4 font-bold text-center">Durasi Pinjam</th>
                                    <th class="px-6 py-4 font-bold">Tgl Kembali</th>
                                    <th class="px-6 py-4 font-bold">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($history as $item)
                                    @php
                                        $tglPinjam = \Carbon\Carbon::parse($item->tanggal_pinjam);
                                        $tglKembali = \Carbon\Carbon::parse($item->tanggal_kembali);
                                        $jatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo);

                                        $isTerlambat = $tglKembali->gt($jatuhTempo);
                                        $durasi = $tglPinjam->diffInDays($tglKembali);
                                    @endphp
                                    <tr class="hover:bg-white/[0.02] transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $item->buku->gambar_sampul }}"
                                                    class="w-10 h-14 rounded object-cover shadow-md grayscale group-hover:grayscale-0 transition-all">
                                                <div>
                                                    <p class="font-bold text-sm text-white">{{ $item->buku->judul }}</p>
                                                    <p class="text-[11px] text-[#92adc9]">
                                                        {{ $item->buku->penulis->nama ?? 'Anonim' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs text-[#92adc9] bg-white/5 px-2 py-1 rounded">
                                                {{ $durasi }} Hari
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-white">
                                            {{ $tglKembali->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($isTerlambat)
                                                <span
                                                    class="flex items-center gap-1 text-red-400 text-[10px] font-bold uppercase">
                                                    <span class="material-symbols-outlined text-xs">history_toggle_off</span>
                                                    Terlambat
                                                </span>
                                            @else
                                                <span
                                                    class="flex items-center gap-1 text-green-400 text-[10px] font-bold uppercase">
                                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                                    Tepat Waktu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="/detail/{{ $item->id_buku }}"
                                                class="p-2 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all inline-block shadow-sm">
                                                <span class="material-symbols-outlined text-sm">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <span class="material-symbols-outlined text-6xl">inventory_2</span>
                                                <p class="mt-4 font-medium">Belum ada history pengembalian.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

            <footer class="mt-auto border-t border-white/5 px-8 py-6 text-center">
                <p class="text-[#92adc9] text-xs">© 2026 Jokopus Library Management. All rights reserved.</p>
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