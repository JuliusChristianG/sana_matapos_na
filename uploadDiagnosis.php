<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Doctor"
if ($_SESSION['role'] !== 'Staff Doctor') {
    // If the user is not a Doctor, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}

require("connection.php"); // Make sure to include your database connection here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['user_id'];
    $findings = $_POST['findings'];
    $impression = $_POST['impression'];
    $appointmentID = $_POST['appointmentID'];
    $diagnosed_by = $_POST['diagnosed_by'];

    // Initialize the database connection
    $mysqli = new mysqli("localhost", "root", "", "mylabclinic");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Update the existing row in the patient_findings table
    $stmt = $mysqli->prepare("UPDATE patient_findings SET findings = ?, impression = ?, diagnosed_by = ? WHERE appointment_id = ?");

    $stmt->bind_param("sssi", $findings, $impression, $diagnosed_by, $appointmentID );


    if ($stmt->execute()) {
        // Update the status in the xrayrequest table to "completed"
        $updateStatusQuery = "UPDATE xrayrequest SET status = 'completed' WHERE appointment_id = ?";
        $stmtUpdateStatus = $mysqli->prepare($updateStatusQuery);
        $stmtUpdateStatus->bind_param("s", $appointmentID);

        if ($stmtUpdateStatus->execute()) {
            // Log the user's activity
            // Get user information from the session (adjust as needed)
            $doctorUserID = $_SESSION['user_id'];
            $loggedInUsername = $_SESSION['username'];
            $loggedInRole = $_SESSION['role'];

            // Define the log query
            $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, NOW(), NOW(), ?)";

            // Define the action message for the log entry
            $action = "Updated final diagnosis for Case No. MLC - $appointmentID";

            // Prepare and execute the log query
            $stmtLog = $mysqli->prepare($logQuery);
            $stmtLog->bind_param("isss", $doctorUserID, $loggedInUsername, $loggedInRole, $action);
            $stmtLog->execute();

            $stmt->close();
            $stmtUpdateStatus->close();
            $stmtLog->close();
            $mysqli->close();

            echo 'Final diagnosis updated in the database, and status updated in xrayrequest table';
            header('Location: staffDoctorViewPatient.php?appointmentID=' . $appointmentID . '&user_id=' . $userID);
        } else {
            die("Error updating status: " . $mysqli->error);
        }
    } else {
        die("Error updating patient findings: " . $mysqli->error);
    }
} else {
    die("Error: " . $mysqli->error);
}
?>
