<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Jokopus</title>

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
            <div class="flex items-center justify-between px-6 py-8">
                <div class="flex items-center gap-3">
                    <div class="size-8 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h2 onclick="window.location.href='/'"
                        class="text-2xl font-bold tracking-tight text-white cursor-pointer">
                        Jokopus
                    </h2>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 mt-4">Main Menu</p>
                <a href="/admin/panel"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-bold transition-all">
                    <span class="material-symbols-outlined fill-1">admin_panel_settings</span>
                    <span>Admin Hub</span>
                </a>

                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 mt-6">Resources</p>
                <a href="#table-buku"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#92adc9] hover:bg-white/5 transition-all">
                    <span class="material-symbols-outlined">menu_book</span>
                    <span>Kelola Buku</span>
                </a>
                <a href="#table-user"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#92adc9] hover:bg-white/5 transition-all">
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
                        <h2 class="text-4xl font-black text-white tracking-tighter uppercase">
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
                                    <tr
                                        class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
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
                                                <span
                                                    class="px-3 py-1 rounded-full text-[9px] font-black {{ $u->role == 1 ? 'bg-primary/20 text-primary border border-primary/20' : 'bg-slate-500/10 text-slate-400 border border-white/5' }}">
                                                    {{ $u->role == 1 ? 'SUPER ADMIN' : 'MEMBER' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <button onclick="openEditUserModal({{ json_encode($u) }})"
                                                    class="p-2 hover:bg-yellow-500/20 text-yellow-500 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-sm">manage_accounts</span>
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
                        <button onclick="toggleModal('modal-add-book')"
                            class="px-5 py-2.5 bg-primary text-xs font-bold rounded-xl hover:scale-105 hover:shadow-lg hover:shadow-primary/20 transition-all">
                            + Add Book
                        </button>
                    </div>

                    <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-white/5 text-[#92adc9] text-[10px] uppercase tracking-widest font-black">
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
                                                    <img src="{{ $b->gambar_sampul }}"
                                                        class="w-12 h-16 rounded-lg shadow-2xl object-cover bg-[#233648]">
                                                    <div>
                                                        <a href="/detail/{{ $b->id }}"
                                                            class="font-bold text-sm text-white group-hover:text-primary transition-colors line-clamp-1">
                                                            {{ $b->judul }}
                                                        </a>

                                                        <p class="text-[11px] text-[#92adc9] mt-1">
                                                            {{ $b->penulis?->nama ?? 'Anon' }}
                                                        </p>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="text-[10px] font-black px-3 py-1 bg-white/5 rounded-lg text-slate-400 border border-white/10 uppercase italic">
                                                    {{ $b->kategori?->nama ?? 'Umum' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center gap-2">
                                                    <button onclick="openEditModal({{ json_encode($b) }})"
                                                        class="p-2 hover:bg-primary/20 text-primary rounded-lg transition-colors">
                                                        <span class="material-symbols-outlined text-base">edit</span>
                                                    </button>

                                                    <button
                                                        onclick="if(confirm('Yakin ingin menghapus buku ini?')) { document.getElementById('delete-form-{{ $b->id }}').submit(); }"
                                                        class="p-2.5 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                                        <span class="material-symbols-outlined text-base">delete</span>
                                                    </button>

                                                    <form id="delete-form-{{ $b->id }}"
                                                        action="/admin/books/delete/{{ $b->id }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
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

            <div id="modal-edit-book" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm">
                <div class="min-h-screen flex items-center justify-center p-4 py-20">
                    <div class="glass-card w-full max-w-2xl rounded-3xl p-8 shadow-2xl border border-white/10 relative">
                        <h3 class="text-4xl font-bold italic mb-6">EDIT <span class="text-primary">BUKU</span></h3>

                        <form action="/admin/books/update" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit-id">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Judul
                                        Buku</label>
                                    <input type="text" name="judul" id="edit-judul" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-primary outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Penulis</label>
                                    <select name="id_penulis" id="edit-penulis" required
                                        class="w-full bg-[#1a2530] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-primary outline-none">
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Kategori</label>
                                    <select name="id_kategori" id="edit-kategori" required
                                        class="w-full bg-[#1a2530] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-primary outline-none">
                                        @foreach($categories as $t)
                                            <option value="{{ $t->id }}">{{ strtoupper($t->nama) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">ISBN</label>
                                    <input type="text" name="isbn" id="edit-isbn" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-primary outline-none">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Link
                                    Gambar Sampul</label>
                                <input type="text" id="edit-sampul" name="gambar_sampul"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all"
                                    placeholder="https://image-url.com/book.jpg">
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" onclick="toggleModal('modal-edit-book')"
                                    class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-white transition-colors">BATAL</button>
                                <button type="submit"
                                    class="px-8 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 transition-all">SIMPAN
                                    PERUBAHAN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="modal-add-book" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="glass-card w-full max-w-2xl rounded-3xl p-8 shadow-2xl border border-white/10 relative">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-4xl font-bold italic tracking-tight">TAMBAH <span class="text-primary">BUKU
                                    BARU</span></h3>
                            <button onclick="toggleModal('modal-add-book')"
                                class="text-slate-400 hover:text-white transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <form action="/admin/books/store" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Judul
                                        Buku</label>
                                    <input type="text" name="judul" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all"
                                        placeholder="Masukkan judul...">
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Penulis</label>
                                    <select name="id_penulis"
                                        class="w-full bg-[#1a2530] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 appearance-none transition-all">
                                        <option value="">Pilih Penulis</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Kategori</label>
                                    <select name="id_kategori"
                                        class="w-full bg-[#1a2530] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">ISBN</label>
                                    <input type="text" name="isbn"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all"
                                        placeholder="Contoh: 978-602...">
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">JUMLAH
                                        HALAMAN</label>
                                    <input type="text" name="halaman"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all"
                                        placeholder="Contoh: 978-602...">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Ringkasan</label>
                                <textarea name="ringkasan" rows="3"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all"
                                    placeholder="Ceritakan sedikit tentang buku ini..."></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Link
                                    Gambar Sampul</label>
                                <input type="text" name="gambar_sampul"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 transition-all"
                                    placeholder="https://image-url.com/book.jpg">
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" onclick="toggleModal('modal-add-book')"
                                    class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-white transition-colors">BATAL</button>
                                <button type="submit"
                                    class="px-8 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 transition-all">SIMPAN
                                    BUKU</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="modal-edit-user" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm">
                <div class="min-h-screen flex items-center justify-center p-4">
                    <div class="glass-card w-full max-w-md rounded-3xl p-8 shadow-2xl border border-white/10 relative">
                        <h3 class="text-xl font-bold italic mb-6">UPDATE <span class="text-primary">USER ROLE</span>
                        </h3>

                        <form action="/admin/users/update-role" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit-user-id">

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Nama
                                        User</label>
                                    <input type="text" id="edit-user-nama" disabled
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-400 outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Pilih
                                        Role</label>
                                    <select name="role" id="edit-user-role" required
                                        class="w-full bg-[#1a2530] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-primary outline-none">
                                        <option value="1">ADMIN</option>
                                        <option value="0">USER</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" onclick="toggleModal('modal-edit-user')"
                                    class="text-sm font-bold text-slate-400 px-4">BATAL</button>
                                <button type="submit"
                                    class="px-8 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-xl transition-all">UPDATE
                                    ROLE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        window.onclick = function (event) {
            const modal = document.getElementById('modal-add-book');
            if (event.target == modal) {
                toggleModal('modal-add-book');
            }
        }

        function openEditModal(book) {
            document.getElementById('edit-id').value = book.id;
            document.getElementById('edit-judul').value = book.judul;
            document.getElementById('edit-penulis').value = book.id_penulis;
            document.getElementById('edit-kategori').value = book.id_kategori;
            document.getElementById('edit-isbn').value = book.isbn;
            document.getElementById('edit-sampul').value = book.gambar_sampul;
            toggleModal('modal-edit-book');
        }

        function openEditUserModal(user) {
            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-user-nama').value = user.name; // atau user.nama sesuai database
            document.getElementById('edit-user-role').value = user.role;

            toggleModal('modal-edit-user');
        }
    </script>
</body>

</html>