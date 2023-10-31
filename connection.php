<?php

$mysqli_hostname = 'localhost';
$mysqli_username = 'root';
$mysqli_password = '';
$mysqli_database = 'mylabclinic';

$mysqli = new mysqli($mysqli_hostname, $mysqli_username, $mysqli_password, $mysqli_database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>

<?php
$dsn = 'mysql:host=localhost;dbname=mylabClinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
