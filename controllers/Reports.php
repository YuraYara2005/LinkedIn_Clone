<?php
class Reports extends Controller {
    public function __construct() {
        $this->reportModel = $this->model('Report');
        $this->connectionModel = $this->model('Connection');
        $this->jobModel = $this->model('Job');
        $this->groupModel = $this->model('Group');
    }
    
    public function index() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get saved reports
        $savedReports = $this->reportModel->getSavedReports($_SESSION['user_id']);
        
        $data = [
            'title' => 'Reports',
            'saved_reports' => $savedReports
        ];
        
        $this->view('reports/index', $data);
    }
    
    public function connections() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Generate connections report
        $reportData = $this->reportModel->generateConnectionReport($_SESSION['user_id']);
        
        $data = [
            'title' => 'Connections Report',
            'report_data' => $reportData,
            'report_type' => 'connections'
        ];
        
        $this->view('reports/connections', $data);
    }
    
    public function applications() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Generate job applications report
        $reportData = $this->reportModel->generateJobApplicationReport($_SESSION['user_id']);
        
        $data = [
            'title' => 'Job Applications Report',
            'report_data' => $reportData,
            'report_type' => 'applications'
        ];
        
        $this->view('reports/applications', $data);
    }
    
    public function postings() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Generate job postings report
        $reportData = $this->reportModel->generateJobPostingReport($_SESSION['user_id']);
        
        $data = [
            'title' => 'Job Postings Report',
            'report_data' => $reportData,
            'report_type' => 'postings'
        ];
        
        $this->view('reports/postings', $data);
    }
    
    public function group($groupId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get group details
        $group = $this->groupModel->getGroupById($groupId);
        
        if (!$group) {
            flash('group_error', 'Group not found', 'alert alert-danger');
            redirect('groups');
            return;
        }
        
        // Generate group activity report
        $reportData = $this->reportModel->generateGroupActivityReport($groupId, $_SESSION['user_id']);
        
        if ($reportData === false) {
            flash('report_error', 'You must be an admin of this group to view this report', 'alert alert-danger');
            redirect('groups/view/' . $groupId);
            return;
        }
        
        $data = [
            'title' => $group->name . ' - Activity Report',
            'group' => $group,
            'report_data' => $reportData,
            'report_type' => 'group'
        ];
        
        $this->view('reports/group', $data);
    }
    
    public function admin() {
        // Check if user is admin
        if (!checkRole('admin')) {
            flash('access_denied', 'You do not have permission to access this page', 'alert alert-danger');
            redirect('');
            return;
        }
        
        // Generate user activity report
        $reportData = $this->reportModel->generateUserActivityReport($_SESSION['user_id']);
        
        $data = [
            'title' => 'System Activity Report',
            'report_data' => $reportData,
            'report_type' => 'admin'
        ];
        
        $this->view('reports/admin', $data);
    }
    
    public function save() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form
            $title = trim($_POST['title']);
            $type = trim($_POST['type']);
            $data = json_decode($_POST['data'], true);
            
            if (empty($title)) {
                flash('report_error', 'Please enter a title for the report', 'alert alert-danger');
                redirect('reports');
                return;
            }
            
            // Save report
            if ($reportId = $this->reportModel->saveReport($_SESSION['user_id'], $title, $type, $data)) {
                flash('report_success', 'Report saved successfully');
                redirect('reports/view/' . $reportId);
            } else {
                flash('report_error', 'Failed to save report', 'alert alert-danger');
                redirect('reports');
            }
        } else {
            redirect('reports');
        }
    }
    
    public function view($view, $data = []) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    // Extract reportId from $view or $data
    $reportId = isset($data['reportId']) ? $data['reportId'] : (is_numeric($view) ? $view : (preg_match('/view\/(\d+)/', $view, $matches) ? $matches[1] : null));
    
    if (!$reportId) {
        flash('report_error', 'Invalid report ID', 'alert alert-danger');
        redirect('reports');
        return;
    }
    
    // Get saved report (assuming a Report model exists)
    $report = $this->reportModel->getSavedReportById($reportId, $_SESSION['user_id']);
    
    if (!$report) {
        flash('report_error', 'Report not found', 'alert alert-danger');
        redirect('reports');
        return;
    }
    
    $data = [
        'title' => $report->title,
        'report' => $report,
        'report_data' => json_decode($report->report_data)
    ];
    
    parent::view('reports/view', $data);
}
    
