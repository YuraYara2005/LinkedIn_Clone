<?php
/**
 * Base Controller
 * Loads models and views
 */
class Controller {
    public function __construct() {
        // Set working directory to APPROOT
        chdir(APPROOT);
    }

    // Load model
    public function model($model) {
        // Require model file
        require_once APPROOT . '/models/' . $model . '.php';

        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Construct absolute path using APPROOT
        $viewPath = rtrim(APPROOT, '/\\') . '/views/' . $view . '.php';
        // Normalize path separators for the current OS
        $viewPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $viewPath);
        error_log("Attempting to load view: $viewPath");
        error_log("Current working directory: " . getcwd());
        error_log("APPROP value: " . APPROOT);

        // Hardcoded manual check for debugging
        $manualPath = 'E:/Xampp/htdocs/linkedin-clone/views/messages/chat.php';
        error_log("Manual path check: $manualPath, Exists: " . (file_exists($manualPath) ? 'Yes' : 'No'));

        // Check for view file with detailed debugging
        if (file_exists($viewPath)) {
            error_log("View found at: $viewPath");
            require_once $viewPath;
        } else {
            error_log("View not found: $viewPath");
            error_log("File exists check failed. Is readable: " . (is_readable($viewPath) ? 'Yes' : 'No'));
            die('View does not exist');
        }
    }

    // Redirect helper
    public function redirect($page) {
        header('location: ' . URLROOT . '/' . $page);
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Check user role
    public function checkRole($requiredRole) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        return ($_SESSION['user_role'] == $requiredRole);
    }
}