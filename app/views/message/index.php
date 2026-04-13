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

    <script>
        const conversations = <?= json_encode($Conversations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const CURRENT_USER_ID = '<?= $_SESSION['user_id'] ?>';
        const CURRENT_USER_ROLE = '<?= $_SESSION['role'] ?>';
        const WEBSOCKET_URL = "<?= WEBSOCKET_URL . "?token=" . $_SESSION['authorize_token'] ?>";
        const USER_PROFILE_PICTURE = `<?= BASE_URL . '/file/user-files/' . $_SESSION['user_image'] ?>`;
    </script>
    <script src="<?= BASE_URL ?>/assets/js/messagePage.js" defer></script>

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


</body>

</html>


<script>
    <?php if (isset($_GET['new']) && is_numeric($_GET['new'])): ?>
        window.addEventListener('load', () => {
            startNewChat(<?= intval($_GET['new']) ?>);
        });
    <?php endif; ?>
</script>