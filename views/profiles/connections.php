<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">
                    <?php if ($data['profile']->id === $_SESSION['user_id']): ?>
                        My Connections
                    <?php else: ?>
                        <?php echo $data['profile']->first_name . "'s Connections"; ?>
                    <?php endif; ?>
                </h2>
                
                <?php if ($data['profile']->id === $_SESSION['user_id']): ?>
                    <div>
                        <a href="<?php echo URLROOT; ?>/profiles/exportConnections" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download"></i> Export Connections
                        </a>
                        <a href="<?php echo URLROOT; ?>/profiles/requests" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user-clock"></i> Pending Requests
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <?php if ($data['connections']): ?>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="connectionSearch" placeholder="Search connections...">
                    </div>
                    
                    <div id="connectionsContainer">
                        <?php foreach ($data['connections'] as $connection): ?>
                            <div class="connection-card">
                                <?php if ($connection->profile_picture): ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $connection->profile_picture; ?>" alt="<?php echo $connection->first_name . ' ' . $connection->last_name; ?>" class="connection-avatar">
                                <?php else: ?>
                                    <div class="connection-avatar d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-user-circle text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="connection-info">
                                    <h3 class="connection-name h5 mb-1"><?php echo $connection->first_name . ' ' . $connection->last_name; ?></h3>
                                    <p class="connection-headline mb-1"><?php echo $connection->headline ?: 'No headline provided'; ?></p>
                                    <div class="d-flex mt-2">
                                        <a href="<?php echo URLROOT; ?>/profiles/<?php echo $connection->id; ?>" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-user"></i> View Profile
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/messages/chat/<?php echo $connection->id; ?>" class="btn btn-sm btn-outline-secondary me-2">
                                            <i class="fas fa-envelope"></i> Message
                                        </a>
                                        <?php if ($data['profile']->id === $_SESSION['user_id']): ?>
                                            <a href="<?php echo URLROOT; ?>/profiles/removeConnection/<?php echo $connection->connection_id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this connection?');">
                                                <i class="fas fa-user-minus"></i> Remove
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <?php if ($data['profile']->id === $_SESSION['user_id']): ?>
                            <i class="fas fa-user-friends text-muted mb-3" style="font-size: 48px;"></i>
                            <h3 class="h5">You don't have any connections yet</h3>
                            <p class="text-muted">Search for people you may know and start building your network.</p>
                            <a href="<?php echo URLROOT; ?>/users/search" class="btn btn-primary">Find Connections</a>
                        <?php else: ?>
                            <i class="fas fa-user-friends text-muted mb-3" style="font-size: 48px;"></i>
                            <h3 class="h5">No connections to show</h3>
                            <p class="text-muted">This person hasn't added any connections yet.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple connection search functionality
    const connectionSearch = document.getElementById('connectionSearch');
    const connectionsContainer = document.getElementById('connectionsContainer');
    const connectionCards = document.querySelectorAll('.connection-card');
    
    if (connectionSearch) {
        connectionSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            connectionCards.forEach(card => {
                const name = card.querySelector('.connection-name').textContent.toLowerCase();
                const headline = card.querySelector('.connection-headline').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || headline.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>