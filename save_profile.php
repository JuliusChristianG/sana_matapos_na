<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $profile_text = $_POST['profile_text'];

    $stmt = $pdo->prepare("UPDATE company_profile SET information=:profile_text WHERE id=1");
    $stmt->bindParam(':profile_text', $profile_text, PDO::PARAM_STR);
    $stmt->execute();

    $_SESSION['success_message'] = "Company Profile Text was edited successfully!";
    header('Location: AdminCMS.php');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
