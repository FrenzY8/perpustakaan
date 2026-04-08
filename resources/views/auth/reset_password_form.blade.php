<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login di Jokopus</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-25..0" />
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#137fec",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(25, 38, 51, 0.6);
            backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.05), 0 20px 50px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body
    class="bg-background-dark font-display text-white min-h-screen flex items-center justify-center px-4 bg-image bg-cover bg-center bg-no-repeat">

    <main class="w-full max-w-[420px] z-10">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-background-dark/85 z-10"></div>

            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="{{ asset('video/library_5.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="glass-card rounded-2xl p-8 shadow-2xl">

            <div class="mb-8 text-center">
                <h2 class="text-4xl font-bold tracking-tight mb-2">Reset Password</h2>
                <p class="text-sm text-slate-400">Reset Password mu</p>
            </div>

            @if(session('success'))
                <div
                    class="mb-4 text-sm text-green-400 bg-green-400/10 border border-green-400/20 p-3 rounded-lg text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-400 bg-red-400/10 border border-red-400/20 p-3 rounded-lg text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/update_password" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ request()->token }}">
                <input type="hidden" name="email" value="{{ request()->email }}">

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Password Baru</label>
                    <input name="password" type="password" required
                        class="w-full h-11 rounded-xl bg-slate-900/50 border border-slate-700 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                        placeholder="••••••••">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Konfirmasi Password Baru</label>
                    <input name="password_confirmation" type="password" required
                        class="w-full h-11 rounded-xl bg-slate-900/50 border border-slate-700 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full h-11 mt-2 rounded-xl bg-primary font-bold text-white hover:bg-blue-600 active:scale-[0.98] transition-all shadow-lg shadow-primary/20">
                    Perbarui Password
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-xs text-slate-500">
            &copy; 2026 Jokopus
        </p>
    </main>

</body>

</html>