<!DOCTYPE html>
<html class="dark" lang="en">

<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Dashboard</title>

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
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary/10 text-primary" href="#">
               <span class="material-symbols-outlined">dashboard</span>
               <span class="font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
               href="#">
               <span class="material-symbols-outlined">library_books</span>
               <span class="font-medium">Library Catalog</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
               href="#">
               <span class="material-symbols-outlined">history</span>
               <span class="font-medium">Reading History</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
               href="#">
               <span class="material-symbols-outlined">reviews</span>
               <span class="font-medium">My Reviews</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
               href="#">
               <span class="material-symbols-outlined">bookmark</span>
               <span class="font-medium">Wishlist</span>
            </a>

            <!-- Settings -->
            <div class="pt-4 mt-4 border-t border-[#233648]">
               <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#92adc9] hover:bg-[#233648] hover:text-white transition-colors"
                  href="#">
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

      <main class="flex-1 overflow-y-auto">
         <main class="flex-1 overflow-y-auto">
            <!-- Top Navigation Bar -->
            <header
               class="sticky top-0 z-10 glass-card border-b border-[#233648] px-8 py-4 flex items-center justify-between">
               <div class="relative w-96">
               </div>
               <div class="flex items-center gap-4">
                  <button
                     class="p-2 text-[#92adc9] hover:text-white hover:bg-[#233648] rounded-lg transition-colors relative">
                     <span class="material-symbols-outlined">notifications</span>
                     <span
                        class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full border-2 border-[#111a22]"></span>
                  </button>
                  <div class="h-8 w-[1px] bg-[#233648]"></div>
                  <div class="relative">

                     <button id="profileBtn"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#233648] transition">
                        <div class="text-right hidden sm:block">
                           <p class="text-sm font-semibold">{{ session('user.name') }}</p>
                           <p class="text-[10px] text-primary font-bold">PREMIUM</p>
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
                        <a href="/settings" class="flex items-center gap-3 px-4 py-3 hover:bg-[#233648] transition">
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
                              <span class="material-symbols-outlined text-xs">payments</span>
                              No Outstanding Fines
                           </div>
                           <div
                              class="flex items-center gap-2 text-sm bg-primary/10 text-primary px-3 py-1 rounded-full">
                              <span class="material-symbols-outlined text-xs">workspace_premium</span>
                              Gold Tier Rewards
                           </div>
                        </div>
                     </div>
                  </div>
                  <button
                     class="px-6 py-2.5 bg-[#233648] hover:bg-[#324d67] rounded-lg font-semibold transition-colors flex items-center gap-2">
                     <span onclick="window.location.href='/profile'"
                        class="material-symbols-outlined text-sm">edit</span>
                     Edit Profile
                  </button>
               </div>
               <!-- Stats Overview -->
               <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="glass-card p-6 rounded-xl border-l-4 border-primary">
                     <div class="flex items-center justify-between mb-2">
                        <p class="text-[#92adc9] font-medium">Books Read</p>
                        <span class="material-symbols-outlined text-primary">auto_stories</span>
                     </div>
                     <p class="text-3xl font-bold">42</p>
                     <p class="text-xs text-green-400 mt-2 font-medium">+3 this month</p>
                  </div>
                  <div class="glass-card p-6 rounded-xl border-l-4 border-amber-500">
                     <div class="flex items-center justify-between mb-2">
                        <p class="text-[#92adc9] font-medium">Currently Borrowed</p>
                        <span class="material-symbols-outlined text-amber-500">menu_book</span>
                     </div>
                     <p class="text-3xl font-bold">3</p>
                     <p class="text-xs text-[#92adc9] mt-2">1 due next week</p>
                  </div>
                  <div class="glass-card p-6 rounded-xl border-l-4 border-purple-500">
                     <div class="flex items-center justify-between mb-2">
                        <p class="text-[#92adc9] font-medium">Favorite Genre</p>
                        <span class="material-symbols-outlined text-purple-500">category</span>
                     </div>
                     <p class="text-xl font-bold">Science Fiction</p>
                     <p class="text-xs text-[#92adc9] mt-2">12 books total</p>
                  </div>
               </div>
               <!-- Tables Row -->
               <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                  <!-- Currently Borrowed -->
                  <div class="space-y-4">
                     <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-bold">Currently Borrowed</h2>
                        <a class="text-primary text-sm font-medium hover:underline" href="#">View All</a>
                     </div>
                     <div class="space-y-3">
                        <!-- Book Item -->
                        <div
                           class="glass-card p-4 rounded-xl flex items-center gap-4 hover:bg-[#2a3f55] transition-colors group">
                           <div class="h-20 w-14 rounded bg-cover bg-center shrink-0"
                              data-alt="Cover of the book Project Hail Mary"
                              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBaZGPg-kil3_0H1XQh1HK6lUVgMoxnjihRl4g0MIeZIwLUEkBh8gZhSQLYhaXZjBjm8D9jiuqslsUdS2GEna4k_hq-2vnJxr5iYWnlwEtnBSzOCf3P4CycnmNms-26NYrMZn-KObx8IRpc2f_9_OtSTkJJVtED3SH1LaCB1IbKGTE6r5CDBxlRTG3HKPYmIWskUVZnkrgPcXF1RsjzR9TTrraxuBvJB3ReNzDAT01hFjKr-FWDP7Md__GWYvpsWDYtjYszHd8aVCfG");'>
                           </div>
                           <div class="flex-1 min-w-0">
                              <h4 class="font-bold truncate group-hover:text-primary transition-colors">Project Hail
                                 Mary</h4>
                              <p class="text-xs text-[#92adc9]">Andy Weir</p>
                              <div class="mt-2 flex items-center gap-3">
                                 <div class="flex-1 bg-[#111a22] rounded-full h-1.5">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: 65%"></div>
                                 </div>
                                 <span class="text-[10px] text-[#92adc9]">Due in 4 days</span>
                              </div>
                           </div>
                           <div class="flex flex-col gap-2">
                              <button
                                 class="p-2 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all"
                                 title="Return Book">
                                 <span class="material-symbols-outlined text-lg">keyboard_return</span>
                              </button>
                           </div>
                        </div>
                        <!-- Book Item 2 -->
                        <div
                           class="glass-card p-4 rounded-xl flex items-center gap-4 hover:bg-[#2a3f55] transition-colors group">
                           <div class="h-20 w-14 rounded bg-cover bg-center shrink-0"
                              data-alt="Cover of Dune by Frank Herbert"
                              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA1OU1OtrMH6w7RupOGtt54MszArZRsutJ2L0-P50VhZSg6UrbVB4NvdS17wXVfZJcxnLiWuP5DSdOIxIdkImqY5714Yo3HZ7K1gXqf7r9QINI7Gn8-EUXc00-jvCbRI3ZtVCO_FS6YfKT9M_gQdkeyd502LfERJI3RJDIB1izyumjnUt8aax1p5GFINNVUtKspgZLosXnc1whr0k4Ktw2yyCWn6KFsVm-STQX0w305Wi02NDNQjVlvyRvdVw0tvZOMJXhunPrUrTDZ");'>
                           </div>
                           <div class="flex-1 min-w-0">
                              <h4 class="font-bold truncate group-hover:text-primary transition-colors">Dune</h4>
                              <p class="text-xs text-[#92adc9]">Frank Herbert</p>
                              <div class="mt-2 flex items-center gap-3">
                                 <div class="flex-1 bg-[#111a22] rounded-full h-1.5">
                                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: 90%"></div>
                                 </div>
                                 <span class="text-[10px] text-amber-500">Overdue (2 days)</span>
                              </div>
                           </div>
                           <div class="flex flex-col gap-2">
                              <button
                                 class="p-2 bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-white rounded-lg transition-all"
                                 title="Renew Book">
                                 <span class="material-symbols-outlined text-lg">event_repeat</span>
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Reading History -->
                  <div class="space-y-4">
                     <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-bold">Reading History</h2>
                        <a class="text-primary text-sm font-medium hover:underline" href="#">Full History</a>
                     </div>
                     <div class="space-y-3">
                        <!-- History Item -->
                        <div class="glass-card p-4 rounded-xl flex items-center gap-4 group">
                           <div
                              class="h-16 w-12 rounded bg-cover bg-center shrink-0 grayscale group-hover:grayscale-0 transition-all"
                              data-alt="Black and white aesthetic book cover"
                              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCuOsr1jdy8jo_Gn_unIoZxPSpojBXZXOFUDJNvmKsnWO4eYU6PS-Etq763hxvVfysrzPBK5Gq8GfKPXy3kfrd8MI1CbE5oWKZfyXLRkAYRRvUXiK1hI-J1ZPDWHZ1Zik4nrrL1TXeZJYo2x4UesUV-LsIPuSDHX6d50hgKmIgYkvZ6g7OBw9PKc0hhqUEs53D9mk8S9vnw05oiKDZaHz66EOGA8HKoM7C1WTFpOkLaiE6M8wubUQyvxY1gkJyLH4CYk7oy-6Ix78tY");'>
                           </div>
                           <div class="flex-1 min-w-0">
                              <h4 class="font-bold truncate">The Midnight Library</h4>
                              <p class="text-xs text-[#92adc9]">Matt Haig • Returned Feb 12</p>
                              <div class="flex mt-1 text-primary">
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star_half</span>
                              </div>
                           </div>
                           <button class="text-[#92adc9] hover:text-white flex items-center gap-1 text-xs font-medium">
                              Re-borrow
                              <span class="material-symbols-outlined text-sm">arrow_forward</span>
                           </button>
                        </div>
                        <!-- History Item 2 -->
                        <div class="glass-card p-4 rounded-xl flex items-center gap-4 group">
                           <div
                              class="h-16 w-12 rounded bg-cover bg-center shrink-0 grayscale group-hover:grayscale-0 transition-all"
                              data-alt="Classic old book spine cover"
                              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDekxvSgysGnmz728yFNtQST5Z05bUwLUAsYduYvizcuP5NeRk3CF0PB5TH2bPH0ZEdG1kyPGvViAVQX1TnudLr7J6TLI_J-3R2kzrBQgPF4tFQSCdgteXpKc0pBpUhFDSwFifn3atMHjsj-qKXIVk2tdp45JZX1J9os6NTqzAuL_aBz2Aktu1OZiyxR2CFA3gG4hNDH7bjNCp8n3Pe-Qev-uLBGDRLhIGEZ_4V5wnAtvAA9TVn-Vlcr4I8h09fvbsJcN6iuU4eqxT9");'>
                           </div>
                           <div class="flex-1 min-w-0">
                              <h4 class="font-bold truncate">Atomic Habits</h4>
                              <p class="text-xs text-[#92adc9]">James Clear • Returned Jan 28</p>
                              <div class="flex mt-1 text-[#92adc9]">
                                 <span class="text-[10px] font-medium px-2 py-0.5 rounded border border-[#324d67]">Not
                                    Rated</span>
                              </div>
                           </div>
                           <button class="text-primary hover:text-white flex items-center gap-1 text-xs font-medium">
                              Review Now
                              <span class="material-symbols-outlined text-sm">edit_square</span>
                           </button>
                        </div>
                        <!-- History Item 3 -->
                        <div class="glass-card p-4 rounded-xl flex items-center gap-4 group">
                           <div
                              class="h-16 w-12 rounded bg-cover bg-center shrink-0 grayscale group-hover:grayscale-0 transition-all"
                              data-alt="Modern minimal book cover"
                              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDiMAnmIaBov0h6n8hvH6rFPz8jOkNgxzwELvGet1tzbQqsyndh2bQ049XXNtwh11KY7h8Co07zXES1VsrO7QPUhE2D3j42sr_eWoVzsl13Cth2ez7Q2xknNfE4W2F6egQ1TB4eXkEJZLTVw053DZqpx9z2NqF0LsQUyipxVJK99xjv8tExay6M7JYKEJUJyg1WDt_VJeHq5JMnKAELaA5pDnBUZRdGnIlVx-a67L5moGYVuBO8F3OcTas7z2bcoUJB0MButDjlcXYf");'>
                           </div>
                           <div class="flex-1 min-w-0">
                              <h4 class="font-bold truncate">Deep Work</h4>
                              <p class="text-xs text-[#92adc9]">Cal Newport • Returned Jan 05</p>
                              <div class="flex mt-1 text-primary">
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                                 <span class="material-symbols-outlined text-sm">star</span>
                              </div>
                           </div>
                           <button class="text-[#92adc9] hover:text-white flex items-center gap-1 text-xs font-medium">
                              Re-borrow
                              <span class="material-symbols-outlined text-sm">arrow_forward</span>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <footer class="mt-8 border-t border-[#233648] px-8 py-6 text-center">
               <p class="text-[#92adc9] text-sm">© 2024 Jokopus Library Management. All rights reserved.</p>
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