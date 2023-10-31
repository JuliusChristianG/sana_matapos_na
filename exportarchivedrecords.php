<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';


try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Build the query to retrieve all archived records from patient_records table
    $query = "SELECT * FROM patient_records WHERE dateAdded <= DATE_SUB(NOW(), INTERVAL 2 YEAR)";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $archivedPatients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="archived_patient_records_Archived.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Case No.', 'First Name', 'Last Name', 'Middle Name', 'Phone Number', 'Gender', 'Age', 'Date Added'));

    foreach ($archivedPatients as $patient) {
        fputcsv($output, array(
            'MLC - ' . $patient['case_no'],
            $patient['fname'],
            $patient['lname'],
            $patient['mname'],
            $patient['mobileNumber'],
            $patient['gender'],
            $patient['age'],
            $patient['dateAdded']
        ));
    }

    fclose($output);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
