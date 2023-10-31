<?php
include('connection.php');
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $stmt = $pdo->prepare("SELECT * FROM userlog WHERE uname LIKE :search");
        $stmt->execute(['search' => '%' . $search . '%']);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($logs as $log) {
            echo '<tr class="cursor-pointer hover:bg-[#eeeeee]">';
            echo '<td class="py-3 px-3">' . $log['logID'] . '</td>';
            echo '<td class="py-3 px-3">' . $log['uname'] . '</td>';
            echo '<td class="py-3 px-2">' . $log['date'] . '</td>';
            echo '<td class="py-3 px-2">' . $log['time'] . '</td>';
            echo '<td class="py-3 px-2">' . $log['role'] . '</td>';
            echo '<td class="py-3 px-2">' . $log['action'] . '</td>';
            echo '</tr>';
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>