<!DOCTYPE html>
<html class="dark">
<head>
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#101922] min-h-screen flex items-center justify-center text-white">

<div class="w-full max-w-sm bg-[#192633] p-6 rounded-xl">

    <h2 class="text-2xl font-bold mb-4 text-center">🔐 Verifikasi OTP</h2>

    @php
        $email = session('success') ?? session('register_data.email');
        $maskedEmail = $email
            ? substr($email, 0, 2) . '*****' . strstr($email, '@')
            : '';
    @endphp

    @if ($maskedEmail)
        <p class="text-sm text-green-400 mb-3 text-center">
            Kode OTP telah dikirim ke
            <span class="font-semibold">{{ $maskedEmail }}</span>
        </p>
    @endif

    @if ($errors->any())
        <p class="text-sm text-red-400 mb-3 text-center">
            {{ $errors->first() }}
        </p>
    @endif

    <form method="POST" action="/otp/verify" class="space-y-4">
        @csrf

        <input
            name="otp"
            type="text"
            placeholder="Masukkan OTP"
            class="w-full h-11 rounded bg-[#101922] border border-white/20 px-3 text-sm">

        <button
            type="submit"
            class="w-full h-11 bg-blue-600 rounded font-semibold hover:bg-blue-700">
            Verifikasi
        </button>
    </form>

    <form method="POST" action="/otp/resend" class="mt-4 text-center">
        @csrf
        <button
            id="resendBtn"
            class="text-sm text-blue-400 hover:underline disabled:text-gray-500"
            disabled>
            Kirim ulang dalam: (<span id="countdown">60</span>s)
        </button>
    </form>

    <p class="text-1xl pt-3 font-bold mb-4 text-center" id="expired"></p>

</div>

<script>
    let time = 60;
    const btn = document.getElementById('resendBtn');
    const cd = document.getElementById('countdown');

    const timer = setInterval(() => {
        time--;
        cd.innerText = time;

        if (time <= 0) {
            clearInterval(timer);
            btn.disabled = false;
            btn.innerText = 'Kirim ulang OTP';
        }
    }, 1000);

      const expired = document.getElementById('expired');

    if (expired) {
    let remaining = {{ max(0, 180 - now()->diffInSeconds(session('otp_generated_at'))) }};

    const timer = setInterval(() => {
      if (remaining <= 0) {
        expired.innerText = 'OTP telah kedaluwarsa';
        clearInterval(timer);
        return;
      }

      expired.innerText = `OTP berlaku ${remaining} detik`;
      remaining--;
    }, 1000);
  }
</script>
</body>
</html>