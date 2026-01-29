<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Browse Books Catalog</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols Outlined -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        .glass-card {
            background-color: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-nav {
            background-color: rgba(16, 25, 34, 0.8);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<!-- Navigation Header -->
<header class="sticky top-0 z-50 glass-nav px-4 lg:px-40 py-4">
<div class="max-w-[1200px] mx-auto flex items-center justify-between gap-4">
<div class="flex items-center gap-8">
<div class="flex items-center gap-2 text-primary">
<span class="material-symbols-outlined text-3xl font-bold">auto_stories</span>
<h2 class="text-white text-xl font-bold leading-tight tracking-tight">BookShelf</h2>
</div>
<!-- Desktop Nav -->
<nav class="hidden md:flex items-center gap-6">
<a class="text-white/70 hover:text-primary text-sm font-medium transition-colors" href="#">Catalog</a>
<a class="text-white/70 hover:text-primary text-sm font-medium transition-colors" href="#">My Library</a>
<a class="text-white/70 hover:text-primary text-sm font-medium transition-colors" href="#">Reservations</a>
</nav>
</div>
<!-- Search Bar -->
<div class="flex-1 max-w-md mx-4 hidden sm:block">
<div class="relative flex items-center">
<span class="material-symbols-outlined absolute left-3 text-slate-400">search</span>
<input class="w-full h-10 pl-10 pr-4 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all text-sm" placeholder="Search for books, authors, or ISBN..." value=""/>
</div>
</div>
<div class="flex items-center gap-3">
<button class="flex items-center justify-center rounded-lg h-10 w-10 bg-white/5 text-white hover:bg-white/10 transition-colors">
<span class="material-symbols-outlined">notifications</span>
</button>
<button class="flex items-center justify-center rounded-lg h-10 w-10 bg-white/5 text-white hover:bg-white/10 transition-colors">
<span class="material-symbols-outlined">settings</span>
</button>
<div class="h-10 w-10 rounded-full border-2 border-primary/20 bg-center bg-cover" data-alt="User profile avatar portrait" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDktQ8vqvf-qhGurPw_zOLBjp0ZvFKMBe1F7zRS4GxwGc3LbR68UGksNJt3dHVcnV9d2T6LbWS95Lll5BLDq95vK5fAYNDogHHLvBqBTzhoJDqL5L0lruznv2CSr4WE-oyO62EUrVSy2-OIl0UCfc4g6HDBm3G40gNMcV4WckMGe-_UQavqyDBdIWNy2Ae7PTvPzbb7uUVivQuU4OUy8FHcXGu2G4vxW3ayBti5aBkOjyBzxEITW3wiDVKAvfAmU3PsHIP8wmb3xwTN");'>
</div>
</div>
</div>
</header>
<main class="flex-1 px-4 lg:px-40 py-8 max-w-[1200px] mx-auto w-full">
<!-- Headline -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 px-2">
<div>
<h1 class="text-white text-3xl font-bold tracking-tight mb-2">Browse Books</h1>
<p class="text-slate-400 text-sm">Discover your next favorite read from our extensive collection.</p>
</div>
<div class="mt-4 md:mt-0 flex gap-2">
</div>
</div>
<!-- Categories / Chips -->
<!-- Book Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 px-2">
<!-- Book Card 1 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Modern book cover for Project Hail Mary" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAnsc5R4AfG7bKStVSrOOeR2iVU2S_qfRvGYIzzIiWDlD6hplzELh19m0jZPvD1xNDRYWe92wABjon80danbnXy8AWKAKytDWHv2xV5jegb7ZNrxKcj0nrCMRYxxOTKYbbEcQ5Pu0_fejPNfcb8pgcYAciy4S7cg4oc--H6NvCdovN0BQrVA07DDw034K2BXCtPr4mwPb_fI4YYcA51wXsJZ4lZg2cdOfSnIC1I38VvG8MKqG8aDWVHeM_z6G2UaghUkYOyI6doj0ir");'>
<div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
<button class="bg-primary text-white p-2 rounded-full">
<span class="material-symbols-outlined">bookmark_add</span>
</button>
</div>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">The Great Gatsby</h3>
<p class="text-slate-400 text-xs mt-1">F. Scott Fitzgerald</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.8</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Book Card 2 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Epic sci-fi book cover design" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBRDnR0iU2FIdgct354mcAbSqwL4HWgQSS2gfC1kmnrAs7iwk3MUA7VBlceQZN4aoCKSfVCZ-nyyfe6nzfylDf4fp6ecWe0l8PH75mrBeF9AdCE4fTAJFf67g3MZgGTD3dZu_SU--gs_CHt4WuHXyGSVi_ipnSlt9jvnEhIWq_qdWjoAAIZORGlQ00jVd-wuR0w5T9-BkxBbR3IbPfct0VJi1tmt4cv-1k4zEQd5w5VXPhFW5vadnEJ-p0fMfQaClngqUCrIsDxi8Lo");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">Dune</h3>
<p class="text-slate-400 text-xs mt-1">Frank Herbert</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.9</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Book Card 3 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Minimalist book cover for history book" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCIychre2Zt0gcUG_EuQHceCCTVdqA3anvV-OaFCmI6WFdBImD86zTLwYspuI91hH8u5l8R_o-Pn8X9GHvmp6bCmAAXn_cZq2W1kZJz0Q9SFGaQjnQD9UNw0ra8okHiFB7VNXcJfs8PdGu1_zxkBH4DW_FOmSaPhtv3tVI9RMV8UoGgq-xgiAMe337fD4bwipcdXLpNWrR5PUS4ot8yxRlqcVWzixYKnrlAOj6saYPnlM0U5DK3aTjA2nlcirkUv7clfKWmj2989vbp");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">Sapiens</h3>
<p class="text-slate-400 text-xs mt-1">Yuval Noah Harari</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.7</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Book Card 4 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Thriller novel book cover art" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBYIfAeAC3CMzzWuraBjsSW01NTgvPza-i03GFhK8gOTG6vxh6jE6EjGG-hXEN-l-k-qvGacp9hkmJmAMDSe3xQ6syBr19oD4Bqsdp8CyteMUG-Se19MmqITQFngZRWw6U_wrLhGaWaIMFGzK7qzUDYQ1boE4CqpA0uYGQ0IVb6zPo7KUvje_ZEV0KoPtnrLfhb9bj18BZVxJ7XcKjFNfpVTaka3mF-tUQmRiuCUxHNchGOm4W-EA13sihpd4-Yt3POy1R4-HHhWyz7");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">The Silent Patient</h3>
<p class="text-slate-400 text-xs mt-1">Alex Michaelides</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.5</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Book Card 5 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Modern biography book cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB8JQMBZ6Ud-WADsFuhIt1XnujNAAateoPdOTyhjaDDBWnJuS-cRfgPG6h0NfYXBF9ZKZxEx9cPuHgh6UzJbJ9ocBesNSl-ttkrEzfksfQiVnidGgjwTZuJaU3_3xOk8vqLHnqqvw0P2JWNLB9hj0hAAzT4JHSR7lLjFPtuqVRyouIsYfLvIR45P-68La_RXUDKc4dUpgKb9QdHuweHLoRcDrbAWGjrtin6s9gGbJMeka0sUn7tkt-_22I6NDeqvYQyeeno4Ib0ock2");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">Steve Jobs</h3>
<p class="text-slate-400 text-xs mt-1">Walter Isaacson</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.8</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Repeat Card 6 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Abstract fiction book cover design" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuChyrXfyC4ybX_CUWeZijrXLOA7cqPs5cCHm4Bth156ZTpD-F0VZeUSTjMAn5EVDe0OC2j0gW-Is0IaMb1sfwGYKqZ087q6V3j-Xz4S5JZA9kwyOfdRvNDTkkcaWPDlaKWlc0d5tynYiWdwBzzmFfhJJOA_GN6YXCfLXAtuL5xKO-tXnT4Qw196dU2JwWLS8FPRpIRn_ZI27FuMCfCa5L5fbRBtpOR1oniWfly6DMkclcJNfS3Y4ikZSauAx2M5d0iYw4HGGWKgeGH5");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">The Alchemist</h3>
<p class="text-slate-400 text-xs mt-1">Paulo Coelho</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.6</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Repeat Card 7 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Self improvement book cover art" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCA4i-dgoyvUn_RLTJ2SgNP0UnlBgDv07CiSm3zNWre3rl6oslx8vnGif68oiT7Stftln4g4dTPDyrSRaToIUejwQoeoz5nEQCsrPlq2k_6UMJQSZGNdjcA2GbbdRkap3I6ZXAX4_Ml_prvqgNsndQBEbqsVp4JYtIel9msPu6iw9Tnl2KijaIXMUfz3O1LyrvOClDvYentw9Y-3bcn8f3Xz6_zjuwMjAPtWQWOBsvBtkOyM0GXKIXnCBOu-HcfD2Q-NrjjyFyFTRgi");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">Atomic Habits</h3>
<p class="text-slate-400 text-xs mt-1">James Clear</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">5.0</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Card 8 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Science fiction adventure book cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCwYB1-Use541vxpA4KOxvBhp_nPCglcBapLAE0Hj_nREexfVf6NDG6_LIuor_o9UzmU3JgoPiSYVLsmgaM44GsMW2Vz57ZhWE31mePUgC8gDTD7gMpkhSLf-XNSGcCt15aR4MeIhmCr9lKTgim-wQxa64SJrVyxNxx94Ye1a1ljAwYmZthn_wq-zX-SCHqFhU5hspPsYTo59NeAKKLygXcnmBQJ_SugQQydKDtES5sx0ahJkxMEu-5lJfch7p42JRZPqmpri-XgBVo");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">Project Hail Mary</h3>
<p class="text-slate-400 text-xs mt-1">Andy Weir</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.9</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Card 9 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="Classic literature book cover portrait" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCfMhEHEUJSsUJfC5xBJSVj7lsycvNGpVCo_-GYtcOCWB08_Em4PE3n7Kj5tIEZpSLddcl6UFLcK5gdXORzbRBTzOqna61sB4NSDyg5Fp6WLvA43b7AbmEbbBO0DVO1cu7EUCLyEG4QsxhohGVtqofRnIC4jmE46RfDGFT8IVwkuqFJxCAsc77n_hZTEu3Eqt3FRtC2gBzZrWxtT8ijkwYYprrKiyr8Hqetx4ElXLgZWda9-IriwK1Ov2kyLFnKKguH2XfYqBvTmvwH");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">1984</h3>
<p class="text-slate-400 text-xs mt-1">George Orwell</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">4.7</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
<!-- Card 10 -->
<div class="flex flex-col gap-4 glass-card p-4 rounded-xl hover:translate-y-[-4px] transition-all group">
<div class="w-full bg-center bg-no-repeat aspect-[2/3] bg-cover rounded-lg shadow-2xl relative overflow-hidden" data-alt="High fantasy novel book cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB-irDBuSc8GOxcop5OVWhNJ1wWZ-S0cgBO-lmmFUPDIFUvKsGrym7z-7abJPnv4ce1xMydAvSw30315w9bQmaZMtCGM_AH2Ek-K8FqvCI2mMyOKxrMkTAP4UgnDGPTkQUb5MAAXaNl0o1-Ma-kxEV7iPgzT3gSHFVErs-peyKR4jkHkGe5LDwC22lEZybZpHTfUpcDTty1lRrJu9H0xKQjpzMuOWKhz8zoUNJ-GC2-ehFkfao8Loj0lftqH3cGdD8R4X00KUqMpCdt");'>
</div>
<div class="flex flex-col flex-1">
<h3 class="text-white text-base font-bold leading-snug line-clamp-1">The Fellowship of the Ring</h3>
<p class="text-slate-400 text-xs mt-1">J.R.R. Tolkien</p>
<div class="flex items-center gap-1 mt-2 text-yellow-500">
<span class="material-symbols-outlined text-[16px] fill-1">star</span>
<span class="text-xs font-semibold text-slate-200">5.0</span>
</div>
<button class="mt-4 w-full py-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all border border-primary/20">
                            View Detail
                        </button>
