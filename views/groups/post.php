<?php
// views/groups/post.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Group Post</h1>
    <?php flash('post_success'); ?>
    <?php flash('post_error'); ?>
    <?php flash('comment_success'); ?>
    <?php flash('comment_error'); ?>
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <?php if ($data['post']->profile_picture): ?>
                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $data['post']->profile_picture; ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 48px; height: 48px;">
                <?php else: ?>
                    <div class="rounded-circle me-2" style="width: 48px; height: 48px; background: #ddd;">
                        <i class="fas fa-user-circle text-secondary" style="font-size: 48px;"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <h5 class="mb-0"><?php echo $data['post']->first_name . ' ' . $data['post']->last_name; ?></h5>
                    <p class="text-muted small mb-0"><?php echo date('M d, Y', strtotime($data['post']->created_at)); ?></p>
                </div>
            </div>
            <p><?php echo nl2br(htmlspecialchars($data['post']->content)); ?></p>
            <?php if ($data['post']->media): ?>
                <img src="<?php echo URLROOT; ?>/public/uploads/group_post_media/<?php echo $data['post']->media; ?>" alt="Post Media" class="img-fluid rounded">
            <?php endif; ?>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="card">
        <div class="card-body">
            <h4>Comments</h4>
            <?php if ($data['comments']): ?>
                <?php foreach ($data['comments'] as $comment): ?>
                    <div class="d-flex mb-3">
                        <?php if ($comment->profile_picture): ?>
                            <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $comment->profile_picture; ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 32px; height: 32px;">
                        <?php else: ?>
                            <div class="rounded-circle me-2" style="width: 32px; height: 32px; background: #ddd;">
                                <i class="fas fa-user-circle text-secondary" style="font-size: 32px;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <strong><?php echo $comment->first_name . ' ' . $comment->last_name; ?></strong>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($comment->content)); ?></p>
                            <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($comment->created_at)); ?></small>
                            <?php if ($comment->user_id == $_SESSION['user_id']): ?>
                                <div class="mt-1">
                                    <a href="<?php echo URLROOT; ?>/groups/editComment/<?php echo $comment->id; ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                    <a href="<?php echo URLROOT; ?>/groups/deleteComment/<?php echo $comment->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>

            <!-- Add Comment Form -->
            <form action="<?php echo URLROOT; ?>/groups/commentPost/<?php echo $data['post']->id; ?>" method="POST" class="mt-3">
                <div class="input-group">
                    <textarea class="form-control" name="content" rows="2" placeholder="Add a comment..." required></textarea>
                    <button type="submit" class="btn btn-primary">Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>