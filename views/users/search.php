<?php require APPROOT . '/views/reused/header.php'; ?>

<style>
    body {
        background-color: #f3f2ef;
        font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .linkedin-search-container {
        max-width: 1128px;
        margin: 0 auto;
        padding: 20px;
    }
    .search-bar {
        background: white;
        border-radius: 40px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px 20px;
        margin-bottom: 24px;
    }
    .search-bar .form-control {
        border: none;
        box-shadow: none;
        font-size: 16px;
        background: transparent;
    }
    .search-bar .form-control:focus {
        box-shadow: none;
        outline: none;
    }
    .search-bar .btn {
        border-radius: 40px;
        background: #0077b5;
        border: none;
        padding: 8px 20px;
        font-weight: 600;
        transition: background 0.2s;
    }
    .search-bar .btn:hover {
        background: #005f8c;
    }
    .user-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        transition: box-shadow 0.2s;
    }
    .user-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        margin-right: 16px;
        object-fit: cover;
    }
    .user-info {
        flex: 1;
    }
    .user-info h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #181818;
    }
    .user-info p {
        margin: 4px 0 0;
        font-size: 14px;
        color: #666;
    }
    .user-role {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    .connect-btn {
        padding: 6px 12px;
        font-size: 12px;
        background: #0077b5;
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .connect-btn:hover {
        background: #005f8c;
    }
    .connect-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    .no-results {
        background: white;
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        color: #666;
        font-size: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="linkedin-search-container">
    <!-- Search Form -->
    <form method="get" action="<?= URLROOT ?>/users/search" class="search-bar">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search for people..." value="<?= htmlspecialchars($data['search_query']) ?>" aria-label="Search users">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    <!-- Results -->
    <?php if (!empty($data['users'])): ?>
        <div class="user-results">
            <?php foreach ($data['users'] as $user): ?>
                <div class="user-card">
                    <!-- Profile Picture -->
                    <img src="<?= !empty($user->profile_picture) ? URLROOT . '/Uploads/' . htmlspecialchars($user->profile_picture) : URLROOT . '/images/default-profile.png' ?>" alt="Profile Picture" class="user-avatar">
                    <div class="user-info">
                        <h5><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h5>
                        <p>Role: 
                            <span class="user-role badge bg-<?= $user->role === 'admin' ? 'success' : 'info' ?>">
                                <?= htmlspecialchars($user->role) ?>
                            </span>
                        </p>
                    </div>
                    <?php if ($user->id != $_SESSION['user_id']): ?>
                        <form action="<?= URLROOT ?>/connections/request" method="POST" style="margin-left: auto;">
                            <input type="hidden" name="target_id" value="<?= htmlspecialchars($user->id) ?>">
                            <button type="submit" class="connect-btn" <?= isset($data['connections'][$user->id]) && $data['connections'][$user->id] != 'none' ? 'disabled' : '' ?>>Connect</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            <i class="bi bi-exclamation-triangle"></i> No people found matching your search.
        </div>
    <?php endif; ?>
    <?php flash('connection_success'); ?>
    <?php flash('connection_error'); ?>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>