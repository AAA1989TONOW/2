<?php
// Start session and check login
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { margin:0; padding:0; }
        .content { margin-left: 250px; padding: 20px; }
        .sidebar { width: 250px; background: #343a40; }
        .sidebar a:hover { background: #495057; text-decoration: none; }
        .submenu { display:none; }
        .has-submenu:hover .submenu { display:block; }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>

<div class="content">
