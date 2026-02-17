<?php require APPROOT . '/views/reused/header.php'; ?>

<style>
    :root {
        --linkedin-blue: #0A66C2;
        --linkedin-gray: #737373;
        --linkedin-dark: #1A1A1A;
        --linkedin-light-gray: #F3F2EF;
        --linkedin-white: #FFFFFF;
    }

    .profile-container {
        max-width: 1128px;
        margin: 0 auto;
        padding: 24px;
        background: var(--linkedin-light-gray);
    }

    .profile-cover {
        height: 200px;
        background-size: cover;
        background-position: center;
        border-radius: 8px 8px 0 0;
        position: relative;
        overflow: hidden;
    }

    .profile-cover::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.3));
    }

    .profile-picture {
        width: 128px;
        height: 128px;
        border: 4px solid var(--linkedin-white);
        border-radius: 50%;
        position: absolute;
        top: 150px; /* Overlaps lower part of cover image */
        left: 24px;
        object-fit: cover;
        background: var(--linkedin-white);
        z-index: 1;
    }

    .profile-picture-placeholder {
        width: 128px;
        height: 128px;
        border-radius: 50%;
        border: 4px solid var(--linkedin-white);
        background: var(--linkedin-light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 150px; /* Overlaps lower part of cover image */
        left: 24px;
        z-index: 1;
    }

    .profile-info {
        padding: 24px;
        padding-top: 160px; /* Adjusted for new picture position */
    }

    .profile-info h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--linkedin-dark);
        margin-bottom: 8px;
    }

    .premium-badge {
        background: #F7E6C4;
        color: #8A6D3B;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-left: 8px;
        vertical-align: middle;
    }

    .profile-info .headline {
        font-size: 16px;
        color: var(--linkedin-dark);
        margin-bottom: 8px;
    }

    .profile-meta {
        font-size: 14px;
        color: var(--linkedin-gray);
    }

    .profile-meta .connections {
        color: var(--linkedin-blue);
        font-weight: 600;
    }

    .profile-actions .btn {
        font-size: 14px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 24px;
        margin-right: 8px;
    }

    .btn-primary {
        background: var(--linkedin-blue);
        border-color: var(--linkedin-blue);
        color: var(--linkedin-white);
    }

    .btn-primary:hover {
        background: #004182;
        border-color: #004182;
    }

    .btn-secondary {
        background: transparent;
        border-color: var(--linkedin-gray);
        color: var(--linkedin-gray);
    }

    .btn-secondary:hover {
        background: #E0E0E0;
        border-color: #E0E0E0;
    }

    .section-card {
        background: var(--linkedin-white);
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        margin-bottom: 16px;
    }

    .section-header {
        padding: 16px 24px;
        border-bottom: 1px solid #E9ECEF;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--linkedin-dark);
        margin: 0;
    }

    .section-body {
        padding: 24px;
    }

    .add-btn, .edit-btn, .delete-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--linkedin-white);
        border: 1px solid var(--linkedin-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin-left: 8px;
        cursor: pointer;
    }

    .add-btn i, .edit-btn i, .delete-btn i {
        font-size: 14px;
        color: var(--linkedin-gray);
    }

    .add-btn:hover, .edit-btn:hover, .delete-btn:hover {
        background: #E0E0E0;
    }

    .edit-btn i {
        color: var(--linkedin-blue);
    }

    .delete-btn i {
        color: #DC3545;
    }

    .post-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
    }

    .post-avatar-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--linkedin-light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .post-card {
        padding: 16px 0;
        border-bottom: 1px solid #E9ECEF;
    }

    .post-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .post-header h5 {
        font-size: 14px;
        font-weight: 600;
        color: var(--linkedin-dark);
        margin: 0;
    }

    .post-header .meta {
        font-size: 12px;
        color: var(--linkedin-gray);
    }

    .post-content p {
        font-size: 14px;
        color: var(--linkedin-dark);
        margin-bottom: 12px;
    }

    .post-media {
        max-width: 100%;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .post-actions a {
        font-size: 14px;
        color: var(--linkedin-gray);
        margin-right: 24px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .post-actions a:hover {
        color: var(--linkedin-blue);
    }

    .post-actions i {
        margin-right: 4px;
    }

    .skills-list .badge {
        font-size: 14px;
        padding: 6px 12px;
        background: #E9ECEF;
        color: var(--linkedin-dark);
        border-radius: 16px;
        margin-right: 8px;
        margin-bottom: 8px;
        display: inline-block;
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        border-bottom: 1px solid #E9ECEF;
        padding: 16px 24px;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-body .form-group {
        margin-bottom: 16px;
    }

    .modal-body label {
        font-size: 14px;
        font-weight: 600;
        color: var(--linkedin-dark);
        margin-bottom: 4px;
    }

    .modal-body .form-control {
        border-radius: 4px;
        font-size: 14px;
        padding: 8px 12px;
    }

    .modal-footer {
        border-top: 1px solid #E9ECEF;
        padding: 16px 24px;
    }

    /* Added table styling for report display */
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .report-table th, .report-table td {
        border: 1px solid #E9ECEF;
        padding: 8px;
        text-align: left;
    }
    .report-table th {
        background-color: #F3F2EF;
        color: var(--linkedin-dark);
    }
</style>

<div class="profile-container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Profile Card -->
            <div class="section-card">
                <div style="position: relative;">
                    <?php if (isset($data['profile']->cover_image) && $data['profile']->cover_image): ?>
                        <div class="profile-cover" style="background-image: url('<?php echo URLROOT; ?>/public/uploads/cover_images/<?php echo htmlspecialchars($data['profile']->cover_image); ?>')"></div>
                    <?php else: ?>
                        <div class="profile-cover" style="background: linear-gradient(180deg, #D3D3D3, #A9A9A9);"></div>
                    <?php endif; ?>
                    
                    <?php if (isset($data['profile']->profile_picture) && $data['profile']->profile_picture): ?>
                        <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo htmlspecialchars($data['profile']->profile_picture); ?>" alt="Profile Picture" class="profile-picture">
                    <?php else: ?>
                        <div class="profile-picture-placeholder">
                            <i class="fas fa-user-circle text-secondary" style="font-size: 64px;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="profile-info">
                    <h1>
                        <?php echo htmlspecialchars(($data['profile']->first_name ?? 'Unknown') . ' ' . ($data['profile']->last_name ?? 'User')); ?>
                        <?php if (isset($data['profile']->role) && $data['profile']->role === 'premium'): ?>
                            <span class="premium-badge"><i class="fas fa-star"></i> Premium</span>
                        <?php endif; ?>
                    </h1>
                    <p class="headline"><?php echo htmlspecialchars($data['profile']->headline ?? 'No headline provided'); ?></p>
                    <p class="profile-meta">
                        <?php echo htmlspecialchars($data['profile']->location ?? 'No location provided'); ?> • 
                        <?php echo htmlspecialchars($data['profile']->industry ?? 'No industry provided'); ?> •
                        <span class="connections"><?php echo $data['connection_count'] ?? 0; ?> connections</span>
                    </p>
                    
                    <!-- Profile Actions -->
                    <div class="profile-actions mt-3">
                        <?php if (isLoggedIn() && isset($data['profile']->user_id) && $data['profile']->user_id === $_SESSION['user_id']): ?>
                            <a href="<?php echo URLROOT; ?>/profiles/edit" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            <a href="<?php echo URLROOT; ?>/profiles/connections" class="btn btn-secondary">
                                <i class="fas fa-user-friends"></i> My Network
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2>About</h2>
                </div>
                <div class="section-body">
                    <p><?php echo nl2br(htmlspecialchars($data['profile']->about ?? 'No information provided.')); ?></p>
                    <?php if (isset($data['profile']->website) && $data['profile']->website): ?>
                        <p class="mt-3"><strong>Website:</strong> <a href="<?php echo htmlspecialchars($data['profile']->website); ?>" target="_blank" style="color: var(--linkedin-blue);"><?php echo htmlspecialchars($data['profile']->website); ?></a></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2>Experience</h2>
                    <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
                        <button class="add-btn" data-bs-toggle="modal" data-bs-target="#experienceModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="section-body">
                    <?php if (isset($data['experiences']) && $data['experiences']): ?>
                        <?php foreach ($data['experiences'] as $experience): ?>
                            <div class="mb-4 d-flex justify-content-between align-items-start">
                                <div>
                                    <h5><?php echo htmlspecialchars($experience->title); ?> at <?php echo htmlspecialchars($experience->company); ?></h5>
                                    <p class="text-muted small"><?php echo htmlspecialchars($experience->location ?? ''); ?> • <?php echo date('M Y', strtotime($experience->start_date)) . ' - ' . ($experience->end_date ? date('M Y', strtotime($experience->end_date)) : 'Present'); ?></p>
                                    <p><?php echo nl2br(htmlspecialchars($experience->description ?? '')); ?></p>
                                </div>
                                <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
                                    <div>
                                        <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#experienceModal" data-id="<?php echo $experience->id; ?>" data-title="<?php echo htmlspecialchars($experience->title); ?>" data-company="<?php echo htmlspecialchars($experience->company); ?>" data-location="<?php echo htmlspecialchars($experience->location ?? ''); ?>" data-start-date="<?php echo $experience->start_date; ?>" data-end-date="<?php echo $experience->end_date ?? ''; ?>" data-description="<?php echo htmlspecialchars($experience->description ?? ''); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?php echo URLROOT; ?>/profiles/deleteExperience/<?php echo $experience->id; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this experience?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No experience listed yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Education Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2>Education</h2>
                    <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
                        <button class="add-btn" data-bs-toggle="modal" data-bs-target="#educationModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="section-body">
                    <?php if (isset($data['educations']) && $data['educations']): ?>
                        <?php foreach ($data['educations'] as $education): ?>
                            <div class="mb-4 d-flex justify-content-between align-items-start">
                                <div>
                                    <h5><?php echo htmlspecialchars($education->degree); ?> at <?php echo htmlspecialchars($education->institution); ?></h5>
                                    <p class="text-muted small"><?php echo date('M Y', strtotime($education->start_date)) . ' - ' . ($education->end_date ? date('M Y', strtotime($education->end_date)) : 'Present'); ?></p>
                                    <p><?php echo nl2br(htmlspecialchars($education->description ?? '')); ?></p>
                                </div>
                                <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
                                    <div>
                                        <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#educationModal" data-id="<?php echo $education->id; ?>" data-degree="<?php echo htmlspecialchars($education->degree); ?>" data-institution="<?php echo htmlspecialchars($education->institution); ?>" data-start-date="<?php echo $education->start_date; ?>" data-end-date="<?php echo $education->end_date ?? ''; ?>" data-description="<?php echo htmlspecialchars($education->description ?? ''); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?php echo URLROOT; ?>/profiles/deleteEducation/<?php echo $education->id; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this education?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No education listed yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2>Skills</h2>
                    <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
                        <button class="add-btn" data-bs-toggle="modal" data-bs-target="#skillsModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="section-body">
                    <?php if (isset($data['skills']) && $data['skills']): ?>
                        <div class="skills-list d-flex flex-wrap gap-2">
                            <?php foreach ($data['skills'] as $skill): ?>
                                <span class="badge d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($skill->skill); ?>
                                    <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
                                        <div>
                                            <button class="edit-btn ms-2" data-bs-toggle="modal" data-bs-target="#skillsModal" data-id="<?php echo $skill->id; ?>" data-skill-name="<?php echo htmlspecialchars($skill->skill); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?php echo URLROOT; ?>/profiles/deleteSkill/<?php echo $skill->id; ?>" class="delete-btn ms-2" onclick="return confirm('Are you sure you want to delete this skill?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No skills listed yet.</p>
                    <?php endif; ?>
                </div>
            </div>

           <!-- Reports Section -->
<div class="section-card">
    <div class="section-header">
        <h2>Reports</h2>
    </div>
    <div class="section-body">
        <?php if (isLoggedIn() && isset($data['profile']->user_id) && (int)$data['profile']->user_id === (int)$_SESSION['user_id']): ?>
            <div class="d-flex flex-wrap gap-2">
                <!-- Connections Report Export -->
                <a href="<?php echo URLROOT; ?>/reports/export/connections" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Export Connections Report
                </a>
                <!-- Applications Report Export -->
                <a href="<?php echo URLROOT; ?>/reports/export/applications" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Export Applications Report
                </a>
                <!-- Postings Report Export -->
                <a href="<?php echo URLROOT; ?>/reports/export/postings" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Export Job Postings Report
                </a>
            </div>
        <?php else: ?>
            <p class="text-muted">Reports are only available for your own profile.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Admin Reports Section (Visible only to admins) -->
<?php if (checkRole('admin')): ?>
<div class="section-card">
    <div class="section-header">
        <h2>Admin Reports</h2>
    </div>
    <div class="section-body">
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo URLROOT; ?>/reports/export/admin" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Export System Activity Report
            </a>
        </div>
    </div>
</div>
<?php endif; ?>
            <!-- Posts Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2>Posts</h2>
                </div>
                <div class="section-body">
                    <?php if (isset($data['posts']) && $data['posts']): ?>
                        <?php foreach ($data['posts'] as $post): ?>
                            <div class="post-card">
                                <div class="post-header">
                                    <?php if (isset($data['profile']->profile_picture) && $data['profile']->profile_picture): ?>
                                        <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo htmlspecialchars($data['profile']->profile_picture); ?>" alt="Avatar" class="post-avatar">
                                    <?php else: ?>
                                        <div class="post-avatar-placeholder">
                                            <i class="fas fa-user-circle text-secondary" style="font-size: 24px;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h5><?php echo htmlspecialchars(($data['profile']->first_name ?? 'Unknown') . ' ' . ($data['profile']->last_name ?? 'User')); ?></h5>
                                        <p class="meta"><?php echo htmlspecialchars($data['profile']->headline ?? ''); ?> • <?php echo date('M d, Y', strtotime($post->created_at)); ?></p>
                                    </div>
                                </div>
                                
                                <div class="post-content">
                                    <p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
                                    <?php if ($post->media): ?>
                                        <img src="<?php echo URLROOT; ?>/public/uploads/post_media/<?php echo htmlspecialchars($post->media); ?>" alt="Post media" class="post-media">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="post-actions">
                                    <a href="<?php echo URLROOT; ?>/posts/like/<?php echo $post->id; ?>" class="post-action post-like-btn" data-post-id="<?php echo $post->id; ?>">
                                        <?php if (isset($post->user_liked) && $post->user_liked): ?>
                                            <i class="fas fa-thumbs-up text-primary"></i>
                                        <?php else: ?>
                                            <i class="far fa-thumbs-up"></i>
                                        <?php endif; ?>
                                        <span>Like (<?php echo $post->like_count ?? 0; ?>)</span>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/posts/view/<?php echo $post->id; ?>" class="post-action">
                                        <i class="far fa-comment"></i> 
                                        <span>Comment (<?php echo $post->comment_count ?? 0; ?>)</span>
                                    </a>
                                    <a href="#" class="post-action">
                                        <i class="far fa-share-square"></i> 
                                        <span>Share</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No posts yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="section-card">
                <div class="section-header">
                    <h2>People Also Viewed</h2>
                </div>
                <div class="section-body">
                    <p class="text-muted">No suggestions yet.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Experience Modal -->
<div class="modal fade" id="experienceModal" tabindex="-1" aria-labelledby="experienceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="experienceModalLabel">Add Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URLROOT; ?>/profiles/manageExperience" method="POST">
                    <input type="hidden" name="id" id="experience-id">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" class="form-control" id="company" name="company" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location">
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="action" value="add">Save</button>
                        <button type="submit" class="btn btn-primary" name="action" value="update" style="display: none;" id="update-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Education Modal -->
<div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="educationModalLabel">Add Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URLROOT; ?>/profiles/manageEducation" method="POST">
                    <input type="hidden" name="id" id="education-id">
                    <div class="form-group">
                        <label for="degree">Degree</label>
                        <input type="text" class="form-control" id="degree" name="degree" required>
                    </div>
                    <div class="form-group">
                        <label for="institution">Institution</label>
                        <input type="text" class="form-control" id="institution" name="institution" required>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="action" value="add">Save</button>
                        <button type="submit" class="btn btn-primary" name="action" value="update" style="display: none;" id="update-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Skills Modal -->
<div class="modal fade" id="skillsModal" tabindex="-1" aria-labelledby="skillsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="skillsModalLabel">Add Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URLROOT; ?>/profiles/manageSkill" method="POST">
                    <input type="hidden" name="id" id="skill-id">
                    <div class="form-group">
                        <label for="skill">Skill</label>
                        <input type="text" class="form-control" id="skill" name="skill" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="action" value="add">Save</button>
                        <button type="submit" class="btn btn-primary" name="action" value="update" style="display: none;" id="update-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle edit functionality
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalId);
            const form = modal.querySelector('form');
            const idInput = modal.querySelector('input[name="id"]');
            const updateBtn = modal.querySelector('#update-btn');

            idInput.value = this.getAttribute('data-id');
            if (modalId === '#experienceModal') {
                modal.querySelector('#title').value = this.getAttribute('data-title');
                modal.querySelector('#company').value = this.getAttribute('data-company');
                modal.querySelector('#location').value = this.getAttribute('data-location');
                modal.querySelector('#start_date').value = this.getAttribute('data-start-date');
                modal.querySelector('#end_date').value = this.getAttribute('data-end-date');
                modal.querySelector('#description').value = this.getAttribute('data-description');
            } else if (modalId === '#educationModal') {
                modal.querySelector('#degree').value = this.getAttribute('data-degree');
                modal.querySelector('#institution').value = this.getAttribute('data-institution');
                modal.querySelector('#start_date').value = this.getAttribute('data-start-date');
                modal.querySelector('#end_date').value = this.getAttribute('data-end-date');
                modal.querySelector('#description').value = this.getAttribute('data-description');
            } else if (modalId === '#skillsModal') {
                modal.querySelector('#skill').value = this.getAttribute('data-skill-name');
            }

            updateBtn.style.display = 'inline-block';
            form.querySelector('button[name="action"][value="add"]').style.display = 'none';
            modal.querySelector('.modal-title').textContent = 'Edit ' + (modalId === '#experienceModal' ? 'Experience' : modalId === '#educationModal' ? 'Education' : 'Skill');
        });
    });

    // Manual modal trigger to ensure functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('data-bs-target');
                const modal = new bootstrap.Modal(document.querySelector(modalId));
                modal.show();
            });
        });
    });
</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>