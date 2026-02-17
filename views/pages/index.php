<?php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Welcome to LinkedIn Clone</h1>
    <?php if (isLoggedIn()): ?>
        <div class="mt-4">
            <h3>Your Feed</h3>
            <?php if (!empty($data['posts'])): ?>
                <?php foreach ($data['posts'] as $post): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <?php if ($post->profile_picture): ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo htmlspecialchars($post->profile_picture); ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                <?php else: ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/default-avatar.png" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo htmlspecialchars($post->first_name . ' ' . $post->last_name); ?></strong>
                                    <small class="text-muted"><?php echo $post->created_at; ?></small>
                                    <?php if (isset($post->group_name)): ?>
                                        <div>
                                            <small class="text-muted">Posted in <a href="<?php echo URLROOT; ?>/groups/show/<?php echo $post->group_id; ?>"><?php echo htmlspecialchars($post->group_name); ?></a></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
                            <?php if ($post->media): ?>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $post->media)): ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/group_post_media/<?php echo htmlspecialchars($post->media); ?>" alt="Post Media" class="img-fluid">
                                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $post->media)): ?>
                                    <video controls class="img-fluid">
                                        <source src="<?php echo URLROOT; ?>/public/uploads/group_post_media/<?php echo htmlspecialchars($post->media); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts to show. Join some groups or follow users to see their posts in your feed!</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Please <a href="<?php echo URLROOT; ?>/users/login">log in</a> to see your feed.</p>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>