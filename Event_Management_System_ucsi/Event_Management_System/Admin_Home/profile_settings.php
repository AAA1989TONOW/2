<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

// Get current user data
$user_id = $_SESSION['user_id'] ?? 1; // Assuming user is logged in
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $affiliation = $_POST['affiliation'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    
    $stmt = $db->prepare("UPDATE users SET first_name = ?, last_name = ?, affiliation = ?, country = ?, phone = ? WHERE user_id = ?");
    $stmt->execute([$first_name, $last_name, $affiliation, $country, $phone, $user_id]);
    $success = "Profile updated successfully!";
    
    // Refresh user data
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
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
    <h1 class="mb-4"><i class="bi bi-person-circle"></i> Profile Settings</h1>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?=$success?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" 
                           value="<?=htmlspecialchars($user['first_name'])?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" 
                           value="<?=htmlspecialchars($user['last_name'])?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?=htmlspecialchars($user['email'])?>" readonly>
                <div class="form-text text-light">Email cannot be changed</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Affiliation</label>
                <input type="text" name="affiliation" class="form-control" 
                       value="<?=htmlspecialchars($user['affiliation'] ?? '')?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" 
                           value="<?=htmlspecialchars($user['country'] ?? '')?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" 
                           value="<?=htmlspecialchars($user['phone'] ?? '')?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Profile</button>
        </form>
    </div>
</div>