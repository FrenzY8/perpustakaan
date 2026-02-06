<!DOCTYPE html>
<html class="dark" lang="en">

<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>{{ session('user.name') }} - Jokopus</title>

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
               fontFamily: {
                  display: ["Inter"]
               }
            }
         }
      }
   </script>

   <style>
      .glass-card {
         background: rgba(35, 54, 72, 0.4);
         backdrop-filter: blur(12px);
         border: 1px solid rgba(255, 255, 255, 0.05);
      }

      body {
         scrollbar-width: thin;
         scrollbar-color: #233648 #101922;
      }
   </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-white min-h-screen">
   <div class="flex min-h-screen">
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
               <span class="material-symbols-outlined {{ request()->is('dashboard') ? 'fill-1' : '' }}">dashboard</span>
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
               @if(session('user.role') == 1)
                  <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
                     href="{{ url('/admin/panel') }}">
                     <span class="material-symbols-outlined">shield</span>
                     <span class="font-medium">Admin Panel</span>
                  </a>
               @endif

            </div>
         </nav>

         <!-- Browse Library Button -->
         <div class="p-4">
            <a href="/buku"
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

      <main class="flex-1 overflow-y-auto">
         <main class="flex-1 overflow-y-auto">
            <!-- Top Navigation Bar -->
            <header
               class="sticky top-0 z-10 glass-card border-b border-[#233648] px-8 py-4 flex items-center justify-between">
               <div class="relative w-96">
               </div>
               <div class="flex items-center gap-4">
                  <div class="h-8 w-[1px] bg-[#233648]"></div>
                  <div class="relative">

                     <button id="profileBtn"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#233648] transition">
                        <div class="text-right hidden sm:block">
                           <p class="text-sm font-semibold">{{ session('user.name') }}</p>
                        </div>
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
                        <div class="h-10 w-10 rounded-full bg-cover bg-center border-2 border-primary/30"
                           style="background-image:url({{ $displayPhoto }})">
                        </div>
                        <span class="material-symbols-outlined text-[#92adc9]">expand_more</span>
                     </button>
                     <div id="profileDropdown"
                        class="hidden absolute right-0 mt-3 w-52 rounded-xl bg-[#192633] shadow-xl border border-white/10 z-50">
                        <a href="/" class="flex items-center gap-3 px-4 py-3 hover:bg-[#233648] transition">
                           <span class="material-symbols-outlined text-sm">home</span>Home
                        </a>
                        <a href="/profile" class="flex items-center gap-3 px-4 py-3 hover:bg-[#233648] transition">
                           <span class="material-symbols-outlined text-sm">settings</span>Settings
                        </a>
                        <div class="border-t border-white/10"></div>
                        <a href="/logout"
                           class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 transition">
                           <span class="material-symbols-outlined text-sm">logout</span>Logout
                        </a>
                     </div>
                  </div>
               </div>
            </header>
            <div class="p-8 max-w-6xl mx-auto space-y-8">
               <!-- Profile Hero Card -->
               <div class="glass-card rounded-xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                  <div onclick="window.location.href='/profile'" class="flex flex-col md:flex-row items-center gap-6">
                     <div class="relative">
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
                        <div
                           class="h-32 w-32 rounded-full bg-center bg-cover ring-4 ring-primary/20 ring-offset-4 ring-offset-background-dark"
                           id="photo-preview" style="background-image: url('{{ $displayPhoto }}');">
                        </div>
                     </div>
                     <div class="text-center md:text-left">
                        <h1 class="text-3xl font-bold">{{ session('user.name') }}</h1>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                           <div
                              class="flex items-center gap-2 text-sm bg-green-500/10 text-green-400 px-3 py-1 rounded-full">
                              <span class="material-symbols-outlined text-xs">shield</span>
                              {{ session('user.role') == 1 ? 'Admin' : 'Member' }}
                           </div>
                        </div>
                     </div>
                  </div>
                  <button onclick="window.location.href='/profile'"
                     class="px-6 py-2.5 bg-[#233648] hover:bg-[#324d67] rounded-lg font-semibold transition-colors flex items-center gap-2">
                     <span class="material-symbols-outlined text-sm">edit</span>
                     Edit Profile
                  </button>
               </div>
               <!-- Stats Overview -->
               <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="glass-card p-6 rounded-xl border-l-4 border-amber-500">
                     <div class="flex items-center justify-between mb-2">
                        <p class="text-[#92adc9] font-medium">Buku Dipinjam</p>
                        <span class="material-symbols-outlined text-amber-500">menu_book</span>
                     </div>
                     <p class="text-3xl font-bold text-white">{{ $totalPinjam }}</p>
                     <p class="text-xs text-[#92adc9] mt-2 italic">Jangan lupa dikembalikan ya!</p>
                  </div>

                  <div class="glass-card p-6 rounded-xl border-l-4 border-red-500">
                     <div class="flex items-center justify-between mb-2">
                        <p class="text-[#92adc9] font-medium">Favorit Saya</p>
                        <span class="material-symbols-outlined text-red-500 text-fill-1">favorite</span>
                     </div>
                     <p class="text-3xl font-bold text-white">{{ $totalFavorit }}</p>
                     <p class="text-xs text-[#92adc9] mt-2 italic">Buku yang ingin kamu baca nanti.</p>
                  </div>

                  <div
                     class="glass-card p-6 rounded-xl border-l-4 border-purple-500 transition-all hover:shadow-purple-500/10">
                     <div class="flex items-center justify-between mb-2">
                        <p class="text-[#92adc9] font-medium">Genre Favorit</p>
                        <span class="material-symbols-outlined text-purple-500">category</span>
                     </div>
                     <p class="text-2xl font-bold text-white truncate">{{ $favGenre->nama }}</p>
                     <p class="text-xs text-[#92adc9] mt-2 italic">
                        {{ $favGenre->total > 0 ? $favGenre->total . ' buku telah dipinjam' : 'Ayo mulai membaca!' }}
                     </p>
                  </div>
               </div>
               <!-- Tables Row -->
               <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                  <div class="space-y-4">
                     <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-bold">Currently Borrowed</h2>
                        <a class="text-primary text-4xl text-sm font-medium hover:underline"
                           href="/dashboard/pinjaman">View All</a>
                     </div>
                     <div class="space-y-3">
                        @forelse($currentlyBorrowed as $pinjam)
                           @php
                              $due = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo);
                              $isOverdue = now()->gt($due);
                              $diff = now()->diffInDays($due, false);

                              $start = \Carbon\Carbon::parse($pinjam->tanggal_pinjam);
                              $totalDays = $start->diffInDays($due) ?: 1;
                              $elapsed = $start->diffInDays(now());
                              $percent = min(100, max(0, ($elapsed / $totalDays) * 100));
                            @endphp

                           <div
                              class="glass-card p-4 rounded-xl flex items-center gap-4 hover:bg-[#2a3f55] transition-colors group">
                              <div class="h-20 w-14 rounded bg-cover bg-center shrink-0 shadow-md"
                                 style='background-image: url("{{ $pinjam->buku->gambar_sampul }}");'>
                              </div>
                              <div class="flex-1 min-w-0">
                                 <h4 class="font-bold truncate group-hover:text-primary transition-colors">
                                    {{ $pinjam->buku->judul }}
                                 </h4>
                                 <p class="text-xs text-[#92adc9]">{{ $pinjam->buku->penulis->nama ?? 'Unknown' }}</p>
                                 <div class="mt-2 flex items-center gap-3">
                                    <div class="flex-1 bg-[#111a22] rounded-full h-1.5">
                                       <div
                                          class="{{ $isOverdue ? 'bg-red-500' : ($percent > 80 ? 'bg-amber-500' : 'bg-primary') }} h-1.5 rounded-full"
                                          style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span
                                       class="text-[10px] {{ $isOverdue ? 'text-red-500 font-bold' : 'text-[#92adc9]' }}">
                                       {{ $isOverdue ? 'Overdue ' . abs($diff) . ' days' : 'Due in ' . $diff . ' days' }}
                                    </span>
                                 </div>
                              </div>
                              <button
                                 class="p-2 {{ $isOverdue ? 'bg-red-500/10 text-red-500' : 'bg-primary/10 text-primary' }} rounded-lg hover:scale-110 transition-all">
                                 <span
                                    class="material-symbols-outlined text-lg">{{ $isOverdue ? 'priority_high' : 'keyboard_return' }}</span>
                              </button>
                           </div>
                        @empty
                           <div class="text-center py-10 glass-card rounded-xl opacity-50">
                              <p class="text-sm">Kamu tidak sedang meminjam buku.</p>
                           </div>
                        @endforelse
                     </div>
                  </div>

                  <div class="space-y-4">
                     <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-bold">Wishlist</h2>
                        <a class="text-primary text-sm font-medium hover:underline" href="/dashboard/wishlist">Full
                           List</a>
                     </div>
                     <div class="space-y-3">
                        @forelse($wishlist as $item)
                           <div
                              class="glass-card p-4 rounded-xl flex items-center gap-4 group hover:bg-[#2a3f55] transition-all">
                              <div class="h-16 w-12 rounded bg-cover bg-center shrink-0 shadow-sm"
                                 style='background-image: url("{{ $item->buku->gambar_sampul }}");'>
                              </div>
                              <div class="flex-1 min-w-0">
                                 <h4 class="font-bold truncate text-sm">{{ $item->buku->judul }}</h4>
                                 <p class="text-[10px] text-[#92adc9]">{{ $item->buku->penulis->nama ?? 'Unknown' }}</p>
                                 <p class="text-[9px] text-primary mt-1">Added
                                    {{ \Carbon\Carbon::parse($item->dibuat_pada)->diffForHumans() }}
                                 </p>
                              </div>
                              <a href="/detail/{{ $item->id_buku }}"
                                 class="bg-primary/20 hover:bg-primary text-primary hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">
                                 Borrow
                              </a>
                           </div>
                        @empty
                           <div class="text-center py-10 glass-card rounded-xl opacity-50">
                              <p class="text-sm">Wishlist kosong.</p>
                           </div>
                        @endforelse
                     </div>
                  </div>
               </div>
               <div class="mt-20">
                  <div class="flex items-center gap-4 mb-8">
                     <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
                     <h3 class="text-xl font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">auto_awesome</span>
                        Mungkin Kamu Suka
                     </h3>
                     <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
                  </div>

                  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                     @foreach($suggestedBooks as $sBook)
                        <div onclick="window.location.href='/detail/{{ $sBook->id }}'"
                           class="glass-card p-3 rounded-2xl group cursor-pointer hover:border-primary/30 transition-all">

                           <div class="aspect-[3/4] rounded-xl overflow-hidden mb-3 relative">
                              <img src="{{ $sBook->gambar_sampul }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                              <div
                                 class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                 <span
                                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-full uppercase">Lihat</span>
                              </div>
                           </div>

                           <div class="px-1">
                              <h4
                                 class="font-bold text-xs truncate text-white/90 group-hover:text-primary transition-colors">
                                 {{ $sBook->judul }}
                              </h4>
                              <p class="text-[10px] text-slate-500 mt-0.5">{{ $sBook->penulis->nama }}</p>
                           </div>
                        </div>
                     @endforeach
                  </div>
               </div>
            </div>
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
                           class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Terms of
                           Service</a>
                     </div>
                  </div>
               </div>
            </footer>
         </main>

      </main>
   </div>

   <script>
      const btn = document.getElementById('profileBtn');
      const dropdown = document.getElementById('profileDropdown');

      btn.addEventListener('click', e => {
         e.stopPropagation();
         dropdown.classList.toggle('hidden');
      });

      document.addEventListener('click', () => {
         dropdown.classList.add('hidden');
      });
   </script>

</body>

</html>