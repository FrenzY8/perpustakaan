<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

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
            <div class="flex items-center justify-between px-6 py-8">
                <div class="flex items-center gap-3">
                    <div class="size-8 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h2 onclick="window.location.href='/'" class="text-2xl font-bold tracking-tight text-white cursor-pointer">
                        Jokopus
                    </h2>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 mt-4">Main Menu</p>
                <a href="/admin/panel" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-bold transition-all">
                    <span class="material-symbols-outlined fill-1">admin_panel_settings</span>
                    <span>Admin Hub</span>
                </a>

                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 mt-6">Resources</p>
                <a href="#table-buku" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#92adc9] hover:bg-white/5 transition-all">
                    <span class="material-symbols-outlined">menu_book</span>
                    <span>Kelola Buku</span>
                </a>
                <a href="#table-user" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#92adc9] hover:bg-white/5 transition-all">
                    <span class="material-symbols-outlined">group</span>
                    <span>Daftar User</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5">
                <div class="flex items-center gap-3 px-2 py-3 bg-white/5 rounded-2xl">
                    <div class="size-10 rounded-full bg-primary flex items-center justify-center font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-slate-400">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <main class="p-8 space-y-12">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-white tracking-tighter uppercase">
                            Admin <span class="text-primary">Panel</span>
                        </h2>
                        <p class="text-[#92adc9] mt-1 italic text-sm">Welcome back, {{ $user->name }}!</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Users</p>
                            <p class="text-xl font-black text-primary">{{ count($users) }}</p>
                        </div>
                        <div class="glass-card px-6 py-3 rounded-2xl text-center min-w-[100px]">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Books</p>
                            <p class="text-xl font-black text-primary">{{ count($books) }}</p>
                        </div>
                    </div>
                </div>

                <section id="table-user" class="space-y-4">
                    <h3 class="text-xl font-bold flex items-center gap-3 px-2">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span> Manajemen Pengguna
                    </h3>
                    <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
                                        <th class="px-6 py-5">Nama & Email</th>
                                        <th class="px-6 py-5">Status Role</th>
                                        <th class="px-6 py-5 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($users as $u)
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-sm text-white">{{ $u->name }}</p>
                                            <p class="text-xs text-[#92adc9] mt-0.5">{{ $u->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black {{ $u->role == 1 ? 'bg-primary/20 text-primary border border-primary/20' : 'bg-slate-500/10 text-slate-400 border border-white/5' }}">
                                                {{ $u->role == 1 ? 'SUPER ADMIN' : 'MEMBER' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="text-[10px] font-black text-slate-500 hover:text-white uppercase underline underline-offset-4 decoration-primary transition-all">
                                                Update Role
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                                <section id="table-buku" class="space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-primary rounded-full"></span> Koleksi Buku
                        </h3>
                        <button class="px-5 py-2.5 bg-primary text-xs font-bold rounded-xl hover:scale-105 hover:shadow-lg hover:shadow-primary/20 transition-all">
                            + Add Book
                        </button>
                    </div>
                    
                    <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
                                        <th class="px-6 py-5">Informasi Buku</th>
                                        <th class="px-6 py-5">Kategori</th>
                                        <th class="px-6 py-5 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($books as $b)
                                    <tr class="hover:bg-white/[0.02] transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $b->gambar_sampul }}" class="w-12 h-16 rounded-lg shadow-2xl object-cover bg-[#233648]">
                                                <div>
                                                    <p class="font-bold text-sm text-white group-hover:text-primary transition-colors line-clamp-1">
                                                        {{ $b->judul }}
                                                    </p>
                                                    <p class="text-[11px] text-[#92adc9] mt-1">{{ $b->penulis->nama }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-[10px] font-black px-3 py-1 bg-white/5 rounded-lg text-slate-400 border border-white/10 uppercase italic">
                                                {{ $b->kategori->nama }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                <button class="p-2.5 bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white rounded-xl transition-all">
                                                    <span class="material-symbols-outlined text-base">edit</span>
                                                </button>
                                                <button class="p-2.5 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                                    <span class="material-symbols-outlined text-base">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

            </main>

            <footer class="mt-auto border-t border-white/5 px-8 py-8 text-center">
                <p class="text-[#92adc9] text-xs">© 2026 Jokopus Library Management. Built with Passion.</p>
            </footer>
        </div>
    </div>

    <script>
        console.log("Admin Dashboard Loaded");
    </script>
</body>

</html>