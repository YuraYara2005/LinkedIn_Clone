<?php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Edit Comment</h1>
    <?php flash('comment_success'); ?>
    <?php flash('comment_error'); ?>
    <div class="card">
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/groups/editComment/<?php echo $data['comment']->id; ?>" method="POST">
                <div class="mb-3">
                    <textarea class="form-control" name="content" rows="3" required><?php echo htmlspecialchars($data['comment']->content); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Comment</button>
                <a href="<?php echo URLROOT; ?>/groups/viewPost/<?php echo $data['comment']->post_id; ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>