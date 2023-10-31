<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Doctor') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <link href="assets/images/logo-no-bg.png" rel="icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="print-charts.css" media="print">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"
        integrity="sha512-q+4liFwdPC/bNdhUpZx6aXDx/h77yEQtn4I1slHydcbZK34nLaR3cAeYSJshoxIOq3mjEf7xJE8YWIUHMn+oCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
    <title>Doctor's Dashboard</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/admindashboard.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .rounded-card {
            border-radius: 15px;
            background-color: #ffffff;
            padding: 20px;
            margin: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>


<!-- simula ng sidebar-->

<body class="">
    <div class="sidebar bg-[#e4e9f7]">
        <div class="logo_details">
            <div class="logo_content">

            </div>
            <div class="logo_name">
                <img src="assets/images/mylabLogo.png" height="60px" width="60px" class="ml-3" alt="MyLab Logo">
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li class="">
                <a href="doctorprofilepage.php">
                    <i class="bx bx-user-circle"></i>

                    <span class="link_name">
                        <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                    </span>
                </a>
                <span class="tooltip">
                    <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                </span>
            </li>
            <li class="active">
                <a href="DoctorDashboard.php">
                    <i class="bx bx-grid-alt"></i>
                    <span class="link_name">Doctor Dashboard</span>
                </a>
                <span class="tooltip">Doctor Dashboard</span>
            </li>
            <li class="">
                <a href="DoctorPatientRecordsTable.php">
                    <i class="bx bx-book-open"></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>

            <li class="">
                <a href="DoctorAddPatient.php">
                    <i class="bx bx-user-plus"></i>
                    <span class="link_name">Patient Accounts</span>
                </a>
                <span class="tooltip">Patient Accounts</span>
            </li>

      
       

            <li class="profile">
                <div class="profile_details">
                    <div class="profile_content">
                        <div class="designation"></div>

                    </div>
                </div>
            <li class="profile">
                <a>
                    <i class="bx bx-log-out" id="log_out"></i>
                    <span class="link_name">
                        <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                        <br>
                        <span class="doctors_text">Doctor Account</span>
                    </span>
                </a>
            </li>

        </ul>
    </div>
    <section class="home-section">
        <section id="content">



            <!-- MAIN -->
            <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Dashboard</h1>
                       

                        <button id="printButton" class="btn btn-primary" onclick="printReport()">Print Report</button>
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
                        </script>












                    </div>

                </div>

                <ul class="box-info">
                    <li>
                        <div class="inner">
                            <i class="bx bxs-briefcase"></i>

                            <?php
                            require_once 'connection.php';
                            // SQL query to get the count of products based on the number of data in the id column
                            $sql = "SELECT COUNT(case_no) AS totalCases FROM patient_records ";
                            $result = $mysqli->query($sql);
                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $totalCases = $row['totalCases'];
                                $_SESSION['totalCases'] = $totalCases;
                            }
                            ?>
                        </div>
                        <span class="text">
                            <h3> Total Cases</h3>
                            <?php echo "<p>$totalCases Cases</p>"; ?>
                        </span>
                    </li>

                    <li>
                        <i class='bx bxs-group'></i>
                        <?php
                        require_once 'connection.php';

                        // Role you want to count (e.g., "patient")
                        $roleToCount = "patient";

                        // SQL query to count users with the specified role
                        $sql = "SELECT COUNT(user_id) AS totalUser FROM users WHERE role = '$roleToCount'";
                        $result = $mysqli->query($sql);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $totalUser = $row['totalUser'];
                            $_SESSION['totalUser'] = $totalUser;
                        } else {
                            // Handle query error if necessary
                            echo "Query failed: " . $mysqli->error;
                        }

                        // Now, $totalUser contains the count of users with the specified role
                        ?>

                        <span class="text">
                            <h3>Total Patients</h3>
                            <?php echo "<p>$totalUser Patients </p>"; ?>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-calendar-check'></i>
                        <?php
                        require_once 'connection.php';
                        date_default_timezone_set('Asia/Manila');

                        // Get the current date in the format 'YYYY-MM-DD'
                        $currentDate = date("Y-m-d");

                        // SQL query to count cases that are dated today
                        
                        $sql = "SELECT COUNT(case_no) AS totalCasesToday FROM patient_records WHERE DATE(dateAdded) = '$currentDate'";
                        $result = $mysqli->query($sql);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $totalCasesToday = $row['totalCasesToday'];
                            $_SESSION['totalCasesToday'] = $totalCasesToday;
                        }
                        ?>
                        <span class="text">
                            <h3>Cases Today</h3>
                            <?php echo "<p>There are $totalCasesToday Cases Today  </p>"; ?>
                        </span>
                    </li>
                </ul>

