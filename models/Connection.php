<?php
class Connection {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Send connection request
    public function sendRequest($userId, $recipientId) {
        $this->db->query('INSERT INTO connections (user_id, connected_user_id, status, created_at) 
                         VALUES (:user_id, :connected_user_id, "pending", NOW())');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':connected_user_id', $recipientId);
        return $this->db->execute();
    }
    
    // Accept connection request
    public function acceptRequest($connectionId, $userId) {
        $this->db->query('UPDATE connections SET status = "accepted", updated_at = NOW() 
                         WHERE id = :id AND connected_user_id = :user_id AND status = "pending"');
        $this->db->bind(':id', $connectionId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    // Reject or withdraw connection request
    public function rejectRequest($connectionId, $userId) {
        $this->db->query('DELETE FROM connections 
                         WHERE id = :id AND (connected_user_id = :user_id OR user_id = :user_id) AND status = "pending"');
        $this->db->bind(':id', $connectionId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    // Remove existing connection
    public function removeConnection($connectionId, $userId) {
        $this->db->query('DELETE FROM connections 
                         WHERE id = :id AND (connected_user_id = :user_id OR user_id = :user_id) AND status = "accepted"');
        $this->db->bind(':id', $connectionId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    // Get all pending requests for a user
    public function getPendingRequests($userId) {
        $this->db->query('SELECT c.*, u.first_name, u.last_name, p.headline, p.profile_picture
                         FROM connections c
                         JOIN users u ON c.user_id = u.id
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE c.connected_user_id = :user_id AND c.status = "pending"
                         ORDER BY c.created_at DESC');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->resultSet();
    
        // Debug: Log the query result
        error_log('getPendingRequests result for user ' . $userId . ': ' . json_encode($result));
    
        return $result ?: [];
    }
    
    // Get all connections for a user
    public function getConnections($userId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT c.id as connection_id, u.id, u.first_name, u.last_name, p.headline, p.profile_picture
                         FROM connections c
                         JOIN users u ON (c.connected_user_id = u.id OR c.user_id = u.id)
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE (c.user_id = :user_id OR c.connected_user_id = :user_id) 
                         AND c.status = "accepted"
                         AND u.id != :user_id
                         ORDER BY u.first_name, u.last_name
                         LIMIT :limit OFFSET :offset');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
    
    // Count connections
    public function countConnections($userId) {
        $this->db->query('SELECT COUNT(*) as count
                         FROM connections
                         WHERE (user_id = :user_id OR connected_user_id = :user_id) 
                         AND status = "accepted"');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->count;
    }

    public function countPendingRequests($userId) {
        $this->db->query('SELECT COUNT(*) as count
                         FROM connections
                         WHERE connected_user_id = :user_id AND status = "pending"');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->count;
    }
    
    // Check connection status between two users
    public function getConnectionStatus($userId, $otherId) {
        $this->db->query('SELECT id, status, user_id
                         FROM connections
                         WHERE (user_id = :user_id AND connected_user_id = :other_id)
                         OR (user_id = :other_id AND connected_user_id = :user_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':other_id', $otherId);
        $result = $this->db->single();
        
        if (!$result) {
            return ['status' => 'none'];
        }
        
        return [
            'id' => $result->id,
            'status' => $result->status,
            'is_sender' => $result->user_id == $userId
        ];
    }
    
    // Export connections to CSV
    public function exportConnections($userId) {
        $connections = $this->getConnections($userId, 1000, 0); // Get up to 1000 connections
        
        $data = [];
        $data[] = ['First Name', 'Last Name', 'Headline', 'Connected Since'];
        
        foreach ($connections as $connection) {
            $data[] = [
                $connection->first_name,
                $connection->last_name,
                $connection->headline,
                date("Y-m-d")
            ];
        }
        
        return $data;
    }
}