<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Generate connection report
    public function generateConnectionReport($userId) {
        $this->db->query('SELECT u.first_name, u.last_name, p.headline, p.industry, c.created_at as connected_since
                         FROM connections c
                         JOIN users u ON (c.user_id = u.id OR c.connected_user_id = u.id)
                         LEFT JOIN profiles p ON u.id = p.user_id
                         WHERE (c.user_id = :user_id OR c.connected_user_id = :user_id)
                         AND c.status = "accepted"
                         AND u.id != :user_id
                         ORDER BY u.first_name, u.last_name');
                         
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    // Generate job application report
    public function generateJobApplicationReport($userId) {
        $this->db->query('SELECT j.title, j.company, j.location, ja.created_at as applied_date, ja.status
                         FROM job_applications ja
                         JOIN jobs j ON ja.job_id = j.id
                         WHERE ja.applicant_id = :user_id
                         ORDER BY ja.created_at DESC');
                         
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    // Generate job posting report (for employers)
    public function generateJobPostingReport($userId) {
        $this->db->query('SELECT j.title, j.company, j.created_at as posted_date, 
                         COUNT(ja.id) as application_count,
                         (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id AND status = "reviewing") as reviewing,
                         (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id AND status = "interviewed") as interviewed,
                         (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id AND status = "rejected") as rejected,
                         (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id AND status = "accepted") as accepted
                         FROM jobs j
                         LEFT JOIN job_applications ja ON j.id = ja.job_id
                         WHERE j.posted_by = :user_id
                         GROUP BY j.id
                         ORDER BY j.created_at DESC');
                         
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    // Generate group activity report (for group admins)
    public function generateGroupActivityReport($groupId, $adminId) {
        // First verify user is admin of this group
        $this->db->query('SELECT id FROM group_members 
                         WHERE group_id = :group_id AND user_id = :user_id AND role = "admin"');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':user_id', $adminId);
        
        if ($this->db->rowCount() == 0) {
            return false; // Not an admin
        }
        
        // Get the report data
        $this->db->query('SELECT COUNT(DISTINCT gm.user_id) as total_members,
                         (SELECT COUNT(*) FROM group_posts WHERE group_id = :group_id) as total_posts,
                         (SELECT COUNT(*) FROM group_posts WHERE group_id = :group_id) as total_posts,
                         (SELECT COUNT(DISTINCT user_id) FROM group_posts WHERE group_id = :group_id) as active_posters,
                         (SELECT COUNT(*) FROM group_members WHERE group_id = :group_id AND joined_at > DATE_SUB(NOW(), INTERVAL 30 DAY)) as new_members
                         FROM group_members gm
                         WHERE gm.group_id = :group_id');
                         
        $this->db->bind(':group_id', $groupId);
        
        $summary = $this->db->single();
        
        // Get recent activity
        $this->db->query('SELECT u.first_name, u.last_name, "post" as activity_type, gp.created_at
                         FROM group_posts gp
                         JOIN users u ON gp.user_id = u.id
                         WHERE gp.group_id = :group_id
                         UNION
                         SELECT u.first_name, u.last_name, "joined" as activity_type, gm.joined_at as created_at
                         FROM group_members gm
                         JOIN users u ON gm.user_id = u.id
                         WHERE gm.group_id = :group_id
                         ORDER BY created_at DESC
                         LIMIT 50');
                         
        $this->db->bind(':group_id', $groupId);
        
        $activities = $this->db->resultSet();
        
        return [
            'summary' => $summary,
            'activities' => $activities
        ];
    }
    
    // Generate user activity report (for admins only)
    public function generateUserActivityReport($adminId) {
        // Verify admin status
        $this->db->query('SELECT role FROM users WHERE id = :id');
        $this->db->bind(':id', $adminId);
        $user = $this->db->single();
        
        if ($user->role !== 'admin') {
            return false; // Not an admin
        }
        
        // Get system stats
        $this->db->query('SELECT 
                         (SELECT COUNT(*) FROM users) as total_users,
                         (SELECT COUNT(*) FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)) as new_users,
                         (SELECT COUNT(*) FROM posts) as total_posts,
                         (SELECT COUNT(*) FROM connections WHERE status = "accepted") as total_connections,
                         (SELECT COUNT(*) FROM jobs) as total_jobs,
                         (SELECT COUNT(*) FROM job_applications) as total_applications,
                         (SELECT COUNT(*) FROM groups) as total_groups');
        
        $stats = $this->db->single();
        
        // Get most active users
        $this->db->query('SELECT u.id, u.first_name, u.last_name, p.profile_picture,
                         (SELECT COUNT(*) FROM posts WHERE user_id = u.id) as post_count,
                         (SELECT COUNT(*) FROM connections WHERE (user_id = u.id OR connected_user_id = u.id) AND status = "accepted") as connection_count
                         FROM users u
                         LEFT JOIN profiles p ON u.id = p.user_id
                         ORDER BY post_count DESC, connection_count DESC
                         LIMIT 10');
        
        $active_users = $this->db->resultSet();
        
        return [
            'stats' => $stats,
            'active_users' => $active_users
        ];
    }
    
    // Save report for later viewing
    public function saveReport($userId, $title, $type, $data) {
        $this->db->query('INSERT INTO saved_reports (user_id, title, report_type, report_data, created_at) 
                         VALUES (:user_id, :title, :type, :data, NOW())');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', $title);
        $this->db->bind(':type', $type);
        $this->db->bind(':data', json_encode($data));
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }
    
    // Get saved reports
    public function getSavedReports($userId) {
        $this->db->query('SELECT id, title, report_type, created_at 
                         FROM saved_reports 
                         WHERE user_id = :user_id
                         ORDER BY created_at DESC');
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    // Get saved report by ID
    public function getSavedReportById($reportId, $userId) {
        $this->db->query('SELECT * FROM saved_reports 
                         WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':id', $reportId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->single();
    }
}