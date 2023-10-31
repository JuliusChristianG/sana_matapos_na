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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); // Get the file extension and convert it to lowercase
        $caseNo = $_POST['caseNo'];

        // Check if the file extension is allowed
        if ($fileExt === 'jpg' || $fileExt === 'png') {
            // Rest of your code...
        } else {
            die('Only .jpg and .png files are allowed.');
        }
    } else {
        die('No image file was uploaded.');
    }
        $db = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');
        $stmt = $db->prepare("SELECT lname FROM patient_records WHERE case_no = ?");
        $stmt->bind_param("i", $caseNo);
        $stmt->execute();
        $stmt->bind_result($lastName);
        
        if ($stmt->fetch()) {
            // Construct the filename as "caseNo_LastName"
            $fileName = $caseNo . '_' . $lastName . '.' . $fileExt;

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
            die('No matching record found for case number: ' . $caseNo);
        }

        $stmt->close();
        $db->close();
    } else {
        die('No image file was uploaded.');
    }

    // Get other form data
    $finalDiagnosis = $_POST['finalDiagnosis'];
    $xrayType = $_POST['xrayType'];
    $issued_by = $_POST['issuedBy'];

    // Store the file path in the database
    // Replace your database credentials and connection code here
    $db = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');
    
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $stmt = $db->prepare("INSERT INTO patient_findings (case_number, xray_file, date_consulted, final_diagnosis, xray_type, issued_by) VALUES (?, ?, NOW(), ?, ?, ?)");
    $stmt->bind_param("issss", $caseNo, $imagePath, $finalDiagnosis, $xrayType, $issued_by);

    if ($stmt->execute()) {
        // Log the user's activity
        // Get user information from the session (adjust as needed)
        $adminUserID = $_SESSION['user_id'];
        $loggedInUsername = $_SESSION['username'];
        $loggedInRole = $_SESSION['role'];

        // Define the log query
        $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";

        $date = date("Y-m-d");
        $time = date("H:i:s");

        // Define the action message for the log entry
        $action = "Uploaded X-ray image for Case No. MLC - $caseNo";

        // Prepare and execute the log query
        $stmtLog = $db->prepare($logQuery);
        $stmtLog->bind_param("isssss", $adminUserID, $loggedInUsername, $loggedInRole, $date, $time, $action);
        $stmtLog->execute();

        $stmt->close();
        $stmtLog->close();
        $db->close();

        echo 'Image path stored in the database';
        header('Location:adminViewPatient.php?caseNo=' . $caseNo); // Redirect to another page after successful insert
        exit;
    } else {
        die("Error: " . $db->error);
    }

?>
