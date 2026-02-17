<?php
// controllers/Groups.php

class Groups extends Controller {
    public function __construct() {
        $this->groupModel = $this->model('Group');
    }
    
    public function index() {
        // Get search query
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // Get groups
        $groups = $this->groupModel->getAllGroups($search, $perPage, $offset);
        
        $data = [
            'title' => 'Groups',
            'groups' => $groups,
            'search' => $search,
            'page' => $page,
            'per_page' => $perPage
        ];
        
        $this->view('groups/index', $data);
    }
    
    public function show($groupId) {
        // Get group details
        $group = $this->groupModel->getGroupById($groupId);
        
        if (!$group) {
            flash('group_error', 'Group not found', 'alert alert-danger');
            redirect('groups');
            return;
        }
        
        // Check if user is a member
        $isMember = false;
        if (isLoggedIn()) {
            $userRole = $this->groupModel->isMember($groupId, $_SESSION['user_id']);
            $isMember = $userRole !== false; // Convert role to boolean membership status
        }
        
        // Get group posts
        $posts = $this->groupModel->getGroupPosts($groupId, 20, 0);
        
        // Get group members
        $members = $this->groupModel->getGroupMembers($groupId, 8, 0);
        
        $data = [
            'title' => $group->name,
            'group' => $group,
            'is_member' => $isMember, // Use 'is_member' to match view logic
            'posts' => $posts,
            'members' => $members
        ];
        
        $this->view('groups/view', $data);
    }

    public function create() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Handle cover image upload if present
            $coverImage = isset($_FILES['cover_image']) ? $_FILES['cover_image'] : null;
            $coverImagePath = null;
            
            if ($coverImage && $coverImage['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/group_covers/';
                
                // Ensure upload directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($coverImage['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($coverImage['tmp_name'], $targetPath)) {
                    $coverImagePath = $filename;
                }
            }
            
