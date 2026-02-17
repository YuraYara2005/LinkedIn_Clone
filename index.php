<?php
/**
 * Main entry point for the LinkedIn Clone application
 */

// Initialize the application
require_once 'config/config.php';
require_once 'helpers/session_helper.php';
require_once 'helpers/url_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    require_once 'libraries/' . $className . '.php';
});

// Initialize Router
$router = new Router();

// Add routes for messages
$router->add('messages', 'Messages/index');
$router->add('messages/getMessages/([0-9]+)', 'Messages/getMessages/$1');
$router->add('messages/send', 'Messages/send');
$router->add('messages/searchUsers', 'Messages/searchUsers');
?>