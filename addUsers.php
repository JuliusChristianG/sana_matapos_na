<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once 'vendor/PHPMailer/src/PHPMailer.php'; // Include PHPMailer library
require_once 'vendor/PHPMailer/src/SMTP.php'; // Include SMTP library
session_start();
date_default_timezone_set('Asia/Manila');
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16)); // Generate a random token
    
    
    // Check if the email already exists and is verified
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser && $existingUser['is_verified'] == 1) {
         //Email already exists and is verified
        header("Location: emailErrorUser.php");
        exit;}

    // Check if the password meets the requirements
    if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        // Password meets the requirements

        // Check if the email format is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Email format is valid

            // Hash the password using bcrypt (recommended for secure password hashing)
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare the SQL query
            $addUserQuery = "INSERT INTO users (username, first_name, last_name, password, role, dateAdded, email) VALUES (?, ?, ?, ?, ?, NOW(), ?)";

            // Prepare and bind the statement
            $stmt = $pdo->prepare($addUserQuery);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $fname);
            $stmt->bindParam(3, $lname);
            $stmt->bindParam(4, $hashed_password);
            $stmt->bindParam(5, $position);
            $stmt->bindParam(6, $email);

            // Execute the statement
            $stmt->execute();
            $getLastUserId = $pdo->lastInsertId();
            $adminUserID = $_SESSION['user_id'];
            $loggedInUsername = $_SESSION['username'];
            $loggedInRole = $_SESSION['role'];

            // Close the statement
            $stmt->closeCursor();

            // Add a log entry for the user creation
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $logAction = "Created a new user: $username";
            $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
            $logStmt = $pdo->prepare($logQuery);
            $logStmt->bindParam(1, $adminUserID);
            $logStmt->bindParam(2, $loggedInUsername);
            $logStmt->bindParam(3, $loggedInRole);
            $logStmt->bindParam(4, $date);
            $logStmt->bindParam(5, $time);
            $logStmt->bindParam(6, $logAction);
            $logStmt->execute();
            
            
             // Insert the token into the 'email_confirmations' table
            $user_id1 = $getLastUserId; // Get the last inserted user_id
            $emailConfirmationQuery = "INSERT INTO email_confirmations (id, token) VALUES (?, ?)";
            $emailConfirmationStmt = $pdo->prepare($emailConfirmationQuery);
            $emailConfirmationStmt->execute([$user_id1, $token]);

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
            $confirmation_link1 = "https://mylabsanjuan.com/confirm_email.php?token=$token"; // Adjust the confirmation link URL
            $mail->Body = "Click the following link to confirm your email: <a href='$confirmation_link1'>$confirmation_link1</a>";
            
           if ($mail->send()) {
            header("Location: adminAddUser.php?registration_success=true");
            exit();
            } else {
             echo "Email sending failed. Please try again later.";
            }


            exit;
        } else {
            // Invalid email format, display a pop-up message using JavaScript
            echo '<script>alert("Invalid email format."); window.location.href = "adminAddUser.php";</script>';
            exit;
        }
    } else {
        // Password does not meet the requirements, display a pop-up message using JavaScript
        echo '<script>alert("Password must be 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character."); window.location.href = "adminAddUser.php";</script>';
        exit;
    }
}
?>
