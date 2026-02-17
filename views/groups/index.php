<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-3">
        <!-- Groups Sidebar -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Manage Groups</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo URLROOT; ?>/groups/myGroups" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users me-2"></i> My Groups</span>
                            <span class="badge bg-primary rounded-pill">4</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/groups" class="list-group-item list-group-item-action active">
                        <i class="fas fa-compass me-2"></i> Discover Groups
                    </a>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo URLROOT; ?>/groups/create" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2"></i> Create a Group
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Groups Categories -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Categories</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="cat1">
                    <label class="form-check-label" for="cat1">
                        Technology
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="cat2">
                    <label class="form-check-label" for="cat2">
                        Business
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="cat3">
                    <label class="form-check-label" for="cat3">
                        Marketing
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="cat4">
                    <label class="form-check-label" for="cat4">
                        Creative
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="cat5">
                    <label class="form-check-label" for="cat5">
                        Science
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="cat6">
                    <label class="form-check-label" for="cat6">
                        Health
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="cat7">
                    <label class="form-check-label" for="cat7">
                        Education
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">Discover Groups</h2>
                    
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo URLROOT; ?>/groups/create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Group
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search Form -->
                <form action="<?php echo URLROOT; ?>/groups" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search groups..." value="<?php echo $data['search']; ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Groups Grid -->
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
                                            <a href="<?php echo URLROOT; ?>/groups/view/<?php echo $group->id; ?>" class="text-decoration-none">
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
                    
                    <!-- Pagination -->
                    <?php
                    $totalPages = ceil(count($data['groups']) / $data['per_page']);
                    if ($totalPages > 1):
                    ?>
                    <nav aria-label="Groups pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i === $data['page']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo URLROOT; ?>/groups?page=<?php echo $i; ?>&search=<?php echo $data['search']; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users text-muted mb-3" style="font-size: 48px;"></i>
                        <h3 class="h5">No groups found</h3>
                        <?php if (empty($data['search'])): ?>
                            <p class="text-muted">Be the first to create a group!</p>
                        <?php else: ?>
                            <p class="text-muted">No groups match your search criteria. Try a different search term or create a new group.</p>
                        <?php endif; ?>
                        
                        <?php if (isLoggedIn()): ?>
                            <a href="<?php echo URLROOT; ?>/groups/create" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Create Group
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>