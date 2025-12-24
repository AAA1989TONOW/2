<?php
// event_manager.php - Complete Event Management System
include('base.php');           // Admin_Home/base.php

$db = require_once(__DIR__ . '/../Database/database.php'); // DB Connection
// Handle actions
$action = $_GET['action'] ?? 'list';
$event_id = $_GET['event_id'] ?? null;
$success_msg = '';
$error_msg = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_event'])) {
        // Delete event
        $event_id = $_POST['event_id'];
        $stmt = $db->prepare("DELETE FROM events WHERE event_id = ?");
        if ($stmt->execute([$event_id])) {
            $success_msg = "Event deleted successfully!";
        } else {
            $error_msg = "Error deleting event.";
        }
        $action = 'list';
    } elseif (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
        // Update existing event
        $event_id = $_POST['event_id'];
        $title = $_POST['title'];
        $type = $_POST['event_type'];
        $desc = $_POST['description'];
        $start = $_POST['start_date'];
        $end = $_POST['end_date'];
        $venue = $_POST['venue'];
        $max = $_POST['max_attendees'];
        $fee = $_POST['registration_fee'];
        $status = $_POST['status'];
        
        $stmt = $db->prepare("UPDATE events SET event_type=?, title=?, description=?, start_date=?, end_date=?, venue=?, max_attendees=?, registration_fee=?, status=? WHERE event_id=?");
        if ($stmt->execute([$type, $title, $desc, $start, $end, $venue, $max, $fee, $status, $event_id])) {
            $success_msg = "Event updated successfully!";
            $action = 'list';
        } else {
            $error_msg = "Error updating event.";
        }
    } else {
        // Create new event
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
        
        $stmt = $db->prepare("INSERT INTO events (event_type, title, description, start_date, end_date, venue, max_attendees, registration_fee, created_by, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$type, $title, $desc, $start, $end, $venue, $max, $fee, $created_by, $status])) {
            $success_msg = "Event created successfully!";
            $action = 'list';
        } else {
            $error_msg = "Error creating event.";
        }
    }
}

// Fetch event data for editing
$event = null;
if ($action === 'edit' && $event_id) {
    $stmt = $db->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$event) {
        $error_msg = "Event not found.";
        $action = 'list';
    }
}

// Fetch all events for listing
if ($action === 'list') {
    $events = $db->query("
        SELECT e.*, u.first_name, u.last_name 
        FROM events e 
        LEFT JOIN users u ON e.created_by = u.user_id 
        ORDER BY e.created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#121212; color:#e0e0e0; font-family:'Roboto', sans-serif; }
        .container { padding-top:30px; }
        .card { background:#1e1e2f; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.6); }
        .table thead { background:#1b1b2a; color:#fff; }
        .table tbody tr:hover { background:#2a2a3d; }
        .btn-sm { padding:5px 10px; }
        input, select, textarea { background:#2a2a3d; color:#fff; border:none; border-radius:6px; padding:8px; }
        input:focus, select:focus, textarea:focus { outline:none; box-shadow:0 0 0 2px #0d6efd; }
        .card-header { background:#1e1e2f; font-weight:600; color:#fff; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success_msg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success_msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error_msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($action === 'list'): ?>
            <!-- Events List View -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-card-list"></i> All Events</h1>
                <a href="?action=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New Event
                </a>
            </div>

            <div class="card p-3">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th width="150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($events as $e): ?>
                        <tr>
                            <td><?= $e['event_id'] ?></td>
                            <td><?= $e['title'] ?></td>
                            <td><?= ucfirst($e['event_type']) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($e['start_date'])) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($e['end_date'])) ?></td>
                            <td><?= $e['venue'] ?></td>
                            <td><?= ucfirst($e['status']) ?></td>
                            <td><?= $e['first_name'] . ' ' . $e['last_name'] ?></td>
                            <td>
                                <!-- EDIT -->
                                <a href="?action=edit&event_id=<?= $e['event_id'] ?>" 
                                   class="btn btn-warning btn-sm">
                                   <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                
                                <!-- DELETE -->
                                <form action="" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="event_id" value="<?= $e['event_id'] ?>">
                                    <button type="submit" name="delete_event"
                                        onclick="return confirm('Are you sure you want to delete this event?');"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        <?php else: ?>
            <!-- Create/Edit Event Form -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi <?= $action === 'create' ? 'bi-plus-circle' : 'bi-pencil-square' ?>"></i> 
                    <?= $action === 'create' ? 'Create Event' : 'Edit Event' ?>
                </h1>
                <a href="?action=list" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Events
                </a>
            </div>

            <div class="card p-4">
                <form method="post">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label style="color:white;">Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= $event['title'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Type</label>
                        <select name="event_type" class="form-control" required>
                            <option value="conference" <?= ($event['event_type'] ?? '') === 'conference' ? 'selected' : '' ?>>Conference</option>
                            <option value="competition" <?= ($event['event_type'] ?? '') === 'competition' ? 'selected' : '' ?>>Competition</option>
                            <option value="webinar" <?= ($event['event_type'] ?? '') === 'webinar' ? 'selected' : '' ?>>Webinar</option>
                            <option value="workshop" <?= ($event['event_type'] ?? '') === 'workshop' ? 'selected' : '' ?>>Workshop</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Description</label>
                        <textarea name="description" rows="4" class="form-control"><?= $event['description'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" class="form-control" 
                               value="<?= isset($event['start_date']) ? date('Y-m-d\TH:i', strtotime($event['start_date'])) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">End Date & Time</label>
                        <input type="datetime-local" name="end_date" class="form-control" 
                               value="<?= isset($event['end_date']) ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Venue</label>
                        <input type="text" name="venue" class="form-control" value="<?= $event['venue'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Max Attendees</label>
                        <input type="number" name="max_attendees" class="form-control" value="<?= $event['max_attendees'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Registration Fee (USD)</label>
                        <input type="number" step="0.01" name="registration_fee" class="form-control" 
                               value="<?= $event['registration_fee'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label style="color:white;">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" <?= ($event['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= ($event['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="ongoing" <?= ($event['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                            <option value="completed" <?= ($event['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= ($event['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> 
                        <?= $action === 'create' ? 'Create Event' : 'Update Event' ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>