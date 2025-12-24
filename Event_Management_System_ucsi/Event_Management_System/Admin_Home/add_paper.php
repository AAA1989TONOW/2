<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

$events = $db->query("SELECT event_id, title FROM events")->fetchAll();
$authors = $db->query("SELECT user_id, first_name, last_name FROM users WHERE user_type IN ('author', 'admin')")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $keywords = $_POST['keywords'];
    $track = $_POST['track'];
    $corresponding_author_id = $_POST['corresponding_author_id'];

    $db->beginTransaction();
    try {
        $stmt = $db->prepare("INSERT INTO papers (event_id, title, abstract, keywords, track, corresponding_author_id) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$event_id, $title, $abstract, $keywords, $track, $corresponding_author_id]);
        $paper_id = $db->lastInsertId();
        
        // Add corresponding author as first author
        $stmt = $db->prepare("INSERT INTO paper_authors (paper_id, author_id, author_order, is_presenting) VALUES (?, ?, 1, TRUE)");
        $stmt->execute([$paper_id, $corresponding_author_id]);
        
        $db->commit();
        $success = "Paper submitted successfully!";
    } catch(Exception $e) {
        $db->rollBack();
        $error = "Error submitting paper: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Paper - Conference Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar included via base.php -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-file-upload me-2"></i>Submit Paper</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="papers_list.php" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Back to Papers
                        </a>
                    </div>
                </div>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?=$success?></div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?=$error?></div>
                <?php endif; ?>

                <div class="card bg-secondary border-0">
                    <div class="card-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event</label>
                                    <select name="event_id" class="form-control bg-dark text-white" required>
                                        <option value="">Select Event</option>
                                        <?php foreach($events as $event): ?>
                                            <option value="<?=$event['event_id']?>"><?=htmlspecialchars($event['title'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Track</label>
                                    <select name="track" class="form-control bg-dark text-white" required>
                                        <option value="sustainable_development">Sustainable Development</option>
                                        <option value="renewable_energy">Renewable Energy</option>
                                        <option value="environment">Environment</option>
                                        <option value="technology">Technology</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Paper Title</label>
                                <input type="text" name="title" class="form-control bg-dark text-white" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keywords</label>
                                <input type="text" name="keywords" class="form-control bg-dark text-white" placeholder="Comma separated keywords">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Abstract</label>
                                <textarea name="abstract" rows="6" class="form-control bg-dark text-white" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Corresponding Author</label>
                                <select name="corresponding_author_id" class="form-control bg-dark text-white" required>
                                    <option value="">Select Author</option>
                                    <?php foreach($authors as $author): ?>
                                        <option value="<?=$author['user_id']?>"><?=htmlspecialchars($author['first_name'].' '.$author['last_name'])?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Submit Paper
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>