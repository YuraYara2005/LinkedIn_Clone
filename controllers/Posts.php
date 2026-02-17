<?php
// controllers/Posts.php

class Posts extends Controller {
    public function __construct() {
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get posts for news feed
        $posts = $this->postModel->getFeedPosts($_SESSION['user_id'], 20, 0);
        
        $data = [
            'title' => 'News Feed',
            'posts' => $posts
        ];
        
        $this->view('posts/index', $data);
    }
    
    public function create() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug: Log the incoming POST data
            error_log("POST Data: " . print_r($_POST, true));
            error_log("FILES Data: " . print_r($_FILES, true));
            
            // Handle media upload if present
            $media = isset($_FILES['media_file']) ? $_FILES['media_file'] : null;
            $mediaPath = null;
            $mediaType = null;
            
            if ($media && $media['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PUBLICROOT . '/uploads/post_media/';
                
                // Ensure upload directory exists with correct permissions
                if (!file_exists($uploadDir)) {
                    if (!mkdir($uploadDir, 0777, true)) {
                        error_log("Failed to create directory: $uploadDir");
                        flash('post_error', 'Failed to create upload directory', 'alert alert-danger');
                        $this->view('posts/create', ['content' => $_POST['content'], 'content_err' => '']);
                        return;
                    }
                }
                
                // Generate unique filename
                $extension = pathinfo($media['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($media['tmp_name'], $targetPath)) {
                    $mediaPath = $filename;
                    $mediaType = $media['type'];
                    error_log("File uploaded successfully to: $targetPath");
                } else {
                    error_log("Failed to move uploaded file to: $targetPath");
                    flash('post_error', 'Failed to upload media file', 'alert alert-danger');
                    $this->view('posts/create', ['content' => $_POST['content'], 'content_err' => '']);
                    return;
                }
            }
            
            // Prepare data
            $data = [
                'user_id' => $_SESSION['user_id'],
                'content' => trim($_POST['content']),
                'media' => $mediaPath,
                'media_type' => $mediaType,
                'content_err' => ''
            ];
            
            // Validate content
            if (empty($data['content']) && empty($data['media'])) {
                $data['content_err'] = 'Please add some content or media to your post';
                error_log("Validation failed: Post cannot be empty");
            }
            
            // Make sure errors are empty
            if (empty($data['content_err'])) {
                // Create post
                $postId = $this->postModel->create($data);
                if ($postId) {
                    flash('post_success', 'Post created successfully');
                    redirect('posts');
                } else {
                    error_log("Failed to create post in database");
                    flash('post_error', 'Failed to create post', 'alert alert-danger');
                    $this->view('posts/create', $data);
                }
            } else {
                // Load view with errors
                $this->view('posts/create', $data);
            }
        } else {
            // Init data
            $data = [
                'content' => '',
                'content_err' => ''
            ];
            
            // Load view
            $this->view('posts/create', $data);
        }
    }
    