public function export($type) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    // Generate report data based on type
    $reportData = [];
    $filename = '';
    
    switch ($type) {
        case 'connections':
            $reportData = $this->reportModel->generateConnectionReport($_SESSION['user_id']);
            $filename = 'connections_report_' . date('Y-m-d') . '.pdf';
            break;
        case 'applications':
            $reportData = $this->reportModel->generateJobApplicationReport($_SESSION['user_id']);
            $filename = 'applications_report_' . date('Y-m-d') . '.pdf';
            break;
        case 'postings':
            $reportData = $this->reportModel->generateJobPostingReport($_SESSION['user_id']);
            $filename = 'job_postings_report_' . date('Y-m-d') . '.pdf';
            break;
        case 'admin':
            // Check if user is admin
            if (!checkRole('admin')) {
                flash('access_denied', 'You do not have permission to export this report', 'alert alert-danger');
                redirect('reports');
                return;
            }
            $reportData = $this->reportModel->generateUserActivityReport($_SESSION['user_id']);
            if ($reportData === false) {
                flash('access_denied', 'You do not have permission to export this report', 'alert alert-danger');
                redirect('reports');
                return;
            }
            $filename = 'system_activity_report_' . date('Y-m-d') . '.pdf';
            break;
        default:
            flash('report_error', 'Invalid report type', 'alert alert-danger');
            redirect('reports');
            return;
    }
    
    // Generate PDF
    $this->generatePDF($reportData, $type, $filename);
}
    private function generatePDF($data, $type, $filename) {
        // This is a simplified version for demonstration
        // In a real application, use a PDF library like FPDF, TCPDF, or mPDF
        
        // For now, just output data as HTML
        echo '<h1>' . ucfirst($type) . ' Report</h1>';
        echo '<p>Generated on: ' . date('Y-m-d H:i:s') . '</p>';
        
        echo '<table border="1" cellpadding="5">';
        
        // Output headers based on report type
        echo '<tr>';
        switch ($type) {
            case 'connections':
                echo '<th>Name</th><th>Headline</th><th>Industry</th><th>Connected Since</th>';
                break;
                
            case 'applications':
                echo '<th>Job Title</th><th>Company</th><th>Location</th><th>Applied Date</th><th>Status</th>';
                break;
                
            case 'postings':
                echo '<th>Job Title</th><th>Company</th><th>Posted Date</th><th>Applications</th>';
                break;
        }
        echo '</tr>';
        
        // Output data rows
        foreach ($data as $row) {
            echo '<tr>';
            switch ($type) {
                case 'connections':
                    echo '<td>' . $row->first_name . ' ' . $row->last_name . '</td>';
                    echo '<td>' . $row->headline . '</td>';
                    echo '<td>' . $row->industry . '</td>';
                    echo '<td>' . date('Y-m-d', strtotime($row->connected_since)) . '</td>';
                    break;
                    
                case 'applications':
                    echo '<td>' . $row->title . '</td>';
                    echo '<td>' . $row->company . '</td>';
                    echo '<td>' . $row->location . '</td>';
                    echo '<td>' . date('Y-m-d', strtotime($row->applied_date)) . '</td>';
                    echo '<td>' . ucfirst($row->status) . '</td>';
                    break;
                    
                case 'postings':
                    echo '<td>' . $row->title . '</td>';
                    echo '<td>' . $row->company . '</td>';
                    echo '<td>' . date('Y-m-d', strtotime($row->posted_date)) . '</td>';
                    echo '<td>' . $row->application_count . '</td>';
                    break;
            }
            echo '</tr>';
        }
        
        echo '</table>';
        
        // In a real application, generate and download a PDF file
        exit;
    }
}