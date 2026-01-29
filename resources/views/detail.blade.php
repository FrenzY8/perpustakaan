<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Details - Modern Library</title>

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
    .material-symbols-outlined {
      font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
    }
    .material-symbols-outlined.fill {
      font-variation-settings: "FILL" 1;
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white min-h-screen">
<header class="sticky top-0 z-50 w-full border-b border-white/10 glass-panel">
  <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
    <div class="flex items-center gap-8">
      <div class="flex items-center gap-3 text-primary">
        <span class="material-symbols-outlined text-3xl">auto_stories</span>
        <h2 class="text-white text-xl font-bold tracking-tight">Modern Library</h2>
      </div>
      <nav class="hidden md:flex items-center gap-8">
        <a href="#" class="text-white/70 hover:text-white text-sm font-medium transition-colors">Browse</a>
        <a href="#" class="text-white/70 hover:text-white text-sm font-medium transition-colors">My Library</a>
        <a href="#" class="text-white/70 hover:text-white text-sm font-medium transition-colors">Wishlist</a>
        <a href="#" class="text-white/70 hover:text-white text-sm font-medium transition-colors">About</a>
      </nav>
    </div>
    <div class="flex items-center gap-6">
      <div class="relative hidden sm:block">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-sm">search</span>
        <input class="bg-white/5 border-none rounded-lg pl-10 pr-4 py-2 text-sm text-white focus:ring-1 focus:ring-primary w-64 placeholder:text-white/30" placeholder="Search for books, authors..." />
      </div>
      <div class="size-9 rounded-full bg-primary/20 border border-primary/30 overflow-hidden">
        <div class="w-full h-full bg-center bg-cover" style='background-image:url("https://lh3.googleusercontent.com/aida-public/AB6AXuB3GqG8K6rRUAZGmTF3S8HPp_NisnN9pQq-uNj_upPNM5p5Z_MnOh8W8b9CXBpy9YuToQTrSJQLL7XNxKrRxxtRksFvVpqeoPxqg_cP9TFYL5-4rR4f16wAUyH5-FxPotgMNruP9lpUZup6spgBFyid583Yok7GNEJDJBOKwZeBWGI4Kjk6_XtXNP1r2YUcgnaHUWAOaXzCCHX2UA5CEKl3B0g0pXjt4S_v_Kot06O0CM-pVgoNxf1XRtbNXlY33LlIem6LmUzZdvjb");'></div>
      </div>
    </div>
  </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-8">
<nav class="flex items-center gap-2 mb-8 text-sm">
  <a href="/" class="text-white/50 hover:text-primary transition-colors">Home</a>
  <span class="material-symbols-outlined text-xs text-white/30">chevron_right</span>
  <a href="/buku" class="text-white/50 hover:text-primary transition-colors">Buku</a>
  <span class="material-symbols-outlined text-xs text-white/30">chevron_right</span>
  <span class="text-white/90">The Future of Mars</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
<div class="lg:col-span-5 xl:col-span-4">
  <div class="group relative aspect-[3/4.5] w-full rounded-xl overflow-hidden shadow-2xl shadow-primary/10 transition-transform duration-500 hover:scale-[1.02]">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
    <div class="w-full h-full bg-center bg-cover" style='background-image:url("https://lh3.googleusercontent.com/aida-public/AB6AXuCVmSYa1eDWWWJsWp3sNmFfbWmC-40EXjEM335Vfu80-L-pgpnMJnZaNA9XEdxsFAVeonbvoZiMMawGzfcwjD49uQDg7Md1E6uW0_8TBVar3_5nhuuq9N-Gc60XjrLzQGmXTinoEdWwZN8U68q8Gb_Obr0uQjQep302IHEeqfU9aHPUlU-9ZbcMabt1pFT7FTgUxD9hY1eNRLj5mzVdx5i2q9kEPLMd-gF2FZxheI9F5mfYV_-4svbdMh5sep-oootjUvqvflAqFCFc");'></div>
    <div class="absolute bottom-6 left-6 z-20">
      <span class="bg-primary px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase shadow-lg">New Release</span>
    </div>
  </div>
</div>

<div class="lg:col-span-7 xl:col-span-8 space-y-8">
<div class="space-y-4">
  <div class="flex flex-wrap items-center gap-4">
    <span class="px-3 py-1 bg-green-500/10 text-green-400 text-xs font-bold rounded-full border border-green-500/20 uppercase tracking-widest">In Stock</span>
    <div class="flex items-center gap-1 text-primary">
      <span class="material-symbols-outlined fill text-lg">star</span>
      <span class="material-symbols-outlined fill text-lg">star</span>
      <span class="material-symbols-outlined fill text-lg">star</span>
      <span class="material-symbols-outlined fill text-lg">star</span>
      <span class="material-symbols-outlined text-lg">star</span>
      <span class="text-white font-bold ml-2">4.8</span>
      <span class="text-white/40 ml-1">(1,240 reviews)</span>
    </div>
  </div>
  <div class="space-y-1">
    <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight">The Future of Mars</h1>
    <p class="text-xl text-primary font-medium italic">by Dr. Adrian Thorne</p>
  </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
  <div class="glass-panel p-4 rounded-xl flex flex-col gap-2">
    <span class="material-symbols-outlined text-primary">auto_stories</span>
    <div>
      <p class="text-white text-base font-bold">350 Pages</p>
      <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">Length</p>
    </div>
  </div>
  <div class="glass-panel p-4 rounded-xl flex flex-col gap-2">
    <span class="material-symbols-outlined text-primary">rocket_launch</span>
    <div>
      <p class="text-white text-base font-bold">Sci-Fi</p>
      <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">Genre</p>
    </div>
  </div>
  <div class="glass-panel p-4 rounded-xl flex flex-col gap-2">
    <span class="material-symbols-outlined text-primary">event</span>
    <div>
      <p class="text-white text-base font-bold">2023</p>
      <p class="text-white/40 text-xs uppercase tracking-wider font-semibold">Published</p>
    </div>
  </div>
</div>

<div class="flex flex-wrap gap-4 pt-2">
  <button class="flex-1 md:flex-none md:min-w-[200px] h-14 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-primary/20">
    <span class="material-symbols-outlined">library_add_check</span>
    Borrow Now
  </button>
  <button class="flex-1 md:flex-none md:min-w-[200px] h-14 glass-panel hover:bg-white/10 text-white font-bold rounded-xl flex items-center justify-center gap-3 transition-all active:scale-[0.98]">
    <span class="material-symbols-outlined">favorite</span>
    Add to Wishlist
  </button>
</div>

<div class="space-y-4">
  <h3 class="text-xl font-bold text-white flex items-center gap-2">
    <span class="material-symbols-outlined text-primary">subject</span>
    Synopsis
  </h3>
  <div class="glass-panel p-6 rounded-xl space-y-4 text-white/70 leading-relaxed font-light">
    <p>In "The Future of Mars," world-renowned planetary scientist Dr. Adrian Thorne presents a compelling blueprint for human colonization of the Red Planet.</p>
    <p>From atmospheric terraforming to the social structures of Martian cities, this book explores technology, ethics, and survival beyond Earth.</p>
  </div>
</div>

<div class="space-y-6">
  <h3 class="text-xl font-bold text-white flex items-center gap-2">
    <span class="material-symbols-outlined text-primary">reviews</span>
    Ratings & Reviews
  </h3>
  <div class="glass-panel p-6 rounded-xl flex flex-col md:flex-row gap-8 items-center">
    <div class="flex flex-col items-center gap-2 min-w-[120px]">
      <p class="text-white text-6xl font-black">4.8</p>
      <div class="flex gap-0.5 text-primary">
        <span class="material-symbols-outlined fill">star</span>
        <span class="material-symbols-outlined fill">star</span>
        <span class="material-symbols-outlined fill">star</span>
        <span class="material-symbols-outlined fill">star</span>
        <span class="material-symbols-outlined">star</span>
      </div>
      <p class="text-white/50 text-xs">Total Reviews</p>
    </div>
  </div>
</div>
</div>
</div>
</main>

<footer class="mt-20 border-t border-white/5 py-12">
  <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-3 text-white/30">
      <span class="material-symbols-outlined">auto_stories</span>
      <span class="text-sm font-medium">© 2024 Modern Library Management System</span>
    </div>
    <div class="flex gap-8">
      <a href="#" class="text-white/40 hover:text-white transition-colors text-sm">Privacy Policy</a>
      <a href="#" class="text-white/40 hover:text-white transition-colors text-sm">Terms of Service</a>
      <a href="#" class="text-white/40 hover:text-white transition-colors text-sm">Help Center</a>
    </div>
  </div>
</footer>
</body>
</html>