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
date_default_timezone_set('Asia/Manila');
require("connection.php");

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $deleteUserQuery = "UPDATE users SET is_deleted = 1 WHERE user_id = ?";

    // Prepare and execute the query to delete the user
    $stmt = $pdo->prepare($deleteUserQuery);
    $stmt->execute([$userId]);

    $adminUserId = $_SESSION['user_id']; // Fix the variable name here
    $loggedInUsername = $_SESSION['username'];
    $loggedInRole = $_SESSION['role'];

    // Add a log entry for the user deletion
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $logAction = "Deactivated a patient user (ID: $userId)";
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
    $logStmt = $pdo->prepare($logQuery);
    $logStmt->bindParam(1, $adminUserId); // Fix the variable name here
    $logStmt->bindParam(2, $loggedInUsername);
    $logStmt->bindParam(3, $loggedInRole);
    $logStmt->bindParam(4, $date);
    $logStmt->bindParam(5, $time);
    $logStmt->bindParam(6, $logAction);
    $logStmt->execute();

    // Redirect back to the page after deletion
    header("Location: adminAddPatient.php");
    exit;
}
?>
