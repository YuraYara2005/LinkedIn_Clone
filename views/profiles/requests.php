<?php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Connection Requests</h1>
    <?php flash('connection_success'); ?>
    <?php flash('connection_error'); ?>

    <?php if (!empty($data['pendingRequests'] ?? [])): ?>
        <div class="card">
            <div class="card-body">
                <h4>Pending Requests</h4>
                <?php foreach ($data['pendingRequests'] as $request): ?>
                    <div class="d-flex align-items-center mb-3 p-3 border-bottom">
                        <?php if ($request->profile_picture): ?>
                            <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $request->profile_picture; ?>" alt="Profile Picture" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle me-3" style="width: 48px; height: 48px; background: #ddd;">
                                <i class="fas fa-user-circle text-secondary" style="font-size: 48px;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <a href="<?php echo URLROOT; ?>/profiles/view/<?php echo $request->user_id; ?>" class="text-decoration-none">
                                    <?php echo $request->first_name . ' ' . $request->last_name; ?>
                                </a>
                            </h6>
                            <p class="text-muted small mb-1"><?php echo isset($request->headline) ? htmlspecialchars($request->headline) : 'No headline'; ?></p>
                            <small class="text-muted">Sent on <?php echo date('M d, Y', strtotime($request->created_at)); ?></small>
                        </div>
                        <div>
                            <a href="<?php echo URLROOT; ?>/profiles/acceptRequest/<?php echo $request->id; ?>" class="btn btn-sm btn-primary me-2">Accept</a>
                            <a href="<?php echo URLROOT; ?>/profiles/rejectRequest/<?php echo $request->id; ?>" class="btn btn-sm btn-outline-danger">Reject</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p>No pending connection requests.</p>
    <?php endif; ?>

    <a href="<?php echo URLROOT; ?>/profiles/connections" class="btn btn-link mt-3">Back to Connections</a>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>