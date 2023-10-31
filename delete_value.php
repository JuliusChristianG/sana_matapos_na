<?php
include 'connection.php'; // Include the database connection file

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fieldName = $_POST['fieldName'];

        // Delete the value for the selected field
        $stmt = $pdo->prepare("UPDATE Services SET $fieldName = NULL");
        $stmt->execute();

        echo "Field value deleted successfully!";
    } else {
        echo "Invalid request method.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
