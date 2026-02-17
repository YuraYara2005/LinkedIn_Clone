<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Edit Profile</h2>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo URLROOT; ?>/profiles/edit" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <?php if ($data['profile_picture']): ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $data['profile_picture']; ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" id="profile-picture-preview">
                            <?php else: ?>
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-light mb-3" style="width: 150px; height: 150px;" id="profile-picture-preview-container">
                                    <i class="fas fa-user-circle text-secondary" style="font-size: 80px;"></i>
                                </div>
                                <img src="" alt="Profile Picture" class="img-fluid rounded-circle mb-3 d-none" style="width: 150px; height: 150px; object-fit: cover;" id="profile-picture-preview">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input class="form-control form-control-sm" id="profile-picture-input" type="file" name="profile_picture" accept="image/*">
                                <div class="form-text">Recommended size: 400x400 pixels</div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="headline" class="form-label">Professional Headline*</label>
                                <input type="text" class="form-control <?php echo (!empty($data['headline_err'])) ? 'is-invalid' : ''; ?>" id="headline" name="headline" value="<?php echo $data['headline']; ?>" placeholder="e.g., Software Engineer at Tech Company">
                                <div class="invalid-feedback"><?php echo $data['headline_err']; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="industry" class="form-label">Industry</label>
                                <input type="text" class="form-control <?php echo (!empty($data['industry_err'])) ? 'is-invalid' : ''; ?>" id="industry" name="industry" value="<?php echo $data['industry']; ?>" placeholder="e.g., Information Technology">
                                <div class="invalid-feedback"><?php echo $data['industry_err']; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control <?php echo (!empty($data['location_err'])) ? 'is-invalid' : ''; ?>" id="location" name="location" value="<?php echo $data['location']; ?>" placeholder="e.g., New York, NY">
                                <div class="invalid-feedback"><?php echo $data['location_err']; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" value="<?php echo $data['website']; ?>" placeholder="e.g., https://example.com">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="about" class="form-label">About</label>
                        <textarea class="form-control <?php echo (!empty($data['about_err'])) ? 'is-invalid' : ''; ?>" id="about" name="about" rows="6" placeholder="Share a summary about yourself, your experience, and what you're interested in."><?php echo $data['about']; ?></textarea>
                        <div class="invalid-feedback"><?php echo $data['about_err']; ?></div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Profile picture preview
    const profilePictureInput = document.getElementById('profile-picture-input');
    const profilePicturePreview = document.getElementById('profile-picture-preview');
    const profilePicturePreviewContainer = document.getElementById('profile-picture-preview-container');
    
    profilePictureInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePicturePreview.src = e.target.result;
                profilePicturePreview.classList.remove('d-none');
                if (profilePicturePreviewContainer) {
                    profilePicturePreviewContainer.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>