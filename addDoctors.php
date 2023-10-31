<?php
session_start();
date_default_timezone_set('Asia/Manila');
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $position = $_POST['position'];
    $email = $_POST['email'];

    // Check if the password meets the requirements
    if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        // Password meets the requirements

        // Check if the email format is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Email format is valid

            // Hash the password using bcrypt (recommended for secure password hashing)
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare the SQL query
            $addUserQuery = "INSERT INTO users (username, first_name, last_name, password, role, dateAdded, email, is_verified) VALUES (?, ?, ?, ?, ?, NOW(), ?, 1)";
            
            // Prepare and bind the statement
            $stmt = $pdo->prepare($addUserQuery);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $fname);
            $stmt->bindParam(3, $lname);
            $stmt->bindParam(4, $hashed_password);
            $stmt->bindParam(5, $position);
            $stmt->bindParam(6, $email);

            // Execute the statement
            $stmt->execute();
            $adminUserID = $_SESSION['user_id'];
            $loggedInUsername = $_SESSION['username'];
            $loggedInRole = $_SESSION['role'];

            // Close the statement
            $stmt->closeCursor();

            // Add a log entry for the user creation
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $logAction = "Created a new user: $username";
            $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
            $logStmt = $pdo->prepare($logQuery);
            $logStmt->bindParam(1, $adminUserID);
            $logStmt->bindParam(2, $loggedInUsername);
            $logStmt->bindParam(3, $loggedInRole);
            $logStmt->bindParam(4, $date);
            $logStmt->bindParam(5, $time);
            $logStmt->bindParam(6, $logAction);
            $logStmt->execute();

            // Redirect to the desired page after adding the user
            header('Location: adminAddDoctors.php');
            exit;
        } else {
            // Invalid email format, display a pop-up message using JavaScript
            echo '<script>alert("Invalid email format."); window.location.href = "adminAddDoctors.php";</script>';
            exit;
        }
    } else {
        // Password does not meet the requirements, display a pop-up message using JavaScript
        echo '<script>alert("Password must be 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character."); window.location.href = "adminAddDoctors.php";</script>';
        exit;
    }
}
?>
