<?php
// Include your database connection code here

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Define the SQL query to get medical history
    $query = "SELECT appointment_id, xray_type, date_consulted FROM patient_findings WHERE user_id = :user_id";

    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($results);
}
?>
