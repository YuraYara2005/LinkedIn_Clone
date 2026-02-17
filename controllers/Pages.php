<?php
class Pages extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
        $this->postModel = $this->model('Post');
        $this->jobModel = $this->model('Job');
        $this->groupModel = $this->model('Group'); // Add this line
    }

    public function index() {
        // Check if logged in
        if (isLoggedIn()) {
            // Get user feed posts
            $userPosts = $this->postModel->getFeedPosts($_SESSION['user_id'], 10, 0);

            // Get groups the user is a member of
            $userGroups = $this->groupModel->getUserGroups($_SESSION['user_id'], 50, 0);
            $groupIds = array_column($userGroups, 'id');

            // Fetch group posts
            $groupPosts = [];
            if (!empty($groupIds)) {
                $groupPosts = $this->groupModel->getPostsFromGroups($groupIds, 10, 0);
            }

            // Merge user posts and group posts, sorting by created_at
            $allPosts = array_merge($userPosts, $groupPosts);
            usort($allPosts, function($a, $b) {
                return strtotime($b->created_at) - strtotime($a->created_at);
            });

            // Get job recommendations
            $jobs = $this->jobModel->searchJobs([], 5, 0);

            $data = [
                'title' => 'Your Feed',
                'posts' => $allPosts, // Use merged posts
                'jobs' => $jobs
            ];

            $this->view('pages/feed', $data);
        } else {
            $data = [
                'title' => 'Welcome to ' . SITENAME,
                'description' => 'A professional networking platform to connect with colleagues and opportunities.'
            ];

            $this->view('pages/landing', $data);
        }
    }
    
    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'LinkedIn Clone is a professional networking platform designed to help you connect with colleagues, find new opportunities, and grow your career.'
        ];
        
        $this->view('pages/about', $data);
    }
    
    public function pricing() {
        $data = [
            'title' => 'Premium Plans',
            'plans' => [
                [
                    'name' => 'Premium',
                    'price' => 29.99,
                    'period' => 'month',
                    'features' => [
                        'See who viewed your profile',
                        'InMail messages',
                        'Advanced search filters',
                        'Applicant insights',
                        'Competitive intelligence'
                    ]
                ],
                [
                    'name' => 'Business',
                    'price' => 59.99,
                    'period' => 'month',
                    'features' => [
                        'All Premium features',
                        'Unlimited people browsing',
                        'Job and talent matching',
                        'Company insights',
                        'Bulk InMail messages'
                    ]
                ]
            ]
        ];
        
        $this->view('pages/pricing', $data);
    }
    
    public function contact() {
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            $data = [
                'title' => 'Contact Us',
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];
            
            // Validate inputs
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }
            
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }
            
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }
            
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your message';
            }
            
            // If no errors, send email
            if (empty($data['name_err']) && empty($data['email_err']) && 
                empty($data['subject_err']) && empty($data['message_err'])) {
                
                // In a real application, send the email here
                
                flash('contact_success', 'Your message has been sent successfully');
                redirect('pages/contact');
            } else {
                // Load view with errors
                $this->view('pages/contact', $data);
            }
        } else {
            // Load form
            $data = [
                'title' => 'Contact Us',
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => '',
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];
            
            $this->view('pages/contact', $data);
        }
    }
}