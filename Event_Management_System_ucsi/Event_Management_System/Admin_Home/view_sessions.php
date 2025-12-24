<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

$sessions = $db->query("
    SELECT s.*, e.title as event_name, 
           CONCAT(u.first_name, ' ', u.last_name) as chair_name
    FROM sessions s 
    LEFT JOIN events e ON s.event_id = e.event_id 
    LEFT JOIN users u ON s.chair_id = u.user_id 
    ORDER BY s.start_time DESC
")->fetchAll();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; }
.container { padding-top:30px; }
.card { background:#1e1e2f; border-radius:12px; }
.table { color:white; }
.table-dark { background:#2a2a3d; }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-calendar-event"></i> All Sessions</h1>
    
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Event</th>
                        <th>Type</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Room</th>
                        <th>Chair</th>
                        <th>Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sessions as $session): ?>
                    <tr>
                        <td><?=htmlspecialchars($session['title'])?></td>
                        <td><?=htmlspecialchars($session['event_name'])?></td>
                        <td><span class="badge bg-primary"><?=ucfirst($session['session_type'])?></span></td>
                        <td><?=date('M j, Y g:i A', strtotime($session['start_time']))?></td>
                        <td><?=date('M j, Y g:i A', strtotime($session['end_time']))?></td>
                        <td><?=htmlspecialchars($session['room'])?></td>
                        <td><?=htmlspecialchars($session['chair_name'])?></td>
                        <td><?=$session['max_capacity'] ?: 'Unlimited'?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>