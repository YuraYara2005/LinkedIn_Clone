<?php
class Notifications extends Controller {
    public function __construct() {
        $this->notificationModel = $this->model('Notification');
    }
    
    public function index() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $notifications = $this->notificationModel->getUserNotifications($_SESSION['user_id'], 30, 0);
        
        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications
        ];
        
        $this->view('notifications/index', $data);
    }
    
    public function markAsRead($id) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->notificationModel->markAsRead($id, $_SESSION['user_id']);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true]);
            exit;
        }
        
        redirect('notifications');
    }
    
    public function markAllAsRead() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true]);
            exit;
        }
        
        flash('notification_success', 'All notifications marked as read');
        redirect('notifications');
    }
    
    public function delete($id) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->notificationModel->delete($id, $_SESSION['user_id']);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true]);
            exit;
        }
        
        flash('notification_success', 'Notification deleted');
        redirect('notifications');
    }
    
    public function count() {
        if (!isLoggedIn()) {
            echo json_encode(['count' => 0]);
            exit;
        }
        
        $count = $this->notificationModel->countUnread($_SESSION['user_id']);
        
        echo json_encode(['count' => $count]);
        exit;
    }
    
    public function new() {
        if (!isLoggedIn()) {
            echo json_encode([]);
            exit;
        }
        
        $notifications = $this->notificationModel->getUserNotifications($_SESSION['user_id'], 10, 0);
        $unread = array_filter($notifications, fn($n) => $n->is_read == 0);
        echo json_encode(array_values($unread));
        exit;
    }
}