<?php
session_start();
date_default_timezone_set('Asia/Manila');
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

require_once('connection.php');
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Build the query for exporting patients
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    if (empty($search)) {
        $query = "SELECT *
                  FROM patients 
                  WHERE is_recorded = 1 
                  AND dateAdded >= DATE_SUB(NOW(), INTERVAL 2 YEAR)";
    } else {
        $query = "SELECT *
                  FROM patients 
                  WHERE is_recorded = 1 
                  AND dateAdded >= DATE_SUB(NOW(), INTERVAL 2 YEAR)
                  AND (first_name LIKE :search OR last_name LIKE :search)";
    }

    $stmt = $pdo->prepare($query);

    if (!empty($search)) {
        $searchTerm = "%{$search}%";
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }

    $stmt->execute();
    $allPatients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="patient_records_.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('User ID', 'First Name', 'Last Name', 'Middle Name', 'Phone Number', 'Gender', 'Age', 'Date Added'));

    foreach ($allPatients as $patient) {
        fputcsv($output, array(
            $patient['user_id'],
            $patient['first_name'],
            $patient['last_name'],
            $patient['mname'],
            $patient['mobileNumber'],
            $patient['gender'],
            $patient['age'],
            $patient['dateAdded']
        ));
    }

    fclose($output);

    // Add a log entry for the download action
    $adminUserID = $_SESSION['user_id'];
    $loggedInUsername = $_SESSION['username'];
    $loggedInRole = $_SESSION['role'];
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $logAction = "Downloaded patient records CSV File";
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
    $logStmt = $pdo->prepare($logQuery);
    $logStmt->execute([$adminUserID, $loggedInUsername, $loggedInRole, $date, $time, $logAction]);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
