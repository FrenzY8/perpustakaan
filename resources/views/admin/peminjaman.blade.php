<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel Peminjaman - Jokopus</title>

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
            <nav class="flex-1 pt-2 px-4 space-y-1">
                <p class="px-4 text-white text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 mt-4">
                    Main Menu</p>
                <a href="/admin/panel"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('admin/panel') ? 'bg-primary/20 text-primary font-bold shadow-lg shadow-primary/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('admin/panel') ? 'fill-1 text-primary' : '' }}">
                        admin_panel_settings
                    </span>
                    <span>Admin Panel</span>
                </a>

                <div class="mt-2"></div>

                <a href="/admin/peminjaman"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('admin/peminjaman*') ? 'bg-primary/20 text-primary font-bold shadow-lg shadow-primary/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('admin/peminjaman*') ? 'fill-1 text-primary' : '' }}">
                        book
                    </span>
                    <span>Kelola Pinjaman</span>
                </a>

                <a href="/admin/chart"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('admin/chart') ? 'bg-primary/20 text-primary font-bold shadow-lg shadow-primary/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <span
                        class="material-symbols-outlined {{ request()->is('admin/charts') ? 'fill-1 text-primary' : '' }}">
                        monitoring
                    </span>
                    <span>Chart</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5">
                <div class="flex items-center gap-3 px-2 py-3 bg-white/5 rounded-2xl">
                    <a href="/dashboard"
                        class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-bold flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Back
                    </a>
                    <a href="/"
                        class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-bold flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-sm">home</span>
                        Home
                    </a>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <main class="p-8 space-y-12">

                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                    <div>
                        <h2 class="text-4xl font-black text-white tracking-tighter uppercase">
                            Admin <span class="text-primary">Panel</span>
                        </h2>
                        <p class="text-[#92adc9] mt-1 italic text-sm">Welcome back, {{ $user->name }}!</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 text-white font-bold uppercase tracking-wider">Users
                            </p>
                            <p class="text-xl font-black text-white">{{ count($peminjaman) }}</p>
                        </div>
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 font-bold uppercase text-white tracking-wider">Books
                            </p>
                            <p class="text-xl font-black text-white">{{ count($books) }}</p>
                        </div>
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 font-bold uppercase text-white tracking-wider">Dipinjam
                            </p>
                            <p class="text-xl font-black text-white">{{ $stats['dipinjam'] }}</p>
                        </div>
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 font-bold uppercase text-white tracking-wider">Tenggak
                            </p>
                            <p class="text-xl font-black text-white">{{ $stats['terlambat'] }}</p>
                        </div>
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 font-bold uppercase text-white tracking-wider">Kembali
                            </p>
                            <p class="text-xl font-black text-white">{{ $stats['kembali'] }}</p>
                        </div>
                    </div>
                </div>

                <section id="table-persetujuan" class="space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span> Menunggu Persetujuan
                            <span
                                class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black border border-emerald-500/20">
                                {{ $stats['menunggu'] }} PENDING
                            </span>
                        </h3>
                    </div>

                    <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
                                        <th class="px-6 py-5">Peminjam</th>
                                        <th class="px-6 py-5">Buku</th>
                                        <th class="px-6 py-5">Tgl Pengajuan</th>
                                        <th class="px-6 py-5 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($pendingLoans as $loan)
                                        <tr class="hover:bg-white/[0.02] transition-colors group">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-sm text-white">{{ $loan->nama_peminjam }}</p>
                                                <p class="text-[11px] text-[#92adc9] mt-0.5">{{ $loan->email_peminjam }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-sm text-primary line-clamp-1 italic">
                                                    "{{ $loan->judul_buku }}"</p>
                                                <p class="text-[10px] text-slate-500 mt-0.5">ISBN: {{ $loan->isbn_buku }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2 text-slate-300">
                                                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                                                    <span
                                                        class="text-xs font-medium">{{ \Carbon\Carbon::parse($loan->dibuat_pada)->format('d M Y') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <form action="/admin/peminjaman/approve/{{ $loan->id }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="flex items-center gap-2 px-3 py-2 bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white rounded-xl text-[10px] font-black transition-all border border-emerald-500/20 group/btn">
                                                                <span
                                                                    class="material-symbols-outlined text-sm">check_circle</span>
                                                                TERIMA
                                                            </button>
                                                        </form>

                                                        <form action="/admin/peminjaman/reject/{{ $loan->id }}"
                                                            method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                                                            @csrf
                                                            <button type="submit"
                                                                class="flex items-center gap-2 px-3 py-2 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-xl text-[10px] font-black transition-all border border-red-500/20 group/btn">
                                                                <span
                                                                    class="material-symbols-outlined text-sm">cancel</span>
                                                                TOLAK
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if(count($pendingLoans) == 0)
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center">
                                                <span
                                                    class="material-symbols-outlined text-slate-600 text-4xl mb-2">assignment_turned_in</span>
                                                <p class="text-slate-500 text-xs italic">Tidak ada antrian persetujuan saat
                                                    ini.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section id="table-denda" class="space-y-4">
                    <h3 class="text-xl font-bold flex items-center gap-3 px-2">
                        <span class="w-1.5 h-6 bg-red-500 rounded-full"></span> Detail Denda Member
                    </h3>

                    <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
                                        <th class="px-6 py-5">Nama Member</th>
                                        <th class="px-6 py-5">Total Denda</th>
                                        <th class="px-6 py-5">Status</th>
                                        <th class="px-6 py-5 text-center">Aksi Cepat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($dendaUser as $d)
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-sm text-white">{{ $d->nama_member }}</p>
                                                <p
                                                    class="text-[10px] font-medium tracking-wide text-[11px] text-[#92adc9] mt-1">
                                                    "{{ $d->judul_buku }}"</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-amber-500 font-black text-sm">
                                                    Rp {{ number_format($d->total_tagihan, 0, ',', '.') }}
                                                </p>
                                                <p class="text-[9px] text-slate-500">{{ $d->hari_telat }} Hari Telat</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-1 rounded bg-red-500/10 text-red-500 text-[9px] font-bold border border-red-500/20">
                                                    BELUM LUNAS
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <button
                                                        onclick="openPaymentModal({{ json_encode(['id' => $d->id, 'name' => $d->nama_member, 'buku' => $d->judul_buku, 'denda' => $d->total_tagihan]) }})"
                                                        class="px-3 text-white py-1.5 bg-primary/20 text-primary text-[10px] font-bold rounded-lg hover:bg-primary hover:text-white transition-all">
                                                        KELOLA DENDA
                                                    </button>

                                                    <form action="/admin/denda/reset/{{ $d->id }}" method="POST"
                                                        onsubmit="return confirm('Selesaikan peminjaman ini dan lunaskan?')">
                                                        @csrf
                                                        <button type="submit"
                                                            class="p-1.5 bg-emerald-500/10 font-bold text-emerald-500 rounded-lg hover:bg-emerald-500 hover:text-white transition-all">
                                                            <span
                                                                class="material-symbols-outlined text-sm">check_circle</span>
                                                            LUNAS KAN
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(count($dendaUser) == 0)
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center">
                                                <span
                                                    class="material-symbols-outlined text-slate-600 text-4xl mb-2">assignment_turned_in</span>
                                                <p class="text-slate-500 text-xs italic">Tidak ada user yang kena
                                                    denda.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section id="table-peminjaman" class="space-y-4">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 px-2">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span> Manajemen Pinjaman
                        </h3>

                        <form action="/admin/peminjaman" method="GET" class="relative w-full md:w-80">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama peminjam atau buku..."
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-primary focus:border-primary text-white placeholder-slate-500">
                            <button type="submit"
                                class="absolute right-3 top-2.5 text-slate-500 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">search</span>
                            </button>
                        </form>
                    </div>

                    @if(request('search'))
                        <p class="px-2 text-xs text-slate-400">
                            Hasil pencarian untuk: <span class="text-primary font-bold">"{{ request('search') }}"</span>
                            <a href="/admin/peminjaman" class="ml-2 text-red-400 hover:underline">Hapus Filter</a>
                        </p>
                    @endif

                    <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
                                        <th class="px-6 py-5">User & Buku</th>
                                        <th class="px-6 py-5">Status</th>
                                        <th class="px-6 py-5">Tgl Pinjam</th>
                                        <th class="px-6 py-5">Jatuh Tempo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($peminjaman as $p)
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-sm text-white">{{ $p->nama_user }}</p>
                                                <p class="text-[11px] text-primary italic">"{{ $p->judul_buku }}"</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusColor = [
                                                        'dipinjam' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                                        'dikembalikan' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                                        'terlambat' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                                        'menunggu' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                    ][$p->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                                @endphp
                                                <span
                                                    class="px-2 py-0.5 rounded-full text-[9px] font-black border {{ $statusColor }} uppercase">
                                                    {{ $p->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-300">
                                                {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 text-xs {{ $p->status == 'terlambat' ? 'text-red-400 font-bold' : 'text-slate-300' }}">
                                                {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center">
                                                <p class="text-slate-500 text-xs italic">Data peminjaman tidak ditemukan.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 px-2">
                        {{ $peminjaman->links() }}
                    </div>
                </section>
            </main>
            @php
                $type = 'success';
                $icon = 'check_circle';
                $color = 'primary';
                $title = 'Berhasil';
                $message = '';

                if (session('success')) {
                    $message = session('success');
                } elseif (session('error')) {
                    $type = 'error';
                    $icon = 'error';
                    $color = 'red-500';
                    $title = 'Error';
                    $message = session('error');
                } elseif (session('info')) {
                    $type = 'info';
                    $icon = 'info';
                    $color = 'amber-500';
                    $title = 'Informasi';
                    $message = session('info');
                }
            @endphp
            @if($message)
                <div id="toast-container" class="fixed bottom-8 right-8 z-[100]">
                    <div id="toast-box" class="transform translate-y-10 opacity-0 transition-all duration-500 ease-out">
                        <div
                            class="bg-slate-900 dark:bg-[#233648] border border-{{ $color }}/30 rounded-2xl px-5 py-4 shadow-2xl flex items-center gap-4 min-w-[300px]">
                            <div class="h-10 w-10 rounded-full bg-{{ $color }}/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-{{ $color }} text-2xl">{{ $icon }}</span>
                            </div>

                            <div class="flex-1">
                                <p class="text-white text-sm font-black uppercase tracking-wider">{{ $title }}</p>
                                <p class="text-[#92adc9] text-xs mt-0.5">{{ $message }}</p>
                            </div>

                            <button onclick="closeToast()" class="text-slate-500 hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                        </div>
                    </div>
                </div>
                <script>
                    const toastBox = document.getElementById('toast-box');

                    setTimeout(() => {
                        toastBox.classList.remove('translate-y-10', 'opacity-0');
                        toastBox.classList.add('translate-y-0', 'opacity-100');
                    }, 100);

                    const autoClose = setTimeout(closeToast, 4000);

                    function closeToast() {
                        toastBox.classList.add('translate-y-10', 'opacity-0');
                        setTimeout(() => {
                            document.getElementById('toast-container')?.remove();
                        }, 500);
                        clearTimeout(autoClose);
                    }
                </script>
            @endif
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
</body>

</html>