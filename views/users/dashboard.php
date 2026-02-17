<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="container mt-5">
    <h1><?php echo $data['title']; ?></h1>

    <!-- Search Form -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" value="<?php echo htmlspecialchars($data['search_query']); ?>" placeholder="Search users...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Users Table -->
    <?php if (!empty($data['users'])): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                        <td><?php echo htmlspecialchars($user->email); ?></td>
                        <td><?php echo htmlspecialchars($user->role); ?></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/users/changeRole/<?php echo $user->id; ?>" class="btn btn-sm btn-warning">Change Role</a>
                            <a href="<?php echo URLROOT; ?>/users/delete/<?php echo $user->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                    <li class="page-item <?php echo $i == $data['page'] ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo URLROOT; ?>/users/dashboard?page=<?php echo $i; ?>&q=<?php echo urlencode($data['search_query']); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <p class="text-center">No users found.</p>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>