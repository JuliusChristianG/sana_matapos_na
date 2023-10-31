<?php
session_start();
date_default_timezone_set('Asia/Manila');

require("connection.php"); // Move the database connection code to the top

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Staff"
if ($_SESSION['role'] !== 'Staff Radtech') {
    // If the user is not a Staff, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); // Get the file extension and convert it to lowercase
        $userID = $_POST['user_id'];
        //$userID = $_POST['userID'];

        // Check if the file extension is allowed
        if ($fileExt === 'jpg' || $fileExt === 'png') {
            // Rest of your code...
        } else {
            die('Only .jpg and .png files are allowed.');
        }
    } else {
        die('No image file was uploaded.');
    }
        
    $stmt = $mysqli->prepare("SELECT last_name FROM patients WHERE user_id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($lastName);
    
    if ($stmt->fetch()) {
        // Construct the filename as "caseNo_LastName"
        $fileName = $userID . '_' . $lastName . '.' . $fileExt;

        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];

        if ($fileError !== UPLOAD_ERR_OK) {
            // Handle the error
            die('File upload failed with error code: ' . $fileError);
        }

        // Assuming you have a directory named "uploads" where you want to store the images
        $uploadDirectory = 'assets/uploads/';
        $fileDestination = $uploadDirectory . $fileName;

        if (!move_uploaded_file($fileTmpName, $fileDestination)) {
            die('Failed to move the uploaded file to the destination.');
        }

        $imagePath = $fileDestination;
    } else {
        die('No matching record found for case number: ' . $userID);
    }

    $stmt->close();
} else {
    die('No image file was uploaded.');
}

// Get other form data
$xrayType = $_POST['xrayType'];
$issued_by = $_POST['issuedBy'];
$appointmentID = $_POST['appointmentID'];

if ($xrayType === 'Other') {
    $xrayType = $_POST['otherOption'];
}


$stmt = $mysqli->prepare("INSERT INTO patient_findings (user_id, appointment_id, xray_file, date_consulted, xray_type, issued_by) VALUES (?, ?, ?, NOW(), ?, ?)");
$stmt->bind_param("sssss", $userID, $appointmentID,  $imagePath, $xrayType, $issued_by);

if ($stmt->execute()) {
    // Update the status in the xrayrequest table to "for diagnosis"
    $updateStatusQuery = "UPDATE xrayrequest SET status = 'for diagnosis' WHERE appointment_id = ?";
    $stmtUpdateStatus = $mysqli->prepare($updateStatusQuery);
    $stmtUpdateStatus->bind_param("s", $appointmentID);
    $stmtUpdateStatus->execute();
    
    // Log the user's activity
    // Get user information from the session (adjust as needed)
    $staffUserID = $_SESSION['user_id'];
    $loggedInUsername = $_SESSION['username'];
    $loggedInRole = $_SESSION['role'];

    // Define the log query
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";

    $date = date("Y-m-d");
    $time = date("H:i:s");

    // Define the action message for the log entry
    $action = "Uploaded X-ray image for Case No. MLC - $userID";

    // Prepare and execute the log query
    $stmtLog = $mysqli->prepare($logQuery);
    $stmtLog->bind_param("isssss", $staffUserID, $loggedInUsername, $loggedInRole, $date, $time, $action);
    $stmtLog->execute();

    $stmt->close();
    $stmtUpdateStatus->close();

    echo 'Image path stored in the database, and status updated in xrayrequest table';
    header('Location: staffRadtechViewPatient.php?appointmentID=' . $appointmentID . '&user_id=' . $userID);
    // Redirect to another page after a successful insert
    exit;
} else {
    die("Error: " . $mysqli->error);
}
?>
