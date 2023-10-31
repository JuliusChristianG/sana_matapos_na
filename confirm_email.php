<?php
session_start();
require_once 'connection.php'; // Include your database connection file

$token = $_GET["token"];

// Retrieve user ID associated with the token
$sql = "SELECT id FROM email_confirmations WHERE token = ?";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $user_id = $result["id"];

        // Mark the user as verified
        $sql = "UPDATE users SET is_verified = 1 WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);

        // Delete the confirmation token
        $sql = "DELETE FROM email_confirmations WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);

        echo "Email confirmed successfully. You can now <a href='loginform.php'>log in</a>.";
    } else {
        echo "Invalid token.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
