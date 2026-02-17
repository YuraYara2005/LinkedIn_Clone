<?php
// controllers/Jobs.php

class Jobs extends Controller {
    public function __construct() {
        $this->jobModel = $this->model('Job');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        // Get search parameters
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';
        $jobType = isset($_GET['job_type']) ? trim($_GET['job_type']) : '';
        $company = isset($_GET['company']) ? trim($_GET['company']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // Build filters
        $filters = [
            'keyword' => $keyword,
            'location' => $location,
            'job_type' => $jobType,
            'company' => $company
        ];
        
        // Get jobs
        $jobs = $this->jobModel->searchJobs($filters, $perPage, $offset);
        
        // Get total job count for pagination
        $totalJobs = $this->jobModel->countJobs($filters);
        $totalPages = ceil($totalJobs / $perPage);
        
        $data = [
            'title' => 'Find Jobs',
            'jobs' => $jobs,
            'filters' => $filters,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'total_items' => $totalJobs
            ]
        ];
        
        $this->view('jobs/index', $data);
    }
    
    public function show($jobId) {
        // Get job details
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job) {
            flash('job_error', 'Job not found', 'alert alert-danger');
            redirect('jobs');
            return;
        }
        
        // Check if user has already applied
        $hasApplied = false;
        if (isLoggedIn()) {
            $hasApplied = $this->jobModel->checkApplicationExists($jobId, $_SESSION['user_id']);
        }
        
        $data = [
            'title' => $job->title . ' at ' . $job->company,
            'job' => $job,
            'has_applied' => $hasApplied
        ];
        
