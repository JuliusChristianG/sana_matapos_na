<?php
session_start();
require("connection.php");
date_default_timezone_set("Asia/Manila");

$role = $_SESSION['role']; 
$userID = $_SESSION['user_id']; 
$username = $_SESSION['username']; // Retrieve the username from the session

// Check if the role is not "Patient" before inserting into the log
if ($role !== 'Patient') {
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES(?,?,?,?,?,?)";
    $log = $mysqli->prepare($logQuery);

    $date = date("Y-m-d");
    $time = date("H:i:s");
    $logout = "Logged out";
    

    $log->bind_param("ssssss", $userID, $username, $role, $date, $time, $logout);
    $log->execute();
}

$_SESSION = array();
unset($_SESSION['authenticated']);
unset($_SESSION['role']);
unset($_SESSION['user_id']);
unset($_SESSION['case_no']);
unset($_SESSION['case_number']);
session_destroy();

header("Location: loginform.php");
exit();
?>
