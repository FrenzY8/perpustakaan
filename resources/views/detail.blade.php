<!DOCTYPE html>
<html lang="en" class="dark">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $book->judul }} - Jokopus</title>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <script id="tailwind-config">
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
            DEFAULT: "0.25rem",
            lg: "0.5rem",
            xl: "0.75rem",
            full: "9999px",
          },
        },
      },
    };
  </script>

  <style>
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
        transform: scale(1);
      }

      to {
        opacity: 0;
        transform: scale(0.95);
      }
    }

    .animate-slide-in-right {
      animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .toast-fade-out {
      animation: fadeOut 0.3s ease forwards;
    }

    .glass-panel {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .glass {
      background: rgba(25, 38, 51, 0.6);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .material-symbols-outlined {
      font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
    }

    .material-symbols-outlined.fill {
      font-variation-settings: "FILL" 1;
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white min-h-screen">

  <header class="fixed top-0 z-50 w-full glass border-b border-white/10 px-4 py-3 md:px-20">
    <div class="mx-auto flex max-w-[1200px] items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="size-8 text-primary">
          <svg viewBox="0 0 48 48" fill="currentColor">
            <path
              d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z" />
          </svg>
        </div>
        <h2 onclick="window.location.href='/'" class="text-xl font-bold tracking-tight">Jokopus</h2>
      </div>

      <nav class="hidden items-center gap-8 md:flex">
        <a href="/" class="text-sm font-medium hover:text-primary">Home</a>
        <a href="/buku" class="text-sm font-medium hover:text-primary">Book</a>
        <a href="/dashboard" class="text-sm font-medium hover:text-primary">Dashboard</a>
      </nav>

      @if (session()->has('user'))
        <!-- Profile Button -->
        <div class="flex-1 max-w-md mx-4 hidden sm:block">
        </div>
        <div class="flex items-center gap-3">

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
          <div onclick="window.location.href='/dashboard'"
            class="h-10 w-10 rounded-full border-2 border-primary/20 bg-center bg-cover"
            data-alt="User profile avatar portrait" style="background-image: url('{{ $displayPhoto }}');">
          </div>

        </div>
      @else
        <!-- Sign In Button -->
        <a href="/daftar"
          class="hidden h-10 min-w-[100px] items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition hover:scale-105 active:scale-95 sm:flex">
          Sign In
        </a>
      @endif

    </div>
  </header>

  <main class="max-w-7xl mx-auto px-6 py-8">
    <nav class="flex items-center gap-2 mb-8 text-sm">
      <a href="/" class="text-white/50 hover:text-primary transition-colors">Home</a>
      <span class="material-symbols-outlined text-xs text-white/30">chevron_right</span>
      <a href="/buku" class="text-white/50 hover:text-primary transition-colors">Buku</a>
      <span class="material-symbols-outlined text-xs text-white/30">chevron_right</span>
      <span class="text-white/90">{{ $book->judul }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      {{-- Kolom Kiri: Cover --}}
      <div class="lg:col-span-5 xl:col-span-4">
        <div
          class="group relative aspect-[3/4.5] w-full rounded-xl overflow-hidden shadow-2xl shadow-primary/10 transition-transform duration-500 hover:scale-[1.02]">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
          <div class="w-full h-full bg-center bg-cover"
            style="background-image:url('{{ $book->gambar_sampul ? $book->gambar_sampul : asset('images/cover-default.jpg') }}');">
          </div>
          <div class="absolute bottom-6 left-6 z-20">
            <span class="bg-primary px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase shadow-lg">
              {{ $book->format ?? 'Fisik' }}
            </span>
          </div>
        </div>
      </div>

      {{-- Kolom Kanan: Detail --}}
      <div class="lg:col-span-7 xl:col-span-8 space-y-8">
        <div class="space-y-4">
          <div class="flex flex-wrap items-center gap-4">
            <span
              class="px-3 py-1 bg-green-500/10 text-green-400 text-xs font-bold rounded-full border border-green-500/20 uppercase tracking-widest">
              Tersedia
            </span>
          </div>
          <div class="space-y-1">
            <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight">{{ $book->judul }}</h1>
            <p class="text-xl text-primary font-medium italic">by
              {{ $book->penulis->nama ?? 'Penulis tidak diketahui' }}
            </p>
          </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div class="glass-panel p-4 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary">auto_stories</span>
            <div>
              <p class="text-white text-base font-bold">{{ $book->jumlah_halaman }} Halaman</p>
              <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">Panjang</p>
            </div>
          </div>
          <div class="glass-panel p-4 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary">fingerprint</span>
            <div>
              <p class="text-white text-base font-bold">{{ $book->isbn ?? '-' }}</p>
              <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">ISBN</p>
            </div>
          </div>
          <div class="glass-panel p-4 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary">event</span>
            <div>
              <p class="text-white text-base font-bold">{{ \Carbon\Carbon::parse($book->tanggal_terbit)->format('Y') }}
              </p>
              <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">Terbit</p>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-4 pt-2">
          @if($isCurrentlyBorrowing)
            <button disabled
              class="w-full md:min-w-[200px] h-14 bg-gray-600 text-gray-400 font-bold rounded-xl flex items-center justify-center gap-3 cursor-not-allowed shadow-inner">
              <span class="material-symbols-outlined">pending_actions</span>
              Sedang Kamu Pinjam
            </button>
          @else
            <form action="/pinjam/{{ $book->id }}" method="POST" class="flex-1 md:flex-none">
              @csrf
              <button type="submit"
                class="w-full md:min-w-[200px] h-14 font-bold rounded-xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg 
                        {{ $hasBorrowedBefore ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-500/20' : 'bg-primary hover:bg-primary/90 shadow-primary/20' }}">

                <span class="material-symbols-outlined">
                  {{ $hasBorrowedBefore ? 'history_edu' : 'library_add_check' }}
                </span>

                <span>
                  {{ $hasBorrowedBefore ? 'Pinjam Lagi' : 'Pinjam Sekarang' }}
                </span>
              </button>
            </form>
          @endif
          <div id="notification-container"
            class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 max-w-sm w-full items-end">
            @if(session('success'))
              <div
                class="toast-card flex items-center gap-3 p-4 bg-green-500/10 border border-green-500/50 backdrop-blur-xl text-green-400 rounded-2xl shadow-2xl animate-slide-in-right">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-sm font-bold">{{ session('success') }}</span>
              </div>
            @endif

            @if(session('error'))
              <div
                class="toast-card flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/50 backdrop-blur-xl text-red-400 rounded-2xl shadow-2xl animate-slide-in-right">
                <span class="material-symbols-outlined">error</span>
                <span class="text-sm font-bold">{{ session('error') }}</span>
              </div>
            @endif
          </div>
          <form action="/wishlist/{{ $book->id }}" method="POST" class="flex-1 md:flex-none">
            @csrf
            <button type="submit" class="w-full md:min-w-[200px] h-14 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-[0.95] border font-bold
        {{ $isWishlisted
  ? 'bg-white border-white text-black shadow-lg shadow-white/20'
  : 'glass-panel border-white/10 hover:bg-white/10 text-white' 
        }}">

              <span class="material-symbols-outlined {{ $isWishlisted ? 'filled' : '' }}">
                favorite
              </span>

              {{ ($isWishlisted ? 'Difavoritkan' : 'Favorit') . ' (' . $wishlistCount . ')' }}
            </button>
          </form>
        </div>

        {{-- Ringkasan --}}
        <div class="space-y-4">
          <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">subject</span>
            Ringkasan
          </h3>
          <div class="glass-panel p-6 rounded-xl space-y-4 text-white/70 leading-relaxed font-light">
            <p>{{ $book->ringkasan }}</p>
          </div>
        </div>

        {{-- Komentar --}}
        <div class="mt-16 max-w-4xl mx-auto">
          <h3 class="text-2xl font-bold mb-8 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">forum</span>
            Diskusi Pembaca <span
              class="text-sm bg-white/10 px-3 py-1 rounded-full text-[#92adc9]">{{ $book->komentar->count() }}</span>
          </h3>

          @if(session()->has('user'))
            <div class="glass-card p-6 rounded-2xl mb-10 border border-white/10">
              <form action="/detail/{{ $book->id }}/komentar" method="POST">
                @csrf
                <div class="flex gap-4">
                  @php
                    $currentUser = DB::table('users')->where('id', session('user.id'))->first();
                    $myPhoto = $currentUser->profile_photo ? asset('storage/avatars/' . $currentUser->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode(session('user.name')) . "&background=137fec&color=fff";
                  @endphp
                  <div class="h-10 w-10 rounded-full bg-cover bg-center shrink-0 border border-primary/30"
                    style="background-image: url('{{ $myPhoto }}')"></div>
                  <div class="flex-1">
                    <textarea name="isi_komentar" rows="2" required
                      class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-gray-500"
                      placeholder="Tulis pendapatmu tentang buku ini..."></textarea>
                    <div class="flex justify-end mt-3">
                      <button type="submit"
                        class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">send</span> Kirim Komentar
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          @else
            <div class="glass-card p-4 rounded-xl mb-10 text-center border border-dashed border-white/20">
              <p class="text-sm text-[#92adc9]">Silahkan <a href="/login"
                  class="text-primary font-bold hover:underline">Login</a> untuk ikut berdiskusi.</p>
            </div>
          @endif

          <div class="space-y-6">
            @forelse($book->komentar->sortByDesc('id') as $komen)
              <div class="flex gap-4 group">
                @php
                  $komenPhoto = $komen->user->profile_photo ? asset('storage/avatars/' . $komen->user->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode($komen->user->name) . "&background=334155&color=fff";
                @endphp
                <div class="h-12 w-12 rounded-full bg-cover bg-center shrink-0 border-2 border-white/5 shadow-lg"
                  style="background-image: url('{{ $komenPhoto }}')"></div>

                <div class="flex-1">
                  <div
                    class="glass-card p-4 rounded-2xl rounded-tl-none border border-white/5 group-hover:border-primary/20 transition-all">
                    <div class="flex justify-between items-center mb-2">
                      <h4 class="font-bold text-sm text-primary">{{ $komen->user->name }}</h4>

                      <span class="text-[10px] text-[#92adc9] italic">
                        @if(isset($komen->dibuat_pada))
                          {{ \Carbon\Carbon::parse($komen->dibuat_pada)->translatedFormat('d M Y, H:i') }}
                        @else
                          Baru saja
                        @endif
                      </span>
                    </div>
                    <p class="text-sm text-gray-300 leading-relaxed italic">"{{ $komen->isi_komentar }}"</p>
                  </div>
                </div>
              </div>
            @empty
              <div class="text-center py-10 opacity-40">
                <span class="material-symbols-outlined text-4xl mb-2">chat_bubble_outline</span>
                <p class="text-sm">Belum ada komentar. Jadilah yang pertama!</p>
              </div>
            @endforelse
          </div>
        </div>

        {{-- Info Tambahan --}}
        <div class="border-t border-white/5 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
          <div class="flex flex-col gap-1">
            <span class="text-white/30 uppercase tracking-widest font-bold text-[10px]">Penerbit</span>
            <span class="text-white/80 font-medium">{{ $book->penerbit }}</span>
          </div>
          <div class="flex flex-col gap-1">
            <span class="text-white/30 uppercase tracking-widest font-bold text-[10px]">Kategori ID</span>
            <span class="text-white/80 font-medium">{{ $book->id_kategori }}</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="border-t border-white/5 bg-background-dark px-4 pb-8 pt-16 md:px-20">
    <div class="mx-auto max-w-[1200px]">
      <p class="text-center text-xs text-[#92adc9]">
        © 2026 Jokopus Management System. All rights reserved.
      </p>
    </div>
  </footer>
</body>

</html>
<script>
  document.querySelectorAll('.toast-card').forEach(toast => {
    setTimeout(() => {
      toast.classList.add('toast-fade-out');
      setTimeout(() => {
        toast.remove();
      }, 300);
    }, 4000);
  });
</script>