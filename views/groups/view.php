<?php
// views/groups/view.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1><?php echo $data['title']; ?></h1>
    <?php flash('group_success'); ?>
    <?php flash('group_error'); ?>
    <?php flash('post_success'); ?>
    <?php flash('post_error'); ?>
    <?php if (isset($data['group'])): ?>
        <div class="card group">
            <?php if ($data['group']->cover_image): ?>
                <div class="group-cover" style="background-image: url('<?php echo URLROOT; ?>/public/uploads/group_covers/<?php echo $data['group']->cover_image; ?>')"></div>
            <?php else: ?>
                <div class="group-cover" style="background-image: url('https://images.pexels.com/photos/1181467/pexels-photo-1181467.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1')"></div>
            <?php endif; ?>
            
            <div class="card-body">
                <h2><?php echo htmlspecialchars($data['group']->name); ?></h2>
                <p><strong>Category:</strong> <?php echo isset($data['group']->category) ? htmlspecialchars($data['group']->category) : 'Uncategorized'; ?></p>
                <p><strong>Members:</strong> <?php echo $data['group']->member_count; ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($data['group']->description)); ?></p>
                <p><strong>Created by:</strong> <?php echo htmlspecialchars($data['group']->first_name . ' ' . $data['group']->last_name); ?></p>
                
                <?php if (isLoggedIn() && !(isset($data['is_member']) && $data['is_member'])): ?>
                    <a href="<?php echo URLROOT; ?>/groups/join/<?php echo $data['group']->id; ?>" class="btn btn-primary">Join Group</a>
                <?php elseif (isset($data['is_member']) && $data['is_member']): ?>
                    <p class="text-success">You are a member of this group.</p>
                    <a href="<?php echo URLROOT; ?>/groups/leave/<?php echo $data['group']->id; ?>" class="btn btn-warning">Leave Group</a>
                <?php endif; ?>
                
                <?php if (isLoggedIn() && (isset($data['group']->created_by) && $data['group']->created_by == $_SESSION['user_id'] || $_SESSION['user_role'] == 'admin')): ?>
                    <a href="<?php echo URLROOT; ?>/groups/edit/<?php echo $data['group']->id; ?>" class="btn btn-warning">Edit Group</a>
                    <a href="<?php echo URLROOT; ?>/groups/delete/<?php echo $data['group']->id; ?>" class="btn btn-danger">Delete Group</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Posting Form for Members -->
        <?php if (isset($data['is_member']) && $data['is_member']): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h3>Create a Post</h3>
                    <form action="<?php echo URLROOT; ?>/groups/post/<?php echo $data['group']->id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3" placeholder="Share something with the group..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="media" class="form-label">Add Media (optional)</label>
                            <input type="file" class="form-control" name="media" id="media" accept="image/*,video/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Display Group Posts -->
        <div class="mt-4">
            <h3>Group Posts</h3>
            <?php if (!empty($data['posts'])): ?>
                <?php foreach ($data['posts'] as $post): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <?php if ($post->profile_picture): ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_pics/<?php echo $post->profile_picture; ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/40" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo htmlspecialchars($post->first_name . ' ' . $post->last_name); ?></strong>
                                    <small class="text-muted"><?php echo $post->created_at; ?></small>
                                </div>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
                            <?php if ($post->media): ?>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $post->media)): ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/group_post_media/<?php echo $post->media; ?>" alt="Post Media" class="img-fluid">
                                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $post->media)): ?>
                                    <video controls class="img-fluid">
                                        <source src="<?php echo URLROOT; ?>/public/uploads/group_post_media/<?php echo $post->media; ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts yet. Be the first to share something!</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p>Group not found.</p>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/reused/footer.php'; ?>