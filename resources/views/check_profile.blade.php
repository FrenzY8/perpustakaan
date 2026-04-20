<!DOCTYPE html>
<html class="dark" lang="en">

<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>{{ $user->name }} - Jokopus</title>

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

      .material-symbols-outlined.text-fill-1 {
         font-variation-settings: 'FILL' 1;
      }
   </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-white min-h-screen">
   <div class="flex min-h-screen">
      <main class="flex-1 overflow-y-auto">
         <div class="p-8 max-w-6xl mx-auto space-y-8">

            <div class="glass-card rounded-xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
               <div class="flex flex-col md:flex-row items-center gap-6">
                  <div class="relative">
                     @php
                        $photo = $user->profile_photo;
                        if ($photo && (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://'))) {
                           $displayPhoto = $photo;
                        } elseif ($photo && file_exists(storage_path('app/public/avatars/' . $photo))) {
                           $displayPhoto = asset('storage/avatars/' . $photo);
                        } else {
                           $displayPhoto = "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=137fec&color=fff";
                        }
                     @endphp
                     <div
                        class="h-32 w-32 rounded-full bg-center bg-cover ring-4 ring-primary/20 ring-offset-4 ring-offset-background-dark"
                        style="background-image: url('{{ $displayPhoto }}');">
                     </div>
                  </div>
                  <div class="text-center md:text-left">
                     <div class="flex items-center gap-3">
                        <h1 class="text-3xl md:text-4xl font-black tracking-tighter text-white">
                           {{ $user->name }}
                        </h1>

                        @if(session('user.id') == $user->id)
                           <div
                              class="px-3 py-1 rounded-full border border-primary/20 bg-primary/10 text-primary text-xm font-black uppercase tracking-widest shadow-[0_0_15px_rgba(19,127,236,0.1)]">
                              (KAMU)
                           </div>
                        @endif
                     </div>
                     <div class="text-xm mt-2 text-slate-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">calendar_month</span>
                        Bergabung {{ $user->created_at->format('M Y') }}
                     </div>
                     <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                        <div
                           class="flex items-center gap-2 text-sm {{ $user->role == 1 ? 'bg-amber-500/10 text-amber-500 border-amber-500/20' : 'bg-primary/10 text-primary border-primary/20' }} px-3 py-1 rounded-full border">
                           <span class="material-symbols-outlined text-xs">
                              {{ $user->role == 1 ? 'verified_user' : 'person' }}
                           </span>
                           {{ $user->role == 1 ? 'Admin' : 'Member' }}
                        </div>
                        @if(session('user.id') != $user->id)
                           <a href="/chat?target_id={{ $user->id }}"
                              class="flex items-center gap-2 text-sm bg-white/5 hover:bg-primary hover:text-white text-slate-300 border border-white/10 px-4 py-1 rounded-full transition-all group">
                              <span
                                 class="material-symbols-outlined text-xs group-hover:scale-110 transition-transform">chat</span>
                              Chat
                           </a>
                        @endif
                     </div>
                  </div>
               </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
               <div class="glass-card p-6 rounded-xl border-l-4 border-amber-500">
                  <div class="flex items-center justify-between mb-2">
                     <p class="text-[#92adc9] font-medium">Buku Dipinjam</p>
                     <span class="material-symbols-outlined text-amber-500">menu_book</span>
                  </div>
                  <p class="text-3xl font-bold text-white">{{ $totalPinjam }}</p>
                  <p class="text-xs text-[#92adc9] mt-2 italic">Total riwayat peminjaman.</p>
               </div>

               <div class="glass-card p-6 rounded-xl border-l-4 border-red-500">
                  <div class="flex items-center justify-between mb-2">
                     <p class="text-[#92adc9] font-medium">Wishlist</p>
                     <span class="material-symbols-outlined text-red-500 text-fill-1">favorite</span>
                  </div>
                  <p class="text-3xl font-bold text-white">{{ $totalFavorit }}</p>
                  <p class="text-xs text-[#92adc9] mt-2 italic">Buku yang disukai.</p>
               </div>

               <div class="glass-card p-6 rounded-xl border-l-4 border-purple-500">
                  <div class="flex items-center justify-between mb-2">
                     <p class="text-[#92adc9] font-medium">Genre Favorit</p>
                     <span class="material-symbols-outlined text-purple-500">category</span>
                  </div>
                  <p class="text-2xl font-bold text-white truncate">{{ $favGenre->nama ?? 'Belum ada' }}</p>
                  <p class="text-xs text-[#92adc9] mt-2 italic">
                     {{ $favGenre->total ?? 0 }} buku telah dibaca.
                  </p>
               </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
               <div class="space-y-4">
                  <div class="flex items-center justify-between px-2">
                     <h2 class="text-xl font-bold">Sedang Dipinjam</h2>
                  </div>
                  <div class="space-y-3">
                     @forelse($currentlyBorrowed as $pinjam)
                        @php
                           $start = \Carbon\Carbon::parse($pinjam->tanggal_pinjam);
                           $due = \Carbon\Carbon::parse($pinjam->tanggal_jatuh_tempo);
                           $now = now();
                           $isOverdue = $now->gt($due);
                           $diff = (int) $now->diffInDays($due, false);
                           $totalHours = $start->diffInHours($due) ?: 1;
                           $elapsedHours = $start->diffInHours($now, false);
                           $percent = $isOverdue ? 100 : min(100, max(0, ($elapsedHours / $totalHours) * 100));
                        @endphp

                        <div
                           class="glass-card p-4 rounded-xl flex items-center gap-4 hover:bg-[#2a3f55] transition-colors group">
                           <div class="h-20 w-14 rounded bg-cover bg-center shrink-0 shadow-md border border-white/5"
                              style='background-image: url("{{ $pinjam->buku->gambar_sampul }}");'>
                           </div>

                           <div class="flex-1 min-w-0">
                              <h4 class="font-bold truncate group-hover:text-primary transition-colors text-sm">
                                 {{ $pinjam->buku->judul }}
                              </h4>
                              <p class="text-[11px] text-[#92adc9]">{{ $pinjam->buku->penulis->nama ?? 'Unknown' }}</p>

                              <div class="mt-2 flex items-center gap-3">
                                 <div class="flex-1 bg-[#111a22] rounded-full h-1.5 overflow-hidden">
                                    <div
                                       class="h-full rounded-full transition-all duration-700 
                                                               {{ $isOverdue ? 'bg-red-500' : ($percent > 80 ? 'bg-amber-500' : 'bg-primary') }}"
                                       style="width: {{ $percent }}%">
                                    </div>
                                 </div>
                                 <span
                                    class="text-[10px] whitespace-nowrap {{ $isOverdue ? 'text-red-500 font-bold' : 'text-[#92adc9]' }}">
                                    {{ $isOverdue ? 'Telat ' . abs($diff) . ' hari' : ($diff == 0 ? 'Hari ini!' : $diff . ' hari lagi') }}
                                 </span>
                              </div>
                           </div>
                        </div>
                     @empty
                        <div class="text-center py-10 glass-card rounded-xl border border-dashed border-white/10">
                           <p class="text-sm text-[#92adc9]">Tidak ada buku yang sedang dipinjam.</p>
                        </div>
                     @endforelse
                  </div>
               </div>

               <div class="space-y-4">
                  <div class="flex items-center justify-between px-2">
                     <h2 class="text-xl font-bold">Wishlist</h2>
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
                           </div>
                           <a href="/detail/{{ $item->id_buku }}"
                              class="bg-primary/20 hover:bg-primary text-primary hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">
                              Detail
                           </a>
                        </div>
                     @empty
                        <div class="text-center py-10 glass-card rounded-xl opacity-50">
                           <p class="text-sm">Belum ada buku di wishlist.</p>
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
                        </div>
                        <div class="px-1 text-center">
                           <h4 class="font-bold text-xs truncate text-white/90 group-hover:text-primary transition-colors">
                              {{ $sBook->judul }}
                           </h4>
                           <p class="text-[10px] text-slate-500 mt-0.5">{{ $sBook->penulis->nama ?? 'Anonim' }}</p>
                        </div>
                     </div>
                  @endforeach
               </div>
            </div>
         </div>

         <footer class="mt-20 border-t border-white/5 bg-background-dark/50 px-8 py-12 text-center">
            <p class="text-xs text-slate-500 font-medium">© 2026 <span class="text-slate-300">Jokopus</span>. All rights
               reserved.</p>
         </footer>
      </main>
   </div>
</body>

</html>