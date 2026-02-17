<?php
class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
        $this->notificationModel = $this->model('Notification');
    }

    public function register() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Validate first name
            if (empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter your first name';
            }
            
            // Validate last name
            if (empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter your last name';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            } else {
                // Check if email exists
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            
            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }
            
            // Make sure errors are empty
            if (empty($data['first_name_err']) && empty($data['last_name_err']) && 
                empty($data['email_err']) && empty($data['password_err']) && 
                empty($data['confirm_password_err'])) {
                
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Register User
                if ($userId = $this->userModel->register($data)) {
                    // Create welcome notification
                    $notification = [
                        'user_id' => $userId,
                        'message' => 'Welcome to LinkedIn Clone! Complete your profile to get started.',
                        'type' => 'welcome',
                        'link' => 'profiles/edit'
                    ];
                    $this->notificationModel->create($notification);
                    
                    flash('register_success', 'You are registered and can now log in');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
        } else {
            // Init data
            $data = [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Load view
            $this->view('users/register', $data);
        }
    }

    public function login() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }
            
            // Check for user/email
            if (!$this->userModel->findUserByEmail($data['email'])) {
                // User not found
                $data['email_err'] = 'No user found';
            }
            
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Create session
                    $_SESSION['user_id'] = $loggedInUser->id;
                    $_SESSION['user_email'] = $loggedInUser->email;
                    $_SESSION['user_name'] = $loggedInUser->first_name . ' ' . $loggedInUser->last_name;
                    $_SESSION['user_role'] = $loggedInUser->role;
                    
                    redirect('');
                } else {
                    $data['password_err'] = 'Password incorrect';
                    
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Load view
            $this->view('users/login', $data);
        }
    }
    
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('users/login');
    }
    
    public function search() {
        // Check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // Get users matching the search
        $users = $this->userModel->getUsers($searchQuery, $perPage, $offset);
        
        $data = [
            'title' => 'User Search',
            'users' => $users,
            'search_query' => $searchQuery,
            'page' => $page,
            'per_page' => $perPage
        ];
        
        $this->view('users/search', $data);
    }
    

    public function dashboard() {
    if (!checkRole('admin')) {
        flash('access_denied', 'You do not have permission to access this page', 'alert alert-danger');
        redirect('');
        return;
    }
    
    $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 20;
    $offset = ($page - 1) * $perPage;
    
    $users = $this->userModel->getUsers($searchQuery, $perPage, $offset);
    $totalUsers = $this->userModel->getTotalUsers($searchQuery); // Line 247 - Now defined
    $totalPages = ceil($totalUsers / $perPage);

    $data = [
        'title' => 'Admin Dashboard',
        'users' => $users,
        'search_query' => $searchQuery,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => $totalPages
    ];
    
    $this->view('users/dashboard', $data);
}
    
    public function changeRole($userId) {
    // Ensure user is admin
    if (!checkRole('admin')) {
        flash('access_denied', 'You do not have permission to access this page', 'alert alert-danger');
        redirect('');
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newRole = $_POST['role'];
        
        // Validate role
        if (!in_array($newRole, ['user', 'premium', 'admin'])) {
            flash('role_error', 'Invalid role specified', 'alert alert-danger');
            redirect('users/dashboard');
            return;
        }
        
        // Update user role
        if ($this->userModel->changeRole($userId, $newRole)) {
            flash('role_success', 'User role updated successfully');
            redirect('users/dashboard');
        } else {
            flash('role_error', 'Failed to update user role', 'alert alert-danger');
            redirect('users/dashboard');
        }
    } else {
        // Get user details
        $user = $this->userModel->getUserById($userId);
        
        if (!$user) {
            flash('user_not_found', 'User not found', 'alert alert-danger');
            redirect('users/dashboard');
            return;
        }
        
        $data = [
            'title' => 'Change User Role',
            'user' => $user,
            'roles' => ['user', 'premium', 'admin']
        ];
        
        $this->view('users/change_role', $data);
    }
}
    
    public function delete($userId) {
    // Ensure user is admin or the account owner
    if (!checkRole('admin') && $_SESSION['user_id'] != $userId) {
        flash('access_denied', 'You do not have permission to delete this account', 'alert alert-danger');
        redirect('');
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Delete the user account
        if ($this->userModel->deleteUser($userId)) {
            // If user deleted their own account, log them out
            if ($_SESSION['user_id'] == $userId) {
                unset($_SESSION['user_id']);
                unset($_SESSION['user_email']);
                unset($_SESSION['user_name']);
                unset($_SESSION['user_role']);
                session_destroy();
                
                flash('account_deleted', 'Your account has been deleted');
                redirect('users/login');
            } else {
                flash('user_deleted', 'User account has been deleted');
                redirect('users/dashboard');
            }
        } else {
            flash('delete_error', 'Failed to delete user account', 'alert alert-danger');
            redirect(checkRole('admin') ? 'users/dashboard' : '');
        }
    } else {
        // Confirm deletion
        $user = $this->userModel->getUserById($userId);
        
        if (!$user) {
            flash('user_not_found', 'User not found', 'alert alert-danger');
            redirect(checkRole('admin') ? 'users/dashboard' : '');
            return;
        }
        
        $data = [
            'title' => 'Delete Account',
            'user' => $user
        ];
        
        $this->view('users/delete', $data);
    }
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
}