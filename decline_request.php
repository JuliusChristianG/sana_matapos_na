<?php
$dsn = 'mysql:host=localhost;dbname=mylabClinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reason']) && isset($_POST['appointment_id'])) {
        $reason = $_POST['reason'];
        $appointment_id = $_POST['appointment_id'];

        try {
            $stmt = $pdo->prepare("UPDATE xrayrequest SET status = 'Declined', message = :message WHERE appointment_id = :appointment_id");
            $stmt->bindParam(':message', $reason, PDO::PARAM_STR);
            $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success_message'] = "Message was sent successfully!";
            header('Location: staffRadtechPatientRequestsTable.php');
            exit(); // Add this line
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
