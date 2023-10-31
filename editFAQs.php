<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted via POST

    // Assuming you have a database connection established
    $dsn = 'mysql:host=localhost;dbname=mylabclinic';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve data from the form
        $id = $_POST['editFAQsId'];
        $title = $_POST['edit_faq_title'];
        $description = $_POST['editdescription'];

        // Prepare and execute the SQL query to update the FAQs
        $stmt = $conn->prepare("UPDATE faqs SET title = :title, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        
        $_SESSION['success_message'] = "FAQs updated successfully!";
        header('Location: AdminCMS.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    } finally {
        $conn = null; // Close the connection
    }
}
?>
