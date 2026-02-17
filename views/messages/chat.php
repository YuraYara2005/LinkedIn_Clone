<?php require APPROOT . '/views/reused/header.php'; ?>

<style>
    body {
        background-color: #f3f2ef;
        font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .linkedin-chat-container {
        max-width: 1128px;
        margin: 0 auto;
        padding: 10px;
        display: flex;
        flex-direction: row;
        gap: 10px;
        min-height: calc(100vh - 60px); /* Adjust for header/footer */
        box-sizing: border-box;
    }

    .conversation-list, .message-window {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .conversation-list {
        width: 30%;
        max-height: 80vh;
        overflow-y: auto;
        transition: transform 0.3s ease;
    }

    .message-window {
        width: 70%;
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }

    .search-bar {
        background: white;
        border-radius: 40px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 8px 15px;
        margin: 10px;
    }

    .search-bar .form-control {
        border: none;
        box-shadow: none;
        font-size: 14px;
        background: transparent;
        width: 100%;
    }

    .search-bar .form-control:focus {
        box-shadow: none;
        outline: none;
    }

    .conversation-item {
        padding: 10px 12px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #e8e8e8;
        cursor: pointer;
        transition: background 0.2s;
    }

    .conversation-item:hover {
        background: #f5f5f5;
    }

    .conversation-item.active {
        background: #e6f0fa;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }

    .user-info h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #181818;
    }

    .user-info p {
        margin: 0;
        font-size: 11px;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 70%;
    }

    .message-header {
        padding: 10px 12px;
        border-bottom: 1px solid #e8e8e8;
        display: flex;
        align-items: center;
    }

    .message-content {
        flex: 1;
        padding: 12px;
        overflow-y: auto;
        max-height: 60vh;
    }

    .message-input {
        padding: 12px;
        border-top: 1px solid #e8e8e8;
    }

    .message-input .form-control {
        border-radius: 20px;
        font-size: 13px;
        padding: 8px 12px;
    }

    .message-input .btn {
        padding: 8px 16px;
        font-size: 13px;
    }

    .message {
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
    }

    .message.sent {
        align-items: flex-end;
    }

    .message.received {
        align-items: flex-start;
    }

    .message-bubble {
        max-width: 80%;
        padding: 6px 10px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.4;
    }

    .message.sent .message-bubble {
        background: #0077b5;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message.received .message-bubble {
        background: #e8e8e8;
        color: #181818;
        border-bottom-left-radius: 4px;
    }

    .message-time {
        font-size: 11px;
        color: #666;
        margin-top: 3px;
    }

    .no-conversations {
        padding: 20px;
        text-align: center;
        color: #666;
        font-size: 14px;
    }

    /* Mobile Toggle Button */
    .menu-toggle {
        display: none;
        position: fixed;
        top: 10px;
        left: 10px;
        background: #0077b5;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        z-index: 1000;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .linkedin-chat-container {
            flex-direction: column;
            padding: 5px;
        }

        .conversation-list {
            width: 100%;
            max-height: 40vh;
            position: fixed;
            top: 0;
            left: -100%;
            z-index: 999;
            background: white;
            transform: translateX(0);
        }

        .conversation-list.active {
            left: 0;
            transform: translateX(0);
        }

        .message-window {
            width: 100%;
            max-height: calc(100vh - 50px);
            margin-top: 40px; /* Space for toggle button */
        }

        .message-content {
            max-height: calc(100vh - 200px);
        }

        .search-bar {
            margin: 8px;
            padding: 6px 10px;
        }

        .search-bar .form-control {
            font-size: 13px;
        }

        .conversation-item {
            padding: 8px 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
        }

        .user-info h6 {
            font-size: 13px;
        }

        .user-info p {
            font-size: 10px;
            max-width: 60%;
        }

        .message-header {
            padding: 8px 10px;
        }

        .message-input {
            padding: 10px;
        }

        .message-bubble {
            max-width: 85%;
            font-size: 12px;
            padding: 5px 8px;
        }

        .message-time {
            font-size: 10px;
        }

        .menu-toggle {
            display: block;
        }
    }

    @media (max-width: 480px) {
        .linkedin-chat-container {
            padding: 2px;
        }

        .message-window {
            margin-top: 45px;
        }

        .message-content {
            padding: 8px;
        }

        .message-input .form-control {
            font-size: 12px;
            padding: 6px 10px;
        }

        .message-input .btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        .search-bar {
            margin: 5px;
        }

        .no-conversations {
            font-size: 13px;
            padding: 15px;
        }
    }
</style>

<div class="linkedin-chat-container">
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" aria-label="Toggle conversation list">
        <i class="bi bi-list"></i>
    </button>

    <!-- Conversation List -->
    <div class="conversation-list">
        <form method="get" action="<?= URLROOT ?>/messages/search" class="search-bar">
            <div class="input-group">
                <input type="text" id="user-search" class="form-control" placeholder="Search people..." aria-label="Search users">
            </div>
        </form>
        <div id="conversation-list">
            <?php if (!empty($data['conversations'])): ?>
                <?php foreach ($data['conversations'] as $conv): ?>
                    <div class="conversation-item" data-user-id="<?= $conv->user_id ?>">
                        <img src="<?= !empty($conv->profile_picture) ? URLROOT . '/public/uploads/profile_pictures/' . htmlspecialchars($conv->profile_picture) : URLROOT . '/public/uploads/profile_pictures/default-avatar.png' ?>" alt="Profile Picture" class="user-avatar">
                        <div class="user-info">
                            <h6><?= htmlspecialchars($conv->first_name . ' ' . $conv->last_name) ?></h6>
                            <p><?= htmlspecialchars($conv->last_message) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-conversations">
                    <i class="bi bi-chat"></i> There is no chats yet.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Message Window -->
    <div class="message-window">
        <div class="message-header">
            <img src="<?= !empty($data['selected_user']->profile_picture) ? URLROOT . '/public/uploads/profile_pictures/' . htmlspecialchars($data['selected_user']->profile_picture) : URLROOT . '/public/uploads/profile_pictures/default-avatar.png' ?>" alt="Profile Picture" class="user-avatar">
            <h6><?= isset($data['selected_user']) ? htmlspecialchars($data['selected_user']->first_name . ' ' . $data['selected_user']->last_name) : 'Choose chat' ?></h6>
        </div>
        <div class="message-content" id="message-content">
            <?php if (isset($data['messages']) && !empty($data['messages'])): ?>
                <?php foreach ($data['messages'] as $msg): ?>
                    <div class="message <?= $msg->sender_id == $data['current_user_id'] ? 'sent' : 'received' ?>">
                        <div class="message-bubble"><?= htmlspecialchars($msg->content) ?></div>
                        <div class="message-time"><?= date('M d, H:i', strtotime($msg->created_at)) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="message-input">
            <form id="message-form">
                <div class="input-group">
                    <input type="text" id="message-input" class="form-control" placeholder="Write a message" <?= !isset($data['selected_user']) ? 'disabled' : '' ?>>
                    <button class="btn btn-primary" type="submit" <?= !isset($data['selected_user']) ? 'disabled' : '' ?>>Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-scroll message content to bottom
    const messageContent = document.getElementById('message-content');
    messageContent.scrollTop = messageContent.scrollHeight;

    // Handle conversation selection
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            const userId = this.dataset.userId;
            loadMessages(userId);
            // Hide conversation list on mobile after selection
            if (window.innerWidth <= 768) {
                document.querySelector('.conversation-list').classList.remove('active');
            }
        });
    });

    // Toggle conversation list on mobile
    document.querySelector('.menu-toggle').addEventListener('click', () => {
        document.querySelector('.conversation-list').classList.toggle('active');
    });

    // Load messages for selected user
    function loadMessages(receiverId) {
        fetch('<?= URLROOT ?>/messages/getMessages/' + receiverId)
            .then(response => response.json())
            .then(data => {
                messageContent.innerHTML = '';
                data.messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = `message ${msg.sender_id == <?= $data['current_user_id'] ?> ? 'sent' : 'received'}`;
                    div.innerHTML = `
                        <div class="message-bubble">${msg.content}</div>
                        <div class="message-time">${new Date(msg.created_at).toLocaleString('ar-EG', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                    `;
                    messageContent.appendChild(div);
                });
                messageContent.scrollTop = messageContent.scrollHeight;
                document.getElementById('message-input').disabled = false;
                document.querySelector('#message-form button').disabled = false;
            });
    }

    // Send message
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const content = document.getElementById('message-input').value;
        const receiverId = document.querySelector('.conversation-item.active')?.dataset.userId;
        if (content && receiverId) {
            fetch('<?= URLROOT ?>/messages/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: receiverId, content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('message-input').value = '';
                    loadMessages(receiverId);
                }
            });
        }
    });

    // Poll for new messages every 5 seconds
    setInterval(() => {
        const activeUser = document.querySelector('.conversation-item.active');
        if (activeUser) {
            loadMessages(activeUser.dataset.userId);
        }
    }, 5000);

    // Search users
    document.getElementById('user-search').addEventListener('input', function() {
        const query = this.value;
        if (query.length > 2) {
            fetch('<?= URLROOT ?>/messages/searchUsers?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    const conversationList = document.getElementById('conversation-list');
                    conversationList.innerHTML = '';
                    data.users.forEach(user => {
                        const div = document.createElement('div');
                        div.className = 'conversation-item';
                        div.dataset.userId = user.id;
                        div.innerHTML = `
                            <img src="${user.profile_picture ? '<?= URLROOT ?>/public/uploads/profile_pictures/' + user.profile_picture : '<?= URLROOT ?>/public/uploads/profile_pictures/default-avatar.png'}" alt="Profile Picture" class="user-avatar">
                            <div class="user-info">
                                <h6>${user.first_name + ' ' + user.last_name}</h6>
                                <p>بدء محادثة جديدة</p>
                            </div>
                        `;
                        div.addEventListener('click', () => {
                            loadMessages(user.id);
                            // Hide conversation list on mobile after selection
                            if (window.innerWidth <= 768) {
                                document.querySelector('.conversation-list').classList.remove('active');
                            }
                        });
                        conversationList.appendChild(div);
                    });
                });
        }
    });
</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>