<?php
include("connection.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['addTitle'];
    $services = [];

    for ($i = 1; $i <= 10; $i++) {
        $service = $_POST['addService'.$i];
        if (!empty($service)) {
            $services[] = $service;
        }
    }

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO services (service_title, ";
        for ($i = 1; $i <= count($services); $i++) {
            $sql .= "service_$i";
            if ($i !== count($services)) {
                $sql .= ", ";
            }
        }
        $sql .= ") VALUES (?, " . implode(", ", array_fill(0, count($services), "?")) . ")";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $params = array_merge([$title], $services);
        $stmt->execute($params);

        header("Location: AdminCMS.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
