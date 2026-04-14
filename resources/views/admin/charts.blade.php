<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Jokopus</title>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

        .apexcharts-menu {
            color: #000000 !important;
            background: #ffffff !important;
            border: 1px solid #233648 !important;
        }

        .apexcharts-menu-item:hover {
            background: #f3f4f6 !important;
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
                        <h2 class="text-6xl font-black text-white tracking-tighter uppercase">
                            Admin <span class="text-primary">Chart</span>
                        </h2>
                        <p class="text-[#92adc9] mt-1 italic text-xl">Welcome back, {{ $user->name }}!</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 text-white font-bold uppercase tracking-wider">Users
                            </p>
                            <p class="text-xl font-black text-white">{{ count($users) }}</p>
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

                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div
                        class="lg:col-span-2 glass-card rounded-3xl p-6 border border-white/5 relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-4xl font-bold tracking-tight text-white">Chart Perpustakaan</h3>
                                </div>
                                <p class="text-xl text-slate-400">Tren peminjaman vs pengembalian</p>
                            </div>
                        </div>

                        <div id="trend-chart" class="w-full"></div>
                    </div>

                    <div class="glass-card rounded-3xl p-6 border border-white/5 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold mb-1">Popolaritas Buku</h3>
                            <p class="text-xl text-slate-400 mb-6">Kategori paling banyak diminati</p>

                            <div id="category-chart" class="min-h-[250px]"></div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-white/5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></span>
                                    Sirkulasi Aktif
                                </span>
                                <span class="font-bold text-blue-400">{{ $stats['dipinjam'] ?? 0 }} Buku</span>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card rounded-3xl p-6 border border-white/5">
                        <h3 class="text-2xl font-bold mb-1">Kepuasan User</h3>
                        <p class="text-xl text-slate-400 mb-6">Distribusi Rating (1-5 Bintang)</p>
                        <div id="rating-radar-chart"></div>
                    </div>
                </section>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const trendOptions = {
            series: [{
                name: 'Peminjaman',
                data: {!! $trendPinjam !!}
            }, {
                name: 'Pengembalian',
                data: {!! $trendKembali !!}
            }],
            chart: {
                height: 430,
                type: 'area',
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true
                },
                toolbar: {
                    show: true,
                    autoSelected: 'pan',
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: false
                    }
                },
                foreColor: '#94a3b8',
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#137fec', '#10b981'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: { opacityFrom: 0.4, opacityTo: 0 }
            },
            grid: { borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 4 },
            xaxis: {
                categories: {!! $trendLabels !!}
            },
            tooltip: { theme: 'dark' }
        };

        new ApexCharts(document.querySelector("#trend-chart"), trendOptions).render();

        const catOptions = {
            series: {!! $catSeries !!},
            chart: {
                height: 380,
                type: 'donut',
            },
            labels: {!! $catLabels !!},
            colors: ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
            stroke: { show: true },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'TOTAL BUKU',
                                color: '#ffffff',
                                formatter: function (w) {
                                    return {{ $stats['total_books'] }}
                                }
                            },
                            value: {
                                show: true,
                                color: '#ffffff',
                                fontSize: '20px',
                            }
                        }
                    }
                }
            },
            legend: { position: 'bottom', horizontalAlign: 'center', labels: { colors: '#94a3b8' } },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark' }
        };

        new ApexCharts(document.querySelector("#category-chart"), catOptions).render();

        const ratingOptions = {
            series: [{
                name: 'Jumlah Rating',
                data: {!! $ratingSeries !!} // Data: [10, 20, 45, 80, 100]
            }],
            chart: {
                height: 350,
                type: 'radar',
                toolbar: { show: false },
                foreColor: '#94a3b8',
            },
            colors: ['#137fec'],
            xaxis: {
                categories: ['1 ⭐', '2 ⭐', '3 ⭐', '4 ⭐', '5 ⭐']
            },
            plotOptions: {
                radar: {
                    polygons: {
                        strokeColors: 'rgba(255,255,255,0.05)',
                        connectorColors: 'rgba(255,255,255,0.05)',
                        fill: { colors: ['transparent'] }
                    }
                }
            },
            markers: { size: 4, colors: ['#137fec'] },
            tooltip: { theme: 'dark' }
        };

        new ApexCharts(document.querySelector("#rating-radar-chart"), ratingOptions).render();
    </script>

</html>