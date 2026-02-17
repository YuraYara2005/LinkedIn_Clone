<?php
class Job {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Create a new job listing
    public function create($data) {
        $this->db->query('INSERT INTO jobs (title, company, location, job_type, description, requirements, 
                         salary_min, salary_max, posted_by, created_at) 
                         VALUES (:title, :company, :location, :job_type, :description, :requirements, 
                         :salary_min, :salary_max, :posted_by, NOW())');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':job_type', $data['job_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':requirements', $data['requirements']);
        $this->db->bind(':salary_min', $data['salary_min']);
        $this->db->bind(':salary_max', $data['salary_max']);
        $this->db->bind(':posted_by', $data['posted_by']);
        
        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Update job listing
    public function update($data) {
        $this->db->query('UPDATE jobs SET 
                         title = :title, 
                         company = :company, 
                         location = :location, 
                         job_type = :job_type, 
                         description = :description, 
                         requirements = :requirements, 
                         salary_min = :salary_min, 
                         salary_max = :salary_max, 
                         updated_at = NOW() 
                         WHERE id = :id AND posted_by = :posted_by');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':job_type', $data['job_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':requirements', $data['requirements']);
        $this->db->bind(':salary_min', $data['salary_min']);
        $this->db->bind(':salary_max', $data['salary_max']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':posted_by', $data['posted_by']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Delete job listing
    public function delete($id, $userId) {
        // Check if the user is admin
        $this->db->query('SELECT role FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        $user = $this->db->single();
        
        if ($user->role === 'admin') {
            // Admin can delete any job
            $this->db->query('DELETE FROM jobs WHERE id = :id');
            $this->db->bind(':id', $id);
        } else {
            // Users can only delete their own jobs
            $this->db->query('DELETE FROM jobs WHERE id = :id AND posted_by = :posted_by');
            $this->db->bind(':id', $id);
            $this->db->bind(':posted_by', $userId);
        }
        
        return $this->db->execute();
    }
    
    // Get single job by ID
    public function getJobById($id) {
        $this->db->query('SELECT j.*, u.first_name, u.last_name 
                         FROM jobs j 
                         JOIN users u ON j.posted_by = u.id 
                         WHERE j.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Search jobs with various filters
    public function searchJobs($filters = [], $limit = 10, $offset = 0) {
        $sql = 'SELECT j.*, u.first_name, u.last_name, 
                COUNT(ja.id) as application_count 
                FROM jobs j 
                JOIN users u ON j.posted_by = u.id 
                LEFT JOIN job_applications ja ON j.id = ja.job_id';
        
        $whereClauses = [];
        $params = [];
        
        // Build WHERE clauses based on filters
        if (!empty($filters['keyword'])) {
            $whereClauses[] = '(j.title LIKE :keyword OR j.description LIKE :keyword OR j.requirements LIKE :keyword)';
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        if (!empty($filters['location'])) {
            $whereClauses[] = 'j.location LIKE :location';
            $params[':location'] = '%' . $filters['location'] . '%';
        }
        
        if (!empty($filters['job_type'])) {
            $whereClauses[] = 'j.job_type = :job_type';
            $params[':job_type'] = $filters['job_type'];
        }
        
        if (!empty($filters['company'])) {
            $whereClauses[] = 'j.company LIKE :company';
            $params[':company'] = '%' . $filters['company'] . '%';
        }
        
        // Add WHERE clause to SQL if filters exist
        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }
        
        // Group by to avoid duplicate results from the JOIN
        $sql .= ' GROUP BY j.id';
        
        // Add sorting
        $sql .= ' ORDER BY j.created_at DESC';
        
        // Add pagination
        $sql .= ' LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);
        
        // Bind parameters
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Apply for a job
    public function applyForJob($jobId, $userId, $resumeFile, $coverLetter) {
        $this->db->query('INSERT INTO job_applications (job_id, applicant_id, resume, cover_letter, created_at) 
                         VALUES (:job_id, :applicant_id, :resume, :cover_letter, NOW())');
        
        $this->db->bind(':job_id', $jobId);
        $this->db->bind(':applicant_id', $userId);
        $this->db->bind(':resume', $resumeFile);
        $this->db->bind(':cover_letter', $coverLetter);
        
        return $this->db->execute();
    }
    
    // Get job applications for a specific job
    public function getJobApplications($jobId, $postedBy) {
        $this->db->query('SELECT ja.*, u.first_name, u.last_name, p.headline 
                         FROM job_applications ja 
                         JOIN users u ON ja.applicant_id = u.id 
                         LEFT JOIN profiles p ON u.id = p.user_id 
                         JOIN jobs j ON ja.job_id = j.id 
                         WHERE ja.job_id = :job_id AND j.posted_by = :posted_by 
                         ORDER BY ja.created_at DESC');
        
        $this->db->bind(':job_id', $jobId);
        $this->db->bind(':posted_by', $postedBy);
        
        return $this->db->resultSet();
    }
    
    // Check if user has already applied to job
    public function checkApplicationExists($jobId, $userId) {
        $this->db->query('SELECT id FROM job_applications 
                         WHERE job_id = :job_id AND applicant_id = :applicant_id');
        
        $this->db->bind(':job_id', $jobId);
        $this->db->bind(':applicant_id', $userId);
        
        $this->db->execute();
        
        return ($this->db->rowCount() > 0);
    }
    
    // Count total jobs based on filters
    public function countJobs($filters = []) {
        $sql = 'SELECT COUNT(*) as count FROM jobs j';
        
        $whereClauses = [];
        $params = [];
        
        // Build WHERE clauses based on filters
        if (!empty($filters['keyword'])) {
            $whereClauses[] = '(j.title LIKE :keyword OR j.description LIKE :keyword OR j.requirements LIKE :keyword)';
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        if (!empty($filters['location'])) {
            $whereClauses[] = 'j.location LIKE :location';
            $params[':location'] = '%' . $filters['location'] . '%';
        }
        
        if (!empty($filters['job_type'])) {
            $whereClauses[] = 'j.job_type = :job_type';
            $params[':job_type'] = $filters['job_type'];
        }
        
        if (!empty($filters['company'])) {
            $whereClauses[] = 'j.company LIKE :company';
            $params[':company'] = '%' . $filters['company'] . '%';
        }
        
        // Add WHERE clause to SQL if filters exist
        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }
        
        $this->db->query($sql);
        
        // Bind parameters
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        $result = $this->db->single();
        return $result->count;
    }

    public function getTotalJobs() {
        $this->db->query('SELECT COUNT(*) as total FROM jobs');
        $result = $this->db->single();
        return $result->total;
    }
}