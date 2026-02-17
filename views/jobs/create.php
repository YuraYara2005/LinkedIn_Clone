<?php
// views/jobs/create.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Create a Job Posting</h1>
    <?php flash('job_success'); ?>
    <?php flash('job_error'); ?>
    <form action="<?php echo URLROOT; ?>/jobs/create" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?php echo !empty($data['title_err']) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo $data['title']; ?>" required>
            <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="company" class="form-label">Company <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?php echo !empty($data['company_err']) ? 'is-invalid' : ''; ?>" id="company" name="company" value="<?php echo $data['company']; ?>" required>
            <span class="invalid-feedback"><?php echo $data['company_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?php echo !empty($data['location_err']) ? 'is-invalid' : ''; ?>" id="location" name="location" value="<?php echo $data['location']; ?>" required>
            <span class="invalid-feedback"><?php echo $data['location_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="job_type" class="form-label">Job Type <span class="text-danger">*</span></label>
            <select class="form-select <?php echo !empty($data['job_type_err']) ? 'is-invalid' : ''; ?>" id="job_type" name="job_type" required>
                <option value="">Select Job Type</option>
                <option value="full-time" <?php echo ($data['job_type'] === 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                <option value="part-time" <?php echo ($data['job_type'] === 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                <option value="contract" <?php echo ($data['job_type'] === 'contract') ? 'selected' : ''; ?>>Contract</option>
                <option value="internship" <?php echo ($data['job_type'] === 'internship') ? 'selected' : ''; ?>>Internship</option>
                <option value="remote" <?php echo ($data['job_type'] === 'remote') ? 'selected' : ''; ?>>Remote</option>
            </select>
            <span class="invalid-feedback"><?php echo $data['job_type_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
            <textarea class="form-control <?php echo !empty($data['description_err']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="5" required><?php echo $data['description']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="requirements" class="form-label">Requirements <span class="text-danger">*</span></label>
            <textarea class="form-control <?php echo !empty($data['requirements_err']) ? 'is-invalid' : ''; ?>" id="requirements" name="requirements" rows="5" required><?php echo $data['requirements']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['requirements_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="salary_min" class="form-label">Salary Min</label>
            <input type="number" class="form-control" id="salary_min" name="salary_min" value="<?php echo $data['salary_min']; ?>" step="1000" min="0">
        </div>
        <div class="mb-3">
            <label for="salary_max" class="form-label">Salary Max</label>
            <input type="number" class="form-control" id="salary_max" name="salary_max" value="<?php echo $data['salary_max']; ?>" step="1000" min="0">
        </div>
        <button type="submit" class="btn btn-primary">Post Job</button>
    </form>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>