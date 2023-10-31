<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Doctor') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}
require("connection.php");


// Check if a user ID is provided in the URL (query parameter)
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    // Fetch the user's first name and last name based on the user ID
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Assign the fetched values to variables
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
} else {
    // If no user ID is provided, set the variables to empty values
    $firstName = '';
    $lastName = '';
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Doctor Dashboard</title>


    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">
    <!-- styles css -->
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/modals2.css">
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons for sidebar Icons -->
 <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
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
            <li class="">
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

            <li class="active">
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
                        <span class="Doctor_text">Doctor Account</span>
                    </span>
                </a>
            </li>

        </ul>
    </div>
    <section class="home-section">

        <br>
        <div class="container">
            <div class="card shadow rounded">
                <div class="card-header bg-white">
                    <!-- Rounded pill label for User ID -->
                    <span class="rounded-pill border border-black bg-white text-black px-2 py-1 position-absolute"
                        style="top: 50px; left: 20px;">User ID:
                        <?php echo $userId; ?>
                    </span>

                    <!-- End of User ID label -->
                    <div class="row align-items-center">
                        <div class="col">
                            <center>
                                <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
                            </center>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="text-lg font-bold py-1 pl-3">ADD PATIENT RECORD</h4>
                    <hr class="mb-4">

                    <form method="POST" action="addPatientRecordsDoctor.php?user_id=<?php echo $userId; ?>" class="row g-3"
                        id="patientForm">


                        <div class="col-md-6">
                            <label for="fname" class="form-label"><b>First Name</b></label>
                            <input type="text" name="fname" required class="form-control rounded-pill"
                                placeholder="First Name" value="<?php echo $firstName; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Last Name</b></label>
                            <input type="text" name="lname" required class="form-control rounded-pill"
                                placeholder="Last Name" value="<?php echo $lastName; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Middle Name</b></label>
                            <input type="text" name="mname" required class="form-control rounded-pill"
                                placeholder="Middle Name">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Address</b></label>
                            <input type="text" name="address" required class="form-control rounded-pill"
                                placeholder="Address (City only)">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Age</b></label>
                            <input type="number" name="age" required class="form-control rounded-pill"
                                placeholder="Age">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Birthday</b></label>
                            <input type="date" name="birthday" required class="form-control rounded-pill"
                                placeholder="Birthday">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Birthplace</b></label>
                            <input type="text" name="birthplace" required class="form-control rounded-pill"
                                placeholder="Birthplace">
                        </div>
                        <div class="col-md-6">
                            <label for="civilStatus" class="form-label"><b>Civil Status</b></label>
                            <select name="civilStatus" required class="form-select rounded-pill">
                                <option disabled selected value="">Select Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label"><b>Gender</b></label>
                            <select name="gender" required class="form-select rounded-pill">
                                <option disabled selected value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Mobile Number</b></label>
                            <input type="tel" name="mobileNum" maxlength="11" required class="form-control rounded-pill"
                                placeholder="09XXXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label for="religion" class="form-label"><b>Religion</b></label>
                            <select name="religion" required class="form-select rounded-pill">
                                <option disabled selected value="">Select Religion</option>
                                <option value="Roman Catholic">Roman Catholic</option>
                                <option value="Born-Again Christian">Born-Again Christian</option>
                                <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                <option value="Islam">Islam</option>
                                <option value="Seventh-Day Adventist">Seventh-Day Adventist</option>
                                <option value="Protestant">Protestant</option>
                                <option value="Jehovah's Witness">Jehovah's Witness</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Occupation</b></label>
                            <input type="text" name="occupation" required class="form-control rounded-pill"
                                placeholder="Occupation">
                        </div>


                        <div class="col-12 mt-3 d-flex justify-content-between">
                            <div>
                                <a href="DoctorAddPatient.php"
                                    class="btn btn-secondary btn-lg rounded-pill px-4 py-2">Back</a>
                            </div>
                            <div>
                                <input type="hidden" name="patientAcc" value="<?php echo $userId; ?>">
                                <button
                                    class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 hover:text-white"
                                    type="submit">Submit</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
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


    <div id="confirmmodal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="modal-bg bg-black opacity-50"></div>
        <div class="modal-content bg-white p-4 rounded shadow-lg text-center">
            <h2 class="text-xl font-bold mb-2">Are all the details correct?</h2>
            <div class="flex justify-center space-x-4">
                <button type="submit1" id="confirmBtn" class="btn btn-primary">Confirm</button>

                <button id="closeBtn" class="btn btn-primary">No</button>
            </div>
        </div>


        <div id="emptyFieldsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-bg bg-black opacity-50"></div>
            <div class="modal-content bg-white p-4 rounded shadow-lg text-center">
                <h2 class="text-xl font-bold mb-2">Please fill in all fields</h2>
                <button id="closeEmptyFieldsModal" class="btn btn-primary">OK</button>
            </div>
        </div>



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



            document.querySelector('#patientForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let fields = document.querySelectorAll('#patientForm input, #patientForm select');
                let isEmpty = false;
                fields.forEach(function (field) {
                    if (!field.value.trim()) {
                        isEmpty = true;
                        return;
                    }
                });

                if (isEmpty) {
                    document.getElementById('emptyFieldsModal').classList.remove('hidden');
                } else {
                    // Show confirmation modal
                    document.getElementById('confirmmodal').classList.remove('hidden');
                }
            });

            document.getElementById('confirmBtn').addEventListener('click', function () {
                // Submit the form if all details are correct
                document.getElementById('patientForm').submit();
            });

            document.getElementById('closeBtn').addEventListener('click', function () {
                // Close the confirmation modal
                document.getElementById('confirmmodal').classList.add('hidden');
            });
        </script>



        <script src="assets/javascript/app.js"></script>


</body>

</html>