        $this->view('jobs/view', $data);
    }
    
    public function create() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Prepare data
            $data = [
                'title' => trim($_POST['title']),
                'company' => trim($_POST['company']),
                'location' => trim($_POST['location']),
                'job_type' => trim($_POST['job_type']),
                'description' => trim($_POST['description']),
                'requirements' => trim($_POST['requirements']),
                'salary_min' => trim($_POST['salary_min']),
                'salary_max' => trim($_POST['salary_max']),
                'posted_by' => $_SESSION['user_id'],
                'title_err' => '',
                'company_err' => '',
                'location_err' => '',
                'job_type_err' => '',
                'description_err' => '',
                'requirements_err' => ''
            ];
            
            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter job title';
            }
            
            // Validate company
            if (empty($data['company'])) {
                $data['company_err'] = 'Please enter company name';
            }
            
            // Validate location
            if (empty($data['location'])) {
                $data['location_err'] = 'Please enter job location';
            }
            
            // Validate job type
            if (empty($data['job_type'])) {
                $data['job_type_err'] = 'Please select job type';
            }
            
            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter job description';
            }
            
            // Validate requirements
            if (empty($data['requirements'])) {
                $data['requirements_err'] = 'Please enter job requirements';
            }
            
            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['company_err']) && 
                empty($data['location_err']) && empty($data['job_type_err']) && 
                empty($data['description_err']) && empty($data['requirements_err'])) {
                
                // Create job
                if ($jobId = $this->jobModel->create($data)) {
                    flash('job_success', 'Job posted successfully');
                    redirect('jobs/show/' . $jobId);
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('jobs/create', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => '',
                'company' => '',
                'location' => '',
                'job_type' => '',
                'description' => '',
                'requirements' => '',
                'salary_min' => '',
                'salary_max' => '',
                'title_err' => '',
                'company_err' => '',
                'location_err' => '',
                'job_type_err' => '',
                'description_err' => '',
                'requirements_err' => ''
            ];
            
            // Load view
            $this->view('jobs/create', $data);
        }
    }
    
    public function edit($jobId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get job details
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job) {
            flash('job_error', 'Job not found', 'alert alert-danger');
            redirect('jobs');
            return;
        }
        
        // Check if user is authorized to edit this job
        if ($job->posted_by != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            flash('job_error', 'You are not authorized to edit this job', 'alert alert-danger');
            redirect('jobs/show/' . $jobId);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Prepare data
            $data = [
                'id' => $jobId,
                'title' => trim($_POST['title']),
                'company' => trim($_POST['company']),
                'location' => trim($_POST['location']),
                'job_type' => trim($_POST['job_type']),
                'description' => trim($_POST['description']),
                'requirements' => trim($_POST['requirements']),
                'salary_min' => trim($_POST['salary_min']),
                'salary_max' => trim($_POST['salary_max']),
                'posted_by' => $_SESSION['user_id'],
                'title_err' => '',
                'company_err' => '',
                'location_err' => '',
                'job_type_err' => '',
                'description_err' => '',
                'requirements_err' => ''
            ];
            
            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter job title';
            }
            
            // Validate company
            if (empty($data['company'])) {
                $data['company_err'] = 'Please enter company name';
            }
            
            // Validate location
            if (empty($data['location'])) {
                $data['location_err'] = 'Please enter job location';
            }
            
            // Validate job type
            if (empty($data['job_type'])) {
                $data['job_type_err'] = 'Please select job type';
            }
            
            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter job description';
            }
            
            // Validate requirements
            if (empty($data['requirements'])) {
                $data['requirements_err'] = 'Please enter job requirements';
            }
            
            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['company_err']) && 
                empty($data['location_err']) && empty($data['job_type_err']) && 
                empty($data['description_err']) && empty($data['requirements_err'])) {
                
                // Update job
                if ($this->jobModel->update($data)) {
                    flash('job_success', 'Job updated successfully');
                    redirect('jobs/show/' . $jobId);
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('jobs/edit', $data);
            }
        } else {
            // Init data
            $data = [
                'id' => $job->id,
                'title' => $job->title,
                'company' => $job->company,
                'location' => $job->location,
                'job_type' => $job->job_type,
                'description' => $job->description,
                'requirements' => $job->requirements,
                'salary_min' => $job->salary_min,
                'salary_max' => $job->salary_max,
                'title_err' => '',
                'company_err' => '',
                'location_err' => '',
                'job_type_err' => '',
                'description_err' => '',
                'requirements_err' => ''
            ];
            
            // Load view
            $this->view('jobs/edit', $data);
        }
    }
    
    public function delete($jobId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get job details
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job) {
            flash('job_error', 'Job not found', 'alert alert-danger');
            redirect('jobs');
            return;
        }
        
        // Check if user is authorized to delete this job
        if ($job->posted_by != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            flash('job_error', 'You are not authorized to delete this job', 'alert alert-danger');
            redirect('jobs/show/' . $jobId);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Delete job
            if ($this->jobModel->delete($jobId, $_SESSION['user_id'])) {
                flash('job_success', 'Job deleted successfully');
                redirect('jobs');
            } else {
                flash('job_error', 'Failed to delete job', 'alert alert-danger');
                redirect('jobs/show/' . $jobId);
            }
        } else {
            // Confirm deletion
            $data = [
                'title' => 'Delete Job',
                'job' => $job
            ];
            
            $this->view('jobs/delete', $data);
        }
    }
    
    public function apply($jobId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get job details
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job) {
            flash('job_error', 'Job not found', 'alert alert-danger');
            redirect('jobs');
            return;
        }
        
        // Check if user has already applied
        if ($this->jobModel->checkApplicationExists($jobId, $_SESSION['user_id'])) {
            flash('job_error', 'You have already applied for this job', 'alert alert-danger');
            redirect('jobs/show/' . $jobId);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle resume upload
            $resume = isset($_FILES['resume']) ? $_FILES['resume'] : null;
            $resumePath = null;
            
            if ($resume && $resume['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/resumes/';
                
                // Ensure upload directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($resume['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($resume['tmp_name'], $targetPath)) {
                    $resumePath = $filename;
                }
            }
            
            // Process form
            $coverLetter = trim($_POST['cover_letter']);
            
            // Validate inputs
            if (empty($resumePath)) {
                flash('resume_error', 'Please upload your resume', 'alert alert-danger');
                redirect('jobs/apply/' . $jobId);
                return;
            }
            
            // Apply for job
            if ($this->jobModel->applyForJob($jobId, $_SESSION['user_id'], $resumePath, $coverLetter)) {
                // Create notification for job poster
                $notification = [
                    'user_id' => $job->posted_by,
                    'message' => $_SESSION['user_name'] . ' applied for your job: ' . $job->title,
                    'type' => 'job_application',
                    'link' => 'jobs/applications/' . $jobId
                ];
                
                createNotification($job->posted_by, $notification['message'], $notification['type'], $notification['link']);
                
                flash('application_success', 'Application submitted successfully');
                redirect('jobs/show/' . $jobId);
            } else {
                flash('application_error', 'Failed to submit application', 'alert alert-danger');
                redirect('jobs/apply/' . $jobId);
            }
        } else {
            // Load application form
            $data = [
                'title' => 'Apply for ' . $job->title,
                'job' => $job
            ];
            
            $this->view('jobs/apply', $data);
        }
    }
    
    public function applications($jobId = null) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($jobId) {
            // View applications for a specific job
            $job = $this->jobModel->getJobById($jobId);
            
            if (!$job) {
                flash('job_error', 'Job not found', 'alert alert-danger');
                redirect('jobs');
                return;
            }
            
            // Check if user is authorized to view applications
            if ($job->posted_by != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
                flash('job_error', 'You are not authorized to view applications for this job', 'alert alert-danger');
                redirect('jobs/show/' . $jobId);
                return;
            }
            
            // Get applications
            $applications = $this->jobModel->getJobApplications($jobId, $_SESSION['user_id']);
            
            $data = [
                'title' => 'Applications for ' . $job->title,
                'job' => $job,
                'applications' => $applications
            ];
            
            $this->view('jobs/applications', $data);
        } else {
            // View all jobs posted by the user
            // This is for showing a dashboard of jobs the user has posted
            
            // Get jobs
            $jobs = $this->jobModel->searchJobs(['posted_by' => $_SESSION['user_id']], 10, 0);
            
            $data = [
                'title' => 'My Job Postings',
                'jobs' => $jobs
            ];
            
            $this->view('jobs/my_postings', $data);
        }
    }

    public function getMoreJobs() {
        $offset = $_GET['offset'] ?? 0;
        $limit = $_GET['limit'] ?? 2;
        $jobs = $this->jobModel->getJobs($offset, $limit); // Adjust based on your Job model
        $totalJobs = $this->jobModel->countJobs();
        echo json_encode(['jobs' => $jobs, 'totalJobs' => $totalJobs]);
    }
}