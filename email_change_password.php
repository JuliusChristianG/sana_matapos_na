<?php
session_start();
include 'connection.php';

function validatePasswordStrength($password) {
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($regex, $password);
}

function changePassword($email, $new_password, $confirm_password, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $hashed_password = $user['password'];
        
        if (password_verify($new_password, $hashed_password)) {
            return json_encode(['success' => false, 'message' => 'New password must be different from the old password']);
        } else if ($new_password === $confirm_password && validatePasswordStrength($new_password)) {
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($stmt->execute([$hashed_new_password, $email])) {
                return json_encode(['success' => true, 'message' => 'Password changed successfully']);
                
                
            } else {
                return json_encode(['success' => false, 'message' => 'Error updating password']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'New passwords do not match or do not meet the strength requirements']);
        }
    } else {
        return json_encode(['success' => false, 'message' => 'Email not found in the database']);
    }
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Call the changePassword function and echo the result
    echo changePassword($email, $new_password, $confirm_password, $pdo);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
