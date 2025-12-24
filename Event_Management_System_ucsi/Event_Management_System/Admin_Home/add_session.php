<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

// Fetch events and users for dropdowns
$events = $db->query("SELECT event_id, title FROM events")->fetchAll();
$chairs = $db->query("SELECT user_id, first_name, last_name FROM users WHERE user_type IN ('admin', 'chair')")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $session_type = $_POST['session_type'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $room = $_POST['room'];
    $chair_id = $_POST['chair_id'] ?: NULL;
    $max_capacity = $_POST['max_capacity'] ?: NULL;

    $stmt = $db->prepare("INSERT INTO sessions (event_id, title, description, session_type, start_time, end_time, room, chair_id, max_capacity) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$event_id, $title, $description, $session_type, $start_time, $end_time, $room, $chair_id, $max_capacity]);
    $success = "Session created successfully!";
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
    <h1 class="mb-4"><i class="bi bi-plus-circle"></i> Create Session</h1>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?=$success?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Event</label>
                    <select name="event_id" class="form-control" required>
                        <option value="">Select Event</option>
                        <?php foreach($events as $event): ?>
                            <option value="<?=$event['event_id']?>"><?=htmlspecialchars($event['title'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Session Type</label>
                    <select name="session_type" class="form-control" required>
                        <option value="keynote">Keynote</option>
                        <option value="technical">Technical</option>
                        <option value="workshop">Workshop</option>
                        <option value="panel">Panel</option>
                        <option value="poster">Poster</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control"></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Start Time</label>
                    <input type="datetime-local" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>End Time</label>
                    <input type="datetime-local" name="end_time" class="form-control" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Room</label>
                    <input type="text" name="room" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Session Chair</label>
                    <select name="chair_id" class="form-control">
                        <option value="">Select Chair</option>
                        <?php foreach($chairs as $chair): ?>
                            <option value="<?=$chair['user_id']?>"><?=htmlspecialchars($chair['first_name'].' '.$chair['last_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Max Capacity</label>
                    <input type="number" name="max_capacity" class="form-control">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Create Session</button>
        </form>
    </div>
</div>