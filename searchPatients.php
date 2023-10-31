<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}
require('connection.php');
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Patient' AND last_name LIKE :query");
        $stmt->bindValue(':query', "%$searchQuery%", PDO::PARAM_STR);
        $stmt->execute();
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    echo "Invalid search query.";
}
?>