            // Prepare data
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'creator_id' => $_SESSION['user_id'],
                'cover_image' => $coverImagePath,
                'name_err' => '',
                'description_err' => ''
            ];
            
            // Validate inputs
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter group name';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter group description';
            }
            
            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['description_err'])) {
                // Create group
                if ($groupId = $this->groupModel->create($data)) {
                    flash('group_success', 'Group created successfully');
                    redirect('groups/show/' . $groupId);
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('groups/create', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => '',
                'description_err' => ''
            ];
            
            // Load view
            $this->view('groups/create', $data);
        }
    }
    
    public function edit($groupId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get group details
        $group = $this->groupModel->getGroupById($groupId);
        
        if (!$group) {
            flash('group_error', 'Group not found', 'alert alert-danger');
            redirect('groups');
            return;
        }
        
        // Check if user is authorized to edit this group
        if ($group->creator_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            flash('group_error', 'You are not authorized to edit this group', 'alert alert-danger');
            redirect('groups/show/' . $groupId);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Handle cover image upload if present
            $coverImage = isset($_FILES['cover_image']) ? $_FILES['cover_image'] : null;
            $coverImagePath = $group->cover_image; // Keep existing cover by default
            
            if ($coverImage && $coverImage['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/group_covers/';
                
                // Ensure upload directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($coverImage['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($coverImage['tmp_name'], $targetPath)) {
                    $coverImagePath = $filename;
                }
            }
            
            // Check if cover should be removed
            if (isset($_POST['remove_cover']) && $_POST['remove_cover'] == '1') {
                $coverImagePath = null;
            }
            
            // Prepare data
            $data = [
                'id' => $groupId,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'creator_id' => $_SESSION['user_id'],
                'cover_image' => $coverImagePath,
                'name_err' => '',
                'description_err' => ''
            ];
            
            // Validate inputs
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter group name';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter group description';
            }
            
            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['description_err'])) {
                // Update group
                if ($this->groupModel->update($data)) {
                    flash('group_success', 'Group updated successfully');
                    redirect('groups/show/' . $groupId);
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('groups/edit', $data);
            }
        } else {
            // Init data
            $data = [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'cover_image' => $group->cover_image,
                'name_err' => '',
                'description_err' => ''
            ];
            
            // Load view
            $this->view('groups/edit', $data);
        }
    }
    
    public function delete($groupId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get group details
        $group = $this->groupModel->getGroupById($groupId);
        
        if (!$group) {
            flash('group_error', 'Group not found', 'alert alert-danger');
            redirect('groups');
            return;
        }
        
        // Check if user is authorized to delete this group
        if ($group->creator_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            flash('group_error', 'You are not authorized to delete this group', 'alert alert-danger');
            redirect('groups/show/' . $groupId);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Delete group
            if ($this->groupModel->delete($groupId, $_SESSION['user_id'])) {
                flash('group_success', 'Group deleted successfully');
                redirect('groups');
            } else {
                flash('group_error', 'Failed to delete group', 'alert alert-danger');
                redirect('groups/show/' . $groupId);
            }
        } else {
            // Confirm deletion
            $data = [
                'title' => 'Delete Group',
                'group' => $group
            ];
            
            $this->view('groups/delete', $data);
        }
    }
    
    public function join($groupId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('group_error', 'Please log in to join a group', 'alert alert-danger');
            redirect('users/login');
            return;
        }
    
        // Get group details to ensure it exists
        $group = $this->groupModel->getGroupById($groupId);
        if (!$group) {
            flash('group_error', 'Group not found', 'alert alert-danger');
            redirect('groups');
            return;
        }
    
        $userId = $_SESSION['user_id'];
        try {
            if ($this->groupModel->joinGroup($groupId, $userId)) {
                // Increment member count only if join is successful
                $this->groupModel->incrementMemberCount($groupId);
                flash('group_success', 'You have successfully joined the group!');
            } else {
                flash('group_error', 'You are already a member of this group or an error occurred', 'alert alert-warning');
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                flash('group_error', 'You are already a member of this group', 'alert alert-warning');
            } else {
                flash('group_error', 'An error occurred while joining the group', 'alert alert-danger');
                error_log("Join Error: " . $e->getMessage());
            }
        }
    
        redirect('groups/show/' . $groupId);
    }
    
    public function leave($groupId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Leave group
        if ($this->groupModel->leaveGroup($groupId, $_SESSION['user_id'])) {
            flash('group_success', 'You have left the group');
        } else {
            flash('group_error', 'Failed to leave group. Note: Group creators cannot leave their groups.', 'alert alert-danger');
        }
        
        redirect('groups/show/' . $groupId);
    }
    
    public function members($groupId) {
        // Get group details
        $group = $this->groupModel->getGroupById($groupId);
        
        if (!$group) {
            flash('group_error', 'Group not found', 'alert alert-danger');
            redirect('groups');
            return;
        }
        
        // Get members
        $members = $this->groupModel->getGroupMembers($groupId, 50, 0);
        
        $data = [
            'title' => $group->name . ' - Members',
            'group' => $group,
            'members' => $members
        ];
        
        $this->view('groups/members', $data);
    }
    
    public function post($groupId) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    // Check if user is a member
    $userRole = $this->groupModel->isMember($groupId, $_SESSION['user_id']);
    
    if (!$userRole) {
        flash('group_error', 'You must be a member to post in this group', 'alert alert-danger');
        redirect('groups/show/' . $groupId);
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process form
        
        // Handle media upload if present
        $media = isset($_FILES['media']) ? $_FILES['media'] : null;
        $mediaPath = null;
        
        if ($media && $media['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/group_post_media/';
            
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
            }
        }
        
        // Prepare data
        $content = trim($_POST['content']);
        
        if (empty($content) && empty($mediaPath)) {
            flash('post_error', 'Post cannot be empty', 'alert alert-danger');
            redirect('groups/show/' . $groupId);
            return;
        }
        
        $data = [
            'group_id' => $groupId,
            'user_id' => $_SESSION['user_id'],
            'content' => $content,
            'media' => $mediaPath
        ];
        
        // Create post
        if ($this->groupModel->createGroupPost($data)) {
            flash('post_success', 'Post created successfully');
        } else {
            flash('post_error', 'Failed to create post', 'alert alert-danger');
        }
        
        redirect('groups/show/' . $groupId);
    } else {
        redirect('groups/show/' . $groupId);
    }
}
    
public function commentPost($postId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $post = $this->groupModel->getGroupPostById($postId);
    if (!$post) {
        flash('post_error', 'Post not found', 'alert alert-danger');
        redirect('groups');
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'post_id' => $postId,
            'user_id' => $_SESSION['user_id'],
            'content' => trim($_POST['content'])
        ];

        if (empty($data['content'])) {
            flash('post_error', 'Comment cannot be empty', 'alert alert-danger');
            redirect('groups/viewPost/' . $postId);
            return;
        }

        if ($this->groupModel->addGroupPostComment($data)) {
            flash('post_success', 'Comment added successfully');
        } else {
            flash('post_error', 'Failed to add comment', 'alert alert-danger');
        }

        redirect('groups/viewPost/' . $postId);
    }
}

    // Like a group post
public function likePost($postId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $post = $this->groupModel->getGroupPostById($postId);
    if (!$post) {
        flash('post_error', 'Post not found', 'alert alert-danger');
        redirect('groups');
        return;
    }

    if ($this->groupModel->likeGroupPost($postId, $_SESSION['user_id'])) {
        flash('post_success', 'Post liked');
    } else {
        flash('post_error', 'Unable to like post', 'alert alert-danger');
    }

    redirect('groups/show/' . $post->group_id);
}

