<!DOCTYPE html>
<html lang="en" class="dark">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jokopus</title>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
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
            display: ["Inter", "sans-serif"],
          },
          borderRadius: {
            DEFAULT: "0.5rem",
            lg: "1rem",
            xl: "1.5rem",
            full: "9999px",
          },
        },
      },
    };
  </script>

  <style>
    .glass {
      background: rgba(16, 25, 34, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .material-symbols-outlined {
      font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-white">
  <div class="relative flex min-h-screen flex-col overflow-x-hidden">

    <header class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-[1200px]">
      <div class="glass border border-white/10 rounded-2xl px-6 py-3 shadow-2xl shadow-black/20">
        <div class="flex items-center justify-between">

          <div onclick="window.location.href='/'" class="flex items-center gap-3 cursor-pointer group">
            <div
              class="size-9 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
              <svg viewBox="0 0 48 48" fill="currentColor" class="size-6">
                <path
                  d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z" />
              </svg>
            </div>
            <h2
              class="text-xl font-black tracking-tighter bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
              JOKOPUS
            </h2>
          </div>

          <nav class="hidden md:flex items-center bg-white/5 rounded-full px-2 py-1 border border-white/5 shadow-inner">
            <a href="/"
              class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('/') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
              Home
            </a>

            <a href="/buku"
              class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('buku') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
              Book
            </a>

            <a href="/dashboard"
              class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('dashboard*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
              Dashboard
            </a>
          </nav>

          <div class="flex items-center gap-4">
            @if (session()->has('user'))
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

              <div class="flex items-center gap-3 pl-4 border-l border-white/10">
                <div class="hidden sm:block text-right">
                  <p class="text-[10px] font-black text-primary uppercase tracking-widest">
                    {{ session('user.role') == 1 ? 'Admin' : 'Member' }}
                  </p>
                  <p class="text-xs font-bold text-white truncate max-w-[100px]">{{ session('user.name') }}</p>
                </div>
                <div onclick="window.location.href='/dashboard'"
                  class="h-10 w-10 rounded-xl border-2 border-white/10 bg-center bg-cover hover:border-primary hover:scale-105 transition-all cursor-pointer shadow-lg"
                  style="background-image: url('{{ $displayPhoto }}');">
                </div>
              </div>
            @else
              <a href="/daftar"
                class="h-10 px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-widest flex items-center justify-center hover:shadow-[0_0_20px_rgba(19,127,236,0.4)] hover:scale-105 active:scale-95 transition-all">
                Sign In
              </a>
            @endif
          </div>

        </div>
      </div>
    </header>

    <main class="flex-1 mt-16">
      <!-- Hero Section -->
      <section class="px-4 md:px-20 py-10">
        <div class="max-w-[1200px] mx-auto">
          <div class="@container">
            <div class="@[480px]:p-4">
              <div
                class="relative flex min-h-[520px] flex-col items-center justify-center gap-6 overflow-hidden rounded-xl bg-cover bg-center bg-no-repeat p-6 text-center @[480px]:gap-10"
                style="background-image: linear-gradient(rgba(16,25,34,.8), rgba(16,25,34,.8)), url('https://media.istockphoto.com/id/1339845062/photo/reading-room-or-library-interior-with-leather-armchair-bookshelf-and-floor-lamp.jpg?s=612x612&w=0&k=20&c=2ghOW2DCvb49Up3D0eFeVzv1kbSMjUq-_psohUYeZB0=');">
                <div class="z-10 flex max-w-[800px] flex-col gap-4">

                  <h1 class="text-4xl font-black tracking-tight leading-tight text-white @[480px]:text-7xl">
                    @if (session()->has('user'))
                      Halo, {{ session('user.name') }}
                    @else
                      Jokopus
                    @endif
                  </h1>
                  <p class="text-base font-normal text-white/80 md:text-lg leading-relaxed">
                    Mau baca buku apa hari ini? Kita punya
                    <span class="text font-bold">{{ $totalBuku }}</span> Buku,
                    <span class="text font-bold">{{ $totalTag }}</span> Tagar, 
                    <span class="text font-bold">{{ $totalKategori }}</span> Kategori Buku dan 
                    <span class="text font-bold">{{ $totalPenulis }}</span> Penulis, dan
                    <span class="text font-bold">{{ $totalUser }}</span> Pengguna!
                  </p>
                </div>

                <!-- Search -->
                <div class="z-10 w-full max-w-[600px]">
                  <form action="/buku" method="GET">
                    <label class="flex h-14 w-full flex-col md:h-16 cursor-text">
                      <div class="flex h-full w-full flex-1 items-stretch rounded-xl shadow-2xl">
                        {{-- Ikon Search --}}
                        <div
                          class="flex items-center justify-center rounded-l-xl border border-white/10 border-r-0 bg-white/5 pl-5 text-[#92adc9] backdrop-blur-md">
                          <span class="material-symbols-outlined">search</span>
                        </div>

                        {{-- Input Field - Tambahkan name="search" --}}
                        <input type="text" name="search" value="{{ request('search') }}"
                          placeholder="Cari judul, penulis, atau genre..."
                          class="form-input flex-1 border border-x-0 border-white/10 bg-white/5 px-4 text-sm text-white placeholder:text-[#92adc9] focus:outline-0 focus:ring-2 focus:ring-primary/50 backdrop-blur-md md:text-base" />

                        {{-- Button Submit --}}
                        <div
                          class="flex items-center justify-center rounded-r-xl border border-white/10 border-l-0 bg-white/5 pr-2 backdrop-blur-md">
                          <button type="submit"
                            class="h-10 min-w-[100px] rounded-lg bg-primary px-5 text-sm font-bold tracking-wide text-white transition hover:scale-95 active:bg-primary/90 md:h-12">
                            Search
                          </button>
                        </div>
                      </div>
                    </label>
                  </form>
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Featured Books -->
      <section class="px-4 md:px-20 py-10">
        <div class="max-w-[1200px] mx-auto">
          <div class="flex items-center justify-between px-4 pb-6">
            <h2 class="text-2xl font-bold tracking-tight md:text-3xl">
              Featured Books
            </h2>
            <button onclick="window.location.href='/buku'" class="text-sm font-semibold text-primary hover:underline">
              View All
            </button>
          </div>

          <div class="grid grid-cols-2 gap-6 px-4 pb-8 md:grid-cols-4">
            @forelse ($books->take(8) as $book)
              <div onclick="window.location.href='/detail/{{ $book->id }}'"
                class="glass flex cursor-pointer snap-start flex-col gap-4 rounded-xl p-4 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                {{-- Cover Buku --}}
                <div class="aspect-[3/4] w-full rounded-lg bg-cover bg-center shadow-lg"
                  style="background-image: url('{{ $book->gambar_sampul ? $book->gambar_sampul : asset('images/cover-default.jpg') }}');">
                </div>

                {{-- Info Buku --}}
                <div class="space-y-1">
                  <h3 class="text-xl line-clamp-2 text-lg font-bold text-white">
                    {{ $book->judul }}
                  </h3>
                  <p class="text-xl text-sm text-[#92adc9]">
                    {{ $book->penulis->nama ?? 'Penulis tidak diketahui' }}
                  </p>
                </div>
              </div>
            @empty
              <div class="col-span-full py-10 text-center">
                <p class="text-gray-400">Belum ada buku tersedia.</p>
              </div>
            @endforelse
          </div>

        </div>
      </section>
    </main>

    <footer
      class="relative mt-20 border-t border-white/5 bg-background-dark/50 px-4 pb-12 pt-20 md:px-20 overflow-hidden">
      <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent">
      </div>
      <div class="absolute -top-24 left-1/2 -translate-x-1/2 size-48 bg-primary/10 blur-[100px] rounded-full"></div>

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
              <li><a href="/" class="text-slate-400 hover:text-primary text-sm transition-colors">Home</a></li>
              <li><a href="/buku" class="text-slate-400 hover:text-primary text-sm transition-colors">List Buku</a>
              </li>
              <li><a href="/dashboard" class="text-slate-400 hover:text-primary text-sm transition-colors">Dashboard</a>
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

        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-xs text-slate-500 font-medium">
            © 2026 <span class="text-slate-300">Jokopus</span>.
          </p>
          <div class="flex gap-6">
            <a href="#" class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Privacy
              Policy</a>
            <a href="#" class="text-[10px] uppercase tracking-tighter text-slate-500 hover:text-slate-300">Terms of
              Service</a>
          </div>
        </div>
      </div>
    </footer>

  </div>
</body>

</html>
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