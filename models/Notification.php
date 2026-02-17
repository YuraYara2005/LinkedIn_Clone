<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Create a notification
    public function create($data) {
        $this->db->query('INSERT INTO notifications (user_id, message, type, link, is_read, created_at) 
                         VALUES (:user_id, :message, :type, :link, 0, NOW())');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':link', $data['link'] ?? '');
        
        return $this->db->execute();
    }
    
    // Get user notifications
    public function getUserNotifications($userId, $limit = 20, $offset = 0) {
        $this->db->query('SELECT * FROM notifications 
                         WHERE user_id = :user_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Mark notification as read
    public function markAsRead($id, $userId) {
        $this->db->query('UPDATE notifications SET is_read = 1 
                         WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Mark all user notifications as read
    public function markAllAsRead($userId) {
        $this->db->query('UPDATE notifications SET is_read = 1 
                         WHERE user_id = :user_id AND is_read = 0');
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Count unread notifications
    public function countUnread($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM notifications 
                         WHERE user_id = :user_id AND is_read = 0');
        
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result->count;
    }
    
    // Delete a notification
    public function delete($id, $userId) {
        $this->db->query('DELETE FROM notifications 
                         WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Delete old notifications (maintenance)
    public function deleteOldNotifications($days = 30) {
        $this->db->query('DELETE FROM notifications 
                         WHERE is_read = 1 AND created_at < DATE_SUB(NOW(), INTERVAL :days DAY)');
        
        $this->db->bind(':days', $days, PDO::PARAM_INT);
        
        return $this->db->execute();
    }
}