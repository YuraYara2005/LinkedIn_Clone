<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="container-fluid p-0">
    <!-- Hero Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Welcome to your professional community</h1>
                    <p class="lead mb-4">Find the right job or internship, connect and strengthen professional relationships, and learn the skills you need to succeed in your career.</p>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg px-4 me-md-2">Join Now</a>
                        <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-secondary btn-lg px-4">Sign In</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <img src="https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Professional networking" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Connect to opportunities</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                                <i class="fas fa-briefcase text-primary fs-1"></i>
                            </div>
                            <h4 class="card-title">Find the right job</h4>
                            <p class="card-text">Access job opportunities that match your skills and interests. Apply with a single click.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                                <i class="fas fa-user-friends text-primary fs-1"></i>
                            </div>
                            <h4 class="card-title">Connect with people</h4>
                            <p class="card-text">Build your network with professionals in your industry and expand your opportunities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                                <i class="fas fa-graduation-cap text-primary fs-1"></i>
                            </div>
                            <h4 class="card-title">Learn new skills</h4>
                            <p class="card-text">Discover courses and learning resources to help you grow professionally.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">What professionals are saying</h2>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://images.pexels.com/photos/2379005/pexels-photo-2379005.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Profile" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">Sarah Johnson</h5>
                                    <p class="text-muted mb-0">Marketing Director</p>
                                </div>
                            </div>
                            <p class="card-text">"LinkedIn Clone helped me find my dream job and connect with industry leaders. The networking opportunities are unmatched."</p>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://images.pexels.com/photos/2613260/pexels-photo-2613260.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Profile" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">Michael Chen</h5>
                                    <p class="text-muted mb-0">Software Engineer</p>
                                </div>
                            </div>
                            <p class="card-text">"I've built a strong professional network that has opened doors to collaborations and opportunities I wouldn't have found otherwise."</p>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://images.pexels.com/photos/1181424/pexels-photo-1181424.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Profile" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">Jessica Martinez</h5>
                                    <p class="text-muted mb-0">HR Specialist</p>
                                </div>
                            </div>
                            <p class="card-text">"As a recruiter, this platform has transformed how we find talent. The quality of candidates and tools available are exceptional."</p>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0">
                    <h2 class="display-4 fw-bold">800M+</h2>
                    <p class="lead">Members</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h2 class="display-4 fw-bold">58M+</h2>
                    <p class="lead">Companies</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h2 class="display-4 fw-bold">20M+</h2>
                    <p class="lead">Job Listings</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold">180+</h2>
                    <p class="lead">Countries</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-4">Join your colleagues, classmates, and friends on LinkedIn Clone</h2>
                    <p class="lead mb-4">Get started building your professional network today.</p>
                    <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg px-5 py-3">Join Now</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>