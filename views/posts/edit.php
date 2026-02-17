<?php
// views/posts/edit.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Edit Post</h1>
    <form action="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <textarea name="content" class="form-control" rows="3"><?php echo htmlspecialchars($data['content'] ?? ''); ?></textarea>
            <?php if (!empty($data['content_err'])): ?>
                <div class="text-danger"><?php echo $data['content_err']; ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <?php if ($data['media']): ?>
                <img src="<?php echo URLROOT . '/public/uploads/post_media/' . $data['media']; ?>" alt="Current Media" style="max-width: 200px;">
                <div>
                    <input type="checkbox" name="remove_media" value="1"> Remove Media
                </div>
            <?php endif; ?>
            <input type="file" name="media_file" class="form-control" accept="image/*,video/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>

<?php require_once APPROOT . '/views/reused/footer.php'; ?>