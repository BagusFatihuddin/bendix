@extends('front.layouts.app')

@section('title', 'Global Chat')

@section('content')
<div class="h-[calc(100vh-4rem)] max-w-xl mx-auto flex flex-col bg-white rounded-2xl shadow-lg overflow-hidden">


    {{-- ================= HEADER ================= --}}
    <header class="flex items-center justify-between px-4 py-3 border-b bg-[#004CFF] text-white rounded-t-xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('front.index') }}">
            <div class="w-8 h-8">
                @include('icons.bendix')
            </div>
            </a>
            <div>
                <h1 class="text-sm font-semibold leading-none">Global Chat</h1>
                <p class="text-[11px] opacity-80">Realtime public room</p>
            </div>
        </div>

        <button class="w-6 h-6 opacity-80 hover:opacity-100">
            @include('icons.chat.Ellipsis')
        </button>
    </header>

    {{-- ================= USER INFO ================= --}}
    <div id="userInfo"
         class="hidden px-4 py-2 border-b flex items-center justify-between bg-gray-50 text-sm">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6">
                @include('icons.chat.user')
            </div>
            <span id="currentUsername" class="font-medium"></span>
            <span class="ml-1 w-4 h-4">
                @include('icons.chat.ceklish')
            </span>
        </div>

        <button id="changeUsernameBtn"
                class="flex items-center gap-1 text-xs text-[#004CFF] hover:underline">
            @include('icons.chat.pencil')
            Ganti
        </button>
    </div>

    {{-- ================= USERNAME FORM ================= --}}
    <div id="usernameBox" class="hidden p-4 border-b bg-gray-50">
        <form id="usernameForm" class="flex gap-2">
            <input
                id="usernameInput"
                type="text"
                class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#004CFF]"
                placeholder="Masukkan username dulu..."
                maxlength="20"
                required
            >
            <button
                class="flex items-center gap-1 bg-[#004CFF] text-white px-4 py-2 rounded-lg text-sm">
                @include('icons.chat.ceklish')
                Simpan
            </button>
        </form>
    </div>

    {{-- ================= CHAT AREA ================= --}}
    <div id="messages"
     class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-[#F6F8FF] text-sm">
    </div>

    {{-- ================= CHAT INPUT ================= --}}
    <form id="chatForm"
          class="border-t p-3 flex items-center gap-2 bg-white rounded-b-xl">
        <input
            id="messageInput"
            type="text"
            class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#004CFF]"
            placeholder="Ketik pesan..."
            maxlength="200"
            required
            disabled
        >
        <button
            id="sendBtn"
            type="submit"
            disabled
            class="w-10 h-10 flex items-center justify-center rounded-full bg-[#ffffff] disabled:opacity-50">
            @include('icons.chat.airplane')
        </button>
    </form>

</div>
@endsection

@push('scripts')

{{-- ================= FIREBASE SDK ================= --}}
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>

{{-- ================= CHAT LOGIC ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    if (window.__GLOBAL_CHAT_INIT__) return;
    window.__GLOBAL_CHAT_INIT__ = true;

    /* USER */
    window.GLOBAL_CHAT_USER = window.GLOBAL_CHAT_USER || (() => {
        let id = sessionStorage.getItem('guest_id');
        let name = sessionStorage.getItem('guest_name');

        if (!id) {
            id = 'guest_' + Math.floor(Math.random() * 100000);
            sessionStorage.setItem('guest_id', id);
        }
        return { id, name };
    })();

    const userInfo = document.getElementById('userInfo');
    const currentUsername = document.getElementById('currentUsername');
    const changeUsernameBtn = document.getElementById('changeUsernameBtn');

    const usernameBox = document.getElementById('usernameBox');
    const usernameForm = document.getElementById('usernameForm');
    const usernameInput = document.getElementById('usernameInput');

    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const messagesBox = document.getElementById('messages');

    function syncUsernameUI() {
        const name = window.GLOBAL_CHAT_USER.name;

        if (!name) {
            userInfo.classList.add('hidden');
            usernameBox.classList.remove('hidden');
            messageInput.disabled = true;
            sendBtn.disabled = true;
        } else {
            currentUsername.textContent = name;
            userInfo.classList.remove('hidden');
            usernameBox.classList.add('hidden');
            messageInput.disabled = false;
            sendBtn.disabled = false;
        }
    }

    // random color
function getUserColor(userId) {
    const colors = [
        'text-[#004CFF]',
        'text-[#7C3AED]',
        'text-[#0EA5E9]',
        'text-[#10B981]',
        'text-[#F97316]',
        'text-[#004CFF]',
        'text-[#7C3AED]',
        'text-[#0EA5E9]',
        'text-[#10B981]',
        'text-[#F97316]',
        'text-[#EC4899]',
    ];

    let hash = 0;
    for (let i = 0; i < userId.length; i++) {
        hash = userId.charCodeAt(i) + ((hash << 5) - hash);
    }

    return colors[Math.abs(hash) % colors.length];
}


// time
function formatTime(ts) {
    const d = new Date(ts);
    return d.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
}



    syncUsernameUI();

    usernameForm.addEventListener('submit', e => {
        e.preventDefault();
        const name = usernameInput.value.trim();
        if (!name) return;

        sessionStorage.setItem('guest_name', name);
        window.GLOBAL_CHAT_USER.name = name;
        usernameInput.value = '';
        syncUsernameUI();
    });

    changeUsernameBtn.addEventListener('click', () => {
        sessionStorage.removeItem('guest_name');
        window.GLOBAL_CHAT_USER.name = null;
        syncUsernameUI();
        usernameInput.focus();
    });

    /* FIREBASE */
    if (!firebase.apps.length) {
        firebase.initializeApp({
            apiKey: "AIzaSyAfawRog5klVCNihJUmmvHG9OZYcATHHbM",
            authDomain: "polling-website-95eaf.firebaseapp.com",
            databaseURL: "https://polling-website-95eaf-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "polling-website-95eaf",
        });
    }

    const db = firebase.database();
    const auth = firebase.auth();
    const messagesRef = db.ref('global_chat/messages');

    if (!auth.currentUser) {
        auth.signInAnonymously().then(initChat);
    } else {
        initChat();
    }

    function initChat() {
        if (window.__GLOBAL_CHAT_LISTENER__) return;
        window.__GLOBAL_CHAT_LISTENER__ = true;

        messagesRef.limitToLast(50).on('child_added', snap => {
            const msg = snap.val();
            const isMe = msg.user_id === window.GLOBAL_CHAT_USER.id;

const div = document.createElement('div');

const userColor = getUserColor(msg.user_id);
const time = formatTime(msg.timestamp);

div.className = `
    max-w-[78%]
    px-4 py-2.5
    rounded-[22px]
    shadow-sm
    ${isMe
        ? 'ml-auto bg-[#004CFF] text-white'
        : 'mr-auto bg-white text-gray-800'}
`;


div.innerHTML = `
    <div class="flex items-center justify-between mb-1">
        <span class="text-xs font-semibold
            ${isMe
                ? 'text-white/90'
                : getUserColor(msg.user_id)
            }">
            ${isMe ? 'You' : msg.username}
        </span>

        <span class="text-[10px]
            ${isMe ? 'text-white/70' : 'text-gray-400'}">
            ${time}
        </span>
    </div>

    <div class="text-sm leading-snug break-words">
        ${msg.message}
    </div>
`;



            messagesBox.appendChild(div);
            messagesBox.scrollTop = messagesBox.scrollHeight;
        });

        chatForm.addEventListener('submit', e => {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            messagesRef.push({
                user_id: window.GLOBAL_CHAT_USER.id,
                username: window.GLOBAL_CHAT_USER.name,
                message: text,
                timestamp: Date.now()
            });

            messageInput.value = '';
        });
    }
});
</script>
@endpush
