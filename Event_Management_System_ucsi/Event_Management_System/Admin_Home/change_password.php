<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

$user_id = $_SESSION['user_id'] ?? 1; // Assuming user is logged in

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current user password
    $stmt = $db->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if(!password_verify($current_password, $user['password'])){
        $error = "Current password is incorrect!";
    } elseif($new_password !== $confirm_password){
        $error = "New passwords do not match!";
    } elseif(strlen($new_password) < 6){
        $error = "New password must be at least 6 characters long!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->execute([$hashed_password, $user_id]);
        $success = "Password changed successfully!";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; font-family:'Roboto', sans-serif; }
.container { padding-top:30px; }
.card { border-radius:12px; background:#1e1e2f; box-shadow:0 4px 20px rgba(0,0,0,0.6); }
.form-control, .form-select { background-color:#2d2d44; border:1px solid #444; color:white; }
.form-control:focus, .form-select:focus { background-color:#2d2d44; border-color:#0d6efd; color:white; }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-shield-lock"></i> Change Password</h1>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?=$success?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
                <div class="form-text text-light">Password must be at least 6 characters long</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Change Password</button>
        </form>
    </div>
</div>