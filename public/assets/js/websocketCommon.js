(function () {
    if (window.MessageSocket) return;

    const listeners = new Map();
    let ws = null;
    let reconnectTimer = null;
    let heartbeatInterval = null;
    let heartbeatTimeout = null;
    let messageBadgeCount = 0;

    const HEARTBEAT_INTERVAL = 25000;
    const HEARTBEAT_TIMEOUT = 10000;
    const RECONNECT_DELAY = 5000;

    function isOpen() {
        return ws && ws.readyState === WebSocket.OPEN;
    }

    function emit(event, payload) {
        const handlers = listeners.get(event);
        if (!handlers) return;
        handlers.forEach((handler) => {
            try {
                handler(payload);
            } catch (err) {
                console.error('MessageSocket listener error for', event, err);
            }
        });
    }

    function on(event, handler) {
        if (!listeners.has(event)) {
            listeners.set(event, []);
        }
        listeners.get(event).push(handler);
    }

    function updateMessageBadge() {
        const badge = document.getElementById('msgBadge');
        if (!badge) return;

        badge.textContent = messageBadgeCount > 9 ? '9+' : String(messageBadgeCount);
        badge.style.display = messageBadgeCount > 0 ? 'flex' : 'none';
    }

    function setMessageBadge(count) {
        messageBadgeCount = Math.max(0, parseInt(count || 0, 10));
        updateMessageBadge();
    }

    function incrementMessageBadge() {
        messageBadgeCount = Math.max(0, messageBadgeCount) + 1;
        updateMessageBadge();
    }

    function decrementMessageBadge(amount) {
        const value = Math.max(0, parseInt(amount || 0, 10));
        messageBadgeCount = Math.max(0, messageBadgeCount - value);
        updateMessageBadge();
    }

    function loadInitialBadgeCount() {
        if (typeof window.INITIAL_MESSAGE_BADGE === 'number') {
            setMessageBadge(window.INITIAL_MESSAGE_BADGE);
            return;
        }

        const endpoint = (window.BASE_URL || '') + '/messages/unread-count';
        fetch(endpoint)
            .then((res) => res.ok ? res.json() : Promise.reject())
            .then((data) => {
                setMessageBadge(data.unread_count || 0);
            })
            .catch(() => {
                setMessageBadge(0);
            });
    }

  
    function sendHeartbeat() {
        if (!isOpen()) return;
        ws.send(JSON.stringify({ Type: 'Ping', Timestamp: Date.now() }));
        heartbeatTimeout = setTimeout(() => {
            console.warn('WebSocket heartbeat timed out, closing socket.');
            ws.close();
        }, HEARTBEAT_TIMEOUT);
    }

    function startHeartbeat() {
        stopHeartbeat();
        heartbeatInterval = setInterval(sendHeartbeat, HEARTBEAT_INTERVAL);
    }

    function stopHeartbeat() {
        if (heartbeatInterval) {
            clearInterval(heartbeatInterval);
            heartbeatInterval = null;
        }
        if (heartbeatTimeout) {
            clearTimeout(heartbeatTimeout);
            heartbeatTimeout = null;
        }
    }

    function scheduleReconnect() {
        if (reconnectTimer) return;
        reconnectTimer = setTimeout(() => {
            reconnectTimer = null;
            connect();
        }, RECONNECT_DELAY);
    }

    function handleIncomingMessage(message) {
        let data;
        try {
            data = JSON.parse(message.data);
        } catch (err) {
            console.warn('Incoming socket message is not JSON', err);
            return;
        }

        if (data.Type === 'Pong') {
            if (heartbeatTimeout) {
                clearTimeout(heartbeatTimeout);
                heartbeatTimeout = null;
            }
            return;
        }

        if (data.Type === 'Ping') {
            if (isOpen()) {
                ws.send(JSON.stringify({ Type: 'Pong', Timestamp: Date.now() }));
            }
            return;
        }

        if (data.Type === 'New Message') {
            const title = data.From_Name ? `New message from ${data.From_Name}` : 'New message';
            const body = typeof data.Content === 'string' && data.Content.trim()
                ? data.Content.trim()
                : 'You received a new message';
            const isCurrentConversation = window.MESSAGE_PAGE_ACTIVE_CONV === data.From;

            if (!isCurrentConversation) {
                incrementMessageBadge();
                showToast('info', title, body, 5000);
            }
        }

        emit('message', data);
        const eventName = data.Type ? data.Type.toLowerCase().replace(/\s+/g, '-') : 'unknown';
        emit(eventName, data);
    }

    function connect() {
        if (!window.WEBSOCKET_URL) {
            return;
        }

        if (ws && (ws.readyState === WebSocket.OPEN || ws.readyState === WebSocket.CONNECTING)) {
            return;
        }

        ws = new WebSocket(window.WEBSOCKET_URL);

        ws.onopen = () => {
            emit('open');
            startHeartbeat();
        };

        ws.onclose = () => {
            emit('close');
            stopHeartbeat();
            scheduleReconnect();
        };

        ws.onerror = (err) => {
            emit('error', err);
        };

        ws.onmessage = handleIncomingMessage;
    }

    function send(data) {
        if (!isOpen()) {
            console.warn('WebSocket is not connected yet');
            return false;
        }
        ws.send(JSON.stringify(data));
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadInitialBadgeCount();
        connect();
    });

    window.MessageSocket = {
        connect,
        send,
        on,
        isConnected: isOpen,
        setBadgeCount: setMessageBadge,
        incrementBadge: incrementMessageBadge,
        decrementBadge: decrementMessageBadge
    };
})();
