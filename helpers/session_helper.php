<?php
session_start();

// Flash message helper
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        // If no message, just retrieve flash
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Notification helper
function createNotification($userId, $message, $type = 'info', $link = '') {
    $db = new Database();
    $db->query('INSERT INTO notifications (user_id, message, type, link, created_at) VALUES (:user_id, :message, :type, :link, NOW())');
    $db->bind(':user_id', $userId);
    $db->bind(':message', $message);
    $db->bind(':type', $type);
    $db->bind(':link', $link);
    return $db->execute();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check user role
function checkRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    return ($_SESSION['user_role'] == $role);
}