<!-- Modal for Logout Confirmation -->
<div id="logoutModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <!-- Add modal content here -->
        <div class="modal-content py-4 text-left px-6">
            <!-- Title -->
            <div class="text-3xl font-bold mb-2">Logging Out</div>
            <!-- Message -->
            <p class="text-gray-700 mb-6">Are you sure you want to logout?</p>
            <!-- Buttons -->
            <div class="flex justify-end items-center space-x-4">
                <button id="cancelLogout" class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Cancel</button>
                <!-- Add an ID to the Yes button for JavaScript handling -->
                <button id="confirmLogout" class="px-4 py-2 bg-red-500 text-white rounded">Yes</button>
            </div>
        </div>
    </div>
</div>

                <!-- Charts -->
                <?php
                function randomDarkRGBA()
                {
                    $red = mt_rand(0, 128); // Generate a random red value between 0 and 128 (dark)
                    $green = mt_rand(0, 128); // Generate a random green value between 0 and 128 (dark)
                    $blue = mt_rand(0, 128); // Generate a random blue value between 0 and 128 (dark)
                    $alpha = (mt_rand(50, 100) / 100); // Random alpha value between 0.5 and 1 for some transparency
                
                    return "rgba($red, $green, $blue, $alpha)";
                }

                $con = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');
                $query = $con->query("SELECT xray_type as xray_type, COUNT(*) as xray_type_count FROM patient_findings GROUP BY xray_type");

                $xray_types = [];
                $xray_type_counts = [];

                foreach ($query as $data) {
                    $xray_types[] = $data['xray_type'];
                    $xray_type_counts[] = $data['xray_type_count'];
                }

                $_SESSION['xray_types'] = $xray_types;
                $_SESSION['xray_type_counts'] = $xray_type_counts;
                ?>

                <!-- Wrap the charts in a flex container -->
                <div style="display: flex;">

                    <!-- First Chart -->
                    <div
                        style="width: 50%; display: flex; flex-direction: column; align-items: center; text-align: center;">
                        <div style="font-weight: bold; font-size: 18px; margin-bottom: 10px;">
                            Patients' Xray Type Count
                        </div>
                        <canvas id="myChart"></canvas>
                    </div>


                    <script>
                        // === include 'setup' then 'config' above ===
                        const labels = <?php echo json_encode($xray_types) ?>;
                        const data = {
                            labels: labels,
                            datasets: [{
                                label: 'X-Ray Count and their Type',
                                data: <?php echo json_encode($xray_type_counts) ?>,
                                backgroundColor: [
                                    <?php
                                    // Generate random RGBA colors for the bars
                                    for ($i = 0; $i < count($xray_types); $i++) {
                                        echo "'" . randomDarkRGBA() . "',";
                                    }
                                    ?>
                                ]
                            }]
                        };

                        const bgColor = {
                            id: 'bgColor',
                            beforeDraw: (chart, options) => {
                                const {
                                    ctx,
                                    width,
                                    height
                                } = chart;
                                ctx.fillStyle = 'white';
                                ctx.fillRect(0, 0, width, height)
                                ctx.restore();
                            }
                        }

                        const config = {
                            type: 'bar',
                            data: data,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false, // Hide legend symbols
                                        onClick: function (e) {
                                            // Disable legend click behavior
                                        }
                                    }

                                }
                            },
                            plugins: [bgColor]
                        };


                        var myChart = new Chart(
                            document.getElementById('myChart'),
                            config
                        );

                    </script>

                    <!-- Second Chart -->
                    <?php
                    $con = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');

                    // Define your age groups
                    $ageGroups = array(
                        '0-10',
                        '11-20',
                        '21-30',
                        '31-40',
                        '41-50',
                        '51-60',
                        '61-70',
                        '71+'
                    );

                    // Initialize an array to store the counts for each age group
                    $ageGroupCounts = array_fill(0, count($ageGroups), 0);

                    // Query to get the count of patients in each age group
                    $query = $con->query("SELECT age FROM patient_records");
                    foreach ($query as $data) {
                        $age = $data['age'];
                        // Determine the age group for each age and increment the count
                        if ($age >= 0 && $age <= 10) {
                            $ageGroupCounts[0]++;
                        } elseif ($age >= 11 && $age <= 20) {
                            $ageGroupCounts[1]++;
                        } elseif ($age >= 21 && $age <= 30) {
                            $ageGroupCounts[2]++;
                        } elseif ($age >= 31 && $age <= 40) {
                            $ageGroupCounts[3]++;
                        } elseif ($age >= 41 && $age <= 50) {
                            $ageGroupCounts[4]++;
                        } elseif ($age >= 51 && $age <= 60) {
                            $ageGroupCounts[5]++;
                        } elseif ($age >= 61 && $age <= 70) {
                            $ageGroupCounts[6]++;
                        } else {
                            $ageGroupCounts[7]++;
                        }
                    }

                    $_SESSION['ageGroups'] = $ageGroups;
                    $_SESSION['ageGroupCounts'] = $ageGroupCounts;

                    // Convert the age group counts to JSON for use in JavaScript
                    $ageGroupCountsJSON = json_encode($ageGroupCounts);
                    ?>



                    <div
                        style="width: 30%; display: flex; flex-direction: column; align-items: center; text-align: center; margin-left: 13%;">
                        <div style="font-weight: bold; font-size: 18px; margin-bottom: 10px;">
                            Patients' Age Groups
                        </div>
                        <canvas id="myChart1" width="100" height="100"></canvas>
                        <!-- Adjust the width and height as needed -->
                    </div>


                    <script>
                        // === include 'setup' then 'config' above ===
                        const ageGroups = <?php echo json_encode($ageGroups) ?>;
                        const data2 = {
                            labels: ageGroups,
                            datasets: [{
                                label: 'Age Groups',
                                data: <?php echo $ageGroupCountsJSON; ?>,
                                backgroundColor: [
                                    <?php
                                    // Generate random RGBA colors for the pie chart segments
                                    for ($i = 0; $i < count($ageGroups); $i++) {
                                        echo "'" . randomDarkRGBA() . "',";
                                    }
                                    ?>
                                ],
                            }]
                        };

                        const config2 = {
                            type: 'pie', // Change chart type to 'pie'
                            data: data2,
                            options: {
                                plugins: {
                                    legend: {
                                        position: 'right', // Set the legend position to 'right'
                                    }
                                }
                            },
                        };

                        var myChart2 = new Chart(
                            document.getElementById('myChart1'),
                            config2
                        );
                    </script>

                </div>

                <?php
                $con = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');
                $query = $con->query("SELECT address as address, COUNT(*) as address_count FROM patient_records GROUP BY address");

                $address = [];
                $address_count = [];

                foreach ($query as $data) {
                    $address[] = $data['address'];
                    $address_count[] = $data['address_count'];
                }
                $_SESSION['address'] = $address;
                $_SESSION['address_count'] = $address_count;
                ?>

                <!-- Wrap the charts in a flex container -->


                <!-- Third and Fourth Charts Container -->
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">

                    <!-- Third Chart -->
                    <div style="width: 50%;"> <!-- Adjust the width as needed -->
                        <div
                            style="display: flex; justify-content: flex-start; align-items: center; text-align: center; height: 100px;">
                            <div style="font-weight: bold; font-size: 18px; margin-left: 320px;">
                                Patients' Address
                            </div>
                        </div>
                        <div>
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>


                    <script>
                        // === include 'setup' then 'config' above ===
                        const labels3 = <?php echo json_encode($address) ?>;
                        const data3 = {
                            labels: labels3,
                            datasets: [{
                                label: "Patients' Address",
                                data: <?php echo json_encode($address_count) ?>,
                                backgroundColor: [
                                    <?php
                                    // Generate random RGBA colors for the bars
                                    for ($i = 0; $i < count($address); $i++) {
                                        echo "'" . randomDarkRGBA() . "',";
                                    }
                                    ?>
                                ]
                            }]
                        };

                        const config3 = {
                            type: 'bar',
                            data: data3,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false, // Hide legend symbols
                                        onClick: function (e) {
                                            // Disable legend click behavior
                                        }
                                    }
                                }
                            },
                        };





                        var myChart3 = new Chart(
                            document.getElementById('myChart2'),
                            config3
                        );
                    </script>

                    <?php
                    // Establish a database connection
                    $con = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');

                    // Check the connection
                    if ($con->connect_error) {
                        die("Connection failed: " . $con->connect_error);
                    }

                    // Get the current year
                    $currentYear = date("Y");

                    // Execute the SQL query
                    $query = "SELECT
    DATE_FORMAT(patient_records.dateAdded, '%M') AS month_added,
    IFNULL(COUNT(userID), 0) AS patient_count
