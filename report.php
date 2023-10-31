<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="assets/images/logo-no-bg.png" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<?php
// Loop through your data and append each line to the respective session variable
if (isset($_SESSION['patient_count']) && isset($_SESSION['month_added'])) {
    $patientCountArray = $_SESSION['patient_count'];
    $monthNamesArray = $_SESSION['month_added'];

    $_SESSION['monthlyCount'] = "Monthly Patient Count: <br>";

    // Loop through the arrays and display the monthly counts
    for ($i = 0; $i < count($monthNamesArray); $i++) {
        $monthName = $monthNamesArray[$i];
        $patientCount = isset($patientCountArray[$monthName]) ? $patientCountArray[$monthName] : 0;

        $monthlyCountText = $monthName . ": " . $patientCount . "<br>";
        $_SESSION['monthlyCount'] .= $monthlyCountText; // Append the line to the existing 'monthlyCount' session variable
    }
}
?>




<?php
// Loop through your data and append each line to the respective session variable
if (isset($_SESSION['ageGroups']) && isset($_SESSION['ageGroupCounts'])) {
    $ageGroupsArray = $_SESSION['ageGroups'];
    $ageGroupCountsArray = $_SESSION['ageGroupCounts'];
    $_SESSION['ageGroupText'] = "Number of Patient in: <br>";

    // Loop through the arrays and display the age groups and their respective counts
    for ($i = 0; $i < count($ageGroupsArray); $i++) {
        $ageGroup = $ageGroupsArray[$i];
        $count = $ageGroupCountsArray[$i];

        $ageGroupText = $ageGroup . " years old: " . $count . "<br>";
        $_SESSION['ageGroupText'] .= $ageGroupText; // Append the line to the existing session variable
    }
}
?>

<?php
// Loop through your data and append each line to the respective session variable
if (isset($_SESSION['xray_types']) && isset($_SESSION['xray_type_counts'])) {
    $xrayTypesArray = $_SESSION['xray_types'];
    $xrayTypeCountsArray = $_SESSION['xray_type_counts'];
    $_SESSION['xrayCountType'] = "X Ray Type Count: <br>";

    // Make sure both arrays have the same length
    $count = min(count($xrayTypesArray), count($xrayTypeCountsArray));
    for ($i = 0; $i < $count; $i++) {
        $xrayType = $xrayTypesArray[$i];
        $xrayTypeCount = $xrayTypeCountsArray[$i];

        $xrayCountType =  $xrayType . ": " . $xrayTypeCount . "<br>";
        $_SESSION['xrayCountType'] .= $xrayCountType; // Append the line to the existing session variable
    }
}
?>

<?php
// Loop through your data and append each line to the respective session variable
if (isset($_SESSION['address']) && isset($_SESSION['address_count'])) {
    $addressArray = $_SESSION['address'];
    $addressCountArray = $_SESSION['address_count'];
    $_SESSION['addressText'] = "Patient Address Count: <br>";

    // Make sure both arrays have the same length
    $count = min(count($addressArray), count($addressCountArray));
    for ($i = 0; $i < $count; $i++) {
        $address = $addressArray[$i];
        $addressCount = $addressCountArray[$i];

        $addressText = $address . ": " . $addressCount . "<br>";
        $_SESSION['addressText'] .= $addressText; // Append the line to the existing session variable
    }
}
?>


<?php
require("connection.php");
// Get the current date
$currentDate = date('F j, Y');
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
    // Get the user ID of the administrator (assuming it's stored in the session)
    $adminUserID = $_SESSION['user_id'];
    $loggedInUsername = $_SESSION['username'];
    $loggedInRole = $_SESSION['role'];

    // Add a log entry for the report generation
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $logAction = "Generated a summary report"; // Customize this message as needed

    // Prepare and execute the SQL statement to insert the log entry
    $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
    $logStmt = $mysqli->prepare($logQuery);
    $logStmt->bind_param("ssssss", $adminUserID, $loggedInUsername, $loggedInRole, $date, $time, $logAction);

    if ($logStmt->execute()) {
        
    } else {
        echo "Error: " . $logStmt->error;
    }
} else {
    echo "Session variables not set for logging";
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

    <div style="text-align: center;">
        <img src="assets/images/mylabLogo.png" alt="Logo" style="width:20%; height:20%; display: block; margin: 0 auto;">
    </div>

    <h1>Summary Report</h1>

    <h3>Total Number of Cases of MyLab Clinic as of <?php echo $currentDate; ?>:</h3>
    <p><?php echo $_SESSION['totalCases']; ?> Cases</p>

    <h3>Total Patients of Mylab Clinic as of <?php echo $currentDate; ?>:</h3>
    <p><?php echo $_SESSION['totalUser']; ?> Patients</p>

    <h3>Total Cases today:</h3>
    <p><?php echo $_SESSION['totalCasesToday']; ?> Cases</p>

    <h3><br>This is the data about the Xray type count as of <?php echo $currentDate; ?>: </h3>
    <?php echo $_SESSION['xrayCountType']; ?>

    <h3>This is the monthly count of patients as of <?php echo $currentDate; ?>:</h3>
    <?php echo $_SESSION['monthlyCount'];?>

    <h3>This is the age group of the patients that have been examined as of <?php echo $currentDate; ?>:</h3>
    <?php echo $_SESSION['ageGroupText'];?>
    

    <h3>These are the address of the patients that have been examined as of <?php echo $currentDate; ?>:</h3>
    <?php echo $_SESSION['addressText'];?>


    

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>


</html>