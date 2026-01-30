<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login di Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
            background: rgba(25, 38, 51, 0.45);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body class="bg-background-dark font-display text-white min-h-screen flex items-center justify-center px-4">

    <main class="w-full max-w-[420px]">
        <div class="glass-card rounded-xl p-7 shadow-2xl">

            <!-- HEADER -->
            <div class="mb-6 text-center">
                <h2 class="text-4xl font-semibold mb-1">Login Jokopus</h2>
                <p class="text-sm text-[#92adc9]">Masuk ke akun kamu</p>
            </div>

            <hr class="border-white/10 mb-6">

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="mb-4 text-sm text-green-400 bg-green-400/10 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ERROR -->
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-400 bg-red-400/10 p-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="/login" class="flex flex-col gap-4">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="text-sm mb-1 block">Email</label>
                    <input name="email" type="email" required
                        class="w-full h-11 rounded-lg bg-[#192633] border border-[#324d67] px-3 text-sm focus:border-primary focus:ring-0"
                        placeholder="email@example.com">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm mb-1 block">Password</label>
                    <input name="password" type="password" required
                        class="w-full h-11 rounded-lg bg-[#192633] border border-[#324d67] px-3 text-sm focus:border-primary focus:ring-0"
                        placeholder="••••••••">
                </div>

                <!-- SUBMIT -->
                <button type="submit"
                    class="mt-3 h-11 rounded-lg bg-primary font-semibold hover:bg-primary/90 transition">
                    Login
                </button>

                <!-- REGISTER LINK -->
                <div class="mt-6 flex justify-center">
                    <a href="/daftar"
                        class="text-sm font-medium text-blue-400 transition hover:text-blue-300 hover:underline">
                        Belum punya akun? <span class="font-semibold">Daftar</span>
                    </a>
                </div>

                <!-- BACK -->
                <a href="/"
                    class="mt-3 inline-flex h-11 items-center justify-center rounded-lg bg-primary/20 px-4 font-semibold transition hover:bg-primary/30">
                    Kembali
                </a>
            </form>
        </div>
    </main>
</body>

</html>