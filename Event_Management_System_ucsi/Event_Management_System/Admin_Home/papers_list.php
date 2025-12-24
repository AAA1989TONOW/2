<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete paper
    if (isset($_POST['delete_paper'])) {
        $paper_id = $_POST['paper_id'];
        
        // Start transaction
        $db->beginTransaction();
        
        try {
            // Delete related records first
            $db->query("DELETE FROM paper_authors WHERE paper_id = $paper_id");
            
            // Delete the paper
            $db->query("DELETE FROM papers WHERE paper_id = $paper_id");
            
            $db->commit();
            $success_message = "Paper deleted successfully!";
        } catch (Exception $e) {
            $db->rollBack();
            $error_message = "Error deleting paper: " . $e->getMessage();
        }
    }
    
    // Update paper
    if (isset($_POST['update_paper'])) {
        $paper_id = $_POST['paper_id'];
        $title = $_POST['title'];
        $event_id = $_POST['event_id'];
        $track = $_POST['track'];
        $status = $_POST['status'];
        $abstract = $_POST['abstract'];
        
        try {
            $stmt = $db->prepare("UPDATE papers SET title = ?, event_id = ?, track = ?, status = ?, abstract = ? WHERE paper_id = ?");
            $stmt->execute([$title, $event_id, $track, $status, $abstract, $paper_id]);
            
            $success_message = "Paper updated successfully!";
        } catch (Exception $e) {
            $error_message = "Error updating paper: " . $e->getMessage();
        }
    }
}

// Get all papers
$papers = $db->query("
    SELECT p.*, e.title as event_name, 
           CONCAT(u.first_name, ' ', u.last_name) as author_name,
           (SELECT COUNT(*) FROM paper_authors pa WHERE pa.paper_id = p.paper_id) as author_count
    FROM papers p 
    LEFT JOIN events e ON p.event_id = e.event_id 
    LEFT JOIN users u ON p.corresponding_author_id = u.user_id 
    ORDER BY p.submission_date DESC
")->fetchAll();

// Get events for dropdown
$events = $db->query("SELECT event_id, title FROM events")->fetchAll();

// Get paper details for editing
$paper_to_edit = null;
if (isset($_GET['edit'])) {
    $paper_id = $_GET['edit'];
    $paper_to_edit = $db->query("
        SELECT p.*, e.title as event_name, 
               CONCAT(u.first_name, ' ', u.last_name) as author_name
        FROM papers p 
        LEFT JOIN events e ON p.event_id = e.event_id 
        LEFT JOIN users u ON p.corresponding_author_id = u.user_id 
        WHERE p.paper_id = $paper_id
    ")->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Papers - Conference Management System</title>
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
                    <h1 class="h2"><i class="fas fa-file-alt me-2"></i>All Papers</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="add_paper.php" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Paper
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $success_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Edit Paper Modal -->
                <?php if ($paper_to_edit): ?>
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Paper</h5>
                                <a href="?" class="btn-close btn-close-white"></a>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="paper_id" value="<?= $paper_to_edit['paper_id'] ?>">
                                    
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Paper Title</label>
                                        <input type="text" class="form-control bg-secondary text-white" id="title" name="title" 
                                               value="<?= htmlspecialchars($paper_to_edit['title']) ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="event_id" class="form-label">Event</label>
                                        <select class="form-control bg-secondary text-white" id="event_id" name="event_id" required>
                                            <option value="">Select Event</option>
                                            <?php foreach($events as $event): ?>
                                                <option value="<?= $event['event_id'] ?>" 
                                                    <?= $event['event_id'] == $paper_to_edit['event_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($event['title']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="track" class="form-label">Track</label>
                                        <select class="form-control bg-secondary text-white" id="track" name="track" required>
                                            <option value="computer_science" <?= $paper_to_edit['track'] == 'computer_science' ? 'selected' : '' ?>>Computer Science</option>
                                            <option value="data_science" <?= $paper_to_edit['track'] == 'data_science' ? 'selected' : '' ?>>Data Science</option>
                                            <option value="artificial_intelligence" <?= $paper_to_edit['track'] == 'artificial_intelligence' ? 'selected' : '' ?>>Artificial Intelligence</option>
                                            <option value="networking" <?= $paper_to_edit['track'] == 'networking' ? 'selected' : '' ?>>Networking</option>
                                            <option value="cybersecurity" <?= $paper_to_edit['track'] == 'cybersecurity' ? 'selected' : '' ?>>Cybersecurity</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control bg-secondary text-white" id="status" name="status" required>
                                            <option value="submitted" <?= $paper_to_edit['status'] == 'submitted' ? 'selected' : '' ?>>Submitted</option>
                                            <option value="under_review" <?= $paper_to_edit['status'] == 'under_review' ? 'selected' : '' ?>>Under Review</option>
                                            <option value="accepted" <?= $paper_to_edit['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                            <option value="rejected" <?= $paper_to_edit['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                            <option value="needs_revision" <?= $paper_to_edit['status'] == 'needs_revision' ? 'selected' : '' ?>>Needs Revision</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="abstract" class="form-label">Abstract</label>
                                        <textarea class="form-control bg-secondary text-white" id="abstract" name="abstract" 
                                                  rows="5"><?= htmlspecialchars($paper_to_edit['abstract']) ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" name="update_paper" class="btn btn-primary">Update Paper</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Delete Confirmation Modal -->
                <?php if (isset($_GET['delete'])): ?>
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <a href="?" class="btn-close btn-close-white"></a>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this paper? This action cannot be undone.</p>
                                <p><strong>Paper:</strong> <?= htmlspecialchars($papers[array_search($_GET['delete'], array_column($papers, 'paper_id'))]['title']) ?></p>
                            </div>
                            <div class="modal-footer">
                                <a href="?" class="btn btn-secondary">Cancel</a>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="paper_id" value="<?= $_GET['delete'] ?>">
                                    <button type="submit" name="delete_paper" class="btn btn-danger">Delete Paper</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card bg-secondary border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Event</th>
                                        <th>Track</th>
                                        <th>Corresponding Author</th>
                                        <th>Authors</th>
                                        <th>Submission Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($papers as $paper): ?>
                                    <tr>
                                        <td><?=htmlspecialchars($paper['title'])?></td>
                                        <td><?=htmlspecialchars($paper['event_name'])?></td>
                                        <td><span class="badge bg-info"><?=ucfirst(str_replace('_', ' ', $paper['track']))?></span></td>
                                        <td><?=htmlspecialchars($paper['author_name'])?></td>
                                        <td><span class="badge bg-secondary"><?=$paper['author_count']?> authors</span></td>
                                        <td><?=date('M j, Y', strtotime($paper['submission_date']))?></td>
                                        <td>
                                            <?php 
                                            $statusClass = [
                                                'submitted' => 'bg-secondary',
                                                'under_review' => 'bg-warning',
                                                'accepted' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                                'needs_revision' => 'bg-info'
                                            ];
                                            ?>
                                            <span class="badge <?=$statusClass[$paper['status']]?>">
                                                <?=ucfirst(str_replace('_', ' ', $paper['status']))?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="?edit=<?= $paper['paper_id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?= $paper['paper_id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>