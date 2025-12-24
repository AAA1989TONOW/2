<?php
// Correct paths based on your structure
include('base.php');           // Admin_Home/base.php
$db = require_once(__DIR__ . '/../Database/database.php'); // DB Connection

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $type = $_POST['event_type'];
    $desc = $_POST['description'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $venue = $_POST['venue'];
    $max = $_POST['max_attendees'];
    $fee = $_POST['registration_fee'];
    $status = $_POST['status'];
    $created_by = 1; // Admin user for now

    $stmt = $db->prepare("INSERT INTO events (event_type, title, description, start_date, end_date, venue, max_attendees, registration_fee, created_by, status)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$type, $title, $desc, $start, $end, $venue, $max, $fee, $created_by, $status]);
    $success = "Event created successfully!";
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; font-family:'Roboto', sans-serif; }
.container { padding-top:30px; }
.card { border-radius:12px; background:#1e1e2f; box-shadow:0 4px 20px rgba(0,0,0,0.6); }
.card-header { background:#1e1e2f; font-weight:600; color:#fff; }
input, select, textarea { background:#2a2a3d; color:#fff; border:none; border-radius:6px; padding:8px; }
input:focus, select:focus, textarea:focus { outline:none; box-shadow:0 0 0 2px #0d6efd; }
button { border-radius:8px; }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-plus-circle"></i> Create Event</h1>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?=$success?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post">
            <div class="mb-3">
                <label style="color:white;">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label style="color:white;">Type</label>
                <select name="event_type" class="form-control" required>
                    <option value="conference">Conference</option>
                    <option value="competition">Competition</option>
                    <option value="webinar">Webinar</option>
                    <option value="workshop">Workshop</option>
                </select>
            </div>
            <div class="mb-3">
                <label style="color:white;">Description</label>
                <textarea name="description" rows="4" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label style="color:white;">Start Date & Time</label>
                <input type="datetime-local" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label style="color:white;">End Date & Time</label>
                <input type="datetime-local" name="end_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label style="color:white;">Venue</label>
                <input type="text" name="venue" class="form-control">
            </div>
            <div class="mb-3">
                <label style="color:white;">Max Attendees</label>
                <input type="number" name="max_attendees" class="form-control">
            </div>
            <div class="mb-3">
                <label style="color:white;">Registration Fee (USD)</label>
                <input type="number" step="0.01" name="registration_fee" class="form-control">
            </div>
            <div class="mb-3">
                <label style="color:white;">Status</label>
                <select name="status" class="form-control" required>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Create Event</button>
        </form>
    </div>
</div>
