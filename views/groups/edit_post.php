<?php
// views/groups/edit_post.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Edit Group Post</h1>
    <div class="card">
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/groups/editPost/<?php echo $data['post']->id; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <textarea class="form-control" name="content" rows="5" required><?php echo htmlspecialchars($data['post']->content); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="media" class="form-label">Update Media (optional)</label>
                    <input type="file" class="form-control" name="media" id="media" accept="image/*,video/*">
                    <?php if ($data['post']->media): ?>
                        <p>Current Media: <img src="<?php echo URLROOT; ?>/public/uploads/group_post_media/<?php echo $data['post']->media; ?>" alt="Current Media" class="img-fluid mt-2" style="max-width: 200px;"></p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="<?php echo URLROOT; ?>/groups/show/<?php echo $data['post']->group_id; ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>