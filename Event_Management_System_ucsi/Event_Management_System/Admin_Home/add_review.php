<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

$papers = $db->query("SELECT paper_id, title FROM papers WHERE status IN ('submitted', 'under_review')")->fetchAll();
$reviewers = $db->query("SELECT user_id, first_name, last_name FROM users WHERE user_type IN ('reviewer', 'admin')")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $paper_id = $_POST['paper_id'];
    $reviewer_id = $_POST['reviewer_id'];
    
    // Check if review already exists
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
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; font-family:'Roboto', sans-serif; }
.container { padding-top:30px; }
.card { border-radius:12px; background:#1e1e2f; box-shadow:0 4px 20px rgba(0,0,0,0.6); }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-clipboard-check"></i> Assign Review</h1>

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
                    <label>Paper</label>
                    <select name="paper_id" class="form-control" required>
                        <option value="">Select Paper</option>
                        <?php foreach($papers as $paper): ?>
                            <option value="<?=$paper['paper_id']?>"><?=htmlspecialchars($paper['title'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Reviewer</label>
                    <select name="reviewer_id" class="form-control" required>
                        <option value="">Select Reviewer</option>
                        <?php foreach($reviewers as $reviewer): ?>
                            <option value="<?=$reviewer['user_id']?>"><?=htmlspecialchars($reviewer['first_name'].' '.$reviewer['last_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="bi bi-person-check"></i> Assign Review</button>
        </form>
    </div>
</div>