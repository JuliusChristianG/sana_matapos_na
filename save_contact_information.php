<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $open_hours = $_POST['open_hours'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];

    $stmt = $pdo->prepare("UPDATE contact_information SET 
        open_hours=:open_hours, 
        contact_number=:contact_number, 
        email_address=:email_address 
        WHERE id=1");

    $stmt->bindParam(':open_hours', $open_hours, PDO::PARAM_STR);
    $stmt->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
    $stmt->bindParam(':email_address', $email_address, PDO::PARAM_STR);

    if ($stmt->execute()) {

        $_SESSION['success_message'] = "Contact Information updated successfully!";
        header('Location: AdminCMS.php');
    } else {
        echo '<script>alert("Error updating contact information!");</script>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>