    public function edit($postId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get post details
        $post = $this->postModel->getPostById($postId);
        
        if (!$post) {
            flash('post_error', 'Post not found', 'alert alert-danger');
            redirect('posts');
            return;
        }
        
        // Check if user is authorized to edit this post
        if ($post->user_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            flash('post_error', 'You are not authorized to edit this post', 'alert alert-danger');
            redirect('posts');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Handle media upload if present
            $media = isset($_FILES['media_file']) ? $_FILES['media_file'] : null;
            $mediaPath = $post->media; // Keep existing media by default
            $mediaType = $post->media_type;
            
            if ($media && $media['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PUBLICROOT . '/uploads/post_media/';
                
                // Ensure upload directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($media['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($media['tmp_name'], $targetPath)) {
                    $mediaPath = $filename;
                    $mediaType = $media['type'];
                }
            }
            
            // Check if media should be removed
            if (isset($_POST['remove_media']) && $_POST['remove_media'] == '1') {
                $mediaPath = null;
                $mediaType = null;
            }
            
            // Prepare data
            $data = [
                'id' => $postId,
                'user_id' => $_SESSION['user_id'],
                'content' => trim($_POST['content']),
                'media' => $mediaPath,
                'media_type' => $mediaType,
                'content_err' => ''
            ];
            
            // Validate content
            if (empty($data['content']) && empty($data['media'])) {
                $data['content_err'] = 'Post cannot be empty';
            }
            
            // Make sure errors are empty
            if (empty($data['content_err'])) {
                // Update post
                if ($this->postModel->update($data)) {
                    flash('post_success', 'Post updated successfully');
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('posts/edit', $data);
            }
        } else {
            // Init data
            $data = [
                'id' => $post->id,
                'content' => $post->content,
                'media' => $post->media,
                'media_type' => $post->media_type,
                'content_err' => ''
            ];
            
            // Load view
            $this->view('posts/edit', $data);
        }
    }
    
    public function delete($postId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get post details
        $post = $this->postModel->getPostById($postId);
        
        if (!$post) {
            flash('post_error', 'Post not found', 'alert alert-danger');
            redirect('posts');
            return;
        }
        
        // Check if user is authorized to delete this post
        if ($post->user_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            flash('post_error', 'You are not authorized to delete this post', 'alert alert-danger');
            redirect('posts');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Delete post
            if ($this->postModel->delete($postId, $_SESSION['user_id'])) {
                flash('post_success', 'Post deleted successfully');
                redirect('posts');
            } else {
                flash('post_error', 'Failed to delete post', 'alert alert-danger');
                redirect('posts');
            }
        } else {
            // Confirm deletion
            $data = [
                'title' => 'Delete Post',
                'post' => $post
            ];
            
            $this->view('posts/delete', $data);
        }
    }
    
    public function like($postId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Like/Unlike post
        $result = $this->postModel->likePost($postId, $_SESSION['user_id']);
        
        if ($result) {
            // If post was liked, create notification for post owner
            if ($result === 'liked') {
                $post = $this->postModel->getPostById($postId);
                
                if ($post && $post->user_id != $_SESSION['user_id']) {
                    $notification = [
                        'user_id' => $post->user_id,
                        'message' => $_SESSION['user_name'] . ' liked your post',
                        'type' => 'post_like',
                        'link' => 'posts/show/' . $postId
                    ];
                    
                    createNotification($post->user_id, $notification['message'], $notification['type'], $notification['link']);
                }
            }
            
            // Return JSON response for AJAX requests
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'success', 'action' => $result]);
                exit;
            }
            
            // Redirect for non-AJAX requests
            redirect($_SERVER['HTTP_REFERER'] ?? 'posts');
        } else {
            // Return JSON response for AJAX requests
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error']);
                exit;
            }
            
            flash('post_error', 'Failed to process like', 'alert alert-danger');
            redirect($_SERVER['HTTP_REFERER'] ?? 'posts');
        }
    }
    
    public function comment($postId) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process comment
        $content = trim($_POST['content']);
        
        if (empty($content)) {
            flash('comment_error', 'Comment cannot be empty', 'alert alert-danger');
            redirect('posts/show/' . $postId);
            return;
        }
        
        // Add comment
        if ($commentId = $this->postModel->addComment($postId, $_SESSION['user_id'], $content)) {
            // Create notification for post owner
            $post = $this->postModel->getPostById($postId);
            
            if ($post && $post->user_id != $_SESSION['user_id']) {
                $notification = [
                    'user_id' => $post->user_id,
                    'message' => $_SESSION['user_name'] . ' commented on your post',
                    'type' => 'post_comment',
                    'link' => 'posts/show/' . $postId
                ];
                
                createNotification($post->user_id, $notification['message'], $notification['type'], $notification['link']);
            }
            
            flash('comment_success', 'Comment added successfully');
        } else {
            flash('comment_error', 'Failed to add comment', 'alert alert-danger');
        }
        
        redirect('posts/show/' . $postId);
    } else {
        redirect('posts/show/' . $postId);
    }
}
    
    public function show($postId) {
        // Get post details
        $post = $this->postModel->getPostById($postId);
        
        if (!$post) {
            flash('post_error', 'Post not found', 'alert alert-danger');
            redirect('posts');
            return;
        }
        
        // Get comments
        $comments = $this->postModel->getComments($postId, 50, 0);
        
        $data = [
            'title' => $post->first_name . ' ' . $post->last_name . '\'s Post',
            'post' => $post,
            'comments' => $comments
        ];
        
        $this->view('posts/view', $data);
    }
}