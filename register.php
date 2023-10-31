<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'vendor/PHPMailer/src/PHPMailer.php'; // Include PHPMailer library
require_once 'vendor/PHPMailer/src/SMTP.php'; // Include SMTP library
require("connection.php");

if (isset($_POST['username'], $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['password'], $_POST['confirm_password'], $_POST['mname'], $_POST['address'], $_POST['age'], $_POST['birthday'], $_POST['gender'], $_POST['mobileNum'])) {
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password1 = $_POST['confirm_password'];
    $mname = $_POST['mname'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $mobileNumber = $_POST['mobileNum'];
    
    // Check if the email already exists and is verified
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser && $existingUser['is_verified'] == 1) {
        //Email already exists and is verified
        header("Location: emailError.php");
        exit;
    }

    $token = bin2hex(random_bytes(16)); // Generate a random token

    // If the passwords match, proceed to register the user.
    if ($password === $password1) {
        // Use password_hash() to securely hash the password.
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Assuming you have already connected to the database, execute the appropriate query.
        // In this example, we use PDO for database operations.

        $stmt = $pdo->prepare("INSERT INTO patients (username, first_name, last_name, password, email, mname, address, age, birthday, gender, mobileNumber, role, dateAdded) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Patient', NOW())");
        $stmt->execute([$username, $fname, $lname, $hashed_password, $email, $mname, $address, $age, $birthday, $gender, $mobileNumber]);

        // Insert the token into the 'email_confirmations' table
        $user_id = $pdo->lastInsertId(); // Get the last inserted user_id
        $stmt = $pdo->prepare("INSERT INTO email_confirmations (id, token) VALUES (?, ?)");
        $stmt->execute([$user_id, $token]);

        // Send email with a confirmation link containing the token using PHPMailer
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'mylabsanjuan@gmail.com'; // Your email
        $mail->Password = 'anquribdcxxqyeqh'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('mylabsanjuan@gmail.com', 'MyLab San Juan'); // Set the sender's name and email
        $mail->addAddress($email); // Set the recipient's email

        $mail->isHTML(true);
        $mail->Subject = 'Email Confirmation';
        $confirmation_link = "https://mylabsanjuan.com/confirm_email.php?token=$token"; // Adjust the confirmation link URL
        $mail->Body = "Click the following link to confirm your email: <a href='$confirmation_link'>$confirmation_link</a";

        if ($mail->send()) {
            echo "Your Registration is successful! Check your email to verify your account! before logging in!";
            header("Location: success.php");
            exit();
        } else {
            echo "Email sending failed. Please try again later.";
        }


        echo "Registered successfully!";
        header("Location: loginform.php");
        exit;
    }
}
?>