


let activeConv = null;
const listEl = document.getElementById('conversationList');
const scrollEl = document.getElementById('messagesScroll');
const emptyEl = document.getElementById('emptyState');
const composerEl = document.getElementById('composer');

const pendingConversations = new Set();
window.INITIAL_MESSAGE_BADGE = Array.isArray(conversations) ? conversations.reduce((sum, c) => sum + (c.unread_count || 0), 0) : 0;


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

function moveConversationToTop(conversationId) {
    const index = conversations.findIndex(c => c.id === conversationId);
    if (index > 0) {
        const [conversation] = conversations.splice(index, 1);
        conversations.unshift(conversation);
    }
}

function openConversation(id) {
    activeConv = conversations.find(c => c.id === id);

    if (!activeConv) return;

    window.MESSAGE_PAGE_ACTIVE_CONV = id;

    if (activeConv.unread_count > 0) {
        window.MessageSocket?.decrementBadge(activeConv.unread_count);
    }

    // ✅ Reset unread count correctly
    activeConv.unread_count = 0;

    // ✅ Re-render sidebar to remove badge
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

    if (window.MessageSocket?.isConnected()) {
        window.MessageSocket.send({
            Type: 'Seen',
            From: activeConv.id
        });
    }

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

function formatMessageText(text) {
    const safeText = escapeHTML(text || '');
    return safeText.replace(/\r\n|\r|\n/g, '<br>');
}

function addMessageBubble(msg) {
    const row = document.createElement('div');
    row.className = 'msg-row' + (msg.self ? ' self' : '');
    row.id = msg.id;

    var user_image = '';
    var messageStatus = "";
    if (msg.self) {
        user_image = USER_PROFILE_PICTURE;
        messageStatus = `<span>${msg.Status.toUpperCase()}</span>`;
    } else {
        user_image = `/file/user-files/${activeConv.Profile_Picture}`;
    }


    // 


    row.innerHTML = `
            <img src="${user_image}" class="avatar-sm" alt="user">
            <div class="bubble">
                <div class="text">${formatMessageText(msg.text)}</div>
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
    window.MessageSocket?.send(data);
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
    }[c]));
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

    fetch((window.BASE_URL || '') + '/messages/get-messages', {
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

function handleSocketAck(data) {
    const msgBubble = document.getElementById(data.Message_ID);
    if (!msgBubble) return;
    const statusEl = msgBubble.querySelector(".meta span:nth-child(2)");
    if (statusEl) {
        statusEl.innerText = data.Status;
    }
}

function handleSocketPresence(data) {
    const conversation = conversations.find(c => c.id === data.User_ID);
    if (!conversation) return;
    conversation.online = data.Status === 'Online';
    toggleOnlineOfflineConversation(data.User_ID);
}

function handleSocketNewMessage(data) {
    if (activeConv && activeConv.id === data.From) {
        window.MessageSocket?.send({
            Type: 'Seen',
            From: data.From
        });

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
            moveConversationToTop(data.From);
        }
        renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
        return;
    }

    const conv = conversations.find(c => c.id === data.From);
    if (conv) {
        conv.last_message = data.Content;
        conv.last_message_time = new Date().toISOString();
        conv.unread_count = (conv.unread_count || 0) + 1;
        moveConversationToTop(data.From);
    } else {
        if (pendingConversations.has(data.From)) {
            return;
        }
        pendingConversations.add(data.From);
        fetch((window.BASE_URL || '') + `/messages/get-user?id=${data.From}`)
            .then(res => res.json())
            .then(user => {
                let existing = conversations.find(c => c.id === user.id);
                if (existing) {
                    existing.last_message = data.Content;
                    existing.last_message_time = new Date().toISOString();
                    existing.unread_count = (existing.unread_count || 0) + 1;
                    moveConversationToTop(user.id);
                    renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
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
                    online: user.online === true
                };
                conversations.unshift(newConv);
                renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
            })
            .finally(() => {
                pendingConversations.delete(data.From);
            });
        return;
    }

    moveConversationToTop(data.From);
    renderConversations(document.querySelector('.filter-chip.active').dataset.filter);
}

function handleSocketSeen(data) {
    if (!activeConv || activeConv.id !== data.From) return;
    const messages = document.querySelectorAll('.msg-row.self');
    messages.forEach(msg => {
        const statusEl = msg.querySelector(".meta span:nth-child(2)");
        if (statusEl) {
            statusEl.innerText = 'Read';
        }
    });
}

function registerSocketEvents() {
    if (!window.MessageSocket) return;

    window.MessageSocket.on('open', () => {
        console.log('Connected');
    });

    window.MessageSocket.on('ack', handleSocketAck);
    window.MessageSocket.on('presence', handleSocketPresence);
    window.MessageSocket.on('new-message', handleSocketNewMessage);
    window.MessageSocket.on('seen', handleSocketSeen);
}

document.addEventListener('DOMContentLoaded', registerSocketEvents);

function startNewChat(userId) {


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

    fetch((window.BASE_URL || '') + '/messages/start-conversation', {
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
                online: data.online === true
            };

            conversations.unshift(newConv);
            renderConversations('all');
            openConversation(userId);
        });
}

const messageInput = document.getElementById('messageInput');
messageInput.addEventListener("keydown", function (event) {
  if (event.key === "Enter" && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
});