<?php
session_start();
date_default_timezone_set('Asia/Manila');
// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Patient"
if ($_SESSION['role'] !== 'Patient') {
    // If the user is not a patient, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}
require("connection.php");
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the appointment_id is provided in the URL.
    if (isset($_GET['appointmentID'])) {
        $appointmentID = $_GET['appointmentID'];

        // Add code to get the user_id from the GET parameters
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

        if (!$user_id) {
            // Handle the case when user_id is not provided in the URL.
            echo "User ID not provided.";
            die();
        }

        // Fetch the patient's findings from the patient_findings table based on the appointment_id.
        $stmtFindings = $pdo->prepare("SELECT * FROM xrayrequest WHERE appointment_id = :appointmentID");
        $stmtFindings->bindParam(':appointmentID', $appointmentID, PDO::PARAM_STR);
        $stmtFindings->execute();
        $findings = $stmtFindings->fetch(PDO::FETCH_ASSOC);

        $stmtWithFindings = $pdo->prepare("SELECT * FROM patient_findings WHERE appointment_id = :appointmentID");
        $stmtWithFindings->bindParam(':appointmentID', $appointmentID, PDO::PARAM_STR);
        $stmtWithFindings->execute();
        $withfind = $stmtWithFindings->fetch(PDO::FETCH_ASSOC);

        /*if (!$findings) {
            // Handle the case when findings for the given appointment_id do not exist.
            echo "Findings not found.";
            die();
        }*/



        // Fetch the specific patient's data from the patients table based on the userID.
        $stmtPatient = $pdo->prepare("SELECT * FROM patients WHERE user_id = :userID");
        $stmtPatient->bindParam(':userID', $findings['user_id'], PDO::PARAM_STR);
        $stmtPatient->execute();
        $patient = $stmtPatient->fetch(PDO::FETCH_ASSOC);

        /* if (!$patient) {
            // Handle the case when the patient with the given userID does not exist.
            echo "Patient not found.";
            die();
        } */

        $_SESSION['withfind'] = $withfind;
        $_SESSION['patient'] = $patient;
    } else {
        // Handle the case when the appointment_id is not provided in the URL.
        echo "Appointment ID not provided.";
        die();
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <title> My X-Ray Record </title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/sidebar.css">
  <style>
    .highlighted-text1 {
      background-color: #0126CC;

      padding: 10px;

      margin-top: 20px;

    }
  </style>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

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
        <a href="patient_profilepage.php?userID=<?php echo $patient['user_id']; ?>">
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
        <a href="patientDashboard.php<?php echo $_SESSION['userID']; ?>">
        <i class='bx bx-book-open'></i>
          <span class="link_name">My Record</span>
        </a>
        <span class="tooltip">My Record</span>
      </li>

      <li class="">
        <a href="patientrequestform.php?userID=<?php echo $patient['user_id']; ?>">
          <i class='bx bx-clipboard'></i>
          <span class="link_name">X-Ray Request</span>
        </a>
        <span class="tooltip">X-Ray Request</span>
      </li>

      <li class="profile">
        <div class="profile_details">
          <div class="profile_content">
            <div class="designation"></div>

          </div>
        </div>
      <li class="profile">
        <a href="logout.php">
          <i class="bx bx-log-out" id="log_out"></i>
          <span class="link_name">
            <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
            <br>
            <span class="admin_text">Patient Account</span>
          </span>
        </a>
      </li>

    </ul>
  </div>
    <section class="home-section">
        <!-- Your original code starts here -->
        <div class="w-full h-screen px-10 py-5 bg-white flex">
            <div class="w-full">
                <div class="w-full bg-white h-[100px]">
                    <div class="container">
                        <div class="card my-4">
                            <div class="card-header bg-white">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <center>
                                            <img src="assets/images/mylabLogo.png" height="90px" width="90px"
                                                class="ml-3">
                                        </center>
                                    </div>
                                    <div class="col-span-6"></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div>
                                    <p class="justify-center flex pt-5 text-3xl font-bold">PATIENT RECORD</p>

                                    <div class="pb-1 flex justify-between">
                                        <!-- Use flex to align buttons to the right and add space between them -->

                                        <a href="patientDashboard.php"
                                            class="btn btn-secondary bg-[#0126CC] btn-lg rounded-pill px-4 py-2">Back</a>
                                        <div class="pb-1 flex justify-between">
                                            <button id="printButtonReport" class="btn btn-secondary bg-[#0126CC] mx-2"
                                                onclick="printReport()">Print Report</button>
                                            <button id="printButtonImage" class="btn btn-secondary bg-[#0126CC] mx-2"
                                                onclick="printImage()">Print Image</button>
                                        </div>

                                        <script>
                                            // Function to handle printing the report
                                            function printReport() {
                                                // Open the report page in a new window or tab
                                                var reportWindow = window.open('result.php', '_blank');

                                                // Wait for the report page to load, then trigger print
                                                reportWindow.onload = function () {
                                                    reportWindow.print();
                                                };
                                            }
                                        </script>
                                        <script>
                                            // Function to handle printing the report
                                            function printImage() {
                                                // Open the report page in a new window or tab
                                                var reportWindow = window.open('image.php', '_blank');

                                                // Wait for the report page to load, then trigger print
                                                reportWindow.onload = function () {
                                                    reportWindow.print();
                                                };
                                            }
                                        </script>
                                

                                </div>

                                <p class="justify-center flex text-md pt-3 font-bold"></p>
                            </div>
                            <div class="grid grid-cols-6">
                                <div
                                    class="col-span-4 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-4 pl-[5px]">
                                    <div class="py-5 font-bold text-[#0126CC]">PATIENT NAME:</div>

                                    <div class="col-span-2 uppercase py-5 text-[#0126CC]">
                                        <?php echo $patient['first_name'] . ' ' . $patient['mname'] . ' ' . $patient['last_name'] ?>
                                    </div>
                                </div>
                                <div
                                    class="col-span-2 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-2 pl-[5px]">
                                    <div class="py-5 font-bold text-[#0126CC]">CASE NO:</div>
                                    <div class="uppercase py-5 justify-center items-center flex text-[#0126CC]">
                                        <?php echo "MLC - " . $patient['user_id'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 my-4">
                                <div class="rounded-lg bg-gray-200 p-4">
                                    <div class="py-1 font-bold text-[#0126CC] text-xl">DETAILS</div>
                                    <hr class="my-2">
                                    <p class="text-sm font-bold text-[#5b5b5b] pt-[5px] text-xl">Address</p>
                                    <p class="text-md font-bold pb-1">
                                        <?php echo $patient['address']; ?>
                                    </p>
                                    <p class="text-sm font-bold text-[#5b5b5b] text-xl">Age</p>
                                    <p class="text-md font-bold pb-1">
                                        <?php echo $patient['age']; ?>
                                    </p>
                                    <p class="text-sm font-bold text-[#5b5b5b] text-xl">Birthday</p>
                                    <p class="text-md font-bold pb-1">
                                        <?php echo $patient['birthday']; ?>
                                    </p>
                                    <p class="text-sm font-bold text-[#5b5b5b] text-xl">Gender</p>
                                    <p class="text-md font-bold pb-1">
                                        <?php echo $patient['gender']; ?>
                                    </p>
                                    <p class="text-sm font-bold text-[#5b5b5b] text-xl">Mobile Number</p>
                                    <p class="text-md font-bold pb-1">
                                        <?php echo $patient['mobileNumber']; ?>
                                    </p>
                                    <hr class="my-2">
                                    <div class="py-1 font-bold text-[#0126CC] text-xl">FINDINGS AND IMPRESSION</div>
                                    <hr class="my-2">
                                    <?php if (!empty($withfind['findings'])): ?>
                                        <div
                                            class="text-lg mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                            <b>Findings:<br></b>
                                            <?= nl2br($withfind['findings']) ?>
                                        </div>
                                    <?php else: ?>
                                        <div
                                            class="text-lg mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                            <b>Findings:</b></div>
                                    <?php endif; ?>

                                    <?php if (!empty($withfind['impression'])): ?>
                                        <div
                                            class="text-lg mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                            <b>Impression:<br></b>
                                            <?= nl2br($withfind['impression']) ?>
                                        </div>
                                    <?php else: ?>
                                        <div
                                            class="text-lg mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                            <b>Impression:</b></div>
                                    <?php endif; ?>

                                </div>

                                <div class="col-span-2 rounded-lg bg-gray-200 p-4 ml-4">
                                    <div class="py-1 font-bold text-[#0126CC]">PATIENT FINDINGS</div>
                                    <hr class="my-2">

                                    <div class="grid grid-cols-2 grid-rows-2 mt-3">
                                        <?php if (!empty($withfind['xray_type'])): ?>
                                            <div
                                                class="text-sm mr-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>X-ray Type: </b>
                                                <?= $withfind['xray_type'] ?>
                                            </div>
                                        <?php else: ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>X-ray Type:</b></div>

                                        <?php endif; ?>

                                        <?php if (!empty($withfind['date_consulted'])): ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>Date Consulted:</b>
                                                <?= $withfind['date_consulted'] ?>
                                            </div>
                                        <?php else: ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>Date Consulted:</b></div>
                                        <?php endif; ?>

                                        <?php if (!empty($withfind['issued_by'])): ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>Issued By:</b>
                                                <?= $withfind['issued_by'] ?>
                                            </div>
                                        <?php else: ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>Issued By:</b></div>
                                        <?php endif; ?>

                                        <?php if (!empty($withfind['diagnosed_by'])): ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>Diagnosed By:</b>
                                                <?= $withfind['diagnosed_by'] ?>
                                            </div>
                                        <?php else: ?>
                                            <div
                                                class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                                                <b>Diagnosed By:</b></div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($withfind['xray_file'])) { ?>
                                        <!-- Display the uploaded file -->
                                        <div class="justify-center items-center flex">
                                            <img class="mt-5" src="<?= $withfind['xray_file'] ?>" height="350px"
                                                width="350px" onclick="window.open(this.src)">
                                        </div>


                                    <?php } ?>

                                    <?php if (empty($withfind['xray_file'])) { ?>
                                        <div class="justify-center items-center flex mt-5">
                                            <div class="h-[150px] w-[150px] bg-[#bcbcbc] items-center rounded-lg">
                                                <p class="text-white text-sm justify-center flex mt-[50px]">No image
                                                    uploaded</p>
                                            </div>
                                        </div>





                                        </form>
                                    <?php } ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <script>
                    // Get references to the age and birthday input fields
                    const ageInput = document.getElementById('age');
                    const birthdayInput = document.getElementById('birthday');

                    // Add an event listener to the age input field
                    ageInput.addEventListener('input', function () {
                        // Calculate the birthdate based on the entered age
                        const age = parseInt(ageInput.value);
                        if (!isNaN(age)) {
                            const today = new Date();
                            const birthdate = new Date(today.getFullYear() - age, today.getMonth(), today.getDate());
                            const formattedDate = formatDate(birthdate);
                            birthdayInput.value = formattedDate;
                        }
                    });

                    // Helper function to format a date as "YYYY-MM-DD"
                    function formatDate(date) {
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    }
                </script>

                <script src="assets/javascript/app.js"></script>
</body>

</html>