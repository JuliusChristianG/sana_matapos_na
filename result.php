<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  
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
            reportWindow.onload = function () {
                reportWindow.print();
            };
        }

        window.onload = function () {
            // Attach the printReport function to the button click event
            var printButton = document.getElementById('printButton');
            printButton.addEventListener('click', printReport);
        };
    </script>

    <body>
    <div class="row">
    <div class="mx-auto">
        <img src="assets/images/header.png">
    </div>
    </div>
    <hr style="width: 100%; margin: 20px auto;">



    <p style="margin: 0; padding: 0;">Name:
        <b>
            <?php echo $firstName . " " . $lastName; ?>
        </b>
    </p>
    <p style="margin: 0; padding: 0;">Age:
        <?php echo $age ?>
    </p>
    <p style="margin: 0; padding: 0;">Gender:
        <?php echo $gender ?>
    </p>
    <p style="margin: 0; padding: 0;">Exam:
        <b>
            <?php echo $xray_type ?>
        </b>
    </p>
    <p style="margin: 0; padding: 0;">Date:
        <?php echo date('F j, Y', strtotime($date_consulted)); ?>
    </p>


    <hr style="width: 100%; margin: 20px auto;">

    <h2 style="text-align: center; font-size: 18px;"><b>RADIOLOGIC REPORT</h2></b>

    <hr style="width: 100%; margin: 20px auto;">
    <br>
    <h3 style="text-align: left;  font-size: 18px;">FINDINGS: </h3>
    <div style="text-align: left; margin-left: 20px; font-size: 18px;">
        <?php
        $lines = explode("\n", $findings);
        foreach ($lines as $line) {
            echo '<p style="text-indent: 20px; font-size: 18px;">' . $line . '</p>';
        }
        ?>
    </div>
    <br>
    <br>
    <h3 style="text-align: left; font-size: 18px;">IMPRESSION: </h3>
    <p style="margin: 0; padding: 0; text-indent: 40px; font-size: 18px;">
        <?php echo $impression; ?>
    </p>





    <div style="position: absolute; bottom: 0; width: 100%; text-align: center;">
        <p style="font-style: italic; font-size: 12px;">
            Note: This report is based entirely on the x-ray examination and should be correlated with the clinical, <br>
            laboratory findings, and/or other imaging modalities.
        </p>
    </div>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


</html>