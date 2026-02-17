<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="container mt-5">
    <h1><?php echo $data['title']; ?></h1>
    <div class="card">
        <div class="card-body">
            <p>Are you sure you want to delete the account for <?php echo htmlspecialchars($data['user']->first_name . ' ' . $data['user']->last_name); ?>?</p>
            <p>This action cannot be undone and will remove all associated data (connections, posts, etc.).</p>
            <form method="POST" action="<?php echo URLROOT; ?>/users/delete/<?php echo $data['user']->id; ?>">
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-danger">Yes, Delete Account</button>
                <a href="<?php echo URLROOT; ?>/users/dashboard" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>