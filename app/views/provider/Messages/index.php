<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Servo | Messages</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/messages.css" />
    
</head>

<body>
    <?php include_once __DIR__ . '/../../includes/navbar.php'; ?>
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
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
    
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

