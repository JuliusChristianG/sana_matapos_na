<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}
require("connection.php");
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

        // Logging the user's activity
        if (isset($_GET['viewButtonArchived'])) {
            // Get user information from the session (adjust as needed)
            $adminUserID = $_SESSION['user_id'];
            $loggedInUsername = $_SESSION['username'];
            $loggedInRole = $_SESSION['role'];
        
            // Define the log query
            $logQuery = "INSERT INTO userlog (userID, uname, role, date, time, action) VALUES (?, ?, ?, ?, ?, ?)";
        
            // Get the case number from the form submission
            $caseNo = isset($_GET['caseNo']) ? $_GET['caseNo'] : '';
        
            // Get the current date and time
            $date = date("Y-m-d");
            $time = date("H:i:s");
        
            // Define the action message for the log entry
            $action = "Viewed archived patient record with Case No. MLC - $caseNo";
        
            // Prepare and execute the log query
            $stmtLog = $pdo->prepare($logQuery);
            $stmtLog->execute([$adminUserID, $loggedInUsername, $loggedInRole, $date, $time, $action]);
        
            // Redirect to the patient record view page
            header("Location: adminViewPatientArchived.php?caseNo=$caseNo");
            exit();
        }
        
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
    <title>Admin View Patient</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="assets/images/logo-no-bg.png" rel="icon">
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
                <a href="profilepage.php">
                    <i class="bx bx-user-circle"></i>

                    <span class="link_name"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
                </a>
                <span class="tooltip"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
            </li>
            
            <li class="active">
                <a href="adminPatientRecordsTable.php">
                    <i class="bx bx-book-open"></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>

            <li class="">
                <a href="adminAddPatient.php">
                    <i class="bx bx-user-plus"></i>
                    <span class="link_name">Patient Accounts</span>
                </a>
                <span class="tooltip">Patient Accounts</span>
            </li>
            <li class="">
                <a href="adminAddUser.php">
                    <i class="bx bx-user"></i>
                    <span class="link_name">User Accounts</span>
                </a>
                <span class="tooltip">User Accounts</span>
            </li>


            <li class="">
                <a href="adminAuditTrail.php">
                    <i class="bx bx-folder"></i>
                    <span class="link_name">Audit Trail</span>
                </a>
                <span class="tooltip">Audit Trail</span>
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
            <span class="admin_text">Admin Account</span>
        </span>
    </a>
