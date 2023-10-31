<?php

// Include your database connection code
include 'connection.php';


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve the values from the form
        $editServiceId = $_POST["editServiceId"];
        $edittitle = $_POST["edittitle"];
        $editservice1 = $_POST["editservice1"];
        $editservice2 = $_POST["editservice2"];
        $editservice3 = $_POST["editservice3"];
        $editservice4 = $_POST["editservice4"];
        $editservice5 = $_POST["editservice5"];
        $editservice6 = $_POST["editservice6"];
        $editservice7 = $_POST["editservice7"];
        $editservice8 = $_POST["editservice8"];
        $editservice9 = $_POST["editservice9"];
        $editservice10 = $_POST["editservice10"];

        // Use prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("UPDATE services SET 
            service_title=?, 
            service_1=?, 
            service_2=?, 
            service_3=?, 
            service_4=?, 
            service_5=?, 
            service_6=?, 
            service_7=?, 
            service_8=?, 
            service_9=?, 
            service_10=? 
            WHERE id=?");

        $stmt->execute([$edittitle, $editservice1, $editservice2, $editservice3, $editservice4, $editservice5, $editservice6, $editservice7, $editservice8, $editservice9, $editservice10, $editServiceId]);

        header("Location: AdminCMS.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
}
?>
