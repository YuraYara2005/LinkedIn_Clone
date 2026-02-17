<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Create your account</h2>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">Make the most of your professional life. Join the LinkedIn Clone community.</p>
                
                <form action="<?php echo URLROOT; ?>/users/register" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control <?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" placeholder="First name" value="<?php echo $data['first_name']; ?>">
                                <label for="first_name">First name</label>
                                <div class="invalid-feedback"><?php echo $data['first_name_err']; ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control <?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" placeholder="Last name" value="<?php echo $data['last_name']; ?>">
                                <label for="last_name">Last name</label>
                                <div class="invalid-feedback"><?php echo $data['last_name_err']; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Email" value="<?php echo $data['email']; ?>">
                        <label for="email">Email</label>
                        <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password" value="<?php echo $data['password']; ?>">
                        <label for="password">Password (6+ characters)</label>
                        <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="<?php echo $data['confirm_password']; ?>">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="agreeTerms" required>
                        <label class="form-check-label" for="agreeTerms">
                            I agree to the LinkedIn Clone <a href="#" class="text-decoration-none">User Agreement</a>, <a href="#" class="text-decoration-none">Privacy Policy</a>, and <a href="#" class="text-decoration-none">Cookie Policy</a>.
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Join Now</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p>Already have an account? <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>