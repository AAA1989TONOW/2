<?php
session_start();
$db = require_once(__DIR__ . '/../Database/database.php');

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);
    $role = trim($_POST['user_type']); // only 'admin'

    if ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {

        $stmt = $db->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "This email is already registered!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare("
                INSERT INTO users (email, password, first_name, last_name, user_type)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([$email, $hashed, $first, $last, $role]);

            $success = "Account created successfully! Redirecting to login...";

            header("refresh:1.5; url=login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - ICSDI 2026</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-lg border-0">
                <div class="card-body p-5">

                    <h2 class="fw-bold text-center mb-3">Create Account</h2>
                    <p class="text-center text-muted mb-4">Join ICSDI 2026</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">First Name *</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <input type="hidden" name="user_type" value="user">

                        <button class="btn btn-primary btn-lg w-100">Create Account</button>

                    </form>

                    <div class="text-center mt-4">
                        <p>Already registered?
                            <a href="login.php" class="fw-bold">Login Here</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
