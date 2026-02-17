<?php
// views/jobs/view.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1><?php echo $data['title']; ?></h1>
    <?php if (isset($data['job'])): ?>
        <div class="card job">
            <h2><?php echo htmlspecialchars($data['job']->title); ?></h2>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($data['job']->company); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($data['job']->location); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($data['job']->job_type); ?></p>
            <p><strong>Salary:</strong> <?php echo htmlspecialchars($data['job']->salary_min . ' - ' . $data['job']->salary_max); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($data['job']->description)); ?></p>
            <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($data['job']->requirements)); ?></p>
            <?php if (isLoggedIn() && !$data['has_applied']): ?>
                <a href="<?php echo URLROOT; ?>/jobs/apply/<?php echo $data['job']->id; ?>" class="btn btn-primary">Apply Now</a>
            <?php elseif ($data['has_applied']): ?>
                <p class="text-success">You have already applied for this job.</p>
            <?php endif; ?>
            <?php if (isLoggedIn() && ($data['job']->posted_by == $_SESSION['user_id'] || $_SESSION['user_role'] == 'admin')): ?>
                <a href="<?php echo URLROOT; ?>/jobs/edit/<?php echo $data['job']->id; ?>" class="btn btn-warning">Edit Job</a>
                <a href="<?php echo URLROOT; ?>/jobs/delete/<?php echo $data['job']->id; ?>" class="btn btn-danger">Delete Job</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Job not found.</p>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>