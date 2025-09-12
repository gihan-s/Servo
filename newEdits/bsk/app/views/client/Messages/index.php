<?php require_once __DIR__ . '/../../../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Messages</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    <style>
        :root {
            --green: #008500;
            --radius: 16px;
        }

        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #111827;
        }

        #messageInput {
            overflow: hidden;
        }

        .attach-btn{
            width: 35px;
            height: 35px;
        }

        .chat-layout {
            display: grid;
            grid-template-columns: 400px 1fr;
            height: 90vh;
            max-height: 100dvh;
            margin: auto;
            max-width: 1300px;
        }

        @media (max-width:1100px) {
            .chat-layout {
                grid-template-columns: 300px 1fr;
            }
        }

        @media (max-width:880px) {
            .chat-layout {
                grid-template-columns: 100%;
            }

            .sidebar {
                position: absolute;
                z-index: 40;
                inset: 0 55% 0 0;
                max-width: 420px;
                transform: translateX(0);
                background: #fff;
                border-right: 1px solid #e2e8f0;
                transition: transform .4s ease;
            }

            .sidebar.hide {
                transform: translateX(-110%);
            }

            .toggle-sidebar {
                display: flex !important;
            }
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
        }

        .sidebar-header {
            padding: 18px 20px 16px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .sidebar-header h1 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-search {
            padding: 0 20px 16px;
        }

        .sidebar-search input {
            width: 100%;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px 12px 40px;
            font-size: 14px;
            outline: none;
            transition: border-color .25s ease, background .25s ease;
        }

        .sidebar-search input:focus {
            border-color: var(--green);
            background: #fff;
            box-shadow: 0 0 0 3px #00850033;
        }

        .sidebar-search .icon {
            position: absolute;
            margin: 10px 0 0 12px;
            color: #64748b;
        }

        .chat-filters {
            padding: 0 20px 14px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-chip {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            display: inline-flex;
            gap: 6px;
            align-items: center;
            transition: all .25s ease;
        }

        .filter-chip.active,
        .filter-chip:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .conversation-list {
            flex: 1;
            overflow: auto;
            padding: 0 10px 12px;
            scrollbar-width: thin;
        }

        .conversation {
            position: relative;
            list-style: none;
            margin: 0;
            padding: 14px 14px 14px 16px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 14px;
            display: flex;
            gap: 14px;
            cursor: pointer;
            align-items: stretch;
            transition: all .25s ease;
        }

        .conversation+.conversation {
            margin-top: 10px;
        }

        .conversation:hover {
            border-color: var(--green);
        }

        .conversation.active {
            background: #f1fdf5;
            border-color: var(--green);
            box-shadow: 0 0 0 1px var(--green);
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 0 0 3px #00850022;
        }

        .conv-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .conv-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: flex-start;
        }

        .conv-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            line-height: 1.3;
        }

        .conv-snippet {
            font-size: 12px;
            color: #475569;
            margin-top: 4px;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conv-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .time {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .badge {
            background: var(--green);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 999px;
            letter-spacing: .5px;
        }

        .typing {
            font-size: 11px;
            color: #008500;
            margin-top: 6px;
            font-weight: 600;
        }

        .pin-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            border-radius: 8px;
            transition: all .2s ease;
        }

        .pin-btn:hover {
            color: #111827;
            background: #f1f5f9;
        }

        .divider-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;
            color: #64748b;
            margin: 16px 0 8px 14px;
            text-transform: uppercase;
        }

        /* Chat Area */
        .chat-area {
            display: flex;
            flex-direction: column;
            background: #ffffff;
            height: 100%;
            position: relative;
        }

        .chat-header {
            padding: 16px 24px 14px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
        }

        .chat-peer {
            display: flex;
            gap: 14px;
            align-items: center;
            min-width: 0;
        }

        .chat-peer img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 0 0 3px #00850022;
        }

        .peer-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .peer-name {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .peer-status {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;
            color: #008500;
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .peer-status.offline {
            color: #64748b;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .h-btn {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 13px;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            gap: 6px;
            align-items: center;
            font-weight: 600;
            color: #374151;
            transition: all .25s ease;
        }

        .h-btn:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .toggle-sidebar {
            display: none;
            background: #fff;
            border: 1px solid #e2e8f0;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            border-radius: 12px;
            cursor: pointer;
        }

        .toggle-sidebar:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .messages-scroll {
            flex: 1;
            overflow: auto;
            padding: 28px 34px 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            background: #f8fafc;
            position: relative;
        }

        .day-separator {
            text-align: center;
            position: relative;
            margin: 10px 0 4px;
        }

        .day-separator span {
            background: #e2e8f0;
            color: #475569;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: .5px;
        }

        .msg-row {
            display: flex;
            gap: 14px;
            align-items: flex-end;
        }

        .msg-row.self {
            flex-direction: row-reverse;
        }

        .bubble {
            max-width: 66%;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            padding: 14px 16px 18px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.5;
            position: relative;
            box-shadow: 0 4px 8px -4px rgba(0, 0, 0, .06);
        }

        .msg-row.self .bubble {
            background: #008500;
            color: #ffffff;
            border-color: #008500;
            box-shadow: 0 4px 14px -4px rgba(0, 133, 0, .45);
        }

        .bubble .meta {
            display: flex;
            gap: 12px;
            font-size: 10px;
            margin-top: 10px;
            color: #64748b;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .msg-row.self .bubble .meta {
            color: #e0ffe8;
        }

        .avatar-sm {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 0 3px #00850022;
        }

        .msg-actions {
            position: absolute;
            top: 4px;
            right: 6px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity .25s ease;
        }

        .bubble:hover .msg-actions {
            opacity: 1;
        }

        .icon-btn {
            background: #fff;
            border: 1px solid #e2e8f0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            cursor: pointer;
            color: #64748b;
            font-size: 12px;
        }

        .icon-btn:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .msg-row.self .icon-btn {
            background: #006d00;
            border-color: #008500;
            color: #e2ffe7;
        }

        .msg-row.self .icon-btn:hover {
            background: #008500;
        }

        .unread-label {
            background: #008500;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            align-self: center;
            letter-spacing: .5px;
        }

        /* Composer */
        .composer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 16px 26px 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .composer-row {
            display: flex;
            gap: 14px;
            align-items: flex-end;
        }

        .composer-text-wrap {
            flex: 1;
            position: relative;
        }

        .composer textarea {
            width: 100%;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 14px 46px 14px 18px;
            font-size: 14px;
            resize: none;
            line-height: 1.5;
            max-height: 190px;
            outline: none;
            transition: border-color .25s ease, background .25s ease;
        }

        .composer textarea:focus {
            background: #fff;
            border-color: var(--green);
            box-shadow: 0 0 0 3px #00850033;
        }

        .attach-btn {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            background: #fff;
            border: 1px solid #e2e8f0;
            width: 38px;
            height: 38px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            cursor: pointer;
            font-size: 15px;
            color: #64748b;
            transition: all .25s ease;
        }

        .attach-btn:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .toolbar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .t-btn {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            gap: 6px;
            align-items: center;
            color: #374151;
        }

        .t-btn:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .send-btn {
            background: var(--green);
            border: 1px solid var(--green);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 26px;
            border-radius: 14px;
            cursor: pointer;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            box-shadow: 0 6px 18px -6px rgba(0, 133, 0, .4);
            transition: all .3s ease;
        }

        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px -8px rgba(0, 133, 0, .5);
        }

        /* Empty State */
        .empty {
            display: flex;
            flex-direction: column;
            gap: 18px;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 40px 20px;
            text-align: center;
            color: #64748b;
        }

        .empty h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .empty p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .skeleton {
            animation: pulse 1.4s ease-in-out infinite;
            background: linear-gradient(90deg, #f1f5f9, #e2e8f0, #f1f5f9);
            background-size: 200% 100%;
        }

        @keyframes pulse {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Scrollbar */
        .conversation-list::-webkit-scrollbar,
        .messages-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .conversation-list::-webkit-scrollbar-track,
        .messages-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .conversation-list::-webkit-scrollbar-thumb,
        .messages-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        .conversation-list::-webkit-scrollbar-thumb:hover,
        .messages-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../navbar.php'; ?>
    <div class="chat-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1>Messages</h1>
            </div>
            <div class="sidebar-search" style="position:relative;">
                <i class="fa-regular fa-magnifying-glass icon"></i>
                <input type="text" id="convSearch" placeholder="Search conversations" />
            </div>
            <div class="chat-filters">
                <div class="filter-chip active" data-filter="all"><i class="fa-regular fa-inbox"></i> All</div>
                <div class="filter-chip" data-filter="unread"><i class="fa-regular fa-envelope"></i> Unread</div>
                <div class="filter-chip" data-filter="starred"><i class="fa-regular fa-star"></i> Starred</div>
                <div class="filter-chip" data-filter="archived"><i class="fa-regular fa-box-archive"></i> Archived</div>
            </div>
            <div class="divider-label">Recent</div>
            <div class="conversation-list" id="conversationList" role="list">
                <!-- Conversations populated by JS -->
            </div>
        </aside>

        <!-- Chat Column -->
        <section class="chat-area">
            <div class="chat-header" id="chatHeader">
                <div class="chat-peer" id="chatPeer">
                    <img src="<?= BASE_URL ?>/uploads/clients/0/default.png" alt="Peer" id="peerAvatar" />
                    <div class="peer-info">
                        <div class="peer-name" id="peerName">Select a conversation</div>
                        <div class="peer-status" id="peerStatus" style="display:none;"><span class="dot"
                                style="width:8px; height:8px; background:#008500; border-radius:50%; display:inline-block;"></span>
                            Online</div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="toggle-sidebar" onclick="toggleSidebar()"><i class="fa-regular fa-bars"></i></button>
                    <button class="h-btn" id="starBtn" disabled><i class="fa-regular fa-star"></i> Star</button>
                    <button class="h-btn" id="archiveBtn" disabled><i class="fa-regular fa-box-archive"></i>
                        Archive</button>
                    <button class="h-btn" id="moreBtn" disabled><i class="fa-regular fa-ellipsis"></i> More</button>
                </div>
            </div>
            <div class="messages-scroll" id="messagesScroll">
                <div class="empty" id="emptyState">
                    <h2>No Conversation Selected</h2>
                    <p>Choose a conversation on the left or start a new one with a provider.</p>
                </div>
            </div>
            <div class="composer" id="composer" style="display:none;">
                <div class="composer-row">
                    <div class="composer-text-wrap">
                        <textarea id="messageInput" rows="1" placeholder="Type a message"
                            oninput="autoGrow(this)"></textarea>
                        <button class="attach-btn" title="Attach" onclick="attachFile()"><i
                                class="fa-regular fa-paperclip"></i></button>
                    </div>
                    <button class="send-btn" onclick="sendMessage()"><i class="fa-regular fa-paper-plane"></i>
                        Send</button>
                </div>
                <div class="toolbar">
                    <button class="t-btn" onclick="insertTemplate('Thanks for the update!')"><i
                            class="fa-regular fa-message-smile"></i> Quick Reply</button>
                    <button class="t-btn" onclick="insertTemplate('Can you clarify the timeline?')"><i
                            class="fa-regular fa-clock"></i> Timeline</button>
                    <button class="t-btn" onclick="insertTemplate('Let\'s schedule a call to discuss further.')"><i
                            class="fa-regular fa-phone"></i> Call</button>
                </div>
            </div>
        </section>
    </div>
    <?php include_once __DIR__ . '/../footer.php'; ?>
    <style>
        .footer-content {
            display: none;
        }

        .footer-bottom {
            margin-bottom: 0;
        }

        footer.Site {
            margin-top: 0;
        }
    </style>
    <script>
        // Mock conversation data (replace with API fetch)
        const conversations = [
            {
                id: 1, name: 'DevStudio Labs', avatar: '<?= BASE_URL ?>/uploads/clients/0/default.png', last: 'Sprint 3 implementation done – review?', time: '2m', unread: 2, starred: false, archived: false, online: true, messages: [
                    { id: 1, self: false, text: 'Hi! We finished the sprint 3 tasks. Please review the authentication module & profile settings.', at: '10:15', read: true },
                    { id: 2, self: true, text: 'Great, I\'ll check now. Any blockers?', at: '10:16', read: true },
                    { id: 3, self: false, text: 'No blockers. Also optimized some queries.', at: '10:17', read: true }
                ]
            },
            {
                id: 2, name: 'DataCraft', avatar: '<?= BASE_URL ?>/uploads/clients/0/default.png', last: 'Analytics widgets spec attached.', time: '1h', unread: 0, starred: true, archived: false, online: false, messages: [
                    { id: 1, self: false, text: 'Uploaded the analytics widget specification doc. Let me know.', at: '09:03', read: true },
                    { id: 2, self: true, text: 'Received. Reviewing after standup.', at: '09:05', read: true }
                ]
            },
            {
                id: 3, name: 'UXPro Studio', avatar: '<?= BASE_URL ?>/uploads/clients/0/default.png', last: 'Wireframe updates ready.', time: '5h', unread: 5, starred: false, archived: false, online: true, messages: [
                    { id: 1, self: false, text: 'Updated wireframes for dashboard & profile flows.', at: '06:00', read: false }
                ]
            },
        ];

        let activeConv = null;
        const listEl = document.getElementById('conversationList');
        const scrollEl = document.getElementById('messagesScroll');
        const emptyEl = document.getElementById('emptyState');
        const composerEl = document.getElementById('composer');

        function renderConversations(filter = 'all') {
            listEl.innerHTML = '';
            let filtered = conversations.filter(c => {
                if (filter === 'unread') return c.unread > 0;
                if (filter === 'starred') return c.starred;
                if (filter === 'archived') return c.archived;
                return true;
            });
            filtered.forEach(c => {
                const div = document.createElement('div');
                div.className = 'conversation' + (activeConv && activeConv.id === c.id ? ' active' : '');
                div.role = 'listitem';
                div.innerHTML = `
                <img src="${c.avatar}" class="avatar" alt="${c.name}">
                <div class="conv-main">
                   <div class="conv-row">
                       <div class="conv-name">${c.name}</div>
                       <div class="conv-meta">
                           <span class="time">${c.time}</span>
                           ${c.unread ? `<span class="badge">${c.unread}</span>` : ''}
                       </div>
                   </div>
                   <div class="conv-snippet">${c.last}</div>
                   ${c.online ? '<div class="typing">Online</div>' : ''}
                </div>
                <button class="pin-btn" title="Star" onclick="toggleStar(event,${c.id})"><i class="fa-${c.starred ? 'solid' : 'regular'} fa-star"></i></button>
            `;
                div.onclick = (e) => { if (e.target.closest('.pin-btn')) return; openConversation(c.id); };
                listEl.appendChild(div);
            });
        }

        function openConversation(id) {
            activeConv = conversations.find(c => c.id === id);
            renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
            document.getElementById('peerName').textContent = activeConv.name;
            const statusEl = document.getElementById('peerStatus');
            statusEl.style.display = 'flex';
            statusEl.classList.toggle('offline', !activeConv.online);
            statusEl.innerHTML = `<span class="dot" style="width:8px; height:8px; background:${activeConv.online ? '#008500' : '#64748b'}; border-radius:50%; display:inline-block;"></span> ${activeConv.online ? 'Online' : 'Offline'}`;
            document.getElementById('starBtn').disabled = false;
            document.getElementById('archiveBtn').disabled = false;
            document.getElementById('moreBtn').disabled = false;
            emptyEl.style.display = 'none';
            composerEl.style.display = 'flex';
            scrollEl.innerHTML = '';
            insertDaySeparator('Today');
            activeConv.messages.forEach(m => addMessageBubble(m));
            scrollToBottom();
            activeConv.unread = 0; // mark as read
            updateLastSnippet();
        }

        function insertDaySeparator(label) {
            const wrap = document.createElement('div');
            wrap.className = 'day-separator';
            wrap.innerHTML = `<span>${label}</span>`;
            scrollEl.appendChild(wrap);
        }

        function addMessageBubble(msg) {
            const row = document.createElement('div');
            row.className = 'msg-row' + (msg.self ? ' self' : '');
            row.innerHTML = `
            <img src="<?= BASE_URL ?>/uploads/clients/0/default.png" class="avatar-sm" alt="user">
            <div class="bubble">
                <div class="text">${escapeHTML(msg.text)}</div>
                <div class="msg-actions">
                    <button class="icon-btn" title="Reply" onclick="quoteMessage(event,'${escapeQuotes(msg.text)}')"><i class="fa-regular fa-reply"></i></button>
                    <button class="icon-btn" title="Copy" onclick="copyMessage(event,'${escapeQuotes(msg.text)}')"><i class="fa-regular fa-copy"></i></button>
                    <button class="icon-btn" title="More"><i class="fa-regular fa-ellipsis"></i></button>
                </div>
                <div class="meta"><span>${msg.at}</span><span>${msg.read ? 'Read' : 'Sent'}</span></div>
            </div>`;
            scrollEl.appendChild(row);
        }

        function sendMessage() {
            if (!activeConv) return; const ta = document.getElementById('messageInput'); const text = ta.value.trim(); if (!text) return;
            const msg = { id: Date.now(), self: true, text, at: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), read: false };
            activeConv.messages.push(msg); addMessageBubble(msg); ta.value = ''; autoGrow(ta); scrollToBottom(); updateLastSnippet();
        }

        function autoGrow(el) { el.style.height = 'auto'; el.style.height = Math.min(el.scrollHeight, 190) + 'px'; }
        function insertTemplate(t) { const ta = document.getElementById('messageInput'); ta.value += (ta.value ? ' ' : '') + t; autoGrow(ta); ta.focus(); }
        function attachFile() { alert('Attachment dialog placeholder'); }
        function quoteMessage(e, text) { e.stopPropagation(); insertTemplate('> ' + text + ' '); }
        function copyMessage(e, text) { e.stopPropagation(); navigator.clipboard.writeText(text); }
        function toggleStar(e, id) { e.stopPropagation(); const c = conversations.find(c => c.id === id); c.starred = !c.starred; renderConversations(document.querySelector('.filter-chip.active').dataset.filter); }
        function updateLastSnippet() { if (!activeConv) return; activeConv.last = activeConv.messages[activeConv.messages.length - 1].text; renderConversations(document.querySelector('.filter-chip.active').dataset.filter); }
        function scrollToBottom() { requestAnimationFrame(() => { scrollEl.scrollTop = scrollEl.scrollHeight; }); }
        function escapeHTML(s) { return s.replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c])); }
        function escapeQuotes(s) { return s.replace(/['"`]/g, '\"'); }
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('hide'); }

        // Filters
        document.querySelectorAll('.filter-chip').forEach(ch => ch.addEventListener('click', () => { document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active')); ch.classList.add('active'); renderConversations(ch.dataset.filter); }));
        document.getElementById('convSearch').addEventListener('input', e => {
            const q = e.target.value.toLowerCase();
            [...document.querySelectorAll('.conversation')].forEach(c => {
                const name = c.querySelector('.conv-name').textContent.toLowerCase();
                c.style.display = name.includes(q) ? 'flex' : 'none';
            });
        });

        // Init
        renderConversations();
    </script>
</body>

</html>