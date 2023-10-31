<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

// Include your database configuration here
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

// Create a PHPMailer instance
$mail = new PHPMailer();

// Set SMTP debugging
$mail->SMTPDebug = SMTP::DEBUG_SERVER;

// SMTP configuration
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
$mail->Port = 587; // Replace with your SMTP port
$mail->SMTPAuth = true;
$mail->Username = 'mylabsanjuan@gmail.com'; // Replace with your SMTP username
$mail->Password = 'anquribdcxxqyeqh'; // Replace with your SMTP password
$mail->SMTPSecure = 'tls'; // Use 'tls' or 'ssl' based on your server

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email from the form
    $email = $_POST["email"];

    // Store the email in a session variable
    $_SESSION["email"] = $email;
}

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the email from the form submission
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    // If the email exists in the database
    if ($stmt->rowCount() > 0) {
        // Generate a random verification code
        $verificationCode = generateVerificationCode();

        // Email content
        $subject = 'Verification Code for Reset Password.';
        $message = "Your verification code is: $verificationCode";

        // Store the verification code in the database
        try {
            $stmt = $pdo->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
            $stmt->execute([$verificationCode, $email]);
        } catch (PDOException $e) {
            // Handle database update error
            echo "Database error: " . $e->getMessage();
            exit();
        }

        // Set sender and recipient
        $mail->setFrom('mylabsanjuan@gmail.com');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(false);
        $mail->setFrom('mylabsanjuan@gmail.com', 'MyLab San Juan');
        $mail->Subject = 'Verification Code for MyLab Clinic';
        $mail->Body = "Your verification code is: $verificationCode";

        // Send the email
        if ($mail->send()) {
            // Email sent successfully, redirect to the verification page
            header("Location: codeverification.php?email=$email");
            exit();
        } else {
            echo "Email could not be sent. Error: " . $mail->ErrorInfo;
        }
    } else {
        // Return a response to indicate invalid email
        echo 'invalid_email';
        exit(); // Terminate the script
    }
} catch (PDOException $e) {
    // Handle database connection error
    echo "Database error: " . $e->getMessage();
}

// Function to generate a random verification code
function generateVerificationCode() {
    $code = '';
    for ($i = 0; $i < 6; $i++) { // Change 6 to the desired length
        $code .= rand(1, 9); // Generates a random digit between 0 and 9
    }

    return $code;
}
?>
