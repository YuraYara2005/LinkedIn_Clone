<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <!-- Left Sidebar -->
    <div class="col-lg-3 d-none d-lg-block">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="text-center mb-3">
                    <?php 
                    $profilePicture = '';
                    $headline = '';
                    
                    foreach ($data['posts'] as $post) {
                        if ($post->user_id == $_SESSION['user_id']) {
                            $profilePicture = $post->profile_picture;
                            $headline = $post->headline;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if ($profilePicture): ?>
                        <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $profilePicture; ?>" alt="Profile Picture" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-light mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-circle text-secondary" style="font-size: 40px;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h5 class="mb-1"><?php echo $_SESSION['user_name']; ?></h5>
                    <p class="text-muted small mb-3"><?php echo $headline ?: 'No headline provided'; ?></p>
                </div>
                
                <hr>
                
                <div class="profile-stats">
                    <a href="<?php echo URLROOT; ?>/profiles" class="d-flex justify-content-between text-decoration-none text-dark mb-2">
                        <span>Who viewed your profile</span>
                        <span class="text-primary fw-bold">24</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/profiles/connections" class="d-flex justify-content-between text-decoration-none text-dark mb-2">
                        <span>Connections</span>
                        <span class="text-primary fw-bold">562</span>
                    </a>
                </div>
                
                <hr>
                
                <div class="sidebar-links">
                    <a href="<?php echo URLROOT; ?>/pages/pricing" class="d-flex align-items-center text-decoration-none text-dark mb-2">
                        <i class="fas fa-bookmark text-muted me-2"></i>
                        <span>My Items</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/pages/pricing" class="d-flex align-items-center text-decoration-none text-dark">
                        <i class="fas fa-crown text-warning me-2"></i>
                        <span>Try Premium for free</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Recent</h5>
                <div class="recent-activity">
                    <a href="<?php echo URLROOT; ?>/groups" class="d-flex align-items-center text-decoration-none text-dark mb-2">
                        <i class="fas fa-users text-muted me-2"></i>
                        <span>Web Development Group</span>
                    </a>
                    <a href="#" class="d-flex align-items-center text-decoration-none text-dark mb-2">
                        <i class="fas fa-hashtag text-muted me-2"></i>
                        <span>javascript</span>
                    </a>
                    <a href="#" class="d-flex align-items-center text-decoration-none text-dark mb-2">
                        <i class="fas fa-hashtag text-muted me-2"></i>
                        <span>webdevelopment</span>
                    </a>
                </div>
                
                <div class="mt-3">
                    <a href="<?php echo URLROOT; ?>/groups" class="text-decoration-none">
                        <span class="text-primary">Groups</span>
                    </a>
                    <a href="#" class="text-decoration-none d-block mt-2">
                        <span class="text-primary">Events</span>
                    </a>
                    <a href="#" class="text-decoration-none d-block mt-2">
                        <span class="text-primary">Followed Hashtags</span>
                    </a>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <a href="#" class="text-decoration-none text-muted">Discover more</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="col-lg-6">
        <!-- Create Post Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <?php 
                    $profilePicture = '';
                    
                    foreach ($data['posts'] as $post) {
                        if ($post->user_id == $_SESSION['user_id']) {
                            $profilePicture = $post->profile_picture;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if ($profilePicture): ?>
                        <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $profilePicture; ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 48px; height: 48px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light me-2" style="width: 48px; height: 48px;">
                            <i class="fas fa-user-circle text-secondary" style="font-size: 24px;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <button type="button" class="form-control text-start text-muted" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        Start a post
                    </button>
                </div>
                
                <div class="d-flex justify-content-between post-actions">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        <i class="fas fa-image text-primary"></i> Photo
                    </button>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        <i class="fab fa-youtube text-success"></i> Video
                    </button>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        <i class="fas fa-calendar-alt text-warning"></i> Event
                    </button>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        <i class="fas fa-newspaper text-danger"></i> Article
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Posts Feed -->
        <?php if ($data['posts']): ?>
            <?php foreach ($data['posts'] as $post): ?>
                <div class="card shadow-sm mb-4 post-card">
                    <div class="card-body">
                        <div class="post-header">
                            <?php if ($post->profile_picture): ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $post->profile_picture; ?>" alt="Profile Picture" class="post-avatar">
                            <?php else: ?>
                                <div class="post-avatar d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-user-circle text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <h5 class="mb-0"><?php echo $post->first_name . ' ' . $post->last_name; ?></h5>
                                <p class="text-muted small mb-0"><?php echo $post->headline ?: ''; ?></p>
                                <p class="text-muted small mb-0">
                                    <?php echo date('M d, Y', strtotime($post->created_at)); ?>
                                    <?php if (isset($post->group_name)): ?>
                                        <span> • Posted in <a href="<?php echo URLROOT; ?>/groups/show/<?php echo $post->group_id; ?>"><?php echo htmlspecialchars($post->group_name); ?></a></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <?php if ($post->user_id === $_SESSION['user_id']): ?>
                                <div class="dropdown ms-auto">
                                    <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo URLROOT; ?>/<?php echo isset($post->group_name) ? 'groups/editPost' : 'posts/edit'; ?>/<?php echo $post->id; ?>">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo URLROOT; ?>/<?php echo isset($post->group_name) ? 'groups/deletePost' : 'posts/delete'; ?>/<?php echo $post->id; ?>">
                                                <i class="fas fa-trash-alt me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-content mt-3">
                            <p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
                            <?php if ($post->media): ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo isset($post->group_name) ? 'group_post_media' : 'post_media'; ?>/<?php echo $post->media; ?>" alt="Post media" class="img-fluid rounded post-media">
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-stats d-flex justify-content-between align-items-center mt-3 text-muted small">
                            <div>
                                <i class="fas fa-thumbs-up text-primary"></i>
                                <span><?php echo $post->like_count ?: 0; ?> likes</span>
                            </div>
                            <div>
                                <span><?php echo $post->comment_count ?: 0; ?> comments</span>
                            </div>
                        </div>
                        
                        <div class="post-actions mt-2 pt-2 border-top">
                            <a href="<?php echo URLROOT; ?>/<?php echo isset($post->group_name) ? 'groups/likePost' : 'posts/like'; ?>/<?php echo $post->id; ?>" class="btn btn-light post-action post-like-btn" data-post-id="<?php echo $post->id; ?>">
                                <?php if (isset($post->user_liked) && $post->user_liked): ?>
                                    <i class="fas fa-thumbs-up text-primary"></i> Like
                                <?php else: ?>
                                    <i class="far fa-thumbs-up"></i> Like
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo URLROOT; ?>/<?php echo isset($post->group_name) ? 'groups/viewPost' : 'posts/view'; ?>/<?php echo $post->id; ?>" class="btn btn-light post-action">
                                <i class="far fa-comment"></i> Comment
                            </a>
                            <a href="<?php echo URLROOT; ?>/<?php echo isset($post->group_name) ? 'groups/sharePost' : 'posts/share'; ?>/<?php echo $post->id; ?>" class="btn btn-light post-action">
                                <i class="fas fa-share"></i> Share
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-newspaper text-muted mb-3" style="font-size: 48px;"></i>
                    <h3 class="h5">No posts in your feed yet</h3>
                    <p class="text-muted">Connect with more people or join groups to see their updates.</p>
                    <a href="<?php echo URLROOT; ?>/users/search" class="btn btn-primary mt-2">
                        <i class="fas fa-user-plus"></i> Find Connections
                    </a>
                    <a href="<?php echo URLROOT; ?>/groups" class="btn btn-primary mt-2">
                        <i class="fas fa-users"></i> Join Groups
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Right Sidebar -->
    <div class="col-lg-3 d-none d-lg-block">
        <!-- Job Recommendations -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recommended Jobs</h5>
            </div>
            <div class="card-body p-0">
                <?php if ($data['jobs']): ?>
                    <ul class="list-group list-group-flush" id="jobList">
                        <?php foreach ($data['jobs'] as $index => $job): ?>
                            <?php if ($index < 3): ?> <!-- Limit to 3 initially -->
                                <li class="list-group-item p-3">
                                    <h6 class="mb-1">
                                        <a href="<?php echo URLROOT; ?>/jobs/view/<?php echo $job->id; ?>" class="text-decoration-none">
                                            <?php echo $job->title; ?>
                                        </a>
                                    </h6>
                                    <p class="mb-1 small"><?php echo $job->company; ?></p>
                                    <p class="text-muted small mb-2"><?php echo $job->location; ?></p>
                                    <a href="<?php echo URLROOT; ?>/jobs/view/<?php echo $job->id; ?>" class="btn btn-sm btn-outline-primary">View Job</a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (count($data['jobs']) > 3 || $data['totalJobs'] > 3): ?>
                        <div class="card-footer bg-white text-center">
                            <button id="viewMoreJobs" class="btn btn-link text-decoration-none text-primary">View More</button>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="p-3 text-center">
                        <p class="text-muted">No job recommendations available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Who to Follow -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">People You May Know</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="d-flex">
                            <img src="https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Profile" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">John Doe</h6>
                                <p class="text-muted small mb-2">Software Developer at Tech Co.</p>
                                <a href="<?php echo URLROOT; ?>/users/connect/1" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user-plus"></i> Connect
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="d-flex">
                            <img src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Profile" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">Jane Smith</h6>
                                <p class="text-muted small mb-2">Product Manager at Innovate Inc.</p>
                                <a href="<?php echo URLROOT; ?>/users/connect/2" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user-plus"></i> Connect
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="d-flex">
                            <img src="https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Profile" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">Alex Johnson</h6>
                                <p class="text-muted small mb-2">Marketing Director at Growth Co.</p>
                                <a href="<?php echo URLROOT; ?>/users/connect/3" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user-plus"></i> Connect
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="card-footer bg-white text-center">
                    <a href="<?php echo URLROOT; ?>/users/search" class="text-decoration-none">View more</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create a post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URLROOT; ?>/posts/create" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <?php if ($profilePicture): ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $profilePicture; ?>" alt="Profile" class="rounded-circle me-2" style="width: 48px; height: 48px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-light me-2" style="width: 48px; height: 48px;">
                                    <i class="fas fa-user-circle text-secondary" style="font-size: 24px;"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h6 class="mb-0"><?php echo $_SESSION['user_name']; ?></h6>
                                <select class="form-select form-select-sm mt-1" name="visibility">
                                    <option value="public">Public</option>
                                    <option value="connections">Connections only</option>
                                </select>
                            </div>
                        </div>
                        
                        <textarea class="form-control post-textarea" name="content" rows="5" placeholder="What do you want to talk about?"></textarea>
                    </div>
                    
                    <div id="mediaPreview" class="mb-3 d-none">
                        <div class="position-relative">
                            <img id="imagePreview" src="" alt="Preview" class="img-fluid rounded">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="removeMediaBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <label for="media" class="btn btn-light me-2">
                                <i class="fas fa-image text-primary"></i>
                            </label>
                            <input type="file" id="media" name="media" class="d-none" accept="image/*">
                            
                            <button type="button" class="btn btn-light me-2">
                                <i class="fas fa-video text-success"></i>
                            </button>
                            <button type="button" class="btn btn-light me-2">
                                <i class="fas fa-file-alt text-warning"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Media upload preview
    const mediaInput = document.getElementById('media');
    const mediaPreview = document.getElementById('mediaPreview');
    const imagePreview = document.getElementById('imagePreview');
    const removeMediaBtn = document.getElementById('removeMediaBtn');
    
    mediaInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                mediaPreview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
    
    removeMediaBtn?.addEventListener('click', function() {
        mediaInput.value = '';
        mediaPreview.classList.add('d-none');
    });

    document.addEventListener('DOMContentLoaded', function() {
        const viewMoreBtn = document.getElementById('viewMoreJobs');
        const jobList = document.getElementById('jobList');
        let offset = 3; // Start after the initial 3 jobs
        const limit = 2; // Load 2 more each time

        viewMoreBtn?.addEventListener('click', function() {
            fetch('<?php echo URLROOT; ?>/jobs/getMoreJobs?offset=' + offset + '&limit=' + limit)
                .then(response => response.json())
                .then(data => {
                    if (data.jobs && data.jobs.length > 0) {
                        data.jobs.forEach(job => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item p-3';
                            li.innerHTML = `
                                <h6 class="mb-1">
                                    <a href="<?php echo URLROOT; ?>/jobs/view/${job.id}" class="text-decoration-none">
                                        ${job.title}
                                    </a>
                                </h6>
                                <p class="mb-1 small">${job.company}</p>
                                <p class="text-muted small mb-2">${job.location}</p>
                                <a href="<?php echo URLROOT; ?>/jobs/view/${job.id}" class="btn btn-sm btn-outline-primary">View Job</a>
                            `;
                            jobList.appendChild(li);
                        });
                        offset += limit;
                        if (offset >= <?php echo $data['totalJobs']; ?>) {
                            viewMoreBtn.style.display = 'none'; // Hide button when all jobs are shown
                        }
                    } else {
                        viewMoreBtn.style.display = 'none'; // Hide if no more jobs
                    }
                })
                .catch(error => console.error('Error fetching jobs:', error));
        });
    });

</script>

<?php require APPROOT . '/views/reused/footer.php'; ?>