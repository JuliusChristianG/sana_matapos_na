<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Staff') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
  $pdo = new PDO($dsn, $username, $password);
  // Set PDO error mode to exception.
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Check if a specific patient's case number is provided in the URL.
  if (isset($_GET['caseNo'])) {
    // Fetch the specific patient's data from the patient_records table based on the case number.
    $stmt = $pdo->prepare("SELECT * FROM patient_records WHERE case_no = :caseNo");
    $stmt->bindParam(':caseNo', $_GET['caseNo'], PDO::PARAM_STR);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
      // Handle the case when the patient with the given case number does not exist.
      echo "Patient not found.";
      die();
    }

    // Fetch the patient's findings from the patient_findings table based on the case number.
    $stmtFindings = $pdo->prepare("SELECT * FROM patient_findings WHERE case_number = :caseNo");
    $stmtFindings->bindParam(':caseNo', $_GET['caseNo'], PDO::PARAM_STR);
    $stmtFindings->execute();
    $findings = $stmtFindings->fetch(PDO::FETCH_ASSOC);

    // Now you have both patient and findings data.
    // You can display them in your HTML as needed.
  } else {
    // Handle the case when the case number is not provided in the URL.
    echo "Case number not provided.";
    die();
  }
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die();
}
?>

<?php
// Create a database connection
$mysqli = new mysqli('localhost', 'u651313594_mylabsanjuan', 'Mylabsanjuan23', 'u651313594_mylabClinic');


// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$patientCaseNo = $_GET["caseNo"]; // Replace this with your input source

$getPatientInfoQuery = "SELECT *, DATE_FORMAT(dateAdded, '%Y-%m-%d') AS formatted_date, DATE_FORMAT(birthday, '%Y-%m-%d') AS formatted_bday, DATE_FORMAT(patient_findings.date_consulted, '%Y-%m-%d') AS formatted_date_consulted FROM patient_records LEFT JOIN patient_findings ON patient_records.case_no = patient_findings.case_number WHERE patient_records.case_no = ?";

if ($stmt = $mysqli->prepare($getPatientInfoQuery)) {
  $stmt->bind_param("s", $patientCaseNo);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $prev = 0;
    $next = 0;
    $row = $result->fetch_assoc();
    $userID = $row["userID"];

    $getNextInfoQuery = "SELECT * FROM patient_records WHERE userID = ? AND case_no > ? LIMIT 1";

    if ($stmt2 = $mysqli->prepare($getNextInfoQuery)) {
      $stmt2->bind_param("ss", $userID, $patientCaseNo);
      $stmt2->execute();
      $result2 = $stmt2->get_result();

      if ($result2->num_rows > 0) {
        $nextRow = $result2->fetch_assoc();
        $next = $nextRow["case_no"];
      }

      $getPreviousInfoQuery = "SELECT * FROM patient_records WHERE userID = ? AND case_no < ? ORDER BY case_no DESC LIMIT 1";

      if ($stmt3 = $mysqli->prepare($getPreviousInfoQuery)) {
        $stmt3->bind_param("ss", $userID, $patientCaseNo);
        $stmt3->execute();
        $result3 = $stmt3->get_result();

        if ($result3->num_rows > 0) {
          $prevRow = $result3->fetch_assoc();
          $prev = $prevRow["case_no"];
        }
      }
    }
  }

  $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Staff View Patient</title>
  <link href="assets/images/logo-no-bg.png" rel="icon">
      <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/sidebar.css">

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
        <a href="staffprofilepage.php">
          <i class="bx bx-user-circle"></i>

          <span class="link_name">
            <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
          </span>
        </a>
        <span class="tooltip">
          <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
        </span>
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
          <span class="link_name">Patient Records</span>
        </a>
        <span class="tooltip">Patient Records</span>
      </li>


      <li class="">
        <a href="staffPatientAccounts.php">
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
        <a href="logout.php">
          <i class="bx bx-log-out" id="log_out"></i>
          <span class="link_name">
            <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
            <br>
            <span class="staff_text">Staff Account</span>
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
    
   
    <p class="justify-center flex text-md pt-3 font-bold"></p>
</div>
<div class="grid grid-cols-6">
                                    <div class="col-span-4 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-4 pl-[5px]">
                                        <div class="py-5 font-bold text-[#0126CC]">PATIENT NAME:</div>

                                        <div class="col-span-2 uppercase py-5 text-[#0126CC]"><?php echo $patient['fname'] . ' ' . $patient['mname'] . ' ' . $patient['lname'] ?></div>
                                    </div>
                                    <div class="col-span-2 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-2 pl-[5px]">
                                        <div class="py-5 font-bold text-[#0126CC]">CASE NO:</div>
                                        <div class="uppercase py-5 justify-center items-center flex text-[#0126CC]"> <?php echo "MLC - " . $patient['case_no'] ?> </div>
                                    </div>
                                </div>
                                
                          

                                    <div class="grid grid-cols-3 my-4">
                                    <div class="col-span-2 rounded-lg bg-gray-200 p-4 ml-4">
                                        <div class="rounded-lg bg-gray-200 p-4">
                                        <div class="py-1 font-bold text-[#0126CC]">DETAILS</div>
                                        <hr class="my-2">
                                        <p class="text-sm font-bold text-[#5b5b5b] pt-[5px]">Address</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['address']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Age</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['age']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Birthday</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['birthday']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Birthplace</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['birthplace']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Civil Status</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['civilStatus']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Gender</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['gender']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Mobile Number</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['mobileNumber']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Religion</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['religion']; ?></p>
                                        <p class="text-sm font-bold text-[#5b5b5b]">Occupation</p>
                                        <p class="text-md font-bold pb-1"><?php echo $patient['occupation']; ?></p>
                                         </div>
                            </div>
                        </div>
                    </div>
    </section>
    <script src="assets/javascript/app.js"></script>
</body>

</html>