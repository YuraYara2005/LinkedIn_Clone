<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo URLROOT; ?>">
            <i class="fab fa-linkedin text-primary fs-2 me-2"></i>
            <span class="fw-bold"><?php echo SITENAME; ?></span>
        </a>
        
        <?php if(isLoggedIn()): ?>
        <!-- Search bar for logged in users -->
        <form class="d-flex mx-auto d-none d-md-flex" action="<?php echo URLROOT; ?>/users/search" method="GET">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search people, jobs, and more..." aria-label="Search" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <?php endif; ?>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isLoggedIn()) : ?>
                    <!-- Navigation for logged in users -->
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="<?php echo URLROOT; ?>">
                            <i class="fas fa-home fs-5"></i>
                            <span class="small">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="<?php echo URLROOT; ?>/profiles/connections">
                            <i class="fas fa-user-friends fs-5"></i>
                            <span class="small">Network</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="<?php echo URLROOT; ?>/jobs">
                            <i class="fas fa-briefcase fs-5"></i>
                            <span class="small">Jobs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center" href="<?php echo URLROOT; ?>/groups">
                            <i class="fas fa-users fs-5"></i>
                            <span class="small">Groups</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center position-relative" href="<?php echo URLROOT; ?>/messages">
                            <i class="fas fa-envelope fs-5"></i>
                            <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger d-none" id="messages-badge">
                                0
                            </span>
                            <span class="small">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column align-items-center position-relative" href="<?php echo URLROOT; ?>/notifications">
                            <i class="fas fa-bell fs-5"></i>
                            <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger d-none" id="notification-badge">
                                0
                            </span>
                            <span class="small">Notices</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex flex-column align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fs-5"></i>
                            <span class="small">Me</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><h6 class="dropdown-header"><?php echo $_SESSION['user_name']; ?></h6></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/profiles">View Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/profiles/edit">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/profiles/requests">Connection Requests</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if(checkRole('admin')) : ?>
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/dashboard">Admin Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/reports">Reports</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <!-- Navigation for guests -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/pages/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="<?php echo URLROOT; ?>/users/register">Join Now</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>