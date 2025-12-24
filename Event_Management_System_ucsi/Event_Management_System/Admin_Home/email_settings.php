<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    foreach($_POST as $key => $value){
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_type) 
                             VALUES (?, ?, 'email') 
                             ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = CURRENT_TIMESTAMP");
        $stmt->execute([$key, $value, $value]);
    }
    $success = "Email settings updated successfully!";
}

// Get current email settings
$settings = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_type = 'email'")->fetchAll(PDO::FETCH_KEY_PAIR);
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
    <h1 class="mb-4"><i class="bi bi-envelope"></i> Email Settings</h1>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?=$success?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">SMTP Host</label>
                    <input type="text" name="smtp_host" class="form-control" 
                           value="<?=htmlspecialchars($settings['smtp_host'] ?? '')?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">SMTP Port</label>
                    <input type="number" name="smtp_port" class="form-control" 
                           value="<?=htmlspecialchars($settings['smtp_port'] ?? '')?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">SMTP Username</label>
                <input type="email" name="smtp_username" class="form-control" 
                       value="<?=htmlspecialchars($settings['smtp_username'] ?? '')?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">SMTP Password</label>
                <input type="password" name="smtp_password" class="form-control" 
                       value="<?=htmlspecialchars($settings['smtp_password'] ?? '')?>">
                <div class="form-text text-light">Leave blank to keep current password</div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Email Settings</button>
        </form>
    </div>
</div>