<?php
// views/jobs/apply.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Apply for <?php echo $data['title']; ?></h1>
    <?php flash('application_success'); ?>
    <?php flash('application_error'); ?>
    <?php flash('resume_error'); ?>
    <form action="<?php echo URLROOT; ?>/jobs/apply/<?php echo $data['job']->id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="cover_letter" class="form-label">Cover Letter</label>
            <textarea class="form-control" id="cover_letter" name="cover_letter" rows="5" placeholder="Write your cover letter here..."></textarea>
        </div>
        <div class="mb-3">
            <label for="resume" class="form-label">Upload Resume <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
        <a href="<?php echo URLROOT; ?>/jobs/show/<?php echo $data['job']->id; ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>