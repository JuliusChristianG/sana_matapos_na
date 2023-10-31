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
    // Check if the "Resend Code" button was clicked
    if (isset($_POST["resend_code"])) {
        // Retrieve the email from the session variable
        $email = $_SESSION["email"];

        // Generate a new random verification code
        $verificationCode = generateVerificationCode();

        // Email content
        $subject = 'Verification Code for Reset Password (Resent).';
        $message = "Your new verification code is: $verificationCode";

        // Store the new verification code in the database
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $mail->Subject = 'Verification Code for MyLab Clinic (Resent)';
        $mail->Body = "Your new verification code is: $verificationCode";

        // Send the email
        if ($mail->send()) {
            echo 'success'; // Signal success to the JavaScript
            exit();
        } else {
            echo "Email could not be sent. Error: " . $mail->ErrorInfo;
            error_log("Email sending error: " . $mail->ErrorInfo);
        }
    }
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
