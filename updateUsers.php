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
$mysqli_hostname = 'localhost';
$mysqli_username = 'u651313594_mylabsanjuan';
$mysqli_password = 'Mylabsanjuan23';
$mysqli_database = 'u651313594_mylabClinic';


$mysqli = new mysqli($mysqli_hostname, $mysqli_username, $mysqli_password, $mysqli_database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Assuming you have a patient_id field in your form as a hidden input
$UserId = $_POST['user_id'];
$editUsername = $_POST['editUsername'];
$editFirstName = $_POST['editFirstName'];
$editLastName = $_POST['editLastName'];
$editEmail = $_POST['editEmail'];
$editPosition = $_POST['editPosition'];

// Validate the email format
if (!filter_var($editEmail, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format. Please provide a valid email address.";
    exit;
}

// Update the patient data
$sql = "UPDATE users SET
        username = ?,
        first_name = ?,
        last_name = ?,
        email = ?,
        role = ?
        WHERE user_id = ?";

// Prepare the SQL query
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Bind parameters
    $stmt->bind_param("sssssi", $editUsername, $editFirstName, $editLastName, $editEmail, $editPosition, $UserId);

    // Execute the SQL query
    if ($stmt->execute()) {
        // Data updated successfully

        $adminUserID = $_SESSION['user_id'];
        $loggedInUsername = $_SESSION['username'];
        $loggedInRole = $_SESSION['role'];
        
        // Add a log entry for the patient data edit
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $logAction = "Edited user data for user ID: $UserId";
        $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
        $logStmt = $mysqli->prepare($logQuery);
        $logStmt->bind_param("ssssss", $adminUserID, $loggedInUsername, $loggedInRole, $date, $time, $logAction);
        $logStmt->execute();

        header("Location: adminadduser.php?user_id=' . $user_id"); // Redirect to a success page
    } else {
        // Error occurred
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Error in preparing the statement
    echo "Error: " . $mysqli->error;
}
?>
