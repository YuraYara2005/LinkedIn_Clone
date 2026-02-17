<?php require_once 'helpers/session_helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' | ' . SITENAME : SITENAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo assets('css/style.css'); ?>">
    
</head>
<body>
    <?php require 'navbar.php'; ?>
    
    <div class="container mt-4">
        <?php flash('register_success'); ?>
        <?php flash('login_success'); ?>
        <?php flash('post_success'); ?>
        <?php flash('profile_success'); ?>
        <?php flash('job_success'); ?>
        <?php flash('application_success'); ?>
        <?php flash('connection_success'); ?>
        <?php flash('group_success'); ?>
        <?php flash('notification_success'); ?>
        <?php flash('report_success'); ?>
        
        <?php flash('register_error', '', 'alert alert-danger'); ?>
        <?php flash('login_error', '', 'alert alert-danger'); ?>
        <?php flash('post_error', '', 'alert alert-danger'); ?>
        <?php flash('profile_error', '', 'alert alert-danger'); ?>
        <?php flash('job_error', '', 'alert alert-danger'); ?>
        <?php flash('application_error', '', 'alert alert-danger'); ?>
        <?php flash('connection_error', '', 'alert alert-danger'); ?>
        <?php flash('group_error', '', 'alert alert-danger'); ?>
        <?php flash('notification_error', '', 'alert alert-danger'); ?>
        <?php flash('report_error', '', 'alert alert-danger'); ?>
        <?php flash('access_denied', '', 'alert alert-danger'); ?>