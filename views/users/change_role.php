<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="container mt-5">
    <h1><?php echo $data['title']; ?></h1>
    <div class="card">
        <div class="card-body">
            <p>Change role for <?php echo htmlspecialchars($data['user']->first_name . ' ' . $data['user']->last_name); ?>:</p>
            <form method="POST" action="<?php echo URLROOT; ?>/users/changeRole/<?php echo $data['user']->id; ?>">
                <div class="mb-3">
                    <label for="role" class="form-label">Select New Role:</label>
                    <select name="role" id="role" class="form-select">
                        <?php foreach ($data['roles'] as $role): ?>
                            <option value="<?php echo $role; ?>" <?php echo $role === $data['user']->role ? 'selected' : ''; ?>>
                                <?php echo ucfirst($role); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Role</button>
                <a href="<?php echo URLROOT; ?>/users/dashboard" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>