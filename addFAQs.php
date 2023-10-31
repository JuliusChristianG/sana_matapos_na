<?php
session_start();

include("connection.php");

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $title = $_POST['addTitle'];
    $description = $_POST['addDescription'];

    $stmt = $pdo->prepare("INSERT INTO faqs (title, description) VALUES (:title, :description)");
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);

    $stmt->execute();

    $_SESSION['success_message'] = "FAQ added successfully!";
    header('Location: AdminCMS.php');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
