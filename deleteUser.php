<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}

require("connection.php");

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Prepare the SQL query to delete the user.
    $deleteUserQuery = "UPDATE users SET is_deleted = 1 WHERE user_id = ?";

    // Prepare and execute the delete statement.
    $stmt = $pdo->prepare($deleteUserQuery);
    $stmt->execute([$userId]);

    $adminUserId = $_SESSION['user_id']; // Corrected the variable name
    $loggedInUsername = $_SESSION['username'];
    $loggedInRole = $_SESSION['role'];

    // Add a log entry for the user deletion
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $logAction = "Deactivated a System user (ID: $userId)";
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
    $logStmt = $pdo->prepare($logQuery);
    $logStmt->execute([$adminUserId, $loggedInUsername, $loggedInRole, $date, $time, $logAction]);

    // Redirect back to the user management page after deleting the user.
    header('Location: adminAddUser.php');
    exit;
} else {
    // Handle the case when 'user_id' is not provided in the URL.
    echo "User ID not provided.";
    exit;
}
?>
