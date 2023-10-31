<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
  header('Location: loginform.php');
  exit();
}

// Check if the user's role is "Staff Radtech"
if ($_SESSION['role'] !== 'Staff Radtech') {
  // If the user is not a staff radtech, you can redirect them to an error page or another appropriate page.
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
  <title>Staff View Patient</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/sidebar.css">

  <script src="https://cdn.tailwindcss.com"></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="">
  <div class="sidebar bg-[#e4e9f7]">
    <div class="logo_details">
      <i class="bx bx-heart icon"></i>
      <div class="logo_name">MyLab Clinic</div>
      <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
      <li class="">
        <a href="staff_profilepage.php">
          <i class="bx bx-user"></i>

          <span class="link_name"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
        </a>
        <span class="tooltip"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
      </li>

      <li class="">
        <a href="staffDashboard.php">
          <i class="bx bx-grid-alt"></i>
          <span class="link_name">Staff Dashboard</span>
        </a>
        <span class="tooltip">Staff Dashboard</span>
      </li>

      <li class="active">
        <a href="staffPatientRecordsTable.php">
          <i class="bx bx-book-open"></i>
          <span class="link_name">Patient Records Table</span>
        </a>
        <span class="tooltip">Patient Records Table</span>
      </li>
      <li class="">
        <a href="staffAddPatient.php">
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
        <a href="logout.php">
          <i class="bx bx-log-out" id="log_out"></i>
          <span class="link_name">
            <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
            <br>
            <span class="admin_text">Staff Account</span>
          </span>
        </a>
      </li>
    </ul>
  </div>

  <!-- end of side bar-->

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

                  <div class="pb-1 flex justify-between"> <!-- Use flex to align buttons to the right and add space between them -->
                    <div>
                      <a href="staffRadtechPatientRecordsTable.php" class="btn btn-secondary bg-[#0126CC] btn-lg rounded-pill px-4 py-2">Back</a>
                    </div>



                    <p class="justify-center flex text-md pt-3 font-bold"></p>
                  </div>
                  <div class="grid grid-cols-6">
                    <div class="col-span-4 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-4 pl-[5px]">
                      <div class="py-5 font-bold text-[#0126CC]">PATIENT NAME:</div>

                      <div class="col-span-2 uppercase py-5 text-[#0126CC]"><?php echo $patient['first_name'] . ' ' . $patient['mname'] . ' ' . $patient['last_name'] ?></div>
                    </div>
                    <div class="col-span-2 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-2 pl-[5px]">
                      <div class="py-5 font-bold text-[#0126CC]">APPOINTMENT ID:</div>
                      <div class="uppercase py-5 justify-center items-center flex text-[#0126CC]"> <?php echo "MLC - " . $findings['appointment_id'] ?> </div>
                    </div>
                  </div>

                  <div class="grid grid-cols-3 my-4">
                    <div class="rounded-lg bg-gray-200 p-4">
                      <div class="py-1 font-bold text-[#0126CC]">DETAILS</div>
                      <hr class="my-2">
                      <p class="text-sm font-bold text-[#5b5b5b] pt-[5px]">Address</p>
                      <p class="text-md font-bold pb-1"><?php echo $patient['address']; ?></p>
                      <p class="text-sm font-bold text-[#5b5b5b]">Age</p>
                      <p class="text-md font-bold pb-1"><?php echo $patient['age']; ?></p>
                      <p class="text-sm font-bold text-[#5b5b5b]">Birthday</p>
                      <p class="text-md font-bold pb-1"><?php echo $patient['birthday']; ?></p>
                      <p class="text-sm font-bold text-[#5b5b5b]">Gender</p>
                      <p class="text-md font-bold pb-1"><?php echo $patient['gender']; ?></p>
                      <p class="text-sm font-bold text-[#5b5b5b]">Mobile Number</p>
                      <p class="text-md font-bold pb-1"><?php echo $patient['mobileNumber']; ?></p>
                      <hr class="my-2">
                      <div class="py-1 font-bold text-[#0126CC]">FINDINGS AND IMPRESSION</div>
                      <hr class="my-2">
                      <?php if (!empty($withfind['findings'])) : ?>
                          <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Findings:<br></b> <?= nl2br($withfind['findings']) ?></div>
                        <?php else : ?>
                          <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Findings:</b></div>
                        <?php endif; ?>

                        <?php if (!empty($withfind['impression'])) : ?>
                          <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Impression:<br></b> <?= nl2br($withfind['impression']) ?></div>
                        <?php else : ?>
                          <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Impression:</b></div>
                        <?php endif; ?>

                    </div>

                    <div class="col-span-2 rounded-lg bg-gray-200 p-4 ml-4">

                      <div class="py-1 font-bold text-[#0126CC]">PATIENT FINDINGS</div>
                      <hr class="my-2">
                      <form action="uploadImageFileStaffRadtech.php" method="POST" enctype="multipart/form-data" class="">
                        <div class="grid grid-cols-2 grid-rows-2 mt-3">
                          <?php if (!empty($withfind['xray_type'])) : ?>
                            <div class="text-sm mr-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>X-ray Type: </b><?= $withfind['xray_type'] ?></div>

                          <?php else : ?>

                            <div class="text-sm mr-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-2 pl-2">
                              <b>X-ray Type:</b>
                              <select id="xray-type" name="xrayType" class="ml-2 rounded-pill bg-gray-200" onchange="checkOtherOption(this)">
                                <?php
                                try {
                                  // Create a PDO connection to your database
                                  $pdo = new PDO('mysql:host=localhost;dbname=mylabclinic', 'root', '');

                                  // Set the PDO error mode to exception
                                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                                  // Query to fetch X-ray types from the database
                                  $sql = "SELECT xray_type FROM xraytypes";
                                  $stmt = $pdo->query($sql);

                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['xray_type'] . '">' . $row['xray_type'] . '</option>';
                                  }
                                } catch (PDOException $e) {
                                  echo "Database connection failed: " . $e->getMessage();
                                }
                                ?>
                                <option value="Other">Other</option>
                              </select>

                              <div id="otherOptionDiv" style="display: none;">
                                <label for="otherOption">Other X-ray Type:</label>
                                <input type="text" id="otherOption" name="otherOption">
                              </div>
                            </div>


                          <?php endif; ?>

                          <?php if (!empty($withfind['date_consulted'])) : ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>Date Consulted:</b> <?= $withfind['date_consulted'] ?></div>
                          <?php else : ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>Date Consulted:</b></div>
                          <?php endif; ?>

                          <?php if (!empty($withfind['issued_by'])) : ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Issued By:</b> <?= $withfind['issued_by'] ?></div>
                          <?php else : ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Issued By:</b></div>
                          <?php endif; ?>

                          <?php if (!empty($withfind['diagnosed_by'])) : ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Diagnosed By:</b> <?= $withfind['diagnosed_by'] ?></div>
                          <?php else : ?>
                            <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Diagnosed By:</b></div>
                          <?php endif; ?>
                        </div>




                        <?php if (!empty($withfind['xray_file'])) { ?>
                          <div class="justify-center items-center flex">
                            <img class="mt-5" src="<?= $withfind['xray_file'] ?>" height="350px" width="350px" onclick="window.open(this.src)">

                          </div>

                        <?php } else { ?>
                          <div class="justify-center items-center flex mt-5">
                            <div class="h-[300px] w-[300px] bg-[#bcbcbc] items-center ">

                              <img id="preview" src="#" alt="Preview Image" style="display:none" height="300px" width="300px">
                            </div>
                          </div>
                          <input type="hidden" name="user_id" value="<?php echo $patient['user_id'] ?>">
                          <input type="hidden" name="appointmentID" value="<?php echo $findings['appointment_id'] ?>">







                          <input type="hidden" name="issuedBy" value="<?php echo $_SESSION['first_name'] . ' ' .  $_SESSION['last_name']; ?>">

                          <input type="file" name="image" onchange="previewImage(event)" class="col-span-2 w-3/9 h-[40px] text-md text-white py-1 px-1 rounded-sm bg-[#0126CC] text-white hover:bg-[#6257b4] mt-3 ml-4" required><br>

                          <button type="submit" class="w-3/9 h-[40px] text-md text-white ounded-sm bg-[#0126CC] px-4 py-1 text-white hover:bg-[#6257b4] mt-3 ml-4" onclick="return confirm('Are details correct?');">Confirm</button>
                          <p class="text-xs ml-5 col-span-2">(File Size 30mb Maximum)</p>
                      </form>
                    <?php } ?>

                    </div>
                    <!-- New div below "DETAILS" -->
                    
                  </div>

                </div>
              </div>
            </div>
  </section>
  <script>
    function checkOtherOption(select) {
      var otherOptionDiv = document.getElementById("otherOptionDiv");
      var otherOptionInput = document.getElementById("otherOption");

      if (select.value === "Other") {
        otherOptionDiv.style.display = "block";
        otherOptionInput.required = true; // Optional: make the input required
      } else {
        otherOptionDiv.style.display = "none";
        otherOptionInput.required = false;
        otherOptionInput.value = ""; // Clear the input value
      }
    }
  </script>
  <script src="assets/javascript/app.js"></script>
</body>

</html>