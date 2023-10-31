<?php
session_start();
include 'connection.php';

// Check if the user is authenticated (You might need this, depending on your authentication system)
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];
    $user_id = $_SESSION['user_id'];

    $updateQuery = "UPDATE users SET username = '$newUsername', first_name = '$newFirstName', last_name = '$newLastName' WHERE user_id = $user_id";

    if (mysqli_query($mysqli, $updateQuery)) {
        $_SESSION['username'] = $newUsername;
        $_SESSION['first_name'] = $newFirstName;
        $_SESSION['last_name'] = $newLastName;

        echo json_encode(['success' => true]);
        exit();
    } else {
        die("Update failed: " . mysqli_error($mysqli));
    }
}

mysqli_close($mysqli);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
