<?php
session_start(); // Start the session (if not already started)

// Check if user is authenticated
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] && $_SESSION['role'] === 'Patient') {
    // Set Cache-Control header to prevent caching
    header('Cache-Control: no-store, must-revalidate');

    // Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
    $dsn = 'mysql:host=localhost;dbname=mylabclinic';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Use a prepared statement with a named parameter
        $getPatientInfoQuery = "SELECT * FROM patients WHERE user_ID = :user_id";
        $stmt = $pdo->prepare($getPatientInfoQuery);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); // Bind the session user_id correctly
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Check if there are no records for the user
        if (empty($result)) {
            header('Location: patientdashboardnorecord.php');
            exit();
        }
        
        // You can access the patient data in $result
        foreach ($result as $row) {
            $patient_user_id = $row['userID'];
            $caseNo = $row['case_no'];
            // Use these variables as needed
        }
        
        header("Location: patientDashboard.php?caseNo=$caseNo");

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }
} else {
    // Redirect to login or access denied page
    header('Location: patientdashboardnorecord.php');
    exit();
}
?>
