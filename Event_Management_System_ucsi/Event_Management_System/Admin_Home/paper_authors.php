<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

// Handle adding author to paper
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_author'])){
    $paper_id = $_POST['paper_id'];
    $author_id = $_POST['author_id'];
    $author_order = $_POST['author_order'];
    $is_presenting = isset($_POST['is_presenting']) ? 1 : 0;

    try {
        $stmt = $db->prepare("INSERT INTO paper_authors (paper_id, author_id, author_order, is_presenting) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$paper_id, $author_id, $author_order, $is_presenting]);
        $success = "Author added to paper successfully!";
    } catch(PDOException $e) {
        if($e->getCode() == 23000) {
            $error = "This author is already associated with this paper!";
        } else {
            $error = "Error adding author: " . $e->getMessage();
        }
    }
}

// Get data
$paper_authors = $db->query("
    SELECT pa.*, 
           p.title as paper_title,
           CONCAT(u.first_name, ' ', u.last_name) as author_name
    FROM paper_authors pa 
    JOIN papers p ON pa.paper_id = p.paper_id 
    JOIN users u ON pa.author_id = u.user_id 
    ORDER BY pa.paper_id, pa.author_order
")->fetchAll();

$papers = $db->query("SELECT paper_id, title FROM papers")->fetchAll();
$authors = $db->query("SELECT user_id, first_name, last_name FROM users WHERE user_type IN ('author', 'admin')")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Authors - Conference Management System</title>
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
                    <h1 class="h2"><i class="fas fa-people-group me-2"></i>Paper Authors</h1>
                </div>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?=$success?></div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?=$error?></div>
                <?php endif; ?>

                <!-- Add Author Form -->
                <div class="card bg-secondary border-0 mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add Author to Paper</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="add_author" value="1">
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
                                    <label class="form-label">Author</label>
                                    <select name="author_id" class="form-control bg-dark text-white" required>
                                        <option value="">Select Author</option>
                                        <?php foreach($authors as $author): ?>
                                            <option value="<?=$author['user_id']?>"><?=htmlspecialchars($author['first_name'].' '.$author['last_name'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Author Order</label>
                                    <input type="number" name="author_order" class="form-control bg-dark text-white" min="1" value="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_presenting" id="is_presenting">
                                        <label class="form-check-label" for="is_presenting">
                                            Is Presenting Author
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Add Author
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Paper Authors Table -->
                <div class="card bg-secondary border-0">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Paper Authors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Paper</th>
                                        <th>Author</th>
                                        <th>Author Order</th>
                                        <th>Presenting</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($paper_authors as $pa): ?>
                                    <tr>
                                        <td><?=htmlspecialchars($pa['paper_title'])?></td>
                                        <td><?=htmlspecialchars($pa['author_name'])?></td>
                                        <td><span class="badge bg-secondary"><?=$pa['author_order']?></span></td>
                                        <td>
                                            <?php if($pa['is_presenting']): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-danger" title="Remove">
                                                    <i class="fas fa-user-minus"></i>
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