<?php
class Profiles extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
        $this->connectionModel = $this->model('Connection');
        $this->postModel = $this->model('Post');
        $this->profileModel = $this->model('Profile');
    }
    
    public function index($userId = null) {
        if (!$userId) {
            if (!isLoggedIn()) {
                redirect('users/login');
            }
            $userId = $_SESSION['user_id'];
        }
        
        $profile = $this->profileModel->getProfileByUserId($userId);
        
        if (!$profile) {
            flash('profile_error', 'Profile not found', 'alert alert-danger');
            redirect('');
            return;
        }
        
        $connectionStatus = null;
        if (isLoggedIn() && $userId != $_SESSION['user_id']) {
            $connectionStatus = $this->connectionModel->getConnectionStatus($_SESSION['user_id'], $userId);
        }
        
        $posts = $this->postModel->getUserPosts($userId, 5, 0);
        $connectionCount = $this->connectionModel->countConnections($userId);
        
        $data = [
            'title' => ($profile->first_name ?? 'Unknown') . ' ' . ($profile->last_name ?? 'User'),
            'profile' => $profile,
            'posts' => $posts,
            'connection_status' => $connectionStatus,
            'connection_count' => $connectionCount
        ];
        
        $this->view('profiles/view', $data);
    }
    
    public function edit() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profilePicture = isset($_FILES['profile_picture']) ? $_FILES['profile_picture'] : null;
            $profilePicturePath = null;
            
            if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/profile_pictures/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $extension = pathinfo($profilePicture['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($profilePicture['tmp_name'], $targetPath)) {
                    $profilePicturePath = $filename;
                }
            }
            
            $existingProfile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'headline' => trim($_POST['headline']),
                'about' => trim($_POST['about']),
                'location' => trim($_POST['location']),
                'industry' => trim($_POST['industry']),
                'website' => trim($_POST['website']),
                'profile_picture' => $profilePicturePath ?: ($existingProfile ? $existingProfile->profile_picture : null),
                'headline_err' => '',
                'about_err' => '',
                'location_err' => '',
                'industry_err' => ''
            ];
            
            if (empty($data['headline'])) {
                $data['headline_err'] = 'Please enter a professional headline';
            }
            
            if (empty($data['headline_err']) && empty($data['about_err']) && 
                empty($data['location_err']) && empty($data['industry_err'])) {
                
                if ($this->profileModel->updateProfile($data)) {
                    flash('profile_success', 'Profile updated successfully');
                    redirect('profiles');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('profiles/edit', $data);
            }
        } else {
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            
            $data = [
                'headline' => $profile ? $profile->headline : '',
                'about' => $profile ? $profile->about : '',
                'location' => $profile ? $profile->location : '',
                'industry' => $profile ? $profile->industry : '',
                'website' => $profile ? $profile->website : '',
                'profile_picture' => $profile ? $profile->profile_picture : '',
                'headline_err' => '',
                'about_err' => '',
                'location_err' => '',
                'industry_err' => ''
            ];
            
            $this->view('profiles/edit', $data);
        }
    }
    
    public function connections($userId = null) {
        if (!$userId) {
            if (!isLoggedIn()) {
                redirect('users/login');
            }
            $userId = $_SESSION['user_id'];
        }
        
        $profile = $this->profileModel->getProfileByUserId($userId);
        
        if (!$profile) {
            flash('profile_error', 'Profile not found', 'alert alert-danger');
            redirect('');
            return;
        }
        
        $canViewConnections = true;
        $connections = $this->connectionModel->getConnections($userId, 50, 0);
        
        $data = [
            'title' => ($profile->first_name ?? 'Unknown') . ' ' . ($profile->last_name ?? 'User') . '\'s Connections',
            'profile' => $profile,
            'connections' => $connections,
            'can_view_connections' => $canViewConnections
        ];
        
        $this->view('profiles/connections', $data);
    }
    
    public function requests() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    
        if (!isset($this->connectionModel)) {
            error_log('Connection model not loaded in Profiles::requests');
            $this->connectionModel = $this->model('Connection');
        }
    
        $userId = $_SESSION['user_id'];
        $pendingRequests = $this->connectionModel->getPendingRequests($userId);
    
        error_log('Pending Requests Result: ' . json_encode($pendingRequests));
    
        $data = [
            'title' => 'Connection Requests',
            'pendingRequests' => $pendingRequests ?: []
        ];
    
        error_log('Data passed to view: ' . json_encode($data));
    
        $this->view('profiles/requests', $data);
    }
    
    public function connect($userId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    
        $currentUserId = $_SESSION['user_id'];
        if ($currentUserId == $userId) {
            flash('connection_error', 'You cannot connect with yourself', 'alert alert-danger');
            redirect('profiles');
            return;
        }
    
        $connectionModel = $this->model('Connection');
        $status = $connectionModel->getConnectionStatus($currentUserId, $userId);
    
        if ($status['status'] === 'none') {
            if ($connectionModel->sendRequest($currentUserId, $userId)) {
                flash('connection_success', 'Connection request sent successfully');
            } else {
                flash('connection_error', 'Failed to send connection request', 'alert alert-danger');
            }
        } else {
            flash('connection_error', 'Connection request already sent or accepted', 'alert alert-danger');
        }
    
        redirect('profiles/view/' . $userId);
    }
    
    public function acceptRequest($connectionId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($this->connectionModel->acceptRequest($connectionId, $_SESSION['user_id'])) {
            flash('connection_success', 'Connection request accepted');
        } else {
            flash('connection_error', 'Failed to accept connection request', 'alert alert-danger');
        }
        
        redirect('profiles/requests');
    }
    
    public function rejectRequest($connectionId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($this->connectionModel->rejectRequest($connectionId, $_SESSION['user_id'])) {
            flash('connection_success', 'Connection request rejected');
        } else {
            flash('connection_error', 'Failed to reject connection request', 'alert alert-danger');
        }
        
        redirect('profiles/requests');
    }
    
    public function removeConnection($connectionId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($this->connectionModel->removeConnection($connectionId, $_SESSION['user_id'])) {
            flash('connection_success', 'Connection removed');
        } else {
            flash('connection_error', 'Failed to remove connection', 'alert alert-danger');
        }
        
        redirect('profiles/connections');
    }
    
    public function exportConnections() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $connections = $this->connectionModel->exportConnections($_SESSION['user_id']);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="connections_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['First Name', 'Last Name', 'Headline', 'Connection Date']);
        foreach ($connections as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
    
    public function showProfile($id) {
        $profileModel = $this->profileModel;
        $postModel = $this->postModel;
        $connectionModel = $this->connectionModel;

        $profile = $profileModel->getProfileById($id);
        if (!$profile) {
            flash('profile_error', 'Profile not found', 'alert alert-danger');
            redirect('');
            return;
        }

        $userId = $profile->user_id;

        $posts = $postModel->getUserPosts($userId, 10, 0); // Fixed method name and added limit/offset
        $connectionStatus = $connectionModel->getConnectionStatus($_SESSION['user_id'] ?? 0, $userId);
        $connectionCount = $connectionModel->countConnections($userId);
        $experiences = $profileModel->getExperiences($userId);
        $educations = $profileModel->getEducations($userId);
        $skills = $profileModel->getSkills($userId);

        $data = [
            'profile' => $profile,
            'posts' => $posts,
            'connection_status' => $connectionStatus,
            'connection_count' => $connectionCount,
            'experiences' => $experiences,
            'educations' => $educations,
            'skills' => $skills
        ];

        $this->view('profiles/view', $data);
    }
    
    public function addExperience() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'company' => trim($_POST['company']),
                'location' => trim($_POST['location']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description'])
            ];
            if ($this->profileModel->addExperience($data)) {
                flash('success', 'Experience added successfully');
            } else {
                flash('error', 'Failed to add experience', 'alert alert-danger');
            }
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
        }
    }
    
    public function addEducation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'degree' => trim($_POST['degree']),
                'institution' => trim($_POST['institution']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description'])
            ];
            if ($this->profileModel->addEducation($data)) {
                flash('success', 'Education added successfully');
            } else {
                flash('error', 'Failed to add education', 'alert alert-danger');
            }
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
        }
    }
    
   public function addSkill() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
        $data = [
            'user_id' => $_SESSION['user_id'],
            'skill' => trim($_POST['skill']) // Changed from skill_name to skill
        ];
        if ($this->profileModel->addSkill($data)) {
            flash('success', 'Skill added successfully');
        } else {
            flash('error', 'Failed to add skill', 'alert alert-danger');
        }
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
    }
}

    public function manageExperience() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
            $data = [
                'id' => trim($_POST['id']),
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'company' => trim($_POST['company']),
                'location' => trim($_POST['location']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description'])
            ];
            $action = $_POST['action'];
            if ($action === 'add') {
                if ($this->profileModel->addExperience($data)) {
                    flash('success', 'Experience added successfully');
                } else {
                    flash('error', 'Failed to add experience', 'alert alert-danger');
                }
            } elseif ($action === 'update' && $data['id']) {
                if ($this->profileModel->updateExperience($data)) {
                    flash('success', 'Experience updated successfully');
                } else {
                    flash('error', 'Failed to update experience', 'alert alert-danger');
                }
            }
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
        }
    }
    
    public function deleteExperience($id) {
        if (isLoggedIn() && $this->profileModel->deleteExperience($id, $_SESSION['user_id'])) {
            flash('success', 'Experience deleted successfully');
        } else {
            flash('error', 'Failed to delete experience', 'alert alert-danger');
        }
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
    }
    
    public function manageEducation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
            $data = [
                'id' => trim($_POST['id']),
                'user_id' => $_SESSION['user_id'],
                'degree' => trim($_POST['degree']),
                'institution' => trim($_POST['institution']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description'])
            ];
            $action = $_POST['action'];
            if ($action === 'add') {
                if ($this->profileModel->addEducation($data)) {
                    flash('success', 'Education added successfully');
                } else {
                    flash('error', 'Failed to add education', 'alert alert-danger');
                }
            } elseif ($action === 'update' && $data['id']) {
                if ($this->profileModel->updateEducation($data)) {
                    flash('success', 'Education updated successfully');
                } else {
                    flash('error', 'Failed to update education', 'alert alert-danger');
                }
            }
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
        }
    }
    
    public function deleteEducation($id) {
        if (isLoggedIn() && $this->profileModel->deleteEducation($id, $_SESSION['user_id'])) {
            flash('success', 'Education deleted successfully');
        } else {
            flash('error', 'Failed to delete education', 'alert alert-danger');
        }
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
    }
    
    public function manageSkill() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
        $data = [
            'id' => trim($_POST['id']),
            'user_id' => $_SESSION['user_id'],
            'skill' => trim($_POST['skill']) // Changed from skill_name to skill
        ];
        $action = $_POST['action'];
        if ($action === 'add') {
            if ($this->profileModel->addSkill($data)) {
                flash('success', 'Skill added successfully');
            } else {
                flash('error', 'Failed to add skill', 'alert alert-danger');
            }
        } elseif ($action === 'update' && $data['id']) {
            if ($this->profileModel->updateSkill($data)) {
                flash('success', 'Skill updated successfully');
            } else {
                flash('error', 'Failed to update skill', 'alert alert-danger');
            }
        }
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
    }
}
    
    public function deleteSkill($id) {
        if (isLoggedIn() && $this->profileModel->deleteSkill($id, $_SESSION['user_id'])) {
            flash('success', 'Skill deleted successfully');
        } else {
            flash('error', 'Failed to delete skill', 'alert alert-danger');
        }
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        redirect('/profiles/showProfile/' . ($profile ? $profile->id : ''));
    }
}