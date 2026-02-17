<?php
class Group {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Create a new group
    public function create($data) {
        $this->db->query('INSERT INTO groups (name, description, creator_id, cover_image, created_at) 
                         VALUES (:name, :description, :creator_id, :cover_image, NOW())');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':creator_id', $data['creator_id']);
        $this->db->bind(':cover_image', $data['cover_image'] ?? null);
        
        // Execute
        if ($this->db->execute()) {
            $groupId = $this->db->lastInsertId();
            
            // Add creator as admin member
            $this->db->query('INSERT INTO group_members (group_id, user_id, role, joined_at) 
                             VALUES (:group_id, :user_id, "admin", NOW())');
            $this->db->bind(':group_id', $groupId);
            $this->db->bind(':user_id', $data['creator_id']);
            $this->db->execute();
            
            return $groupId;
        } else {
            return false;
        }
    }
    
    // Update a group
    public function update($data) {
        $this->db->query('UPDATE groups SET 
                         name = :name, 
                         description = :description, 
                         cover_image = :cover_image, 
                         updated_at = NOW() 
                         WHERE id = :id AND creator_id = :creator_id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':cover_image', $data['cover_image'] ?? null);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':creator_id', $data['creator_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Delete a group
    public function delete($id, $userId) {
        // Check if user is admin or group creator
        $this->db->query('SELECT role FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        $user = $this->db->single();
        
        if ($user->role === 'admin') {
            // Admin can delete any group
            $this->db->query('DELETE FROM groups WHERE id = :id');
            $this->db->bind(':id', $id);
        } else {
            // Only creator can delete their groups
            $this->db->query('DELETE FROM groups WHERE id = :id AND creator_id = :creator_id');
            $this->db->bind(':id', $id);
            $this->db->bind(':creator_id', $userId);
        }
        
        return $this->db->execute();
    }
    
    // Get single group by ID
    public function getGroupById($id) {
        $this->db->query('SELECT g.*, u.first_name, u.last_name, 
                         (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count 
                         FROM groups g
                         JOIN users u ON g.creator_id = u.id
                         WHERE g.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Get all groups with optional search
    public function getAllGroups($search = '', $limit = 10, $offset = 0) {
        $sql = 'SELECT g.*, u.first_name, u.last_name, 
               (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count 
               FROM groups g
               JOIN users u ON g.creator_id = u.id';
        
        if (!empty($search)) {
            $sql .= ' WHERE g.name LIKE :search OR g.description LIKE :search';
        }
        
        $sql .= ' ORDER BY g.created_at DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);
        
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Get groups user is member of
    public function getUserGroups($userId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT g.*, gm.role as user_role, 
                         (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count 
                         FROM groups g
                         JOIN group_members gm ON g.id = gm.group_id
                         WHERE gm.user_id = :user_id
                         ORDER BY g.name
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Join a group
    public function joinGroup($groupId, $userId) {
        // Check if already a member
        $this->db->query('SELECT id FROM group_members WHERE group_id = :group_id AND user_id = :user_id');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':user_id', $userId);
        $this->db->execute(); // Execute the query
        if ($this->db->rowCount() > 0) {
            return false; // Already a member
        }
    
        // Insert new membership
        $this->db->query('INSERT INTO group_members (group_id, user_id, role, joined_at) 
                         VALUES (:group_id, :user_id, "member", NOW())');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':user_id', $userId);
    
        try {
            if ($this->db->execute()) {
                return true; // Successful join
            }
            return false; // Insert failed for some reason
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("JoinGroup Error: " . $e->getMessage());
            return false;
        }
    }
    
    // Leave a group
    public function leaveGroup($groupId, $userId) {
        // Check if user is the creator
        $this->db->query('SELECT creator_id FROM groups WHERE id = :id');
        $this->db->bind(':id', $groupId);
        $group = $this->db->single();
        
        if ($group && $group->creator_id == $userId) {
            return false; // Creator cannot leave
        }
        
        $this->db->query('DELETE FROM group_members WHERE group_id = :group_id AND user_id = :user_id');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Get group members
    public function getGroupMembers($groupId, $limit = 20, $offset = 0) {
        $this->db->query('SELECT gm.*, u.first_name, u.last_name, p.profile_picture, p.headline 
                         FROM group_members gm
                         JOIN users u ON gm.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE gm.group_id = :group_id
                         ORDER BY gm.role = "admin" DESC, u.first_name, u.last_name
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Create group post
    public function createGroupPost($data) {
        $this->db->query('INSERT INTO group_posts (group_id, user_id, content, media, created_at) 
                         VALUES (:group_id, :user_id, :content, :media, NOW())');
        
        $this->db->bind(':group_id', $data['group_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':media', $data['media'] ?? null);
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }
    
    // Get group posts
    public function getGroupPosts($groupId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT gp.*, u.first_name, u.last_name, p.profile_picture 
                         FROM group_posts gp
                         JOIN users u ON gp.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE gp.group_id = :group_id
                         ORDER BY gp.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    public function getGroupPostById($postId) {
        $this->db->query("SELECT gp.*, g.name as group_name, u.first_name, u.last_name, p.profile_picture, p.headline 
                         FROM group_posts gp
                         JOIN groups g ON gp.group_id = g.id
                         JOIN users u ON gp.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE gp.id = :post_id");
        $this->db->bind(':post_id', $postId, PDO::PARAM_INT);
        return $this->db->single();
    }
    
    // Check if user is member of group
    public function isMember($groupId, $userId) {
        $this->db->query('SELECT role FROM group_members 
                         WHERE group_id = :group_id AND user_id = :user_id');
        
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        return $result ? $result->role : false;
    }

    public function addGroupPostComment($data) {
        $this->db->query("INSERT INTO group_post_comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
        $this->db->bind(':post_id', $data['post_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content', $data['content']);
        if ($this->db->execute()) {
            // Increment comment_count
            $this->db->query("UPDATE group_posts SET comment_count = comment_count + 1 WHERE id = :post_id");
            $this->db->bind(':post_id', $data['post_id']);
            $this->db->execute();
            return true;
        }
        return false;
    }

    // Increment Members Count
    public function incrementMemberCount($groupId) {
        $this->db->query('UPDATE groups SET member_count = member_count + 1 WHERE id = :id');
        $this->db->bind(':id', $groupId);
        return $this->db->execute();
    }

    // Add the new method
    public function getPostsFromGroups($groupIds, $limit = 10, $offset = 0) {
        if (empty($groupIds)) {
            return [];
        }
    
        $params = [];
        foreach ($groupIds as $index => $groupId) {
            $params[':group_' . $index] = $groupId;
        }
        $placeholders = implode(', ', array_keys($params));
    
        $this->db->query("SELECT gp.*, g.name as group_name, u.first_name, u.last_name, p.profile_picture, p.headline 
                         FROM group_posts gp
                         JOIN groups g ON gp.group_id = g.id
                         JOIN users u ON gp.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE gp.group_id IN ($placeholders)
                         ORDER BY gp.created_at DESC
                         LIMIT :limit OFFSET :offset");
    
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value, PDO::PARAM_INT);
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
    
        return $this->db->resultSet();
    }

    public function getGroupPostComments($postId) {
        $this->db->query("SELECT c.*, u.first_name, u.last_name, p.profile_picture 
                         FROM group_post_comments c
                         JOIN users u ON c.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE c.post_id = :post_id
                         ORDER BY c.created_at ASC");
        $this->db->bind(':post_id', $postId, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function editGroupPostComment($commentId, $content) {
        $this->db->query("UPDATE group_post_comments SET content = :content WHERE id = :comment_id");
        $this->db->bind(':comment_id', $commentId, PDO::PARAM_INT);
        $this->db->bind(':content', $content);
        return $this->db->execute();
    }
    
    public function deleteGroupPostComment($commentId, $postId) {
        $this->db->query("DELETE FROM group_post_comments WHERE id = :comment_id");
        $this->db->bind(':comment_id', $commentId, PDO::PARAM_INT);
        if ($this->db->execute()) {
            // Decrement comment_count
            $this->db->query("UPDATE group_posts SET comment_count = comment_count - 1 WHERE id = :post_id");
            $this->db->bind(':post_id', $postId, PDO::PARAM_INT);
            $this->db->execute();
            return true;
        }
        return false;
    }
    
    public function getGroupPostCommentById($commentId) {
        $this->db->query("SELECT c.*, u.first_name, u.last_name, p.profile_picture 
                         FROM group_post_comments c
                         JOIN users u ON c.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE c.id = :comment_id");
        $this->db->bind(':comment_id', $commentId, PDO::PARAM_INT);
        return $this->db->single();
    }
}