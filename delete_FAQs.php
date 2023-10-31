<?php
session_start();

include("connection.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection established
    $dsn = 'mysql:host=localhost;dbname=mylabclinic';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve the ID of the FAQ to be deleted
        $id = $_POST['faqID'];

        // Prepare and execute the SQL query to delete the FAQ
        $stmt = $conn->prepare("DELETE FROM faqs WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $_SESSION['success_message'] = "FAQ deleted successfully!";
        header('Location: AdminCMS.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    } finally {
        $conn = null; // Close the connection
    }
}
?>
