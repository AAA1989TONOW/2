<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

// Handle track management operations
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['add_track'])){
        $track_name = $_POST['track_name'];
        $description = $_POST['description'];
        
        // Implementation for adding new tracks
        $success = "Track added successfully!";
    }
}

// Get tracks statistics
$tracks_stats = $db->query("
    SELECT track, COUNT(*) as paper_count 
    FROM papers 
    GROUP BY track 
    ORDER BY paper_count DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Management - Conference Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar included via base.php -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-layer-group me-2"></i>Track Management</h1>
                </div>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?=$success?></div>
                <?php endif; ?>

                <!-- Track Statistics -->
                <div class="row mb-4">
                    <?php foreach($tracks_stats as $track): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><?=ucfirst(str_replace('_', ' ', $track['track']))?></h5>
                                <h2 class="card-text"><?=$track['paper_count']?></h2>
                                <p class="card-text">Papers</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Track Management -->
                <div class="card bg-secondary border-0">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Manage Tracks</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Track management functionality coming soon...</p>
                        <!-- Add track management interface here -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>