</div>
</div>
</div>
<!-- Pagination -->
<div class="flex items-center justify-center p-12">
<nav class="flex items-center gap-2">
<a class="flex h-10 w-10 items-center justify-center text-white hover:bg-white/10 rounded-lg transition-colors" href="#">
<span class="material-symbols-outlined">chevron_left</span>
</a>
<a class="text-sm font-bold flex h-10 w-10 items-center justify-center text-white rounded-lg bg-primary" href="#">1</a>
<a class="text-sm font-medium flex h-10 w-10 items-center justify-center text-white/70 hover:bg-white/10 rounded-lg transition-all" href="#">2</a>
<a class="text-sm font-medium flex h-10 w-10 items-center justify-center text-white/70 hover:bg-white/10 rounded-lg transition-all" href="#">3</a>
<span class="text-sm font-medium flex h-10 w-10 items-center justify-center text-white/40">...</span>
<a class="text-sm font-medium flex h-10 w-10 items-center justify-center text-white/70 hover:bg-white/10 rounded-lg transition-all" href="#">24</a>
<a class="flex h-10 w-10 items-center justify-center text-white hover:bg-white/10 rounded-lg transition-colors" href="#">
<span class="material-symbols-outlined">chevron_right</span>
</a>
</nav>
</div>
</main>
<!-- Footer -->
<footer class="mt-auto px-4 lg:px-40 py-8 border-t border-white/5 bg-background-dark/50">
<div class="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
<div class="flex items-center gap-2 text-white/40">
<span class="material-symbols-outlined">auto_stories</span>
<span class="text-sm font-medium">© 2024 BookShelf Digital Library</span>
</div>
<div class="flex gap-8">
<a class="text-xs text-white/40 hover:text-primary transition-colors" href="#">Privacy Policy</a>
<a class="text-xs text-white/40 hover:text-primary transition-colors" href="#">Terms of Service</a>
<a class="text-xs text-white/40 hover:text-primary transition-colors" href="#">Contact Support</a>
</div>
</div>
</footer>
</div>
</body></html>