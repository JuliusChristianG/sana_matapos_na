
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<?php
session_start(); // Start the session on the other page

if (isset($_SESSION['withfind'])) {
    $withfind = $_SESSION['withfind'];

    // You can access individual variables from the $withfind array as needed
    $xray_type = $withfind['xray_type'];
    $date_consulted = $withfind['date_consulted'];
    $findings = $withfind['findings'];
    $issued_by = $withfind['issued_by'];
    $xray_file = $withfind['xray_file'];
    $impression = $withfind['impression'];

    // Retrieve patient data from the session
    $patientData = $_SESSION['patient'];

    if ($patientData) {
        // Access patient data variables as needed
        $firstName = $patientData['first_name'];
        $lastName = $patientData['last_name'];
        $age = $patientData['age'];
        $gender = $patientData['gender'];
        // Add more as needed

        
        
    } else {
        // Handle the case when patient data is not found in the session
        echo "Patient data not found.";
    }

} else {
    // Handle the case when the session variable is not set
    echo "withfind session data not found.";
}


?>



<script>
        // Function to handle printing the report
        function printReport() {
            // Open the report page in a new window or tab
            var reportWindow = window.open('report.php', '_blank');

            // Wait for the report page to load, then trigger print
            reportWindow.onload = function() {
                reportWindow.print();
            };
        }

        window.onload = function() {
            // Attach the printReport function to the button click event
            var printButton = document.getElementById('printButton');
            printButton.addEventListener('click', printReport);
        };
    </script>
<img class="mt-5" src="<?= $withfind['xray_file'] ?>" height="1000px" width="800px" onclick="window.open(this.src)">
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>


</html>