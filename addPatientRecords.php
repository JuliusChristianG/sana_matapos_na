<?php
session_start();
date_default_timezone_set('Asia/Manila');
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $birthday = $_POST['birthday'];
    $birthplace = $_POST['birthplace'];
    $civilStatus = $_POST['civilStatus'];
    $gender = $_POST['gender'];
    $mobileNum = $_POST['mobileNum'];
    $religion = $_POST['religion'];
    $occupation = $_POST['occupation'];
    $userID = $_POST['patientAcc'];
    $date1 = date("Y-m-d");
    $time = date("H:i:s");



    // Prepare the SQL query to insert the patient record with the date only
    $addPatientQuery = "INSERT INTO patient_records (fname, lname, mname, address, age, birthday, birthplace, civilStatus, gender, mobileNumber, religion, occupation, dateAdded, userID, active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?, 1)";

    // Prepare and bind the statement for inserting the patient record
    $stmt = $pdo->prepare($addPatientQuery);
    $stmt->bindParam(1, $fname);
    $stmt->bindParam(2, $lname);
    $stmt->bindParam(3, $mname);
    $stmt->bindParam(4, $address);
    $stmt->bindParam(5, $age);
    $stmt->bindParam(6, $birthday);
    $stmt->bindParam(7, $birthplace);
    $stmt->bindParam(8, $civilStatus);
    $stmt->bindParam(9, $gender);
    $stmt->bindParam(10, $mobileNum);
    $stmt->bindParam(11, $religion);
    $stmt->bindParam(12, $occupation);
    $stmt->bindParam(13, $date1); // Use the date only
    $stmt->bindParam(14, $userID);

    // Execute the statement to insert the patient record
    $stmt->execute();
    $adminUserID = $_SESSION['user_id'];
    $loggedInUsername = $_SESSION['username'];
    $loggedInRole = $_SESSION['role'];

    // Add a log entry for the patient record creation
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $logAction = "Added a new patient record for $fname $lname";
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
    $logStmt = $pdo->prepare($logQuery);
    $logStmt->bindParam(1, $adminUserID); // Assuming you want to log the user who added the record
    $logStmt->bindParam(2, $loggedInUsername); // Assuming you want to log the username who added the record
    $logStmt->bindParam(3, $loggedInRole);
    $logStmt->bindParam(4, $date);
    $logStmt->bindParam(5, $time);
    $logStmt->bindParam(6, $logAction);
    $logStmt->execute();

    // Close the statement for inserting the patient record
    $stmt->closeCursor();

    // Redirect to the desired page after adding the patient record
    header("Location: adminPatientRecordsTable.php");
    exit;
}
?>
