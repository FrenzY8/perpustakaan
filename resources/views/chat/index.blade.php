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
                        "background-dark": "#101922"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "1rem",
                        xl: "1.5rem",
                        full: "9999px"
                    }
                }
            }
        }
    </script>

    <style>
        .glass {
            background: rgba(16, 25, 34, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1)
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .animate-fade-in {
            animation: fadeIn .4s ease forwards
        }

        .prose strong {
            color: #137fec;
            font-weight: 700
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin: .5rem 0
        }

        .prose ol {
            list-style-type: decimal;
            padding-left: 1.25rem
        }

        .prose p {
            margin-bottom: .5rem
        }

        .prose code {
            background: rgba(255, 255, 255, .1);
            padding: .2rem .4rem;
            border-radius: .25rem;
            font-size: .8rem
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
                            JOKOPUS</h2>
                    </div>

                    <nav
                        class="hidden md:flex items-center bg-white/5 rounded-full px-2 py-1 border border-white/5 shadow-inner">
                        <a href="/"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest {{ request()->is('/') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">Home</a>
                        <a href="/buku"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest {{ request()->is('buku') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">Book</a>
                        <a href="/dashboard"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest {{ request()->is('dashboard*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">Dashboard</a>
                        <a href="/chat/jokobot"
                            class="px-5 py-2 text-[11px] font-bold uppercase tracking-widest {{ request()->is('chat*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">Chat</a>
                    </nav>

                    <div class="flex items-center gap-4">
                        @if(session()->has('user'))
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
                                    style="background-image:url('{{ $displayPhoto }}')"></div>
                            </div>
                        @else
                            <a href="/daftar"
                                class="h-10 px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-widest flex items-center justify-center hover:shadow-[0_0_20px_rgba(19,127,236,0.4)] hover:scale-105 active:scale-95 transition-all">Sign
                                In</a>
                        @endif
                    </div>

                </div>
            </div>
        </header>

        <main class="flex-1 mt-24 px-4 flex justify-center overflow-hidden">
            <div class="w-full max-w-[1200px] flex gap-4 h-[calc(100vh-120px)] mb-6">

                <aside class="hidden md:flex flex-col w-80 glass rounded-3xl overflow-hidden border-white/5">
                    <div class="p-6 border-b border-white/10">
                        <h3 class="text-xl font-black tracking-tighter">CHAT <span class="text-primary">TERSEDIA</span>
                        </h3>
                    </div>

                    <div class="flex-1 overflow-y-auto scrollbar-hide p-2 space-y-1">
                        <div onclick="window.location.href='/chat/jokobot'"
                            // "loadChat(0,'Jokobot','https://ui-avatars.com/api/?name=Jokobot&background=137fec&color=fff')"
                            class="user-card flex items-center gap-3 p-3 rounded-2xl hover:bg-white/5 transition-all cursor-pointer group"
                            data-user-id="0">
                            <div class="relative size-11 flex-none">
                                <img src="https://ui-avatars.com/api/?name=Jokobot&background=137fec&color=fff"
                                    class="rounded-xl object-cover w-full h-full shadow-md">
                                <div
                                    class="absolute -bottom-1 -right-1 size-3 bg-primary border-2 border-background-dark rounded-full">
                                </div>
                            </div>

                            <div class="overflow-hidden">
                                <p class="text-sm font-bold truncate group-hover:text-primary transition-colors">
                                    JOKOBOT
                                </p>
                                <p class="text-[10px] text-primary uppercase font-black tracking-widest">
                                    System
                                </p>
                            </div>
                        </div>
                        @foreach($users as $u)
                            @php
                                $photo = $u->profile_photo;
                                if ($photo && (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://'))) {
                                    $displayPhoto = $photo;
                                } elseif ($photo && file_exists(storage_path('app/public/avatars/' . $photo))) {
                                    $displayPhoto = asset('storage/avatars/' . $photo);
                                } else {
                                    $displayPhoto = "https://ui-avatars.com/api/?name=" . urlencode($u->name) . "&background=137fec&color=fff";
                                }
                            @endphp

                            <div onclick="loadChat({{ $u->id }},'{{ $u->name }}','{{ $displayPhoto }}')"
                                class="user-card flex items-center gap-3 p-3 rounded-2xl hover:bg-white/5 transition-all cursor-pointer group"
                                data-user-id="{{ $u->id }}">
                                <div class="relative size-11 flex-none">
                                    <img src="{{ $displayPhoto }}" class="rounded-xl object-cover w-full h-full shadow-md">
                                    <div
                                        class="absolute -bottom-1 -right-1 size-3 bg-green-500 border-2 border-background-dark rounded-full">
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold truncate group-hover:text-primary transition-colors">
                                        {{ $u->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">
                                        {{ $u->role == 1 ? 'Admin' : 'Member' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </aside>

                <section id="main-chat-area"
                    class="flex-1 flex flex-col glass rounded-3xl border-white/5 overflow-hidden opacity-100 pointer-events-none">
                    <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between bg-white/5">
                        <div class="flex items-center gap-3">
                            <img id="active-avatar" src="" class="size-9 rounded-lg hidden">
                            <div>
                                <h4 id="active-name" class="text-sm font-bold">Pilih teman untuk chat</h4>
                                <p id="active-status"
                                    class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Offline</p>
                            </div>
                        </div>
                    </div>

                    <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-4 scrollbar-hide flex flex-col">
                        <div class="h-full flex flex-col items-center justify-center text-slate-500 space-y-2">
                            <span class="material-symbols-outlined text-8xl">forum</span>
                            <p class="text-xl bold italic">Mulai chat dengan memilih tujuan Chat di sebelah kiri.</p>
                        </div>
                    </div>

                    <div class="p-4 bg-white/5 border-t border-white/10">
                        <form id="chat-form-user" class="flex items-center gap-3">
                            <input type="hidden" id="receiver-id">
                            <input type="text" id="user-input" placeholder="Tulis pesan..."
                                class="flex-1 bg-white/5 border-none rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-primary/50 text-white">
                            <button type="submit"
                                class="size-11 flex-none bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/30">
                                <span class="material-symbols-outlined text-white">send</span>
                            </button>
                        </form>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <script>
        let currentReceiverId = null
        let pollingInterval = null
        let lastMessageCount = 0
        const currentUserId ={{ session('user.id') }}

            async function loadChat(userId, userName, userAvatar) {
                lastMessageCount = 0
                currentReceiverId = userId

                document.getElementById('main-chat-area').classList.remove('opacity-50', 'pointer-events-none')
                document.getElementById('active-name').innerText = userName
                document.getElementById('active-avatar').src = userAvatar
                document.getElementById('active-avatar').classList.remove('hidden')
                document.getElementById('active-status').innerText = "Online"
                document.getElementById('active-status').classList.replace('text-slate-500', 'text-green-500')
                document.getElementById('receiver-id').value = userId

                document.querySelectorAll('.user-card').forEach(el => el.classList.remove('bg-primary/10', 'border', 'border-primary/20'))
                const activeCard = document.querySelector(`.user-card[data-user-id="${userId}"]`)
                if (activeCard) activeCard.classList.add('bg-primary/10', 'border', 'border-primary/20')

                fetchMessages()

                if (pollingInterval) clearInterval(pollingInterval)
                pollingInterval = setInterval(fetchMessages, 3000)
            }

        async function fetchMessages() {
            if (!currentReceiverId) return

            try {
                const response = await fetch(`/chat/history/${currentReceiverId}`)
                const messages = await response.json()

                if (messages.length === lastMessageCount) return

                const container = document.getElementById('chat-container')
                container.innerHTML = ''

                messages.forEach((msg) => {
                    const isMe = msg.sender_id == currentUserId;
                    let displayMessage = msg.message;
                    const msgHtml = `
                    <div class="flex gap-3 max-w-[85%] ${isMe ? 'ml-auto flex-row-reverse' : ''} mb-4">
                        <div class="${isMe ? 'bg-primary/20 border border-primary/30 rounded-tr-none' : 'bg-slate-800/80 border-2 border-slate-700 rounded-tl-none'} p-3 rounded-2xl shadow-xl">
                            ${displayMessage}
                            <span class="text-[9px] ${isMe ? 'text-primary/70' : 'text-slate-500'} mt-1 block ${isMe ? 'text-right' : ''}">
                                ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                            </span>
                        </div>
                    </div> `;
                    container.insertAdjacentHTML('beforeend', msgHtml);
                });

                lastMessageCount = messages.length

                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                })
            } catch (e) { console.error(e) }
        }

        document.getElementById('chat-form-user').addEventListener('submit', async e => {
            e.preventDefault()

            const input = document.getElementById('user-input')
            const message = input.value.trim()
            const receiverId = document.getElementById('receiver-id').value

            if (!message || !receiverId) return

            input.value = ''

            try {
                await fetch('/chat/send-user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        message: message
                    })
                })
                fetchMessages()
            } catch (e) { console.error(e) }
        })
    </script>

</body>

</html>