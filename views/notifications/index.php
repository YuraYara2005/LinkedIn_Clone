<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">Notifications</h2>
                
                <?php if (!empty($data['notifications'])): ?>
                    <button id="mark-all-as-read-btn" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                <?php endif; ?>
            </div>
            
            <div class="card-body p-0">
                <?php if ($data['notifications']): ?>
                    <div id="notification-list" class="list-group list-group-flush">
                        <?php foreach ($data['notifications'] as $notification): ?>
                            <div id="notification-<?php echo $notification->id; ?>" class="list-group-item notification-item <?php echo ($notification->is_read == 0) ? 'notification-unread' : ''; ?>">
                                <div class="d-flex">
                                    <?php 
                                    $iconClass = 'fas fa-bell';
                                    switch ($notification->type) {
                                        case 'connection_request':
                                            $iconClass = 'fas fa-user-plus';
                                            break;
                                        case 'message':
                                            $iconClass = 'fas fa-envelope';
                                            break;
                                        case 'post_like':
                                            $iconClass = 'fas fa-thumbs-up';
                                            break;
                                        case 'post_comment':
                                            $iconClass = 'fas fa-comment';
                                            break;
                                        case 'job_application':
                                            $iconClass = 'fas fa-briefcase';
                                            break;
                                        case 'welcome':
                                            $iconClass = 'fas fa-star';
                                            break;
                                    }
                                    ?>
                                    
                                    <div class="notification-icon">
                                        <i class="<?php echo $iconClass; ?>"></i>
                                    </div>
                                    
                                    <div class="notification-content">
                                        <p class="mb-1"><?php echo $notification->message; ?></p>
                                        <p class="notification-time mb-0"><?php echo date('M d, Y h:i A', strtotime($notification->created_at)); ?></p>
                                    </div>
                                    
                                    <div class="notification-actions">
                                        <?php if ($notification->is_read == 0): ?>
                                            <button class="btn btn-sm btn-outline-primary mark-as-read-btn" data-notification-id="<?php echo $notification->id; ?>">
                                                <i class="fas fa-check"></i> Mark as Read
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if ($notification->link): ?>
                                    <div class="mt-2">
                                        <a href="<?php echo URLROOT . '/' . $notification->link; ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-external-link-alt"></i> View Details
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash text-muted mb-3" style="font-size: 48px;"></i>
                        <h3 class="h5">No notifications yet</h3>
                        <p class="text-muted">When you have new notifications, they'll appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Notification Settings -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h5 mb-0">Notification Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="connectionRequests" checked>
                    <label class="form-check-label" for="connectionRequests">Connection requests</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="messages" checked>
                    <label class="form-check-label" for="messages">Messages</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="jobAlerts" checked>
                    <label class="form-check-label" for="jobAlerts">Job alerts</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="profileViews" checked>
                    <label class="form-check-label" for="profileViews">Profile views</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="postEngagement" checked>
                    <label class="form-check-label" for="postEngagement">Post engagement (likes, comments)</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                    <label class="form-check-label" for="emailNotifications">Email notifications</label>
                </div>
                
                <div class="d-grid mt-4">
                    <button class="btn btn-primary">Save Settings</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read
    document.querySelectorAll('.mark-as-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-notification-id');
            fetch('<?php echo URLROOT; ?>/notifications/markAsRead/' + id, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.getElementById('notification-' + id);
                    item.classList.remove('notification-unread');
                    const readButton = item.querySelector('.mark-as-read-btn');
                    if (readButton) readButton.remove();
                }
            });
        });
    });

    // Mark all as read
    document.getElementById('mark-all-as-read-btn')?.addEventListener('click', function() {
        fetch('<?php echo URLROOT; ?>/notifications/markAllAsRead', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-unread').forEach(item => {
                    item.classList.remove('notification-unread');
                    const readButton = item.querySelector('.mark-as-read-btn');
                    if (readButton) readButton.remove();
                });
                this.remove();
            }
        });
    });

    // Poll for new notifications every 10 seconds
    setInterval(() => {
        fetch('<?php echo URLROOT; ?>/notifications/count', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            const currentCount = document.querySelectorAll('.notification-unread').length;
            if (data.count > currentCount) {
                fetch('<?php echo URLROOT; ?>/notifications', {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newNotifications = doc.querySelectorAll('.notification-item');
                    const list = document.getElementById('notification-list');
                    newNotifications.forEach(item => {
                        if (!document.getElementById('notification-' + item.id.split('-')[1])) {
                            list.insertBefore(item, list.firstChild);
                        }
                    });
                });
            }
        });
    }, 10000);
});
</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>