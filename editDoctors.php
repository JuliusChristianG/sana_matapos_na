<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $id = $_POST['id'];
    $firstName = $_POST['editFirstName'];
    $lastName = $_POST['editLastName'];
    $specialization = $_POST['editSpecialization'];
    $imagePath = null; // Set default value to null

    // Check if a file has been uploaded
    if (!empty($_FILES['fileUpload']['name'])) {
        // Handle file upload
        $targetDir = 'assets/images/';
        $targetFile = $targetDir . basename($_FILES['fileUpload']['name']);

        if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetFile)) {
            // File uploaded successfully
            $imagePath = $targetFile;
        } else {
            // Error uploading file
            echo "Error uploading file.";
            exit();
        }
    }

    // Update doctor information in the database
    $stmt = $pdo->prepare("UPDATE doctors SET first_name=?, last_name=?, specialization=?, image=? WHERE id=?");

    try {
        $stmt->execute([$firstName, $lastName, $specialization, $imagePath, $id]);
        header('Location: adminCMS.php'); // Redirect to doctors page after successful update
        exit();
    } catch (PDOException $e) {
        echo "Error updating doctor information: " . $e->getMessage();
    }
}

$pdo = null;


?>
