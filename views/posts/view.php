<?php
// views/posts/view.php
require_once APPROOT . '/views/reused/header.php';
require_once APPROOT . '/views/reused/navbar.php';
?>

<div class="container">
    <h1><?php echo $data['title']; ?></h1>
    <?php if (isset($data['post'])): ?>
        <div class="card post">
            <div class="post-header">
                <img src="<?php echo $data['post']->profile_picture ?: URLROOT . '/public/uploads/profile_pictures/default_profile_small.png'; ?>" alt="Poster">
                <div>
                    <h5><?php echo htmlspecialchars($data['post']->first_name . ' ' . $data['post']->last_name); ?></h5>
                    <p>Professional • <?php echo date('M d, Y', strtotime($data['post']->created_at)); ?> • <i class="fas fa-globe-americas"></i></p>
                </div>
            </div>
            <p class="post-content"><?php echo htmlspecialchars($data['post']->content); ?></p>
            <?php if ($data['post']->media): ?>
                <?php if (strpos($data['post']->media_type, 'image') !== false): ?>
                    <img src="<?php echo URLROOT . '/public/uploads/post_media/' . $data['post']->media; ?>" alt="Post Image" class="post-image">
                <?php else: ?>
                    <video controls class="post-image"><source src="<?php echo URLROOT . '/public/uploads/post_media/' . $data['post']->media; ?>" type="<?php echo $data['post']->media_type; ?>">Your browser does not support the video tag.</video>
                <?php endif; ?>
            <?php endif; ?>
            <div class="post-stats">
                <span><?php echo $data['post']->like_count; ?> Likes</span> • 
                <span><?php echo $data['post']->comment_count; ?> Comments</span>
            </div>
            <div class="post-actions">
                <a href="javascript:void(0);" onclick="likePost(<?php echo $data['post']->id; ?>)">
                    <i class="fas fa-thumbs-up"></i> Like
                </a>
                <a href="javascript:void(0);" onclick="document.getElementById('comment-section').style.display = 'block';">
                    <i class="fas fa-comment"></i> Comment
                </a>
                <a href="#"><i class="fas fa-share"></i> Share</a>
            </div>
            <div id="comment-section" class="comment-section" style="display:none;">
                <h3>Comments</h3>
                <?php if (isset($data['comments']) && is_array($data['comments'])): ?>
                    <?php foreach ($data['comments'] as $comment): ?>
                        <div class="comment">
                            <img src="<?php echo $comment->profile_picture ?: URLROOT . '/public/uploads/profile_pictures/default_profile_small.png'; ?>" alt="Commenter">
                            <div>
                                <h6><?php echo htmlspecialchars($comment->first_name . ' ' . $comment->last_name); ?></h6>
                                <p><?php echo htmlspecialchars($comment->content); ?></p>
                                <small><?php echo date('M d, Y', strtotime($comment->created_at)); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet.</p>
                <?php endif; ?>
                <form action="<?php echo URLROOT; ?>/posts/comment/<?php echo $data['post']->id; ?>" method="POST" class="comment-form" onsubmit="return addComment(event, <?php echo $data['post']->id; ?>)">
                    <input type="text" name="content" placeholder="Add a comment..." required>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p>Post not found.</p>
    <?php endif; ?>
</div>

<script>
// Ensure the comment section is shown immediately if there are comments
document.addEventListener('DOMContentLoaded', function() {
    const commentSection = document.getElementById('comment-section');
    if (commentSection.querySelectorAll('.comment').length > 0) {
        commentSection.style.display = 'block';
    }
});

function addComment(event, postId) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Optionally reload the page to show the new comment
            window.location.reload();
        } else {
            alert('Failed to add comment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the comment');
    });
    
    return false;
}

function likePost(postId) {
    fetch('<?php echo URLROOT; ?>/posts/like/' + postId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        }
    });
}
</script>

<?php require_once APPROOT . '/views/reused/footer.php'; ?>