<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profile Settings - Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap"
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
                        display: ["Inter"],
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
            background: rgba(35, 54, 72, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
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
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark min-h-screen text-slate-900 dark:text-white">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            <main class="flex-1 flex flex-col items-center">
                <form action="/profile/update" method="POST" enctype="multipart/form-data"
                    class="w-full max-w-[960px] px-6 md:px-10 py-8">
                    @csrf
                    <div class="flex flex-wrap justify-between gap-3 mb-8">
                        <div class="flex min-w-72 flex-col gap-2">
                            <h1 class="text-slate-900 dark:text-white text-4xl font-black">Settings</h1>
                            <p class="text-slate-500">Manage your profile and account settings.</p>
                        </div>
                    </div>

                    <div class="glass-panel rounded-xl overflow-hidden shadow-2xl">
                        <div class="flex p-6 md:p-8 border-b border-white/5">
                            <div class="flex w-full flex-col gap-6 md:flex-row md:justify-between md:items-center">
                                <div class="flex items-center gap-6">
                                    <div class="relative group">
                                        @php
                                            $photoPath = $user->profile_photo ? asset('storage/avatars/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name);
                                        @endphp
                                        <div id="photo-preview"
                                            class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-24 w-24 md:h-32 md:w-32 ring-4 ring-primary/30"
                                            style="background-image: url('{{ $photoPath }}');">
                                        </div>
                                        <input type="file" name="photo" id="photo-input" class="hidden" accept="image/*"
                                            onchange="previewImage(this)">
                                        <div onclick="document.getElementById('photo-input').click()"
                                            class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity cursor-pointer">
                                            <span class="material-symbols-outlined text-white">photo_camera</span>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-white text-4xl font-bold">{{ $user->name }}</p>
                                        <p class="text-slate-400">{{ $user->email }}</p>
                                        <span
                                            class="mt-2 inline-block px-2 py-0.5 rounded bg-primary/10 text-primary text-1xl font-semibold">{{ session('user.role') == 1 ? 'Admin' : 'Member' }}</span>
                                    </div>
                                </div>
                                <button type="button" onclick="document.getElementById('photo-input').click()"
                                    class="bg-primary px-6 py-2 rounded-lg font-bold text-sm">Update Photo</button>
                            </div>
                        </div>

                        <div class="p-6 md:p-8 space-y-10">
                            <section>
                                <h2 class="text-white text-xl font-bold mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">person</span> Account Details
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-2">
                                        <label class="text-sm font-medium text-slate-300">Full Name</label>
                                        <input name="name"
                                            class="bg-[#111a22] border-[#233648] rounded-lg text-white px-4 py-2.5"
                                            type="text" value="{{ $user->name }}" />
                                    </div>
                                    <div class="flex flex-col gap-2 opacity-60">
                                        <label class="text-sm font-medium text-slate-300">Email (Cannot change)</label>
                                        <input class="bg-[#111a22] border-[#233648] rounded-lg text-white px-4 py-2.5"
                                            type="email" value="{{ $user->email }}" readonly />
                                    </div>
                                </div>
                            </section>

                            <hr class="border-white/5" />

                            <section>
                                <h2 class="text-white text-xl font-bold mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">lock</span> Change Password
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-2">
                                        <label class="text-sm font-medium text-slate-300">New Password</label>
                                        <input name="password"
                                            class="bg-[#111a22] border-[#233648] rounded-lg text-white px-4 py-2.5"
                                            type="password" placeholder="••••••••" />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label class="text-sm font-medium text-slate-300">Confirm Password</label>
                                        <input name="password_confirmation"
                                            class="bg-[#111a22] border-[#233648] rounded-lg text-white px-4 py-2.5"
                                            type="password" placeholder="••••••••" />
                                    </div>
                                </div>
                            </section>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    class="glass-panel group border-white/5 hover:border-white/20 transition-all duration-500 rounded-2xl p-6 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="size-12 rounded-full bg-white/5 flex items-center justify-center text-slate-400 group-hover:text-white transition-colors">
                                            <span class="material-symbols-outlined">logout</span>
                                        </div>
                                        <div>
                                            <h3 class="text-white font-bold text-sm">Sign Out</h3>
                                            <p class="text-slate-500 text-xs">Keluar dari sesi ini</p>
                                        </div>
                                    </div>
                                    <a href="/logout"
                                        class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white text-xs font-bold transition-all">
                                        Logout
                                    </a>
                                </div>
                            </div>
                            </section>

                            <div class="flex items-center justify-end gap-4 pt-6">
                                <a href="/dashboard" class="text-slate-400 hover:text-white transition-colors">Kembali
                                    ke Dashboard</a>
                                <button type="submit"
                                    class="px-8 py-2.5 rounded-lg bg-primary text-white font-bold shadow-lg hover:scale-[1.02] transition-all">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </main>

            <script>
                function previewImage(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            document.getElementById('photo-preview').style.backgroundImage = "url('" + e.target.result + "')";
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>

            @if(session('success_update') || session('error'))
                @php
                    $isError = session('error');
                    $bgClass = $isError ? 'border-red-500/50' : 'border-primary/30';
                    $icon = $isError ? 'error' : 'check_circle';
                    $iconCol = $isError ? 'text-red-500' : 'text-primary';
                    $title = $isError ? 'Failed' : 'Profile Updated';
                    $msg = $isError ? session('error') : 'Profile berhasil diperbarui.';
                @endphp

                <div id="toast"
                    class="fixed bottom-8 right-8 z-[100] transform translate-y-0 opacity-100 transition-all duration-300">
                    <div
                        class="bg-slate-900 dark:bg-[#233648] border {{ $bgClass }} rounded-xl px-5 py-4 shadow-2xl flex items-center gap-4">
                        <div class="h-8 w-8 rounded-full bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-outlined {{ $iconCol }} text-xl">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="text-white text-sm font-bold">{{ $title }}</p>
                            <p class="text-[#92adc9] text-xs">{{ $msg }}</p>
                        </div>
                        <button onclick="document.getElementById('toast').remove()"
                            class="ml-4 text-slate-400 hover:text-white">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                </div>

                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toast');
                        if (toast) {
                            toast.style.opacity = '0';
                            toast.style.transform = 'translateY(20px)';
                            setTimeout(() => toast.remove(), 300);
                        }
                    }, 3000);
                </script>
            @endif

        </div>
    </div>
</body>

</html>