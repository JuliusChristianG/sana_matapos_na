<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if current password matches the one in the database
    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    $hashed_password = $row['password'];

    // Verify the password using password_verify()
    if (password_verify($current_password, $hashed_password)) {
        if ($new_password === $confirm_password) {
            // Hash the new password using password_hash()
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the password in the database
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$hashed_new_password, $username]);

            // Password changed successfully
            echo json_encode(['success' => true]);
            exit();
        } else {
            // New passwords do not match
            echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
            exit();
        }
    } else {
        // Invalid current password
        echo json_encode(['success' => false, 'message' => 'Invalid current password']);
        exit();
    }
}
?>