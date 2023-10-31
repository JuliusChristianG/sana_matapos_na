<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mission = $_POST['mission'];
    $vision = $_POST['vision'];
    $objectives = $_POST['objectives'];

    $stmt = $pdo->prepare("UPDATE about_us SET 
        mission=:mission, 
        vission=:vision, 
        objectives=:objectives 
        WHERE id=1");

    $stmt->bindParam(':mission', $mission, PDO::PARAM_STR);
    $stmt->bindParam(':vision', $vision, PDO::PARAM_STR);
    $stmt->bindParam(':objectives', $objectives, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "About Us information updated successfully!!";
        header('Location: AdminCMS.php');
       
    } else {
        echo '<script>alert("Error updating About Us information!");</script>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