FROM
    patient_records
WHERE YEAR(patient_records.dateAdded) = $currentYear
GROUP BY
    DATE_FORMAT(patient_records.dateAdded, '%M')
ORDER BY
    STR_TO_DATE(month_added, '%M');";

                    $result = $con->query($query);

                    // Initialize an empty array to store patient counts by month
                    $patient_count = [];

                    // Initialize an array with all month names
                    $month_names = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December'
                    ];



                    // Populate the $patient_count array with actual patient counts
                    while ($row = $result->fetch_assoc()) {
                        $monthName = $row['month_added'];
                        $patientCount = $row['patient_count'];
                        $patient_count[$monthName] = $patientCount; // Update the array with data
                    }

                    // Store the patient_count array in a session variable
                    $_SESSION['patient_count'] = $patient_count;
                    $_SESSION['month_added'] = $month_names;

                    // Don't forget to close the connection when you're done with it
                    $con->close();
                    ?>



                    <!-- Fourth Chart -->
                    <div style="width: 50%;"> <!-- Adjust the width as needed -->
                        <div
                            style="display: flex; justify-content: flex-start; align-items: center; text-align: center; height: 100px;">
                            <div style="font-weight: bold; font-size: 18px; margin-left: 320px;">
                                Patients Added by Month
                            </div>
                        </div>
                        <div>
                            <canvas id="myChart3"></canvas>
                        </div>
                    </div>


                    <script>
                        // === include 'setup' then 'config' above ===
                        const data4 = {
                            labels: <?php echo json_encode($month_names) ?>,
                            datasets: [{
                                label: 'Patients Added in this Month',
                                data: <?php echo json_encode($patient_count) ?>,
                                backgroundColor: [
                                    <?php
                                    // Generate random RGBA colors for the bars
                                    for ($i = 0; $i < count($patient_count); $i++) {
                                        echo "'" . randomDarkRGBA() . "',";
                                    }
                                    ?>
                                ]
                            }]
                        };

                        const config4 = {
                            type: 'bar',
                            data: data4,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false,
                                        onClick: function (e) {
                                            // Disable legend click behavior
                                        }
                                    }
                                }
                            },
                        };

                        var myChart4 = new Chart(
                            document.getElementById('myChart3'),
                            config4
                        );
                    </script>

            </main>
            <!-- MAIN -->
        </section>

<script>
    
document.getElementById('log_out').addEventListener('click', function() {
        document.getElementById('logoutModal').classList.toggle('hidden');
    });

    document.getElementById('cancelLogout').addEventListener('click', function() {
        document.getElementById('logoutModal').classList.toggle('hidden');
    });

    // Add an event listener for the "Yes" button
    document.getElementById('confirmLogout').addEventListener('click', function() {
        // Redirect to the logout page
        window.location.href = 'logout.php';
    });
</script>

        <script src="assets/javascript/app.js"></script>
</body>

</html>