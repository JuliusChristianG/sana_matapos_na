<?php
// Include your database connection code
include('connection.php');

if (isset($_GET['caseNo'])) {
    $caseNo = $_GET['caseNo'];

    // Query to fetch patient details
    $query = "SELECT * FROM patient_records WHERE case_no = :caseNo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':caseNo', $caseNo, PDO::PARAM_INT);
    $stmt->execute();
    $patientDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Query to count unique case_no values for the specific userID
    $countQuery = "SELECT COUNT(DISTINCT case_no) as recordCount FROM patient_records WHERE userID = :userID";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->bindParam(':userID', $patientDetails['userID'], PDO::PARAM_INT);
    $countStmt->execute();
    $recordCountResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    $recordCount = $recordCountResult['recordCount'];

    // Add the record count to the patient details array
    $patientDetails['recordCount'] = $recordCount;

    echo json_encode($patientDetails);
}
?>
