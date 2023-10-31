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
    // Retrieve data from the form
    $firstName = $_POST['addFirstName'];
    $lastName = $_POST['addLastName'];
    $email = $_POST['addEmail'];
    $specialization = $_POST['addSpecialization'];

    // Handle file upload
    $targetDir = 'assets/images/';
    $targetFile = $targetDir . basename($_FILES['fileUpload']['name']);

    if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetFile)) {
        // File uploaded successfully
        $imagePath = $targetFile;
    } else {
        // Error uploading file
        $imagePath = ''; // Set to an empty string or handle error as needed
    }

    // Insert doctor information into the database
    $stmt = $pdo->prepare("INSERT INTO doctors (first_name, last_name, specialization, email, image) VALUES (?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$firstName, $lastName, $specialization, $email, $imagePath]);
        $_SESSION['success_message'] = "Doctor added successfully!";
         header('Location: AdminCMS.php');// Display success alert
        exit();
        
    } catch (PDOException $e) {
        echo "Error adding doctor: " . $e->getMessage();
    }

}


$pdo = null;
?>