<?php
class Message {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getConversations($userId) {
        $this->db->query("
            SELECT DISTINCT u.id AS user_id, u.first_name, u.last_name, p.profile_picture,
                (SELECT content FROM messages m 
                 WHERE (m.sender_id = u.id AND m.receiver_id = :user_id) 
                    OR (m.sender_id = :user_id AND m.receiver_id = u.id) 
                 ORDER BY m.created_at DESC LIMIT 1) AS last_message
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            JOIN messages m ON (m.sender_id = u.id AND m.receiver_id = :user_id) 
                             OR (m.receiver_id = u.id AND m.sender_id = :user_id)
            WHERE u.id != :user_id
            ORDER BY (SELECT MAX(created_at) FROM messages WHERE (sender_id = u.id AND receiver_id = :user_id) OR (receiver_id = u.id AND sender_id = :user_id)) DESC
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getMessages($userId, $receiverId) {
        $this->db->query("
            SELECT m.*, u.first_name, u.last_name, p.profile_picture
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE (m.sender_id = :user_id AND m.receiver_id = :receiver_id)
               OR (m.sender_id = :receiver_id AND m.receiver_id = :user_id)
            ORDER BY m.created_at ASC
        ");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':receiver_id', $receiverId);
        return $this->db->resultSet();
    }

    public function sendMessage($data) {
        $this->db->query("
            INSERT INTO messages (sender_id, receiver_id, content)
            VALUES (:sender_id, :receiver_id, :content)
        ");
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':content', $data['content']);
        return $this->db->execute();
    }

    public function searchUsers($query, $userId) {
        $this->db->query("
            SELECT u.id, u.first_name, u.last_name, p.profile_picture
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE (u.first_name LIKE :query OR u.last_name LIKE :query OR u.email LIKE :query)
              AND u.id != :user_id
            LIMIT 10
        ");
        $this->db->bind(':query', '%' . $query . '%');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
}