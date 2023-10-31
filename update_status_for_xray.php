<?php
session_start();

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['forxray'])) {
        $appointment_id = $_POST['appointment_id'];

        try {
            $stmt = $pdo->prepare("UPDATE xrayrequest SET status = 'forxray' WHERE appointment_id = :appointmentID");
            $stmt->bindParam(':appointmentID', $appointment_id, PDO::PARAM_INT);
            $stmt->execute();

            // Redirect back to the page where the form was submitted
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    } elseif (isset($_POST['delete'])) {
        // Handle delete logic here
    }
} else {
    // Handle the case when the form is not submitted properly
}
?>
