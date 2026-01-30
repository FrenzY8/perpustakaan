<!DOCTYPE html>
<html lang="en" class="dark">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $book->judul }} - Jokopus</title>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

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
        <div class="group relative aspect-[3/4.5] w-full rounded-xl overflow-hidden shadow-2xl shadow-primary/10 transition-transform duration-500 hover:scale-[1.02]">
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
            <span class="px-3 py-1 bg-green-500/10 text-green-400 text-xs font-bold rounded-full border border-green-500/20 uppercase tracking-widest">
              Tersedia
            </span>
            <div class="flex items-center gap-1 text-primary">
              <span class="material-symbols-outlined fill text-lg">star</span>
              <span class="material-symbols-outlined fill text-lg">star</span>
              <span class="material-symbols-outlined fill text-lg">star</span>
              <span class="material-symbols-outlined fill text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="text-white font-bold ml-2">4.8</span>
            </div>
          </div>
          <div class="space-y-1">
            <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight">{{ $book->judul }}</h1>
            <p class="text-xl text-primary font-medium italic">by {{ $book->penulis->nama ?? 'Penulis tidak diketahui' }}</p>
          </div>
        </div>

        {{-- Meta Data --}}
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
              <p class="text-white text-base font-bold">{{ \Carbon\Carbon::parse($book->tanggal_terbit)->format('Y') }}</p>
              <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">Terbit</p>
            </div>
          </div>
        </div>

        {{-- Button Actions --}}
        <div class="flex flex-wrap gap-4 pt-2">
          <button class="flex-1 md:flex-none md:min-w-[200px] h-14 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined">library_add_check</span>
            Pinjam Sekarang
          </button>
          <button class="flex-1 md:flex-none md:min-w-[200px] h-14 glass-panel hover:bg-white/10 text-white font-bold rounded-xl flex items-center justify-center gap-3 transition-all active:scale-[0.98]">
            <span class="material-symbols-outlined">favorite</span>
            Wishlist
          </button>
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

  <footer class="mt-20 border-t border-white/5 py-12">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
      <div class="flex items-center gap-3 text-white/30">
        <span class="material-symbols-outlined">auto_stories</span>
        <span class="text-sm font-medium">© 2026 Jokopus Modern Library</span>
      </div>
      <div class="flex gap-8">
        <a href="#" class="text-white/40 hover:text-white transition-colors text-sm">Privacy Policy</a>
        <a href="#" class="text-white/40 hover:text-white transition-colors text-sm">Terms of Service</a>
      </div>
    </div>
  </footer>
</body>

</html>