</li>
        </ul>
    </div>
 <section>
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
            <a href="adminPatientRecordsTableArchived.php" class="btn btn-secondary bg-[#0126CC] btn-lg rounded-pill px-4 py-2">Back</a>
        </div>

        <div> <!-- Wrap the Previous and Next buttons in a container -->
            <?php if ($prev != 0) : ?>
                <a href="adminViewPatient.php?caseNo=<?= $prev ?>"><button class="btn btn-secondary bg-[#0126CC] btn-lg rounded-pill px-4 py-2">Previous</button></a>
            <?php else : ?>
                <button class="btn btn-secondary btn-lg rounded-pill px-4 py-2" disabled>Previous</button>
            <?php endif; ?>
            
            <?php if ($next != 0) : ?>
                <a href="adminViewPatient.php?caseNo=<?= $next ?>"><button class="btn btn-secondary btn-lg bg-[#0126CC] rounded-pill px-4 py-2">Next</button></a>
            <?php else : ?>
                <button class="btn btn-secondary btn-lg rounded-pill px-4 py-2" disabled>Next</button>
            <?php endif; ?>
        </div>
    </div>

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

                                    <div class="col-span-2 rounded-lg bg-gray-200 p-4 ml-4">

                                        <div class="py-1 font-bold text-[#0126CC]">PATIENT FINDINGS</div>
                                        <hr class="my-2">
                                        <form action="uploadImageFile.php" method="POST" enctype="multipart/form-data" class="">
                                            <div class="grid grid-cols-2 grid-rows-2 mt-3">
                                                <?php if (!empty($findings['xray_type'])) : ?>
                                                    <div class="text-sm mr-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>X-ray Type: </b><?= $findings['xray_type'] ?></div>

                                                <?php else : ?>

                                                    <div class="text-sm mr-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-2 pl-2 "><b>X-ray Type:</b>
                                                        <select id="xray-type" name="xrayType" class="ml-2 rounded-pill bg-gray-200">
                                                            <option value="Wrist">Wrist</option>
                                                            <option value="Knee">Knee</option>
                                                            <option value="Leg">Leg</option>
                                                            <option value="Foot">Foot</option>
                                                            <option value="Ankle">Ankle</option>
                                                            <option value="Pelvis">Pelvis</option>
                                                            <option value="Lumbosacral AP/L">Lumbosacral AP/L</option>
                                                            <option value="Thoraco-lumbar AP/L">Thoraco-lumbar AP/L</option>
                                                            <option value="Abdomen Sup and Up">Abdomen Sup and Up</option>
                                                            <option value="Chest PA">Chest PA</option>
                                                            <option value="Apicolordotic View">Apicolordotic View</option>
                                                            <option value="Thoracic Cage">Thoracic Cage</option>
                                                            <option value="Skull AP/L">Skull AP/L</option>
                                                            <option value="Paranasal Sinuses">Paranasal Sinuses</option>
                                                            <option value="Shoulder's Joint">Shoulder's Joint</option>
                                                            <option value="Forearm">Forearm</option>
                                                            <option value="Elbow">Elbow</option>
                                                        </select>
                                                    </div>

                                                <?php endif; ?>

                                                <?php if (!empty($findings['date_consulted'])) : ?>
                                                    <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>Date Consulted:</b> <?= $findings['date_consulted'] ?></div>
                                                <?php else : ?>
                                                    <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>Date Consulted:</b></div>
                                                <?php endif; ?>

                                                <?php if (!empty($findings['final_diagnosis'])) : ?>
                                                    <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Final Diagnosis:</b> <?= $findings['final_diagnosis'] ?></div>
                                                <?php else : ?>
                                                    <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Final Diagnosis:</b></div>
                                                <?php endif; ?>

                                                <?php if (!empty($findings['issued_by'])) : ?>
                                                    <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Issued By:</b> <?= $findings['issued_by'] ?></div>
                                                <?php else : ?>
                                                    <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Issued By:</b></div>
                                                <?php endif; ?>
                                            </div>




                                            <?php if (!empty($findings['xray_file'])) { ?>
                                                <div class="justify-center items-center flex">
                                                    <img class="mt-5" src="<?= $findings['xray_file'] ?>" height="350px" width="350px" onclick="window.open(this.src)">
                                                </div>

                                            <?php } else { ?>
                                                <div class="justify-center items-center flex mt-5">
                                                    <div class="h-[300px] w-[300px] bg-[#bcbcbc] items-center ">

                                                        <img id="preview" src="#" alt="Preview Image" style="display:none" height="300px" width="300px">
                                                    </div>
                                                </div>


                                                <div class="col-span-4 input-group mt-1 px-2 ml-2">
                                                    <ul><label class="text-center text-[#221E3F] font-bold text-md">Final Diagnosis: </label></ul>
                                                </div>

                                                <div class="col-span-4 input-group mt-1 px-2 ml-2">
                                                    <input type="text" name="finalDiagnosis" required class="mt-1 bg-[#efefef] text-md text-[#525252] h-[20px] w-2/5 mb-2 p-1 rounded-sm outline outline-offset-2 outline-gray mr-4" placeholder="Final Diagnosis">
                                                </div>
                                                <input type="text" name="caseNo" value="<?= $patient['case_no'] ?>" hidden>
                                                <input type="hidden" name="issuedBy" value="<?php echo $_SESSION['first_name'] . ' ' .  $_SESSION['last_name']; ?>">

                                                <input type="file" name="image" onchange="previewImage(event)" class="col-span-2 w-3/9 h-[40px] text-md text-white py-1 px-1 rounded-sm bg-[#0126CC] text-white hover:bg-[#6257b4] mt-3 ml-4" required><br>

                                                <button type="submit" class="w-3/9 h-[40px] text-md text-white ounded-sm bg-[#0126CC] px-4 py-1 text-white hover:bg-[#6257b4] mt-3 ml-4" onclick="return confirm('Are details correct?');">Confirm</button>
                                                <p class="text-xs ml-5 col-span-2">(File Size 30mb Maximum)</p>
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