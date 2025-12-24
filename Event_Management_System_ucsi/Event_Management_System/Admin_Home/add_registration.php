<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

$users = $db->query("SELECT user_id, first_name, last_name, email FROM users")->fetchAll();
$events = $db->query("SELECT event_id, title FROM events WHERE status = 'published'")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    $registration_type = $_POST['registration_type'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['payment_amount'] ?: NULL;

    try {
        $stmt = $db->prepare("INSERT INTO registrations (user_id, event_id, registration_type, payment_status, payment_amount) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $event_id, $registration_type, $payment_status, $payment_amount]);
        $success = "Registration created successfully!";
    } catch(PDOException $e) {
        if($e->getCode() == 23000) {
            $error = "This user is already registered for this event!";
        } else {
            $error = "Error creating registration: " . $e->getMessage();
        }
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; font-family:'Roboto', sans-serif; }
.container { padding-top:30px; }
.card { border-radius:12px; background:#1e1e2f; box-shadow:0 4px 20px rgba(0,0,0,0.6); }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-person-plus"></i> Create Registration</h1>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?=$success?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>User</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select User</option>
                        <?php foreach($users as $user): ?>
                            <option value="<?=$user['user_id']?>">
                                <?=htmlspecialchars($user['first_name'].' '.$user['last_name'])?> (<?=$user['email']?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Event</label>
                    <select name="event_id" class="form-control" required>
                        <option value="">Select Event</option>
                        <?php foreach($events as $event): ?>
                            <option value="<?=$event['event_id']?>"><?=htmlspecialchars($event['title'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Registration Type</label>
                    <select name="registration_type" class="form-control" required>
                        <option value="attendee">Attendee</option>
                        <option value="author">Author</option>
                        <option value="student">Student</option>
                        <option value="speaker">Speaker</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Payment Status</label>
                    <select name="payment_status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Payment Amount (USD)</label>
                    <input type="number" step="0.01" name="payment_amount" class="form-control">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Create Registration</button>
        </form>
    </div>
</div>