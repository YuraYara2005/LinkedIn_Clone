<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-5 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Sign in</h2>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">Stay updated on your professional world</p>
                
                <form action="<?php echo URLROOT; ?>/users/login" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Email" value="<?php echo $data['email']; ?>">
                        <label for="email">Email</label>
                        <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password">
                        <label for="password">Password</label>
                        <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="text-decoration-none">Forgot password?</a>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">Sign in</button>
                    </div>
                </form>
                
                <div class="text-center">
                    <p>New to LinkedIn Clone? <a href="<?php echo URLROOT; ?>/users/register" class="text-decoration-none">Join now</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>