// View a group post (for comments)
public function viewPost($postId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $post = $this->groupModel->getGroupPostById($postId);
    if (!$post) {
        flash('post_error', 'Post not found', 'alert alert-danger');
        redirect('groups');
        return;
    }

    // Fetch comments (assuming a comments table and method exist)
    $comments = $this->groupModel->getGroupPostComments($postId);

    $data = [
        'title' => 'Group Post',
        'post' => $post,
        'comments' => $comments
    ];

    $this->view('groups/post', $data);
}

// Share a group post
public function sharePost($postId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $post = $this->groupModel->getGroupPostById($postId);
    if (!$post) {
        flash('post_error', 'Post not found', 'alert alert-danger');
        redirect('groups');
        return;
    }

    // Create a new user post that shares the group post
    $shareData = [
        'user_id' => $_SESSION['user_id'],
        'content' => "Shared a post from {$post->group_name}: " . $post->content,
        'media' => $post->media,
        'visibility' => 'public'
    ];

    if ($this->postModel->create($shareData)) {
        flash('post_success', 'Post shared successfully');
    } else {
        flash('post_error', 'Failed to share post', 'alert alert-danger');
    }

    redirect('groups/show/' . $post->group_id);
}

// Edit a group post
public function editPost($postId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $post = $this->groupModel->getGroupPostById($postId);
    if (!$post || $post->user_id != $_SESSION['user_id']) {
        flash('post_error', 'You are not authorized to edit this post', 'alert alert-danger');
        redirect('groups');
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'id' => $postId,
            'content' => trim($_POST['content']),
            'media' => $post->media
        ];

        // Handle media upload if present
        $media = isset($_FILES['media']) ? $_FILES['media'] : null;
        if ($media && $media['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/group_post_media/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($media['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($media['tmp_name'], $targetPath)) {
                $data['media'] = $filename;
            }
        }

        if ($this->groupModel->updateGroupPost($data)) {
            flash('post_success', 'Post updated successfully');
            redirect('groups/show/' . $post->group_id);
        } else {
            flash('post_error', 'Failed to update post', 'alert alert-danger');
            $data['post'] = (object)$data;
            $this->view('groups/edit_post', $data);
        }
    } else {
        $data = [
            'title' => 'Edit Group Post',
            'post' => $post
        ];
        $this->view('groups/edit_post', $data);
    }
}

// Delete a group post
public function deletePost($postId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $post = $this->groupModel->getGroupPostById($postId);
    if (!$post || $post->user_id != $_SESSION['user_id']) {
        flash('post_error', 'You are not authorized to delete this post', 'alert alert-danger');
        redirect('groups');
        return;
    }

    if ($this->groupModel->deleteGroupPost($postId)) {
        flash('post_success', 'Post deleted successfully');
    } else {
        flash('post_error', 'Failed to delete post', 'alert alert-danger');
    }

    redirect('groups/show/' . $post->group_id);
}

public function editComment($commentId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $comment = $this->groupModel->getGroupPostCommentById($commentId);
    if (!$comment || $comment->user_id != $_SESSION['user_id']) {
        flash('comment_error', 'You can only edit your own comments', 'alert alert-danger');
        redirect('groups');
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $content = trim($_POST['content']);
        if (empty($content)) {
            flash('comment_error', 'Comment cannot be empty', 'alert alert-danger');
            redirect('groups/viewPost/' . $comment->post_id);
            return;
        }

        if ($this->groupModel->editGroupPostComment($commentId, $content)) {
            flash('comment_success', 'Comment updated successfully');
        } else {
            flash('comment_error', 'Failed to update comment', 'alert alert-danger');
        }
        redirect('groups/viewPost/' . $comment->post_id);
    } else {
        $data = [
            'title' => 'Edit Comment',
            'comment' => $comment
        ];
        $this->view('groups/edit_comment', $data);
    }
}

public function deleteComment($commentId) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }

    $comment = $this->groupModel->getGroupPostCommentById($commentId);
    if (!$comment || $comment->user_id != $_SESSION['user_id']) {
        flash('comment_error', 'You can only delete your own comments', 'alert alert-danger');
        redirect('groups');
        return;
    }

    if ($this->groupModel->deleteGroupPostComment($commentId, $comment->post_id)) {
        flash('comment_success', 'Comment deleted successfully');
    } else {
        flash('comment_error', 'Failed to delete comment', 'alert alert-danger');
    }
    redirect('groups/viewPost/' . $comment->post_id);
}
}