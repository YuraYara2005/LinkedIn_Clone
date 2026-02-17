<?php
class Messages extends Controller {
    public function __construct() {
        $this->messageModel = $this->model('Message');
        // make sure the user is logged-in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
    }

    public function index() {
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
        $data = [
            'conversations' => $conversations,
            'current_user_id' => $_SESSION['user_id'],
            'selected_user' => null,
            'messages' => []
        ];
        if (!empty($conversations)) {
            $data['selected_user'] = $conversations[0];
            $data['messages'] = $this->messageModel->getMessages($_SESSION['user_id'], $conversations[0]->user_id);
        }
        $this->view('messages/chat', $data);
    }

    public function view($view, $data = []) {
        // Extract receiver_id from params (set by router) or data
        $receiverId = $this->params[0] ?? ($data['receiver_id'] ?? null);
        if ($receiverId) {
            $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
            $messages = $this->messageModel->getMessages($_SESSION['user_id'], $receiverId);
            
            // Fetch the selected user's details for display
            $this->userModel = $this->model('User');
            $selectedUser = $this->userModel->getUserById($receiverId);
            
            $data = array_merge($data, [
                'conversations' => $conversations,
                'current_user_id' => $_SESSION['user_id'],
                'selected_user' => $selectedUser, // Use full user object
                'messages' => $messages
            ]);
        }
        parent::view($view, $data);
    }

    public function getMessages($receiverId) {
        $messages = $this->messageModel->getMessages($_SESSION['user_id'], $receiverId);
        header('Content-Type: application/json');
        echo json_encode(['messages' => $messages]);
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => filter_var($input['receiver_id'], FILTER_SANITIZE_NUMBER_INT),
                'content' => filter_var($input['content'], FILTER_SANITIZE_STRING)
            ];
            $result = $this->messageModel->sendMessage($data);
            if ($result) {
                // Create notification for new message
                $this->loadModel('Notification');
                $notificationData = [
                    'user_id' => $data['receiver_id'],
                    'message' => 'You have a new message from ' . ($_SESSION['user_name'] ?? 'a user'),
                    'type' => 'message',
                    'link' => 'messages/chat'
                ];
                $this->notificationModel->create($notificationData);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }
    

    public function searchUsers() {
        $query = $_GET['q'] ?? '';
        $users = $this->messageModel->searchUsers($query, $_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode(['users' => $users]);
    }
}