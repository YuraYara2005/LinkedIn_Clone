<?php
// views/groups/create.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1>Create a Group</h1>
    <?php flash('group_success'); ?>
    <?php flash('group_error'); ?>
    <form action="<?php echo URLROOT; ?>/groups/create" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Group Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name']; ?>" required>
            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
            <textarea class="form-control <?php echo !empty($data['description_err']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="5" required><?php echo $data['description']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
            <select class="form-select <?php echo !empty($data['category_err']) ? 'is-invalid' : ''; ?>" id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Technology" <?php echo (isset($data['category']) && $data['category'] === 'Technology') ? 'selected' : ''; ?>>Technology</option>
                <option value="Business" <?php echo (isset($data['category']) && $data['category'] === 'Business') ? 'selected' : ''; ?>>Business</option>
                <option value="Marketing" <?php echo (isset($data['category']) && $data['category'] === 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                <option value="Creative" <?php echo (isset($data['category']) && $data['category'] === 'Creative') ? 'selected' : ''; ?>>Creative</option>
                <option value="Science" <?php echo (isset($data['category']) && $data['category'] === 'Science') ? 'selected' : ''; ?>>Science</option>
                <option value="Health" <?php echo (isset($data['category']) && $data['category'] === 'Health') ? 'selected' : ''; ?>>Health</option>
                <option value="Education" <?php echo (isset($data['category']) && $data['category'] === 'Education') ? 'selected' : ''; ?>>Education</option>
            </select>
            <span class="invalid-feedback"><?php echo $data['category_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Create Group</button>
    </form>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>