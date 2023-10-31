<?php
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $filterValue = $_GET['filter'];

    $stmt = $pdo->prepare("SELECT * FROM userlog WHERE action = :filter");
    $stmt->bindParam(':filter', $filterValue, PDO::PARAM_STR);
    $stmt->execute();
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
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
