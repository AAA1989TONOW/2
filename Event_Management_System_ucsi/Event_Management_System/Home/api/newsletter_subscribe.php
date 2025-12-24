<?php
// Mock newsletter subscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // In a real app, you'd save this to a 'subscribers' table
        echo json_encode(['status' => 'success', 'message' => 'Subscribed successfully!']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    }
}
?>
