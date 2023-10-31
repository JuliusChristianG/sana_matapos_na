<?php
require("connection.php");

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Define the SQL query to fetch medical history
    $query = "SELECT appointment_id, xray_type, date_consulted FROM patient_findings WHERE user_id = :user_id";

    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        echo '<table class="min-w-full bg-white border border-gray-300">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="py-2 px-3 border-b text-center">Appointment ID</th>';
        echo '<th class="py-2 px-4 border-b text-center">X Ray Type</th>';
        echo '<th class="py-2 px-4 border-b text-center">Date Consulted</th>';
        echo '<th class="py-2 px-4 border-b text-center">Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($results as $result) {
            echo '<tr class="cursor-pointer hover:bg-[#eeeeee]">';
            echo '<td class="px-2 text-center">ID - ' . $result['appointment_id'] . '</td>';
            echo '<td class="py-3 px-2 text-center">' . $result['xray_type'] . '</td>';
            echo '<td class="py-3 px-2 text-center">' . $result['date_consulted'] . '</td>';
            echo '<td>';
            echo '<form action="staffRadtechViewPatient.php" method="get">';
            echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
            echo '<input type="hidden" name="appointmentID" value="' . $result['appointment_id'] . '">';
            echo '<button name="viewButtonPatientRadtech" type="submit" class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">View</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
         // Close button
         echo '<div class="mt-4 flex justify-end">';
         echo '<button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2" onclick="closeMedicalHistoryModal()">Close</button>';
         echo '</div>';
    } else {
        echo 'No medical history records found.';
    }
} else {
    echo 'User ID is missing.';
}
?>
