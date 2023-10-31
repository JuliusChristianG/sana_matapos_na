<?php
session_start();
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_schedule = $_POST["appointment_schedule"];
    $status = $_POST['status'];
    $userID = $_SESSION['user_id'];

    // Calculate the start and end time of the 1-hour interval
    $start_time = date('Y-m-d H:i:s', strtotime($appointment_schedule . '-1 hour'));
    $end_time = date('Y-m-d H:i:s', strtotime($appointment_schedule . '+1 hour'));

    // Check if there are any existing appointments within the 1-hour interval
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM xrayrequest WHERE appointment_schedule BETWEEN :start_time AND :end_time");
    $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Chosen time is not available, inform the user
        echo "<script>alert('There is already an appointment scheduled within 1 hour of the chosen time. Please select a different time.');</script>";
        exit();
    }

    // Get the first name and last name of the current user based on the case number
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE user_id = :userID");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
        echo "Patient not found.";
        die();
    }
    // Handle file upload
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Generate a unique image name based on appointment ID and user's name
        $imageName = $userID . '_' . date('Ymd', strtotime($appointment_schedule)) . '.' . $fileExt;

        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];

        if ($fileError !== UPLOAD_ERR_OK) {
            die('File upload failed with error code: ' . $fileError);
        }

        $uploadDirectory = 'assets/proof_of_referral/';
        $fileDestination = $uploadDirectory . $imageName;

        if (!move_uploaded_file($fileTmpName, $fileDestination)) {
            die('Failed to move the uploaded file to the destination.');
        }

        $imagePath = $fileDestination;
    } else {
        die('No image file was uploaded.');
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO xrayrequest (user_id, fname, lname, appointment_schedule, status, date_created,  referral_image) VALUES (:user_id, :fname, :lname, :appointment_schedule, :status, NOW(), :image_path)");
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':fname', $patient['first_name'], PDO::PARAM_STR);
        $stmt->bindParam(':lname', $patient['last_name'], PDO::PARAM_STR);
        $stmt->bindParam(':appointment_schedule', $appointment_schedule, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
        $stmt->execute();



        echo "<script>
            window.onload = function() {
                alert('Request submitted successfully');
                window.history.back();
            }
          </script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>
            window.onload = function() {
                alert('Error: " . $e->getMessage() . "');
                window.history.back();
            }
          </script>";
        die();
    }

} else {
    die('Invalid request method.');
}
?>