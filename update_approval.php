<?php
session_start();

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $appointment_id = $_POST['appointment_id'];

        try {
            $stmt = $pdo->prepare("UPDATE xrayrequest SET status = 'Approved' WHERE appointment_id = :appointmentID");
            $stmt->bindParam(':appointmentID', $appointment_id, PDO::PARAM_INT);
            $stmt->execute();

            // Redirect back to the page where the form was submitted
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }

    if (isset($_POST['forxray'])) {
        $request_id = $_POST['request_id'];

        try {
            $stmt = $pdo->prepare("UPDATE xrayrequest SET status = 'For Xray' WHERE appointment_id = :appointmentID");
            $stmt->bindParam(':appointmentID', $request_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }

    if (isset($_POST['noshow'])) {
        $request_id = $_POST['request_id'];

        try {
            $stmt = $pdo->prepare("UPDATE xrayrequest SET status = 'No show' WHERE appointment_id = :appointmentID");
            $stmt->bindParam(':appointmentID', $request_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }

    // Redirect back to the page where the form was submitted
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Handle the case when the form is not submitted properly
}
?>
