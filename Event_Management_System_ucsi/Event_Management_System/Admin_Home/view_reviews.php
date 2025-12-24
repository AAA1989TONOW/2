<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

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
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; }
.container { padding-top:30px; }
.card { background:#1e1e2f; border-radius:12px; }
.score { font-weight:bold; }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-clipboard-data"></i> All Reviews</h1>
    
    <div class="card p-4">
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
                                O: <span class="score"><?=$review['score_originality']?></span> |
                                T: <span class="score"><?=$review['score_technical']?></span> |
                                C: <span class="score"><?=$review['score_clarity']?></span> |
                                S: <span class="score"><?=$review['score_significance']?></span>
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>