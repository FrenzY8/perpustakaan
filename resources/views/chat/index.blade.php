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
                            class="relative px-5 py-2 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 
   {{ request()->is('chat*') ? 'text-white bg-primary shadow-[0_0_15px_rgba(19,127,236,0.3)] rounded-full' : 'text-slate-400 hover:text-white' }}">

                            Chat

                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white shadow-lg">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </nav>

                    <div class="flex items-center gap-4">
                        @if (session()->has('user'))
                            @php
                                $unreadCount = DB::table('notifications')
                                    ->where('user_id', session('user.id'))
                                    ->where('is_read', 0)
                                    ->count();
                              @endphp

                            <button onclick="window.location.href='/notifications'"
                                class="relative p-2 text-slate-400 hover:text-white transition-all duration-300 group hover:scale-110 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-2 right-2 flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                                    </span>
                                @endif
                            </button>
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
                                        {{ session('user.name') }}</p>
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
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold truncate group-hover:text-primary transition-colors">
                                        {{ $u->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">
                                        {{ $u->role == 1 ? 'Admin' : 'Member' }}
                                    </p>
                                </div>
                                @if($u->unread_count > 0)
                                    <div
                                        class="notif-badge flex-none min-w-[20px] h-5 px-1.5 bg-primary rounded-full flex items-center justify-center shadow-[0_0_12px_rgba(19,127,236,0.5)] animate-bounce-short border border-white/20">
                                        <span class="text-[9px] font-black text-white whitespace-nowrap">
                                            {{ $u->unread_count > 99 ? '99+' : $u->unread_count }} PESAN
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </aside>

                <section id="main-chat-area"
                    class="flex-1 flex flex-col glass rounded-3xl border-white/5 overflow-hidden opacity-100 pointer-events-none">
                    <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between bg-white/5">
                        <div class="flex items-center gap-3">
                            <img id="active-avatar" src="" class="size-9 rounded-lg hidden aspect-square object-cover">
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
                lastMessageCount = 0;
                currentReceiverId = userId;

                const container = document.getElementById('chat-container');
                container.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-slate-500 animate-pulse">
                    <span class="material-symbols-outlined text-4xl">sync</span>
                    <p class="text-sm italic">Memuat pesan...</p>
                </div>`;

                document.getElementById('main-chat-area').classList.remove('opacity-100', 'pointer-events-none');
                document.getElementById('active-name').innerText = userName;
                document.getElementById('active-avatar').src = userAvatar;
                document.getElementById('active-avatar').classList.remove('hidden');
                document.getElementById('active-status').innerText = "";
                document.getElementById('receiver-id').value = userId;

                document.querySelectorAll('.user-card').forEach(el => el.classList.remove('bg-primary/10', 'border', 'border-primary/20'));
                const activeCard = document.querySelector(`.user-card[data-user-id="${userId}"]`);
                if (activeCard) activeCard.classList.add('bg-primary/10', 'border', 'border-primary/20');
                await fetchMessages();
                const userCard = document.querySelector(`.user-card[data-user-id="${userId}"]`);
                if (userCard) {
                    const badge = userCard.querySelector('.notif-badge');
                    if (badge) badge.remove();
                }
                if (pollingInterval) clearInterval(pollingInterval);
                pollingInterval = setInterval(fetchMessages, 1000);
            }

        async function fetchMessages() {
            if (!currentReceiverId) return;
            const requestedId = currentReceiverId;

            try {
                const response = await fetch(`/chat/history/${requestedId}`);
                const messages = await response.json();

                if (requestedId !== currentReceiverId) return;
                if (messages.length === lastMessageCount && messages.length > 0) return;

                const container = document.getElementById('chat-container');
                if (messages.length === 0) {
                    if (lastMessageCount === -1) return;

                    container.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-slate-500/50 space-y-2 animate-fade-in">
                        <span class="material-symbols-outlined text-6xl">chat_bubble_outline</span>
                        <p class="text-sm italic font-medium">Belum ada percakapan di sini.</p>
                        <p class="text-[10px] uppercase tracking-widest">Kirim pesan untuk memulai</p>
                    </div>`;

                    lastMessageCount = -1;
                    return;
                }

                let htmlContent = '';
                messages.forEach((msg) => {
                    const isMe = msg.sender_id == currentUserId;
                    let displayMessage = msg.message;

                    if (msg.message.includes('[INVOICE_PDF]')) {
                        const urlMatch = msg.message.match(/https?:\/\/[^\s]+/);
                        const url = urlMatch ? urlMatch[0] : '#';
                        const cleanMessage = msg.message.replace('[INVOICE_PDF]', '').split('Invoice denda')[0].trim();
                        const fileName = url.split('/').pop() || "Invoice_Denda.pdf";

                        displayMessage = `
                        <div class="flex flex-col gap-3">
                            
                            <div class="group relative flex flex-col bg-gradient-to-br from-white/[0.08] to-transparent backdrop-blur-md rounded-2xl border border-white/10 overflow-hidden max-w-[280px] shadow-2xl transition-all duration-300 hover:border-primary/40 hover:shadow-primary/10">
                                <div class="absolute inset-0 bg-gradient-to-tr from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                
                                <div class="p-4 flex items-center gap-4 relative z-10">
                                    <div class="relative size-12 flex-none">
                                        <div class="absolute inset-0 bg-primary blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                        <div class="relative size-12 bg-slate-900/80 rounded-xl flex items-center justify-center text-primary border border-white/10 shadow-inner">
                                            <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform duration-300">description</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="size-1.5 rounded-full bg-primary animate-pulse"></span>
                                            <p class="text-[9px] font-black text-primary uppercase tracking-[0.1em]">Official Invoice</p>
                                        </div>
                                        <p class="text-xs font-bold text-slate-100 truncate leading-tight tracking-wide">${fileName}</p>
                                        <p class="text-[9px] text-slate-500 font-medium mt-0.5 uppercase">PDF Document</p>
                                    </div>
                                </div>

                                <a href="${url}" target="_blank" 
                                class="relative z-10 flex items-center justify-center gap-2 py-3 bg-white/[0.03] hover:bg-primary text-white text-[10px] font-bold uppercase tracking-widest transition-all duration-300 border-t border-white/5 hover:border-primary group/btn">
                                    <span class="material-symbols-outlined text-sm group-hover/btn:-translate-y-0.5 transition-transform">download_for_offline</span>
                                    Lihat & Unduh PDF
                                </a>
                            </div>
                        </div>`;
                    }
                    if (msg.message.includes('[SHARE_BOOK]')) {
                        const bookId = msg.message.split(': ')[1].split(']')[0];
                        const sisaPesan = msg.message.split(']&&')[1] || "";
                        const dataBuku = sisaPesan.split('&&');

                        const bookTitle = dataBuku[0] || "Detail Buku";
                        const bookCover = dataBuku[1] || "";
                        const userCaption = msg.message.split('[SHARE_BOOK]')[0].trim();

                        displayMessage = `
                        <div class="flex flex-col gap-2 w-full max-w-[260px] animate-fade-in">
                            ${userCaption ? `<p class="text-xs text-slate-300 leading-relaxed px-1">${userCaption}</p>` : ''}
                            
                            <div class="group relative bg-gradient-to-b from-slate-800/40 to-slate-900/90 rounded-3xl border border-white/10 overflow-hidden shadow-2xl transition-all duration-500 hover:border-primary/50 hover:shadow-primary/20">
                                <!-- Glassy Header -->
                                <div class="px-4 py-2.5 flex items-center justify-between bg-white/5 border-b border-white/5 backdrop-blur-md">
                                    <div class="flex items-center gap-2">
                                        <div class="size-6 bg-primary/20 rounded-lg flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[15px] text-primary" style="font-variation-settings: 'FILL' 1">auto_stories</span>
                                        </div>
                                        <p class="text-[10px] font-black text-slate-200 uppercase tracking-widest">Membagikan Buku</p>
                                    </div>
                                    <span class="size-1.5 rounded-full bg-primary animate-pulse"></span>
                                </div>

                                <!-- Cover Image Container -->
                                <div class="relative aspect-[3/4] overflow-hidden">
                                    <img src="${bookCover}" 
                                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 group-hover:rotate-1" 
                                        onerror="this.src='https://placehold.co/400x600/101922/FFF?text=No+Cover'">
                                    
                                    <!-- Overlay Gradient -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                                    
                                    <!-- Floating Action Button (Hover) -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 z-20">
                                        <a href="/detail/${bookId}" class="px-6 py-2.5 bg-primary text-white text-[11px] font-black uppercase rounded-full shadow-[0_10px_20px_rgba(19,127,236,0.4)] hover:scale-105 active:scale-95 transition-all">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>

                                <!-- Info Section -->
                                <div class="p-4 bg-slate-900/80 backdrop-blur-xl relative">
                                    <div class="absolute -top-10 left-0 right-0 h-10 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                    <p class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-primary transition-colors duration-300">
                                        ${bookTitle}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2 pt-2 border-t border-white/5">
                                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-tighter">Jokopus Digital Library</p>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                    htmlContent += `
                    <div class="flex gap-3 max-w-[85%] ${isMe ? 'ml-auto flex-row-reverse' : ''} mb-4 animate-fade-in">
                        <div class="${isMe ? 'bg-primary/20 border border-primary/30 rounded-tr-none' : 'bg-slate-800/80 border-2 border-slate-700 rounded-tl-none'} p-3 rounded-2xl shadow-xl">
                            <div class="text-sm">${displayMessage}</div>
                            <span class="text-[9px] ${isMe ? 'text-primary/70' : 'text-slate-500'} mt-1 block ${isMe ? 'text-right' : ''}">
                                ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                            </span>
                        </div>
                    </div>`;
                });

                container.innerHTML = htmlContent;
                lastMessageCount = messages.length;
                container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });

            } catch (e) {
                console.error("Fetch Error:", e);
            }
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
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const targetUserId = urlParams.get('target_id');

            if (targetUserId) {
                const userCard = document.querySelector(`.user-card[data-user-id="${targetUserId}"]`);

                if (userCard) {
                    userCard.click();
                } else {
                    console.log("User target tidak ditemukan di daftar sidebar.");
                }
            }
        });
        async function updateSidebarNotif() {
            try {
                const response = await fetch('/chat/users/json');
                const users = await response.json();

                users.forEach(u => {
                    const userCard = document.querySelector(`.user-card[data-user-id="${u.id}"]`);
                    if (userCard) {
                        let badge = userCard.querySelector('.notif-badge');

                        if (u.unread_count > 0) {
                            if (!badge) {
                                badge = document.createElement('div');
                                badge.className = "notif-badge flex-none size-5 bg-primary rounded-full flex items-center justify-center shadow-[0_0_10px_rgba(19,127,236,0.4)] animate-bounce-short";
                                userCard.appendChild(badge);
                            }
                            badge.innerHTML = `<span class="text-[10px] font-black text-white">${u.unread_count}</span>`;
                        } else if (badge) {
                            badge.remove();
                        }
                    }
                });
            } catch (e) {
                console.error("Sidebar update error:", e);
            }
        }

        setInterval(updateSidebarNotif, 2000);
    </script>
</body>

</html>