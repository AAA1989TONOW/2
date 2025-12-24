<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

// Handle review assignment
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_review'])){
    $paper_id = $_POST['paper_id'];
    $reviewer_id = $_POST['reviewer_id'];
    
    $check = $db->prepare("SELECT COUNT(*) FROM reviews WHERE paper_id = ? AND reviewer_id = ?");
    $check->execute([$paper_id, $reviewer_id]);
    
    if($check->fetchColumn() == 0){
        $stmt = $db->prepare("INSERT INTO reviews (paper_id, reviewer_id) VALUES (?, ?)");
        $stmt->execute([$paper_id, $reviewer_id]);
        $success = "Review assignment created successfully!";
    } else {
        $error = "This reviewer is already assigned to this paper!";
    }
}

// Get data
$reviews = $db->query("
    SELECT r.*, p.title as paper_title, 
           CONCAT(u.first_name, ' ', u.last_name) as reviewer_name,
           e.title as event_name
    FROM reviews r 
    JOIN papers p ON r.paper_id = p.paper_id 
    JOIN users u ON r.reviewer_id = u.user_id 
    JOIN events e ON p.event_id = e.event_id 
    ORDER BY r.assigned_date DESC
")->fetchAll();

$papers = $db->query("SELECT paper_id, title FROM papers WHERE status IN ('submitted', 'under_review')")->fetchAll();
$reviewers = $db->query("SELECT user_id, first_name, last_name FROM users WHERE user_type IN ('reviewer', 'admin')")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Papers - Conference Management System</title>
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
                    <h1 class="h2"><i class="fas fa-clipboard-check me-2"></i>Review Papers</h1>
                </div>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?=$success?></div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?=$error?></div>
                <?php endif; ?>

                <!-- Assign Review Form -->
                <div class="card bg-secondary border-0 mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Assign New Review</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="assign_review" value="1">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Paper</label>
                                    <select name="paper_id" class="form-control bg-dark text-white" required>
                                        <option value="">Select Paper</option>
                                        <?php foreach($papers as $paper): ?>
                                            <option value="<?=$paper['paper_id']?>"><?=htmlspecialchars($paper['title'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reviewer</label>
                                    <select name="reviewer_id" class="form-control bg-dark text-white" required>
                                        <option value="">Select Reviewer</option>
                                        <?php foreach($reviewers as $reviewer): ?>
                                            <option value="<?=$reviewer['user_id']?>"><?=htmlspecialchars($reviewer['first_name'].' '.$reviewer['last_name'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-check me-1"></i> Assign Review
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reviews Table -->
                <div class="card bg-secondary border-0">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>All Reviews</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Paper</th>
                                        <th>Event</th>
                                        <th>Reviewer</th>
                                        <th>Scores</th>
                                        <th>Overall</th>
                                        <th>Status</th>
                                        <th>Assigned Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($reviews as $review): ?>
                                    <tr>
                                        <td><?=htmlspecialchars($review['paper_title'])?></td>
                                        <td><?=htmlspecialchars($review['event_name'])?></td>
                                        <td><?=htmlspecialchars($review['reviewer_name'])?></td>
                                        <td>
                                            <?php if($review['overall_score']): ?>
                                                O: <span class="fw-bold"><?=$review['score_originality']?></span> |
                                                T: <span class="fw-bold"><?=$review['score_technical']?></span> |
                                                C: <span class="fw-bold"><?=$review['score_clarity']?></span> |
                                                S: <span class="fw-bold"><?=$review['score_significance']?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Not scored</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($review['overall_score']): ?>
                                                <span class="badge bg-primary"><?=$review['overall_score']?>/5</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?=$review['review_status'] == 'completed' ? 'success' : 'warning'?>">
                                                <?=ucfirst(str_replace('_', ' ', $review['review_status']))?>
                                            </span>
                                        </td>
                                        <td><?=date('M j, Y', strtotime($review['assigned_date']))?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-outline-info" title="View Review">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit Review">
                                                    <i class="fas fa-edit"></i>
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