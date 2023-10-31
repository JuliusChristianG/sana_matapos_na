<?php
session_start();
date_default_timezone_set('Asia/Manila');
// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
  header('Location: loginform.php');
  exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Staff Doctor') {
  // If the user is not an admin, you can redirect them to an error page or another appropriate page.
  header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
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
  <title>Staff Doctor View Patient</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/sidebar.css">

  <link rel="icon" href=assets/images/mylablogo.png type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
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
        <a href="staffDoctor_profilepage.php">
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
        <a href="staffDoctorForDiagnosisTable.php">
          <i class='bx bx-edit'></i>
          <span class="link_name">Patient for Diagnosis</span>
        </a>
        <span class="tooltip">For Diagnosis Table</span>
      </li>



      <li>
        <a href="staffDoctorPatientRecordsTable.php">
          <i class='bx bx-book-alt'></i>
          <span class="link_name">Patient Records</span>
        </a>
        <span class="tooltip">Patient Records</span>
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
            <span class="admin_text">Staff-Doctor Account</span>
          </span>
        </a>
      </li>

    </ul>
  </div>
  <style>
    .highlighted-text1 {
      background-color: #0126CC;

      padding: 10px;

      margin-top: 20px;

    }
  </style>
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
                      <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
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
                    <div>
                      <a href="staffDoctorPatientRecordsTable.php"
                        class="btn btn-secondary bg-[#0126CC] btn-lg rounded-pill px-4 py-2">Back</a>
                    </div>



                    <p class="justify-center flex text-md pt-3 font-bold"></p>
                  </div>
                  <div class="grid grid-cols-6">
                    <div
                      class="col-span-4 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-4 pl-[5px]">
                      <div class="py-5 font-bold text-[#0126CC] text-xl">PATIENT NAME:</div>

                      <div class="col-span-2 uppercase py-5 text-[#0126CC] text-lg">
                        <?php echo $patient['first_name'] . ' ' . $patient['mname'] . ' ' . $patient['last_name'] ?>
                      </div>
                    </div>
                    <div
                      class="col-span-2 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-2 pl-[5px]">
                      <div class="py-5 font-bold text-[#0126CC] text-xl">USER ID:</div>
                      <div class="uppercase py-5 justify-center items-center flex text-[#0126CC] text-lg">
                        <?php echo "MLC - " . $findings['user_id'] ?>
                      </div>
                    </div>
                  </div>
                  <form action="uploadDiagnosis.php" method="POST" enctype="multipart/form-data" class="">
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

                        <?php if (empty($withfind['findings'])) { ?>
                          <div class="col-span-4 input-group mt-1 px-2 ml-2">
                            <ul><label class="text-center text-[#221E3F] font-bold text-lg">Findings: </label></ul>
                          </div>

                          <div class="col-span-4 input-group mt-1 px-2 ml-2">
                            <textarea name="findings" required
                              class="mt-1 bg-[#efefef] text-md text-[#525252] h-[80px] w-full mb-2 p-1 rounded-sm outline outline-offset-2 outline-gray mr-4"
                              placeholder="Findings"></textarea>
                          </div>

                          <div class="col-span-4 input-group mt-1 px-2 ml-2">
                            <ul><label class="text-center text-[#221E3F] font-bold text-lg">Impression: </label></ul>
                          </div>

                          <div class="col-span-4 input-group mt-1 px-2 ml-2">
                            <textarea name="impression" required
                              class="mt-1 bg-[#efefef] text-md text-[#525252] h-[80px] w-full mb-2 p-1 rounded-sm outline outline-offset-2 outline-gray mr-4"
                              placeholder="Impression"></textarea>
                          </div>




                          <input type="hidden" name="user_id" value="<?php echo $patient['user_id'] ?>">
                          <input type="hidden" name="appointmentID" value="<?php echo $findings['appointment_id'] ?>">
                          <input type="hidden" name="diagnosed_by"
                            value="<?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?>">

                          <button type="submit"
                            class="w-3/9 h-[40px] text-md text-white ounded-sm bg-[#0126CC] px-4 py-1 text-white hover-bg-[#6257b4] mt-3 ml-4"
                            onclick="return confirm('Are details correct?');">Confirm</button>
                        <?php } else { ?>
                          <!-- Hide the input field and the "Confirm" button if final diagnosis is not empty -->
                          <div class="col-span-4 input-group mt-1 px-2 ml-2" style="display: none;"></div>
                          <button type="submit" style="display: none;"></button>
                          <?php if (!empty($withfind['findings'])): ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Findings:<br></b>
                              <?= nl2br($withfind['findings']) ?>
                            </div>
                          <?php else: ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Findings:</b>
                            </div>
                          <?php endif; ?>

                          <?php if (!empty($withfind['impression'])): ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Impression:<br></b>
                              <?= nl2br($withfind['impression']) ?>
                            </div>
                          <?php else: ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Impression:</b>
                            </div>
                          <?php endif; ?>
                        <?php } ?>
                      </div>

                      <div class="col-span-2 rounded-lg bg-gray-200 p-4 ml-4">
                        <div class="py-1 font-bold text-[#0126CC] text-xl">PATIENT FINDINGS</div>
                        <hr class="my-2">

                        <div class="grid grid-cols-2 grid-rows-2 mt-3">
                          <?php if (!empty($withfind['xray_type'])): ?>
                            <div class="text-sm mr-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>X-ray Type: </b>
                              <?= $withfind['xray_type'] ?>
                            </div>
                          <?php else: ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>X-ray Type:</b>
                            </div>

                          <?php endif; ?>

                          <?php if (!empty($withfind['date_consulted'])): ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Date Consulted:</b>
                              <?= $withfind['date_consulted'] ?>
                            </div>
                          <?php else: ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Date Consulted:</b>
                            </div>
                          <?php endif; ?>

                          <?php if (!empty($withfind['issued_by'])): ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Issued By:</b>
                              <?= $withfind['issued_by'] ?>
                            </div>
                          <?php else: ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Issued By:</b>
                            </div>
                          <?php endif; ?>

                          <?php if (!empty($withfind['diagnosed_by'])): ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Diagnosed By:</b>
                              <?= $withfind['diagnosed_by'] ?>
                            </div>
                          <?php else: ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2">
                              <b>Diagnosed By:</b>
                            </div>
                          <?php endif; ?>
                        </div>

                        <?php if (!empty($withfind['xray_file'])) { ?>
                          <!-- Display the uploaded file -->
                          <div class="justify-center items-center flex">
                            <img class="mt-5" src="<?= $withfind['xray_file'] ?>" height="350px" width="350px"
                              onclick="window.open(this.src)">
                          </div>


                        <?php } ?>

                        <?php if (empty($withfind['xray_file'])) { ?>
                          <div class="justify-center items-center flex mt-5">
                            <div class="h-[150px] w-[150px] bg-[#bcbcbc] items-center rounded-lg">
                              <p class="text-white text-sm justify-center flex mt-[50px]">No image uploaded</p>
                            </div>
                          </div>





                    </form>
                  <?php } ?>

                </div>
              </div>

            </div>
          </div>
        </div>
  </section>
  <script src="assets/javascript/app.js"></script>
</body>

</html>