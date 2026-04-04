<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Servo | Messages</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <!-- <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/messages.css" />

</head>

<body>
    <?php include_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="chat-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1>Messages</h1>
            </div>
            <div class="sidebar-search" style="position:relative;">
                <i class="fa-solid fa-magnifying-glass icon"></i>
                <input type="text" id="convSearch" placeholder="Search conversations" />
            </div>
            <div class="chat-filters">
                <div class="filter-chip active" data-filter="all"><i class="fa-solid fa-inbox"></i> All</div>
                <div class="filter-chip" data-filter="unread"><i class="fa-solid fa-envelope"></i> Unread</div>
                <div class="filter-chip" data-filter="starred"><i class="fa-solid fa-star"></i> Starred</div>
                <div class="filter-chip" data-filter="archived"><i class="fa-solid fa-box-archive"></i> Archived</div>
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
                    <img src="<?= BASE_URL ?>/assets/img/user.jpeg" alt="Peer" id="peerAvatar" />
                    <div class="peer-info">
                        <div class="peer-name" id="peerName">Select a conversation</div>
                        <div class="peer-status" id="peerStatus" style="display:none;"><span class="dot"
                                style="width:8px; height:8px; background:#008500; border-radius:50%; display:inline-block;"></span>
                            Online</div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="toggle-sidebar" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
                    <button class="h-btn" id="starBtn" disabled><i class="fa-solid fa-star"></i> Star</button>
                    <button class="h-btn" id="archiveBtn" disabled><i class="fa-solid fa-box-archive"></i>
                        Archive</button>
                    <button class="h-btn" id="moreBtn" disabled><i class="fa-solid fa-ellipsis"></i> More</button>
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
                                class="fa-solid fa-paperclip"></i></button>
                    </div>
                    <button class="send-btn" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i>
                        Send</button>
                </div>
                <div class="toolbar">
                    <button class="t-btn" onclick="insertTemplate('Thanks for the update!')"><i
                            class="fa-solid fa-message-smile"></i> Quick Reply</button>
                    <button class="t-btn" onclick="insertTemplate('Can you clarify the timeline?')"><i
                            class="fa-solid fa-clock"></i> Timeline</button>
                    <button class="t-btn" onclick="insertTemplate('Let\'s schedule a call to discuss further.')"><i
                            class="fa-solid fa-phone"></i> Call</button>
                </div>
            </div>
        </section>
    </div>
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script>
        // Mock conversation data (replace with API fetch)
        // const conversations = [
        //     {
        //         id: 1, name: 'DevStudio Labs', avatar: '<?= BASE_URL ?>/uploads/clients/0/default.png', last: 'Sprint 3 implementation done – review?', time: '2m', unread: 2, starred: false, archived: false, online: true, messages: [
        //             { id: 1, self: false, text: 'Hi! We finished the sprint 3 tasks. Please review the authentication module & profile settings.', at: '10:15', read: true },
        //             { id: 2, self: true, text: 'Great, I\'ll check now. Any blockers?', at: '10:16', read: true },
        //             { id: 3, self: false, text: 'No blockers. Also optimized some queries.', at: '10:17', read: true }
        //         ]
        //     },
        //     {
        //         id: 2, name: 'DataCraft', avatar: '<?= BASE_URL ?>/uploads/clients/0/default.png', last: 'Analytics widgets spec attached.', time: '1h', unread: 0, starred: true, archived: false, online: false, messages: [
        //             { id: 1, self: false, text: 'Uploaded the analytics widget specification doc. Let me know.', at: '09:03', read: true },
        //             { id: 2, self: true, text: 'Received. Reviewing after standup.', at: '09:05', read: true }
        //         ]
        //     },
        //     {
        //         id: 3, name: 'UXPro Studio', avatar: '<?= BASE_URL ?>/uploads/clients/0/default.png', last: 'Wireframe updates ready.', time: '5h', unread: 5, starred: false, archived: false, online: true, messages: [
        //             { id: 1, self: false, text: 'Updated wireframes for dashboard & profile flows.', at: '06:00', read: false }
        //         ]
        //     },
        // ];

        const conversations = <?= json_encode($Conversations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        let activeConv = null;
        const listEl = document.getElementById('conversationList');
        const scrollEl = document.getElementById('messagesScroll');
        const emptyEl = document.getElementById('emptyState');
        const composerEl = document.getElementById('composer');

        const pendingConversations = new Set();


        function renderConversations(filter = 'all') {

            conversations.sort((a, b) => {
                return new Date(b.last_message_time) - new Date(a.last_message_time);
            });

            listEl.innerHTML = '';
            let filtered = conversations.filter(c => {
                if (filter === 'unread') return c.unread_count > 0;
                if (filter === 'starred') return c.starred;
                if (filter === 'archived') return c.archived;
                return true;
            });
            if (filtered.length === 0) {
                listEl.innerHTML = '<div class="no-conversations">Nothing to show here</div>';
                return;
            }
            filtered.forEach(c => {
                const div = document.createElement('div');
                div.id = `Conversation_ID_${c.id}`;
                div.className = 'conversation' + (activeConv && activeConv.id === c.id ? ' active' : '');
                div.role = 'listitem';
                div.innerHTML = `
                <img src="/file/user-files/${c.Profile_Picture}" class="avatar" alt="${c.name}">
                <div class="conv-main">
                   <div class="conv-row">
                       <div class="conv-name">${c.First_Name} ${c.Last_Name}</div>
                       <div class="conv-meta">
                           <span class="time" data-time="${c.last_message_time}">${timeAgo(c.last_message_time)}</span>
                           ${c.unread_count ? `<span class="badge">${c.unread_count}</span>` : ''}
                       </div>
                   </div>
                   <div class="conv-snippet">${c.last_message}</div>
                   
                </div>
                <button class="pin-btn" title="Star" onclick="toggleStar(event,${c.id})"><i class="fa-${c.starred ? 'solid' : 'regular'} fa-star"></i></button>
            `;
                div.onclick = (e) => {
                    if (e.target.closest('.pin-btn')) return;
                    openConversation(c.id);
                };
                listEl.appendChild(div);
            });
        }

        function openConversation(id) {
            activeConv = conversations.find(c => c.id === id);


            // ✅ Reset unread count correctly
            activeConv.unread_count = 0;

            // ✅ Re-render sidebar to remove badge
            renderConversations(document.querySelector('.filter-chip.active').dataset.filter);


            renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
            document.getElementById('peerName').textContent = activeConv.First_Name + " " + activeConv.Last_Name;
            document.getElementById("peerAvatar").src = `/file/user-files/${activeConv.Profile_Picture}`;

            document.getElementById('starBtn').disabled = false;
            document.getElementById('archiveBtn').disabled = false;
            document.getElementById('moreBtn').disabled = false;
            emptyEl.style.display = 'none';
            composerEl.style.display = 'flex';
            scrollEl.innerHTML = '';

            console.log(id);

            activeConv.unread = 0; // mark as read
            fetchMessagesById(id);
            toggleOnlineOfflineConversation(id);

            ws.send(JSON.stringify({
                Type: 'Seen',
                From: activeConv.id
            }));
        }


        function toggleOnlineOfflineConversation(id) {
            if (activeConv && activeConv.id === id) {
                const statusEl = document.getElementById('peerStatus');
                statusEl.style.display = 'flex';
                statusEl.classList.toggle('offline', !activeConv.online);
                statusEl.innerHTML = `<span class="dot" style="width:8px; height:8px; background:${activeConv.online ? '#008500' : '#64748b'}; border-radius:50%; display:inline-block;"></span> ${activeConv.online ? 'Online' : 'Offline'}`;
            }
        }



        function insertDaySeparator(label) {

            const input = new Date(label + 'T00:00:00');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const diffDays = (today - input) / (1000 * 60 * 60 * 24);
            if (diffDays === 0) label = 'Today';
            if (diffDays === 1) label = 'Yesterday';

            const wrap = document.createElement('div');
            wrap.className = 'day-separator';
            wrap.innerHTML = `<span>${label}</span>`;
            scrollEl.appendChild(wrap);
        }

        function addMessageBubble(msg) {
            const row = document.createElement('div');
            row.className = 'msg-row' + (msg.self ? ' self' : '');
            row.id = msg.id;

            var user_image = '';
            var messageStatus = "";
            if (msg.self) {
                user_image = `<?= BASE_URL . '/file/user-files/' . $_SESSION['user_image'] ?>`;
                messageStatus = `<span>${msg.Status.toUpperCase()}</span>`;
            } else {
                user_image = `/file/user-files/${activeConv.Profile_Picture}`;
            }


            // 


            row.innerHTML = `
            <img src="${user_image}" class="avatar-sm" alt="user">
            <div class="bubble">
                <div class="text">${escapeHTML(msg.text)}</div>
                <div class="msg-actions">
                    <button class="icon-btn" title="Reply" onclick="quoteMessage(event,'${escapeQuotes(msg.text)}')"><i class="fa-solid fa-reply"></i></button>
                    <button class="icon-btn" title="Copy" onclick="copyMessage(event,'${escapeQuotes(msg.text)}')"><i class="fa-solid fa-copy"></i></button>
                    <button class="icon-btn" title="More"><i class="fa-solid fa-ellipsis"></i></button>
                </div>
                <div class="meta"><span>${msg.Time}</span>${messageStatus}</div>
            </div>`;
            scrollEl.appendChild(row);
        }

        function sendMessage() {
            if (!activeConv) return;
            const ta = document.getElementById('messageInput');
            const text = ta.value.trim();
            if (!text) return;
            var Message_ID = crypto.randomUUID();
            const msg = {
                id: Message_ID,
                self: true,
                text,
                Time: new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                }),
                Status: 'Sending'
            };
            // activeConv.messages.push(msg);
            addMessageBubble(msg);
            ta.value = '';
            autoGrow(ta);
            scrollToBottom();
            // updateLastSnippet();

            const data = {
                'Type': 'Message',
                'Content': text,
                'To': activeConv.id,
                'Message_ID': Message_ID
            }

            document.querySelector(".conversation.active .conv-snippet").innerText = text;
            ws.send(JSON.stringify(data));
        }

        function autoGrow(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 190) + 'px';
        }

        function insertTemplate(t) {
            const ta = document.getElementById('messageInput');
            ta.value += (ta.value ? ' ' : '') + t;
            autoGrow(ta);
            ta.focus();
        }

        function attachFile() {
            alert('Attachment dialog placeholder');
        }

        function quoteMessage(e, text) {
            e.stopPropagation();
            insertTemplate('> ' + text + ' ');
        }

        function copyMessage(e, text) {
            e.stopPropagation();
            navigator.clipboard.writeText(text);
        }

        function toggleStar(e, id) {
            e.stopPropagation();
            const c = conversations.find(c => c.id === id);
            c.starred = !c.starred;
            renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
        }

        function updateLastSnippet() {
            if (!activeConv) return;
            activeConv.last = activeConv.messages[activeConv.messages.length - 1].text;
            renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
        }

        function scrollToBottom() {
            requestAnimationFrame(() => {
                scrollEl.scrollTop = scrollEl.scrollHeight;
            });
        }

        function escapeHTML(s) {
            return s.replace(/[&<>"]/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;'
            } [c]));
        }

        function escapeQuotes(s) {
            return s.replace(/['"`]/g, '\"');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('hide');
        }

        // Filters
        document.querySelectorAll('.filter-chip').forEach(ch => ch.addEventListener('click', () => {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            ch.classList.add('active');
            renderConversations(ch.dataset.filter);
        }));
        document.getElementById('convSearch').addEventListener('input', e => {
            const q = e.target.value.toLowerCase();
            [...document.querySelectorAll('.conversation')].forEach(c => {
                const name = c.querySelector('.conv-name').textContent.toLowerCase();
                c.style.display = name.includes(q) ? 'flex' : 'none';
            });
        });


        function timeAgo(dateString) {
            const now = new Date();
            const past = new Date(dateString.replace(' ', 'T')); // make it ISO-safe
            const diffMs = now - past;

            if (diffMs < 0) return 'now';

            const seconds = Math.floor(diffMs / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const weeks = Math.floor(days / 7);

            if (seconds < 60) return 'Now';
            if (minutes < 60) return `${minutes} min`;
            if (hours < 24) return `${hours}h`;
            if (days < 7) return `${days}d`;
            if (weeks < 4) return `${weeks}w`;

            // fallback for older dates
            return past.toLocaleDateString();
        }

        setInterval(() => {
            const timeElements = document.querySelectorAll(".conversation .time");
            timeElements.forEach(element => {
                element.innerText = timeAgo(element.dataset.time);
            });

        }, 60000)


        function fetchMessagesById(id) {
            console.log(id);

            const formData = new FormData();
            formData.append('User_ID', id);

            fetch('/messages/get-messages', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then((data) => {
                    console.log(data);

                    var lastDate = '';
                    data.forEach((m) => {
                        if (m.Date !== lastDate) {
                            insertDaySeparator(m.Date);
                            lastDate = m.Date;
                        }

                        addMessageBubble(m);
                    });


                    scrollToBottom();

                })

        }
        // Init
        renderConversations();


        const ws = new WebSocket("<?= WEBSOCKET_URL . "?token=" . $_SESSION['authorize_token'] ?>");

        ws.onopen = () => {
            console.log("Connected");
        };

        ws.onclose = () => {
            alert("Something went wrong with the connection! Please log in again to continue.");
        }

        ws.onmessage = (e) => {
            const data = JSON.parse(e.data);
            console.log(data);

            if (data.Type && data.Type === 'Ack') {
                const msgBubble = document.getElementById(data.Message_ID);
                msgBubble.querySelector(".meta span:nth-child(2)").innerText = data.Status;
                console.log(msgBubble);
            }

            if (data.Type === 'Presence') {
                var conversation = conversations.find(c => c.id === data.User_ID);
                conversation.online = (data.Status === 'Online') ? true : false;
                toggleOnlineOfflineConversation(data.User_ID);
            }

            if (data.Type && data.Type === 'New Message') {

                if (activeConv && activeConv.id === data.From) {

                    // send seen immediately
                    ws.send(JSON.stringify({
                        Type: 'Seen',
                        From: data.From
                    }));

                    const msg = {
                        id: crypto.randomUUID(),
                        self: false,
                        text: data.Content,
                        Time: new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }),
                        Status: 'Seen'
                    };
                    addMessageBubble(msg);
                    scrollToBottom();


                    const conv = conversations.find(c => c.id === data.From);

                    if (conv) {
                        conv.last_message = data.Content;
                        conv.last_message_time = new Date().toISOString();
                    }
                    renderConversations(document.querySelector('.filter-chip.active').dataset.filter);

                    return;
                }

                const conv = conversations.find(c => c.id === data.From);

                console.log(conversations);

                if (conv) {
                    conv.last_message = data.Content;
                    conv.last_message_time = new Date().toISOString();
                    conv.unread_count = (conv.unread_count || 0) + 1;
                } else {

                    // 🔥 Prevent duplicate fetch
                    if (pendingConversations.has(data.From)) {
                        return;
                    }

                    pendingConversations.add(data.From);


                    fetch(`/messages/get-user?id=${data.From}`)
                        .then(res => res.json())
                        .then(user => {

                            // ✅ Double-check again (IMPORTANT)
                            let existing = conversations.find(c => c.id === user.id);
                            if (existing) {
                                existing.last_message = data.Content;
                                existing.last_message_time = new Date().toISOString();
                                existing.unread_count = (existing.unread_count || 0) + 1;
                                return;
                            }

                            const newConv = {
                                id: parseInt(user.id),
                                First_Name: user.first_name,
                                Last_Name: user.last_name,
                                Profile_Picture: user.profile_picture,
                                last_message: data.Content,
                                last_message_time: new Date().toISOString(),
                                unread_count: 1,
                                online: false
                            };

                            conversations.unshift(newConv);

                            renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
                        })
                        .finally(() => {
                            pendingConversations.delete(data.From);
                            console.log(data.From + " Removed");
                            console.log(pendingConversations);
                        });

                    return;


                }

                // ✅ Re-render sidebar
                renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
            }


            if (data.Type === 'Seen') {

                // Only update if active conversation
                if (activeConv && activeConv.id === data.From) {

                    const messages = document.querySelectorAll('.msg-row.self');

                    messages.forEach(msg => {
                        const statusEl = msg.querySelector(".meta span:nth-child(2)");
                        if (statusEl) {
                            statusEl.innerText = 'Read';
                        }
                    });
                }
            }
        };


        function startNewChat(userId) {

            const CURRENT_USER_ID = '<?= $_SESSION['user_id'] ?>';
            const CURRENT_USER_ROLE = '<?= $_SESSION['role'] ?>';

            const exists = conversations.find(c => c.id === userId);
            if (exists) {
                openConversation(userId);
                return;
            }

            const formData = new FormData();

            // ✅ Determine roles dynamically
            if (CURRENT_USER_ROLE === 'Client') {
                formData.append('Client_ID', CURRENT_USER_ID);
                formData.append('Provider_ID', userId);
            } else {
                formData.append('Client_ID', userId);
                formData.append('Provider_ID', CURRENT_USER_ID);
            }

            fetch('/messages/start-conversation', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {

                    const newConv = {
                        id: userId,
                        First_Name: data.first_name,
                        Last_Name: data.last_name,
                        Profile_Picture: data.profile_picture,
                        last_message: '',
                        last_message_time: new Date().toISOString(),
                        unread_count: 0,
                        online: false
                    };

                    conversations.unshift(newConv);
                    renderConversations('all');
                    openConversation(userId);
                });
        }
    </script>
</body>

</html>


<script>
    <?php if (isset($_GET['new']) && is_numeric($_GET['new'])): ?>
        window.addEventListener('load', () => {
            startNewChat(<?= intval($_GET['new']) ?>);
        });
    <?php endif; ?>
</script>