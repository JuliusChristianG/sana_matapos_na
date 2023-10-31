<?php
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the entered verification code
    $enteredCode = $_POST['code']; // Change 'verification_code' to 'code'

    // Check if the entered verification code is correct
    try {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE verification_code = ? LIMIT 1");
        $stmt->execute([$enteredCode]);

        if ($stmt->rowCount() > 0) {
            // Verification code matches, fetch the email
            $result = $stmt->fetch();
            $email = $result['email'];

            // Proceed to forgotpassword.php or perform other actions
            header("Location: forgotpassword.php?email=$email");
            exit();
        } else {
            // Verification code does not match, display an error message
          header("Location: error_message.php");
             exit();
        }
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Database error: " . $e->getMessage();
    }
} catch (PDOException $e) {
    // Handle database connection error
    echo "Database error: " . $e->getMessage();
}
?>