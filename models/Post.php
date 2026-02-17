<?php
class Post {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Create a new post
    public function create($data) {
        $this->db->query('INSERT INTO posts (user_id, content, media, created_at) 
                         VALUES (:user_id, :content, :media, NOW())');
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':media', $data['media'] ?? null);
        
        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Update a post
    public function update($data) {
        $this->db->query('UPDATE posts SET content = :content, media = :media, updated_at = NOW() 
                         WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':media', $data['media'] ?? null);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Delete a post
    public function delete($id, $userId) {
        // First check if user is admin or post owner
        $this->db->query('SELECT role FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        $user = $this->db->single();
        
        if ($user->role === 'admin') {
            // Admin can delete any post
            $this->db->query('DELETE FROM posts WHERE id = :id');
            $this->db->bind(':id', $id);
        } else {
            // Regular user can only delete their own posts
            $this->db->query('DELETE FROM posts WHERE id = :id AND user_id = :user_id');
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $userId);
        }
        
        return $this->db->execute();
    }
    
    // Get single post by ID
    public function getPostById($id) {
        $this->db->query('SELECT p.*, u.first_name, u.last_name, pr.profile_picture, pr.headline,
                         (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
                         (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) as comment_count
                         FROM posts p
                         JOIN users u ON p.user_id = u.id
                         LEFT JOIN profiles pr ON u.id = pr.user_id
                         WHERE p.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Get feed posts (posts from user and their connections)
    public function getFeedPosts($userId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT p.*, u.first_name, u.last_name, pr.profile_picture, pr.headline,
                         (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
                         (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) as comment_count,
                         (SELECT COUNT(*) > 0 FROM post_likes WHERE post_id = p.id AND user_id = :current_user) as user_liked
                         FROM posts p
                         JOIN users u ON p.user_id = u.id
                         LEFT JOIN profiles pr ON u.id = pr.user_id
                         WHERE p.user_id = :user_id 
                         OR p.user_id IN (
                            SELECT IF(user_id = :user_id, connected_user_id, user_id) 
                            FROM connections 
                            WHERE (user_id = :user_id OR connected_user_id = :user_id) 
                            AND status = "accepted"
                         )
                         ORDER BY p.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':current_user', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Get posts by a specific user
    public function getUserPosts($userId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT p.*, u.first_name, u.last_name, pr.profile_picture, pr.headline,
                         (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
                         (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) as comment_count
                         FROM posts p
                         JOIN users u ON p.user_id = u.id
                         LEFT JOIN profiles pr ON u.id = pr.user_id
                         WHERE p.user_id = :user_id
                         ORDER BY p.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Like a post
    public function likePost($postId, $userId) {
        // Check if already liked
        $this->db->query('SELECT id FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
        $this->db->bind(':post_id', $postId);
        $this->db->bind(':user_id', $userId);
        $existingLike = $this->db->single();
        
        if ($existingLike) {
            // Unlike if already liked
            $this->db->query('DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
            $this->db->bind(':post_id', $postId);
            $this->db->bind(':user_id', $userId);
            return $this->db->execute() ? 'unliked' : false;
        } else {
            // Like if not liked
            $this->db->query('INSERT INTO post_likes (post_id, user_id, created_at) 
                             VALUES (:post_id, :user_id, NOW())');
            $this->db->bind(':post_id', $postId);
            $this->db->bind(':user_id', $userId);
            return $this->db->execute() ? 'liked' : false;
        }
    }
    
    // Add comment to post
    public function addComment($postId, $userId, $content) {
        $this->db->query('INSERT INTO post_comments (post_id, user_id, content, created_at) 
                         VALUES (:post_id, :user_id, :content, NOW())');
        
        $this->db->bind(':post_id', $postId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':content', $content);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Get comments for a post
    public function getComments($postId, $limit = 5, $offset = 0) {
        $this->db->query('SELECT pc.*, u.first_name, u.last_name, pr.profile_picture 
                         FROM post_comments pc
                         JOIN users u ON pc.user_id = u.id
                         LEFT JOIN profiles pr ON u.id = pr.user_id
                         WHERE pc.post_id = :post_id
                         ORDER BY pc.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':post_id', $postId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
}