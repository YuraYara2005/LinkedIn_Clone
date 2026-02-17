<?php
class Connections extends Controller {
    public function __construct() {
        $this->connectionModel = $this->model('Connection');
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
    }

    public function request() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targetId = filter_var($_POST['target_id'], FILTER_SANITIZE_NUMBER_INT);
            if ($targetId && $targetId != $_SESSION['user_id']) {
                // Check if a connection already exists
                $existingConnection = $this->connectionModel->getConnectionStatus($_SESSION['user_id'], $targetId);
                if ($existingConnection && isset($existingConnection['status']) && $existingConnection['status'] !== 'none') {
                    flash('connection_error', 'A connection request already exists between you and this user.', 'alert');
                } else {
                    $result = $this->connectionModel->sendRequest($_SESSION['user_id'], $targetId);
                    if ($result) {
                        $this->notificationModel = $this->model('Notification'); // Fixed: Use $this->model() instead of $this->loadModel()
                        $notificationData = [
                            'user_id' => $targetId,
                            'message' => 'You have a new connection request from ' . ($_SESSION['user_name'] ?? 'a user'),
                            'type' => 'connection_request',
                            'link' => 'profiles/view/' . $_SESSION['user_id']
                        ];
                        $this->notificationModel->create($notificationData);
                        flash('connection_success', 'Connection request sent successfully');
                    } else {
                        flash('connection_error', 'Failed to send connection request', 'alert');
                    }
                }
            } else {
                flash('connection_error', 'Invalid request', 'alert');
            }
            $query = urlencode($_GET['q'] ?? '');
            redirect("users/search?q=$query");
        }
        redirect('users/search');
    }
}