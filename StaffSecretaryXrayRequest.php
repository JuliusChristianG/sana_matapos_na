<?php

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $appointment_schedule = $_POST['appointment_schedule'];
    $status = 'pending';

    try {
        $stmt = $pdo->prepare("INSERT INTO xrayrequest (user_id, fname, lname, appointment_schedule, status) VALUES (:user_id, :first_name, :last_name, :appointment_schedule, :status)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_schedule', $appointment_schedule, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

        // Send back a success message (optional)
        echo "X-Ray request created successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
} else {
    // Handle the case when the form is not submitted properly
    echo "Invalid request";
}

?>