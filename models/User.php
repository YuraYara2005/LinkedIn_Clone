<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Register user
    public function register($data) {
        $this->db->query('INSERT INTO users (first_name, last_name, email, password, role, created_at) 
                          VALUES (:first_name, :last_name, :email, :password, :role, NOW())');
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'user'); // Default role

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            }
        }
        
        return false;
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get User by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Get user profile by ID
    public function getProfileById($userId) {
        $this->db->query('SELECT u.*, p.* 
                         FROM users u 
                         LEFT JOIN profiles p ON u.id = p.user_id 
                         WHERE u.id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    // Update user profile
    public function updateProfile($data) {
        $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $data['user_id']);
        $existing = $this->db->single();

        if ($existing) {
            $this->db->query('UPDATE profiles SET 
                             headline = :headline, 
                             about = :about, 
                             location = :location, 
                             industry = :industry,
                             profile_picture = :profile_picture,
                             website = :website,
                             updated_at = NOW() 
                             WHERE user_id = :user_id');
        } else {
            $this->db->query('INSERT INTO profiles 
                             (user_id, headline, about, location, industry, profile_picture, website, created_at) 
                             VALUES 
                             (:user_id, :headline, :about, :location, :industry, :profile_picture, :website, NOW())');
        }

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':headline', $data['headline']);
        $this->db->bind(':about', $data['about']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':industry', $data['industry']);
        $this->db->bind(':profile_picture', $data['profile_picture']);
        $this->db->bind(':website', $data['website']);

        return $this->db->execute();
    }

    // Get all users (with optional filter)
    public function getUsers($filter = '', $limit = 10, $offset = 0) {
        $sql = 'SELECT u.id, u.first_name, u.last_name, u.email, u.role, p.headline, p.profile_picture 
                FROM users u 
                LEFT JOIN profiles p ON u.id = p.user_id';
        
        if (!empty($filter)) {
            $sql .= ' WHERE CONCAT(u.first_name, " ", u.last_name) LIKE :filter 
                     OR p.headline LIKE :filter';
        }
        
        $sql .= ' ORDER BY u.created_at DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);
        
        if (!empty($filter)) {
            $this->db->bind(':filter', '%' . $filter . '%');
        }
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Change user role
    public function changeRole($userId, $newRole) {
        $this->db->query('UPDATE users SET role = :role WHERE id = :id');
        $this->db->bind(':role', $newRole);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($id) {
        $this->db->query('START TRANSACTION');
        $this->db->execute();
        
        $this->db->query('DELETE FROM connections WHERE user_id = :id OR connected_user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        $this->db->query('DELETE FROM job_applications WHERE applicant_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        $this->db->query('DELETE FROM jobs WHERE posted_by = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        $this->db->query('DELETE FROM posts WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        $this->db->query('DELETE FROM profiles WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        $this->db->query('DELETE FROM notifications WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->execute();
        
        if ($result) {
            $this->db->query('COMMIT');
            $this->db->execute();
            return true;
        } else {
            $this->db->query('ROLLBACK');
            $this->db->execute();
            return false;
        }
    }

    // Get total users for pagination
    public function getTotalUsers($searchQuery = '') {
        $sql = 'SELECT COUNT(*) as total 
                FROM users u 
                LEFT JOIN profiles p ON u.id = p.user_id';
        
        if (!empty($searchQuery)) {
            $sql .= ' WHERE CONCAT(u.first_name, " ", u.last_name) LIKE :search 
                     OR p.headline LIKE :search';
        }
        
        $this->db->query($sql);
        
        if (!empty($searchQuery)) {
            $this->db->bind(':search', "%$searchQuery%");
        }
        
        $result = $this->db->single();
        return $result->total;
    }
}