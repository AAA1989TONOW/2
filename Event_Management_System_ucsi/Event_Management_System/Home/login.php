<?php
session_start();
$db = require_once(__DIR__ . '/../Database/database.php'); // DB Connection

$error = "";

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user from database
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['first_name'] . " " . $user['last_name'];
        $_SESSION['user_type'] = $user['user_type'];

        if ($user['user_type'] === 'admin') {
            header("Location: ../Admin_Home/my_admin.php");
        } else {
            header("Location: System_user.php");
        }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ICSDI 2026</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">

            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <h2 class="fw-bold text-center mb-3">Welcome Back</h2>
                    <p class="text-center text-muted mb-4">Sign in to your ICSDI account</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>

                    </form>

                    <div class="text-center mt-4">
                        <p>Don't have an account?
                            <a href="register.php" class="fw-bold">Register Here</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
