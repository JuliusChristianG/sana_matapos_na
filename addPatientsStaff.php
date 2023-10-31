<?php
session_start();
date_default_timezone_set('Asia/Manila');
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $position = $_POST['position'];
    $email = $_POST['email'];

    if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        // Password meets the requirements

        // Check if the email format is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Email format is valid

            // Hash the password using bcrypt (recommended for secure password hashing)
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare the SQL query
            $addUserQuery = "INSERT INTO users (username, first_name, last_name, password, role, dateAdded, email) VALUES (?, ?, ?, ?, ?, NOW(), ?)";

            // Prepare and bind the statement
            $stmt = $mysqli->prepare($addUserQuery);
            $stmt->bind_param("ssssss", $username, $fname, $lname, $hashed_password, $position, $email);

            // After executing the user creation query, get the user ID of the newly created user
            if ($stmt->execute()) {
                // Get the user ID of the staff member (assuming it's stored in the session)
                $staffUserID = $_SESSION['user_id'];
                $loggedInUsername = $_SESSION['username'];
                $loggedInRole = $_SESSION['role'];

                // Add a log entry for the user creation
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $logAction = "Created a new user: $fname $lname";
                $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
                $logStmt = $mysqli->prepare($logQuery);
                $logStmt->bind_param("ssssss", $staffUserID, $loggedInUsername, $loggedInRole, $date, $time, $logAction);
                $logStmt->execute();

                echo "Added successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement and connection
            $stmt->close();
            $mysqli->close();

            // Redirect to the desired page after adding the patient
            header('Location: staffAddPatient.php');
            exit;
        } else {
            // Invalid email format, display a pop-up message using JavaScript
            echo '<script>alert("Invalid email format."); window.location.href = "staffAddPatient.php";</script>';
            exit;
        }
    } else {
        // Password does not meet the requirements, display a pop-up message using JavaScript
        echo '<script>alert("Password must be 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character."); window.location.href = "staffAddPatient.php";</script>';
        exit;
    }
}
?>
