<?php
session_start();

include("connection.php");

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorID = $_POST['doctorID'];

    $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
    try {
        $stmt->execute([$doctorID]);
        $_SESSION['success_message'] = "Doctor deleted successfully!";
        header('Location: AdminCMS.php'); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        echo "Error deleting doctor: " . $e->getMessage();
    }
}

$pdo = null;
?>
