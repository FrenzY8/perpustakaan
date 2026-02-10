<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Denda - Jokopus</title>

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
                <a href="/dashboard/uang"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('dashboard/uang') ? 'bg-primary/10 text-primary font-bold' : 'text-[#92adc9] hover:bg-[#233648] hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('dashboard/uang') ? 'fill-1' : '' }}">monetization_on</span>
                    <span>Denda</span>
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
                <div class="mb-8">
                    <h1 class="text-3xl font-black tracking-tight text-white uppercase">Informasi Denda</h1>
                    <p class="text-slate-400 text-sm mt-1">Halo {{ $user->name }}, berikut adalah rincian keterlambatan
                        kamu.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="md:col-span-2 glass-card p-8 rounded-3xl relative overflow-hidden border border-primary/20">
                        <div class="absolute top-0 right-0 p-8 opacity-30">
                            <span class="material-symbols-outlined text-9xl">request_quote</span>
                        </div>

                        <p class="text-[11px] text-white text-md font-black uppercase tracking-[0.2em] text-primary mb-2">Total Tagihan
                            Kamu</p>
                        <h2 class="text-5xl font-black text-white mb-6">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </h2>

                        <div class="flex gap-4">
                            <div class="bg-white/5 rounded-2xl p-4 flex-1 border border-white/5">
                                <p class="text-[10px] uppercase text-slate-500 font-bold mb-1">Total Hari Telat</p>
                                <p class="text-xl font-bold text-white">{{ $totalHariTelat }} <span
                                        class="text-sm font-normal text-slate-400">Hari</span></p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-4 flex-1 border border-white/5">
                                <p class="text-[10px] uppercase text-slate-500 font-bold mb-1">Status</p>
                                @if($totalTagihan > 0)
                                    <p class="text-xl font-bold text-red-400">Belum Lunas</p>
                                @else
                                    <p class="text-xl font-bold text-emerald-400">Bersih</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div
                        class="glass-card p-6 rounded-3xl border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                        <h3 class="text-3xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">info</span>
                            Cara Pembayaran
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex gap-3">
                                <div
                                    class="size-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0">
                                    1</div>
                                <p class="text-md text-slate-400 leading-relaxed">Kunjungi meja pustakawan di gedung
                                    utama.</p>
                            </li>
                            <li class="flex gap-3">
                                <div
                                    class="size-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0">
                                    2</div>
                                <p class="text-md text-slate-400 leading-relaxed">Tunjukkan ID User atau Email kamu ke
                                    petugas.</p>
                            </li>
                            <li class="flex gap-3">
                                <div
                                    class="size-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0">
                                    3</div>
                                <p class="text-md text-slate-400 leading-relaxed">Bayar sesuai nominal denda dan minta
                                    petugas reset status.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <h3
                    class="text-sm font-black uppercase tracking-widest text-slate-500 mt-12 mb-6 flex items-center gap-2">
                    <span class="size-2 bg-red-500 rounded-full animate-pulse"></span>
                    Rincian Buku Terlambat
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($bukuTelat as $b)
                        <div class="glass-card p-4 rounded-2xl flex items-center gap-6 border border-white/5">
                            <div class="relative">
                                <img src="{{ $b->buku->gambar_sampul }}"
                                    class="w-16 h-20 object-cover rounded-xl shadow-2xl">
                                <div
                                    class="absolute -top-2 -right-2 bg-red-500 text-[10px] font-black px-2 py-1 rounded-lg text-white">
                                    {{ $b->hari_telat }} HARI
                                </div>
                            </div>

                            <div class="flex-1">
                                <h4 class="text-white font-bold">{{ $b->buku->judul }}</h4>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">
                                    Jatuh Tempo: {{ \Carbon\Carbon::parse($b->tanggal_jatuh_tempo)->format('d M Y') }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-[10px] text-slate-500 font-black uppercase mb-1">Denda</p>
                                <p class="text-lg font-black text-amber-500">Rp
                                    {{ number_format($p->total_denda_item ?? $b->total_denda_item, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card p-12 rounded-3xl text-center border-dashed border-white/10">
                            <p class="text-slate-500 text-sm italic">Tidak ada buku yang terlambat. Akun kamu aman!</p>
                        </div>
                    @endforelse
                </div>

                @if($totalTagihan > 0)
                    <div class="mt-8 p-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-500">warning</span>
                        <p class="text-1xl text-amber-200/80 leading-relaxed">
                            <strong>Perhatian:</strong> Keterlambatan dikenakan denda progresif. Akun kamu mungkin dibekukan
                            sementara sampai denda dilunasi.
                        </p>
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