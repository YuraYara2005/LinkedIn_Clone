<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-3">
        <!-- Job Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">Filters</h2>
            </div>
            <div class="card-body">
                <form id="job-filter-form" action="<?php echo URLROOT; ?>/jobs" method="GET">
                    <div class="mb-3">
                        <label for="keyword" class="form-label">Keywords</label>
                        <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Job title, skills, or company" value="<?php echo $data['filters']['keyword']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="City, state, or remote" value="<?php echo $data['filters']['location']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="job_type" class="form-label">Job Type</label>
                        <select class="form-select" id="job_type" name="job_type">
                            <option value="">All types</option>
                            <option value="full-time" <?php echo ($data['filters']['job_type'] === 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                            <option value="part-time" <?php echo ($data['filters']['job_type'] === 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="contract" <?php echo ($data['filters']['job_type'] === 'contract') ? 'selected' : ''; ?>>Contract</option>
                            <option value="internship" <?php echo ($data['filters']['job_type'] === 'internship') ? 'selected' : ''; ?>>Internship</option>
                            <option value="remote" <?php echo ($data['filters']['job_type'] === 'remote') ? 'selected' : ''; ?>>Remote</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="company" class="form-label">Company</label>
                        <input type="text" class="form-control" id="company" name="company" placeholder="Company name" value="<?php echo $data['filters']['company']; ?>">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <button type="button" id="clear-filters-btn" class="btn btn-outline-secondary" onclick="document.getElementById('job-filter-form').reset();">Clear Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">Find Jobs</h2>
                
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo URLROOT; ?>/jobs/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Post a Job
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <?php if ($data['jobs']): ?>
                    <p class="text-muted mb-4">Showing <?php echo count($data['jobs']); ?> of <?php echo $data['pagination']['total_items']; ?> jobs</p>
                    
                    <?php foreach ($data['jobs'] as $job): ?>
                        <div class="job-card p-3 border-bottom">
                            <div class="row">
                                <div class="col-md-9">
                                    <h3 class="job-title h5 mb-1">
                                        <a href="<?php echo URLROOT; ?>/jobs/show/<?php echo $job->id; ?>" class="text-decoration-none">
                                            <?php echo $job->title; ?>
                                        </a>
                                    </h3>
                                    <p class="job-company mb-1"><?php echo $job->company; ?></p>
                                    <p class="job-location mb-2"><?php echo $job->location; ?> • <?php echo ucfirst($job->job_type); ?></p>
                                    <div class="d-flex align-items-center text-muted small mb-2">
                                        <i class="far fa-clock me-1"></i>
                                        <span class="job-posted-date">Posted <?php echo date('M d', strtotime($job->created_at)); ?></span>
                                        <span class="mx-2">•</span>
                                        <i class="far fa-user me-1"></i>
                                        <span><?php echo $job->application_count; ?> applicants</span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <a href="<?php echo URLROOT; ?>/jobs/show/<?php echo $job->id; ?>" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Pagination -->
                    <?php if ($data['pagination']['total_pages'] > 1): ?>
                        <nav aria-label="Jobs pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                    <li class="page-item <?php echo ($i === $data['pagination']['page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo URLROOT; ?>/jobs?page=<?php echo $i; ?>&keyword=<?php echo $data['filters']['keyword']; ?>&location=<?php echo $data['filters']['location']; ?>&job_type=<?php echo $data['filters']['job_type']; ?>&company=<?php echo $data['filters']['company']; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-search text-muted mb-3" style="font-size: 48px;"></i>
                        <h3 class="h5">No jobs found</h3>
                        <p class="text-muted">Try adjusting your search criteria or check back later for new opportunities.</p>
                        
                        <?php if (isLoggedIn()): ?>
                            <a href="<?php echo URLROOT; ?>/jobs/create" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Post a Job
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>