<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jokopus</title>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
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
      background: rgba(25, 38, 51, 0.6);
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

    <header class="fixed top-0 z-50 w-full glass border-b border-white/10 px-4 py-3 md:px-20">
      <div class="mx-auto flex max-w-[1200px] items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="size-8 text-primary">
            <svg viewBox="0 0 48 48" fill="currentColor">
              <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z"/>
            </svg>
          </div>
          <h2 class="text-xl font-bold tracking-tight">Jokopus</h2>
        </div>

        <nav class="hidden items-center gap-8 md:flex">
          <a href="#" class="text-sm font-medium hover:text-primary">Home</a>
          <a href="#" class="text-sm font-medium hover:text-primary">Catalog</a>
          <a href="#" class="text-sm font-medium hover:text-primary">Membership</a>
          <a href="#" class="text-sm font-medium hover:text-primary">About Us</a>
        </nav>

    @if (session()->has('user'))
    <!-- Profile Button -->
    <button id="profileBtn"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#233648] transition relative">
        <!-- User Info -->
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold">{{ session('user.name') }}</p>
            <p class="text-[10px] text-primary font-bold">PREMIUM</p>
        </div>

        <!-- Avatar -->
        <div class="h-10 w-10 rounded-full bg-cover bg-center border-2 border-primary/30"
             style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuCZfG2SJx7jt-Cd6VQUBwU7qxAaKq5nXu15bbn73Z6Dg27wZJ32gI2YI7yMCTk1827CUdDr6PRR0yGDdk7CUNJIoJHsjK7GpUt5QrUZ1T4ejS_b3Jr5Zx2W3qF2z6Pgq64ysSkLvI-jdO_mDCtFJMP9xmb4jwrBsApvDntRkR2YqNGXFqJ18IqwQChKiu-rWCihPZC2yKQfpuOXKiiKu5bknrMDTcGuw4dG061OP1rrqsyomNIbG7GfMTkRZoO_s69C4_6MqI-_U0FO')">
        </div>

        <span class="material-symbols-outlined text-[#92adc9]">expand_more</span>
    </button>

    <div id="profileDropdown"
         class="hidden absolute right-0 mt-3 w-52 rounded-xl bg-[#192633] shadow-xl border border-white/10 z-50">
        <a href="/profile" class="flex items-center gap-3 px-4 py-3 hover:bg-[#233648] transition">
            <span class="material-symbols-outlined text-sm">person</span>
            Profile
        </a>
        <a href="/settings" class="flex items-center gap-3 px-4 py-3 hover:bg-[#233648] transition">
            <span class="material-symbols-outlined text-sm">settings</span>
            Settings
        </a>
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 hover:bg-[#233648] transition">
            <span class="material-symbols-outlined text-sm">dashboard</span>
            Dashboard
        </a>

        <div class="border-t border-white/10"></div>

        <a href="/logout" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 transition">
            <span class="material-symbols-outlined text-sm">logout</span>
            Logout
        </a>
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

    <main class="flex-1 mt-16">
    <!-- Hero Section -->
    <section class="px-4 md:px-20 py-10">
    <div class="max-w-[1200px] mx-auto">
      <div class="@container">
        <div class="@[480px]:p-4">
          <div
            class="relative flex min-h-[520px] flex-col items-center justify-center gap-6 overflow-hidden rounded-xl bg-cover bg-center bg-no-repeat p-6 text-center @[480px]:gap-10"
            style="background-image: linear-gradient(rgba(16,25,34,.8), rgba(16,25,34,.8)), url('https://lh3.googleusercontent.com/aida-public/AB6AXuAK6eul6H5gmVU4VIqvk9McvfSYcQuhXTnIKQbH4sYM2Fzlc5anDXwjUnLGAAPZeCu6IMh5mukLZ_Dne4R9Rp01Rfo3szGuCL0fghYEPiYz43yQqB_Afhd7HCzG4Gi2bXx9C-lVbE-ePOQq7po2G-_7BJKLdNlrpTi47TjA2g7Nzirry3_kPr2x85x4Ln6XxXvO_itXZCO2jjZ-yoLPTdjDxb5w0TH7R_J4b4fGH7mPYbSPpGgYtf6ymF3fr2SLhkj7dSU_ugbWbzzS');"
          >
            <div class="z-10 flex max-w-[800px] flex-col gap-4">

              <h1 class="text-4xl font-black tracking-tight leading-tight text-white @[480px]:text-6xl">
                @if (session()->has('user'))
                 Halo, {{ session('user.name') }}
                @else
                 Jokopus
                @endif
              </h1>
              <p class="text-base font-normal text-white/80 md:text-lg">
                Mau baca apa buku apa hari ini?
              </p>
            </div>

            <!-- Search -->
            <div class="z-10 w-full max-w-[600px]">
              <label class="flex h-14 w-full flex-col md:h-16">
                <div class="flex h-full w-full flex-1 items-stretch rounded-xl shadow-2xl">
                  <div class="flex items-center justify-center rounded-l-xl border border-white/10 border-r-0 bg-white/5 pl-5 text-[#92adc9] backdrop-blur-md">
                    <span class="material-symbols-outlined">search</span>
                  </div>

                  <input
                    type="text"
                    placeholder="Search for books, authors, or genres..."
                    class="form-input flex-1 border border-x-0 border-white/10 bg-white/5 px-4 text-sm text-white placeholder:text-[#92adc9] focus:outline-0 focus:ring-2 focus:ring-primary/50 backdrop-blur-md md:text-base"
                  />

                  <div class="flex items-center justify-center rounded-r-xl border border-white/10 border-l-0 bg-white/5 pr-2 backdrop-blur-md">
                    <button class="h-10 min-w-[100px] rounded-lg bg-primary px-5 text-sm font-bold tracking-wide text-white transition hover:bg-primary/90 md:h-12">
                      Search
                    </button>
                  </div>
                </div>
              </label>
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
        <button class="text-sm font-semibold text-primary hover:underline">
          View All
        </button>
      </div>

      <div class="flex snap-x gap-6 overflow-x-auto px-4 pb-8 scrollbar-hide">
        <!-- Book Card -->
        <div class="glass flex min-w-[240px] snap-start flex-col gap-4 rounded-xl p-4 transition hover:scale-[1.02]">
          <div
            class="aspect-[3/4] w-full rounded-lg bg-cover bg-center shadow-lg"
            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA6mH4TsF_4f1hE_lKKKFrz26KAjSp_8EEgKVRzpIHU8oN_ZOP0f0y8_heV3ja21fE6aZFefS6tr6kCIYv4ZXPi3ZbcOAi3xJRPKmQM44MyGuWiW49nkFeCo1_rGOhu2HojgpmM5nbnmG2_ORcJNtaaKGITx3sm0rX6NPgeUsdOAkM8XX_5RItTNG-p1mjikK6y_Rki-3xz_LVQYDQZF9rdc3496Wzh7wVJC4iS_oG6yMo1-WM-85d-MzE1jNsrfCZxpLOv41jFmR3_');"
          ></div>

          <div>
            <p class="text-lg font-bold text-white">The Great Gatsby</p>
            <p class="text-sm text-[#92adc9]">F. Scott Fitzgerald</p>
          </div>
        </div>
        <!-- /Book Card -->
      </div>
    </div>
    </section>
    </main>

    <footer class="border-t border-white/5 bg-background-dark px-4 pb-8 pt-16 md:px-20">
      <div class="mx-auto max-w-[1200px]">
        <p class="text-center text-xs text-[#92adc9]">
          © 2024 Jokopus Management System. All rights reserved.
        </p>
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