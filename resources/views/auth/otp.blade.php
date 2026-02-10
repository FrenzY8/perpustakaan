<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi OTP - Jokopus</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-25..0"
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
        .bg-fullscreen {
            background-image: linear-gradient(rgba(16, 25, 34, 0.8), rgba(16, 25, 34, 0.8)),
                url('https://media.istockphoto.com/id/1339845062/photo/reading-room-or-library-interior-with-leather-armchair-bookshelf-and-floor-lamp.jpg?s=612x612&w=0&k=20&c=2ghOW2DCvb49Up3D0eFeVzv1kbSMjUq-_psohUYeZB0=');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-card {
            background: rgba(25, 38, 51, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body
    class="bg-background-dark bg-fullscreen font-display text-white min-h-screen flex items-center justify-center p-4">

    <main class="w-full max-w-[420px] z-10">
        <div class="glass-card rounded-[2rem] p-8 md:p-10 relative overflow-hidden">

            <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 blur-3xl rounded-full"></div>

            <div class="mb-8 text-center relative z-10">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/20 text-primary mb-5 ring-1 ring-primary/30 rotate-3">
                    <span class="material-symbols-outlined text-3xl">shield_person</span>
                </div>
                <h2 class="text-3xl font-extrabold mb-3">Verifikasi OTP</h2>

                @php
                    $email = session('success') ?? session('register_data.email');
                    $maskedEmail = $email
                        ? substr($email, 0, 2) . '*****' . strstr($email, '@')
                        : 'email Anda';
                @endphp

                <p class="text-sm text-slate-300 leading-relaxed">
                    Kami telah mengirimkan kode OTP ke <br>
                    <span class="text-white text-md font-semibold underline decoration-primary/40">{{ $maskedEmail }}</span>
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 text-sm text-red-400 bg-red-400/10 border border-red-400/20 p-3 rounded-xl text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/otp/verify" class="space-y-6 relative z-10">
                @csrf

                <div class="space-y-3">
                    <input name="otp" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" autofocus
                        class="w-full h-16 rounded-2xl bg-black/40 border border-white/10 px-3 text-center text-3xl font-black tracking-[0.4em] focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none placeholder:text-slate-700 placeholder:tracking-normal"
                        placeholder="000000">

                    <div class="flex justify-center">
                        <span
                            class="inline-flex text-white items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] bg-white/5 px-3 py-1 rounded-full border border-white/5"
                            id="expired">
                            Memuat status...
                        </span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full h-12 rounded-2xl bg-primary font-bold text-white hover:bg-blue-600 active:scale-[0.97] transition-all shadow-xl shadow-primary/25">
                    Verifikasi Sekarang
                </button>
            </form>

            <div class="mt-8 flex flex-col gap-5 text-center relative z-10">
                <form method="POST" action="/otp/resend">
                    @csrf
                    <button id="resendBtn"
                        class="text-sm text-white font-semibold hover:text-blue-300 disabled:text-slate-450 transition-all"
                        disabled>
                        Kirim ulang dalam (<span id="countdown">60</span>s)
                    </button>
                </form>

                <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent w-full"></div>

                <a href="/login"
                    class="group text-sm font-medium text-slate-400 hover:text-white flex items-center justify-center gap-2 transition">
                    <span
                        class="material-symbols-outlined !text-[18px] transition-transform group-hover:-translate-x-1">
                        arrow_back
                    </span>
                    Kembali ke Login
                </a>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-slate-500 font-medium tracking-wide">
            &copy; 2026 Jokopus OTP
        </p>
    </main>

    <script>
        // Logika Resend Timer
        let time = 60;
        const btn = document.getElementById('resendBtn');
        const cd = document.getElementById('countdown');
        const timerResend = setInterval(() => {
            time--;
            if (cd) cd.innerText = time;
            if (time <= 0) {
                clearInterval(timerResend);
                btn.disabled = false;
                btn.innerText = 'Kirim ulang kode OTP';
            }
        }, 1000);

        // Logika Expired Timer
        const expired = document.getElementById('expired');
        if (expired) {
            let remaining = {{ max(0, 180 - now()->diffInSeconds(session('otp_generated_at', now()))) }};
            const timerExp = setInterval(() => {
                if (remaining <= 0) {
                    expired.innerHTML = '<span class="text-red-400">OTP KEDALUWARSA</span>';
                    clearInterval(timerExp);
                    return;
                }
                expired.innerText = `BERLAKU: ${remaining} DETIK`;
                remaining--;
            }, 1000);
        }
    </script>
</body>

</html>