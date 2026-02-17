<?php
class Profile {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Fetch a profile by its ID, joining with users table
    public function getProfileById($id) {
        $this->db->query('
            SELECT p.id, p.user_id, u.first_name, u.last_name, u.email, p.headline, p.about, p.location, p.industry, p.website, p.profile_picture, p.cover_image, p.created_at, p.updated_at, u.role
            FROM profiles p
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        if (!$result) {
            error_log("No profile found for ID: $id");
            // Optionally, create a default profile for the user_id if found
            $this->db->query('SELECT user_id FROM profiles WHERE id = :id');
            $this->db->bind(':id', $id);
            $userRow = $this->db->single();
            if ($userRow && $userRow->user_id) {
                $this->createDefaultProfile($userRow->user_id);
                // Retry the query
                $this->db->query('
                    SELECT p.id, p.user_id, u.first_name, u.last_name, u.email, p.headline, p.about, p.location, p.industry, p.website, p.profile_picture, p.cover_image, p.created_at, p.updated_at, u.role
                    FROM profiles p
                    LEFT JOIN users u ON p.user_id = u.id
                    WHERE p.id = :id
                ');
                $this->db->bind(':id', $id);
                $result = $this->db->single();
            }
        }
        return $result;
    }

    // Fetch a profile by user_id, joining with users table
    public function getProfileByUserId($userId) {
        $this->db->query('
            SELECT p.id, p.user_id, u.first_name, u.last_name, u.email, p.headline, p.about, p.location, p.industry, p.website, p.profile_picture, p.cover_image, p.created_at, p.updated_at, u.role
            FROM profiles p
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        if (!$result) {
            error_log("No profile found for user_id: $userId");
            // Verify if user exists
            $this->db->query('SELECT id FROM users WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            $userExists = $this->db->single();
            if ($userExists) {
                // Create a default profile
                $this->createDefaultProfile($userId);
                // Retry the query
                $this->db->query('
                    SELECT p.id, p.user_id, u.first_name, u.last_name, u.email, p.headline, p.about, p.location, p.industry, p.website, p.profile_picture, p.cover_image, p.created_at, p.updated_at, u.role
                    FROM profiles p
                    LEFT JOIN users u ON p.user_id = u.id
                    WHERE p.user_id = :user_id
                ');
                $this->db->bind(':user_id', $userId);
                $result = $this->db->single();
                if ($result) {
                    error_log("Created default profile for user_id: $userId");
                } else {
                    error_log("Failed to fetch profile after creating default for user_id: $userId");
                }
            } else {
                error_log("User does not exist for user_id: $userId");
            }
        }
        return $result;
    }

    // Create a default profile for a user
    private function createDefaultProfile($userId) {
        $this->db->query('
            INSERT INTO profiles (user_id, headline, created_at)
            VALUES (:user_id, :headline, NOW())
        ');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':headline', 'New User');
        $success = $this->db->execute();
        if (!$success) {
            error_log("Failed to create default profile for user_id: $userId");
        }
        return $success;
    }

    // Update profile
    public function updateProfile($data) {
        $this->db->query('
            UPDATE profiles 
            SET headline = :headline, about = :about, location = :location, industry = :industry, website = :website, profile_picture = :profile_picture, updated_at = NOW()
            WHERE user_id = :user_id
        ');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':headline', $data['headline']);
        $this->db->bind(':about', $data['about']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':industry', $data['industry']);
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':profile_picture', $data['profile_picture']);
        return $this->db->execute();
    }

    // Fetch profile views count
    public function getProfileViewsCount($userId) {
        $this->db->query("SELECT COUNT(*) as count FROM profile_views WHERE viewed_user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->count ?: 0;
    }

    // Fetch experiences by user_id
    public function getExperiences($userId) {
        $this->db->query('SELECT * FROM experiences WHERE user_id = :user_id ORDER BY start_date DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Add a new experience
    public function addExperience($data) {
        $this->db->query('INSERT INTO experiences (user_id, title, company, location, start_date, end_date, description) VALUES (:user_id, :title, :company, :location, :start_date, :end_date, :description)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date'] ?: null);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    // Update an existing experience
    public function updateExperience($data) {
        $this->db->query('UPDATE experiences SET title = :title, company = :company, location = :location, start_date = :start_date, end_date = :end_date, description = :description WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date'] ?: null);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    // Delete an experience
    public function deleteExperience($id, $userId) {
        $this->db->query('DELETE FROM experiences WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Fetch educations by user_id
    public function getEducations($userId) {
        $this->db->query('SELECT * FROM educations WHERE user_id = :user_id ORDER BY start_date DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Add a new education
    public function addEducation($data) {
        $this->db->query('INSERT INTO educations (user_id, degree, institution, start_date, end_date, description) VALUES (:user_id, :degree, :institution, :start_date, :end_date, :description)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':degree', $data['degree']);
        $this->db->bind(':institution', $data['institution']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date'] ?: null);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    // Update an existing education
    public function updateEducation($data) {
        $this->db->query('UPDATE educations SET degree = :degree, institution = :institution, start_date = :start_date, end_date = :end_date, description = :description WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':degree', $data['degree']);
        $this->db->bind(':institution', $data['institution']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date'] ?: null);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    // Delete an education
    public function deleteEducation($id, $userId) {
        $this->db->query('DELETE FROM educations WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Fetch skills by user_id
    public function getSkills($userId) {
        $this->db->query('SELECT * FROM skills WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Add a new skill
   public function addSkill($data) {
    $this->db->query('INSERT INTO skills (user_id, skill) VALUES (:user_id, :skill)');
    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':skill', $data['skill']); // Changed from skill_name to skill
    return $this->db->execute();
}
    // Update an existing skill
    public function updateSkill($data) {
    $this->db->query('UPDATE skills SET skill = :skill WHERE id = :id AND user_id = :user_id');
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':skill', $data['skill']); // Changed from skill_name to skill
    return $this->db->execute();
}

    // Delete a skill
    public function deleteSkill($id, $userId) {
        $this->db->query('DELETE FROM skills WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
}