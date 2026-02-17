<?php
// views/groups/myGroups.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>My Groups</h1>
    <?php if ($data['groups']): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($data['groups'] as $group): ?>
                <div class="col">
                    <div class="card h-100 group-card">
                        <?php if ($group->cover_image): ?>
                            <div class="group-cover" style="background-image: url('<?php echo URLROOT; ?>/public/uploads/group_covers/<?php echo $group->cover_image; ?>')"></div>
                        <?php else: ?>
                            <div class="group-cover" style="background-image: url('https://images.pexels.com/photos/1181467/pexels-photo-1181467.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1')"></div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h3 class="group-name h5 mb-1">
                                <a href="<?php echo URLROOT; ?>/groups/show/<?php echo $group->id; ?>" class="text-decoration-none">
                                    <?php echo $group->name; ?>
                                </a>
                            </h3>
                            <p class="group-member-count mb-2"><?php echo $group->member_count; ?> members</p>
                            <p class="card-text small text-muted mb-3"><?php echo substr($group->description, 0, 100) . (strlen($group->description) > 100 ? '...' : ''); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?php echo URLROOT; ?>/groups/show/<?php echo $group->id; ?>" class="btn btn-outline-primary btn-sm">
                                    View Group
                                </a>
                                <small class="text-muted">Created by <?php echo $group->first_name . ' ' . $group->last_name; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-users text-muted mb-3" style="font-size: 48px;"></i>
            <h3 class="h5">You haven't joined or created any groups yet.</h3>
            <p class="text-muted">Discover groups to join or create your own!</p>
            <a href="<?php echo URLROOT; ?>/groups/create" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> Create Group
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>