<?php
session_start();

// Include your database connection code here (e.g., database.php)
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the user's email from the session
    if (isset($_SESSION['email'])) {
        $user_email = $_SESSION['email'];

        // Update the verification_code in your database to NULL
        $sql = "UPDATE users SET verification_code = NULL WHERE email = :email";
        
        $stmt_update = $pdo->prepare($sql);
        $stmt_update->bindParam(':email', $user_email);
        $stmt_update->execute();

        echo 'success'; // Return success to your JavaScript
    } else {
        echo 'user_not_found'; // Return an error message if user email is not in the session
    }
} catch (PDOException $e) {
    echo 'error: ' . $e->getMessage(); // Return an error message
}
?>
