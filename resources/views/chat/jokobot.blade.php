<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jokopus</title>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
            background: rgba(16, 25, 34, 0.7);
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

        <header class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-[1200px]">
            <div class="glass border border-white/10 rounded-2xl px-6 py-3 shadow-2xl shadow-black/20">
                <div class="flex items-center justify-between">

                    <div onclick="window.location.href='/'" class="flex items-center gap-3 cursor-pointer group">
                        <div
                            class="size-9 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                            <svg viewBox="0 0 48 48" fill="currentColor" class="size-6">
                                <path
                                    d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078V7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094V42.4379Z" />
                            </svg>
                        </div>
                        <h2
                            class="text-xl font-black tracking-tighter bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                            JOKOPUS
                        </h2>
                    </div>

                    <nav
                        class="hidden md:flex items-center bg-white/5 rounded-full px-2 py-1 border border-white/5 shadow-inner">
                        <a href="/"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('/') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Home
                        </a>

                        <a href="/buku"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('buku') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Book
                        </a>

                        <a href="/chat"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
       {{ request()->is('chat*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">
                            Chat
                        </a>
                    </nav>

                    <div class="flex items-center gap-4">
                        @if (session()->has('user'))
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

                            <div class="flex items-center gap-3 pl-4 border-l border-white/10">
                                <div class="hidden sm:block text-right">
                                    <p class="text-[10px] font-black text-primary uppercase tracking-widest">
                                        {{ session('user.role') == 1 ? 'Admin' : 'Member' }}
                                    </p>
                                    <p class="text-xs font-bold text-white truncate max-w-[100px]">
                                        {{ session('user.name') }}
                                    </p>
                                </div>
                                <div onclick="window.location.href='/dashboard'"
                                    class="h-10 w-10 rounded-xl border-2 border-white/10 bg-center bg-cover hover:border-primary hover:scale-105 transition-all cursor-pointer shadow-lg"
                                    style="background-image: url('{{ $displayPhoto }}');">
                                </div>
                            </div>
                        @else
                            <a href="/daftar"
                                class="h-10 px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-widest flex items-center justify-center hover:shadow-[0_0_20px_rgba(19,127,236,0.4)] hover:scale-105 active:scale-95 transition-all">
                                Sign In
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </header>

        <main class="flex-1 mt-24 px-4 flex flex-col items-center overflow-hidden">
            <div class="w-full max-w-[900px] flex flex-col h-[calc(100vh-120px)]">

                <div id="chat-container"
                    class="flex-1 overflow-y-auto space-y-6 pb-6 pr-2 transition-all duration-500 scrollbar-hide">

                    {{-- 1. Kondisi Welcome Text: Hanya muncul jika history kosong --}}
                    @if($history->isEmpty())
                        <div id="welcome-text" class="text-center my-10">
                            <h1 class="text-4xl md:text-6xl font-black tracking-tighter mb-4">
                                Tanya <span class="text-primary">Jokobot AI</span>
                            </h1>
                            <p class="pb-4 text-slate-400 text-sm md:text-base">Eksplorasi koleksi buku lewat percakapan.
                            </p>
                        </div>
                    @endif

                    {{-- 2. Pesan Sambutan Default (Selalu muncul di paling atas jika mau) --}}
                    <div class="flex gap-4 items-start animate-fade-in">
                        <div
                            class="size-10 rounded-xl bg-primary/20 flex-none flex items-center justify-center text-primary border border-primary/20">
                            <span class="material-symbols-outlined">smart_toy</span>
                        </div>
                        <div class="glass p-4 rounded-2xl rounded-tl-none max-w-[80%] border-white/5 shadow-xl">
                            <p class="text-sm leading-relaxed text-slate-200">
                                Halo! Saya asisten perpustakaan Jokopus. Ada yang bisa saya bantu cari di database hari
                                ini?
                            </p>
                        </div>
                    </div>

                    @foreach($history as $msg)
                        @php $isBot = ($msg->role === 'assistant'); @endphp
                        <div class="flex gap-4 items-start animate-fade-in {{ !$isBot ? 'flex-row-reverse' : '' }} mb-4">
                            <div
                                class="size-10 rounded-xl {{ $isBot ? 'bg-primary/20 text-primary' : 'border border-white/10' }} flex-none flex items-center justify-center overflow-hidden">
                                @if($isBot)
                                    <span class="material-symbols-outlined">smart_toy</span>
                                @else
                                    <img src="{{ $displayPhoto }}" class="size-full object-cover rounded-xl" alt="Profile">
                                @endif
                            </div>
                            <div
                                class="glass p-4 rounded-2xl {{ $isBot ? 'rounded-tl-none' : 'rounded-tr-none bg-primary/10' }} max-w-[80%] border-white/5 shadow-xl">
                                {{-- Tambahkan class 'markdown-content' agar bisa ditangkap JS --}}
                                <div
                                    class="prose prose-sm prose-invert max-w-none text-slate-200 {{ $isBot ? 'markdown-content' : '' }}">
                                    {{ $msg->content }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full bg-background-dark/50 pt-4 pb-6">
                    <form id="chat-form"
                        class="glass p-2 rounded-2xl border-white/10 shadow-2xl flex items-center gap-2">
                        <input type="text" id="user-input"
                            placeholder="Tanya stok buku, peminjaman, atau rekomendasi..."
                            class="flex-1 bg-transparent border-none focus:ring-0 text-sm px-4 py-2 placeholder:text-slate-500 text-white"
                            required>
                        <button type="submit" id="send-btn"
                            class="size-11 flex-none bg-primary rounded-xl flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-lg shadow-primary/30">
                            <span class="material-symbols-outlined text-white">send</span>
                        </button>
                    </form>
                    <p
                        class="text-[10px] text-center text-slate-500 mt-3 uppercase tracking-widest font-bold opacity-50">
                        AI ASSISTEN JOKOPUS
                    </p>
                </div>
            </div>
        </main>
    </div>
    <script>
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const chatContainer = document.getElementById('chat-container');
        const sendBtn = document.getElementById('send-btn');
        const welcomeText = document.getElementById('welcome-text');

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = userInput.value.trim();
            if (!message || sendBtn.disabled) return;

            userInput.disabled = true;
            sendBtn.disabled = true;
            sendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            userInput.placeholder = "Sabar ya, Jokopus sedang berpikir...";
            if (welcomeText) welcomeText.classList.add('hidden');
            appendMessage('user', message);
            userInput.value = '';
            const loadingId = 'loading-' + Date.now();
            appendMessage('bot', 'Thinking...', loadingId);

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                document.getElementById(loadingId).parentElement.parentElement.remove();
                appendMessage('bot', data.answer);

            } catch (error) {
                document.getElementById(loadingId).parentElement.parentElement.remove();
                appendMessage('bot', 'Waduh, koneksi ke server terputus. Coba lagi ya!');
            } finally {
                userInput.disabled = false;
                sendBtn.disabled = false;
                sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                userInput.placeholder = "Tanya stok buku, peminjaman, atau rekomendasi...";
                userInput.focus();
            }
        });

        function appendMessage(sender, text, id = null) {
            const isBot = sender === 'bot';
            const formattedText = isBot ? marked.parse(text) : text;

            const avatarHtml = isBot
                ? `<span class="material-symbols-outlined">smart_toy</span>`
                : `<img src="${userProfilePhoto}" class="size-full object-cover rounded-xl" alt="Profile">`;

            const msgHtml = `
            <div class="flex gap-4 items-start animate-fade-in ${!isBot ? 'flex-row-reverse' : ''} mb-4">
                <div class="size-10 rounded-xl ${isBot ? 'bg-primary/20 text-primary' : 'border border-white/10'} flex-none flex items-center justify-center overflow-hidden">
                    ${avatarHtml}
                </div>
                <div class="glass p-4 rounded-2xl ${isBot ? 'rounded-tl-none' : 'rounded-tr-none bg-primary/10'} max-w-[80%] border-white/5 shadow-xl">
                    <div class="prose prose-sm prose-invert max-w-none text-slate-200" ${id ? `id="${id}"` : ''}>
                        ${formattedText}
                    </div>
                </div>
            </div>`;

            chatContainer.insertAdjacentHTML('beforeend', msgHtml);
            setTimeout(() => {
                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }, 100);
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.markdown-content').forEach(el => {
                el.innerHTML = marked.parse(el.textContent.trim());
            });
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: 'instant'
            });
        });

        window.userProfilePhoto = "{{ $displayPhoto }}";
    </script>
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease forwards;
        }

        .prose strong {
            color: #137fec;
            font-weight: 700;
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .prose ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
        }

        .prose p {
            margin-bottom: 0.5rem;
        }

        .prose code {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
        }
    </style>
</body>

</html>