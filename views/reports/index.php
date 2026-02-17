<?php require APPROOT . '/views/reused/header.php'; ?>

<div class="row">
    <div class="col-lg-3">
        <!-- Reports Sidebar -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Report Types</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="<?php echo URLROOT; ?>/reports/connections" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-friends me-2"></i> Connections Report
                    </a>
                    <a href="<?php echo URLROOT; ?>/reports/applications" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i> Job Applications Report
                    </a>
                    <a href="<?php echo URLROOT; ?>/reports/postings" class="list-group-item list-group-item-action">
                        <i class="fas fa-briefcase me-2"></i> Job Postings Report
                    </a>
                    <?php if(checkRole('admin')): ?>
                        <a href="<?php echo URLROOT; ?>/reports/admin" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i> System Activity Report
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Saved Reports -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Saved Reports</h5>
            </div>
            <div class="card-body">
                <?php if ($data['saved_reports']): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($data['saved_reports'] as $report): ?>
                            <li class="list-group-item px-0">
                                <a href="<?php echo URLROOT; ?>/reports/view/<?php echo $report->id; ?>" class="text-decoration-none d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block"><?php echo $report->title; ?></span>
                                        <small class="text-muted"><?php echo date('M d, Y', strtotime($report->created_at)); ?></small>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill"><?php echo ucfirst($report->report_type); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">No saved reports yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h4 mb-0">Reports Dashboard</h2>
            </div>
            
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <!-- Connections Report Card -->
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-friends text-primary mb-3" style="font-size: 48px;"></i>
                                <h3 class="h5">Connections Report</h3>
                                <p class="text-muted">Analyze your professional network growth and connections.</p>
                                <a href="<?php echo URLROOT; ?>/reports/connections" class="btn btn-primary">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Job Applications Report Card -->
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt text-success mb-3" style="font-size: 48px;"></i>
                                <h3 class="h5">Job Applications Report</h3>
                                <p class="text-muted">Track your job applications and their status.</p>
                                <a href="<?php echo URLROOT; ?>/reports/applications" class="btn btn-success">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Job Postings Report Card -->
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-briefcase text-warning mb-3" style="font-size: 48px;"></i>
                                <h3 class="h5">Job Postings Report</h3>
                                <p class="text-muted">Monitor performance of your job listings.</p>
                                <a href="<?php echo URLROOT; ?>/reports/postings" class="btn btn-warning">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(checkRole('admin')): ?>
                    <!-- Admin Report Card -->
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar text-danger mb-3" style="font-size: 48px;"></i>
                                <h3 class="h5">System Activity Report</h3>
                                <p class="text-muted">View overall platform statistics and user activities.</p>
                                <a href="<?php echo URLROOT; ?>/reports/admin" class="btn btn-danger">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <hr class="my-4">
                
                <!-- Report Export Options -->
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Export Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-file-pdf text-danger me-3" style="font-size: 32px;"></i>
                                    <div>
                                        <h5 class="mb-1">PDF Export</h5>
                                        <p class="text-muted mb-0">Download reports as PDF documents</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-file-csv text-success me-3" style="font-size: 32px;"></i>
                                    <div>
                                        <h5 class="mb-1">CSV Export</h5>
                                        <p class="text-muted mb-0">Download data in spreadsheet format</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Generate a report first, then use the export options available on the report page.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>