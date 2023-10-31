<?php
session_start();
require("connection.php");

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


try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Patient' AND is_verified = 1 AND is_deleted = 0 ORDER BY user_id DESC");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 10; // Set the number of items per page
$start = ($page - 1) * $perPage;
$end = $start + $perPage;

$displayedPatients = array_slice($patients, $start, $perPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Add Patient</title>
    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">
    <!-- styles css -->
    <link rel="stylesheet" href="assets/css/sidebar.css">
  
      
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons for sidebar Icons -->

    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
    <link href="assets/images/logo-no-bg.png" rel="icon">
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
                <a href="profilepage.php">
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
                <a href="adminPatientRecordsTable.php">
                    <i class="bx bx-book-open"></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>

            <li class="active">
                <a href="#">
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

            <li class="">
                <a href="adminCMS.php">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Content Management</span>
                </a>
                <span class="tooltip">Content Management</span>
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
    <!-- Start of main content-->
    <section class="home-section">
        <br>
        <div class="container">
            <div class="card shadow rounded">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <center>
                                <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
                            </center>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="text-lg font-bold py-1 pl-3">PATIENT ACCOUNT</h4>
                    <hr class="mb-4">

                    <form id="patientForm" action="addPatients.php" method="POST" class="row g-3"
                        onsubmit="onSubmitForm(event)">


                        <div class="col-md-6">
                            <label for="username" class="form-label"><b>Username</b></label>
                            <input type="text" name="username" required class="form-control rounded-pill"
                                placeholder="Username">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label"><b>Password</b></label>
                            <div class="position-relative">
                                <input type="password" name="password" id="password" required
                                    class="form-control rounded-pill" placeholder="Password">
                                <span id="togglePassword" class="position-absolute top-50 end-3 translate-middle-y">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <script>
                            document.getElementById('togglePassword').addEventListener('click', function () {
                                const passwordField = document.getElementById('password');
                                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                                passwordField.setAttribute('type', type);
                                this.querySelector('i').classList.toggle('fa-eye-slash');
                            });
                        </script>
                        <div class="col-md-6">
                            <label for="fname" class="form-label"><b>First Name</b></label>
                            <input type="text" name="fname" required class="form-control rounded-pill"
                                placeholder="First Name" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Last Name</b></label>
                            <input type="text" name="lname" required class="form-control rounded-pill"
                                placeholder="Last Name" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label"><b>Email</b></label>
                            <input type="email" name="email" required class="form-control rounded-pill"
                                placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label"><b>Position</b></label>
                            <select name="position" required class="form-select rounded-pill">
                                <option value="Patient" selected>Patient</option>
                            </select>
                        </div>


                        <div class="col-12 text-center mt-3">
                            <button
                                class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 hover:text-white"
                                type="submit">Add Patient</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <div class="card shadow rounded">
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">PATIENT ACCOUNTS TABLE</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <div class="form-group">

                                <input type="text" name="search" id="search" class="form-control rounded-pill smaller-search"
                       placeholder="Search by Last Name" style="width: 250px; height: 40px;">

                              
                                <br>
                            </div>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                <tr>
                                    <th class="py-3 px-3 rounded-tl">Username</th>
                                    <th class="px-3">First Name</th>
                                    <th class="px-3">Last Name</th>
                                    <th class="px-3">Position</th>
                                    <th class="px-3">Date Added</th>
                                    <th class="rounded-tr px-3">Action</th>
                                </tr>
                            </thead>
                            <tbody id="patientTableBody">
                                <?php
                                // Assuming $patients is an array containing all patient records
                                
                                $patientsPerPage = 10; // Number of patients per table
                                $totalPatients = count($patients); // Total number of patients
                                
                                // Calculate the current table and total tables based on the current page
                                $currentTable = isset($_GET['table']) ? (int) $_GET['table'] : 1;
                                $totalTables = ceil($totalPatients / $patientsPerPage);

                                // Define your filter parameter if needed
                                $filterParam = isset($filter) ? "&filter=$filter" : "";

                                // Calculate the start and end indices for the current table
                                $startIndex = ($currentTable - 1) * $patientsPerPage;
                                $endIndex = min($startIndex + $patientsPerPage, $totalPatients);

                                // Display the patient table
                                for ($i = $startIndex; $i < $endIndex; $i++) {
                                    $patient = $patients[$i];
                                    ?>
                                    <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                        <td class="py-3 px-3">
                                            <?php echo $patient['username']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $patient['first_name']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $patient['last_name']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $patient['role']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $patient['dateAdded']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            
                                            <button
                                                class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm"
                                                onclick='openEditPatientModal(<?php echo json_encode($patient); ?>)'>Edit</button>
                                            <button
                                                class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm"
                                                onclick="openDeleteConfirmationModal(<?php echo $patient['user_id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    // Display the pagination buttons and current page number
                    echo '<div class="pagination-container" style="text-align: center; display: flex; justify-content: center; align-items: center;">';
                    if ($currentTable > 1) {
                        echo '<a href="?table=' . ($currentTable - 1) . '&search=' . urlencode($_GET['search']) . '" class="btn btn-primary mr-2" style="vertical-align: middle;"><i class="fas fa-chevron-left"></i></a>';
                    }
                    echo '<span class="page-number" style="vertical-align: middle;"> Page ' . $currentTable . ' of ' . $totalTables . '</span>';
                    if ($currentTable < $totalTables) {
                        echo '<a href="?table=' . ($currentTable + 1) . '&search=' . urlencode($_GET['search']) . '" class="btn btn-primary ml-2" style="vertical-align: middle;"><i class="fas fa-chevron-right"></i></a>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
        <!-- edit MODAL -->
        <div id="editPatientModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

                <!-- Form for editing the patient -->
                <div class="modal-content py-4 text-left px-6">
                    <h2 class="text-2xl font-semibold mb-4">Edit Patient</h2>

                    <!-- Add your form for editing the patient here -->
                    <form action="editPatient.php" method="POST" id="editForm">
                        <input type="hidden" name="patient_id" id="editPatientId" value="">
                        <div class="mb-4">
                            <label for="editUsername" class="block mb-2 font-semibold">Username</label>
                            <input type="text" name="editUsername" id="editUsername"
                                class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="editFirstName" class="block mb-2 font-semibold">First Name</label>
                            <input type="text" name="editFirstName" id="editFirstName"
                                class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="editLastName" class="block mb-2 font-semibold">Last Name</label>
                            <input type="text" name="editLastName" id="editLastName"
                                class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="editEmail" class="block mb-2 font-semibold">Email</label>
                            <input type="text" name="editEmail" id="editEmail"
                                class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="editPosition" class="block mb-2 font-semibold">Position</label>
                            <select name="editPosition" id="editPosition" class="w-full px-4 py-2 border rounded-lg"
                                required>
                                <option value="Patient">Patient</option>
                                <!-- Add more options if needed -->
                            </select>
                        </div>


                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                                onclick="closeEditPatientModal()">Cancel</button>
                            <button type="button" class="bg-green-500 text-white py-2 px-4 rounded-lg"
                                onclick="openEditConfirmationModal()">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- DELETE CONFIRMATION MODAL -->
        <div id="deleteConfirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">

                <h2 class="text-2xl font-semibold mb-4">Confirm Deletion</h2>

                <p class="mb-8">Are you sure you want to delete this patient?</p>
                <input type="hidden" id="deleteUserId" value="">

                <div class="flex justify-center">
                    <button class="bg-red-500 text-white py-2 px-4 rounded-lg mr-2"
                        onclick="deletePatient()">Delete</button>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                        onclick="closeDeleteConfirmationModal()">Cancel</button>
                </div>
            </div>
        </div>
        <div id="passwordModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

                <div class="modal-content py-4 text-left px-6">
                    <h2 class="text-2xl font-semibold mb-4">Password Error</h2>
                    <p class="mb-8">Password must be at least 8 characters long and include uppercase, lowercase,
                        numbers, and special characters (@$!%*?&).</p>

                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                            onclick="closePasswordModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
       <!-- Confirmation Modal for Add Patient -->
<div id="confirmationforaddpatient" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-700 bg-opacity-75">
    <div class="modal-box bg-white p-6 rounded shadow-lg w-1/4 text-center"> <!-- Changed class to w-1/4 -->
        <h2 class="text-xl font-bold mb-4">Confirm Details</h2>
        <p class="text-gray-700 mb-4">Are you sure all details are correct?</p>
        <button id="confirmationYesButton" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Yes</button>

        <button onclick="closeConfirmationModalforaddpatient()" class="bg-red-500 text-white py-2 px-4 rounded-lg">No</button>
    </div>
</div>


        <div id="invalidEmailModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">
                <h2 class="text-2xl font-semibold mb-4">Invalid Email Format</h2>
                <p class="mb-8">Please enter a valid email address.</p>

                <button class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                    onclick="closeInvalidEmailModal()">OK</button>
            </div>
        </div>
        <!-- Confirmation Modal for Edit -->
        <div id="editConfirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">
                <h2 class="text-2xl font-semibold mb-4">Confirm Changes</h2>
                <p class="mb-8">Are you sure the details are final?</p>

                <button class="bg-green-500 text-white py-2 px-4 rounded-lg mr-2"
                    onclick="confirmEditFormSubmission()">I Confirm</button>

                <button class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                    onclick="closeEditConfirmationModal()">Cancel</button>
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
        
     <!-- Modal for Registration Success -->
<div id="registrationSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
  <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
    <div class="modal-content py-4 text-left px-6">
      <div class="flex justify-between items-center pb-3">
        <p class="text-2xl font-bold">Registration Success!</p>
        <div class="modal-close cursor-pointer z-50">
          
        </div>
      </div>
      <p>Your Registration is successful! Check your email to verify your account before logging in!</p>
         <div id="countdown"></div>
    </div>
  </div>
</div>
<script>
  <?php if(isset($_GET['registration_success']) && $_GET['registration_success'] == 'true') { ?>
    document.addEventListener('DOMContentLoaded', function() {
      var registrationSuccessModal = document.getElementById('registrationSuccessModal');
      if (registrationSuccessModal) {
        registrationSuccessModal.classList.remove('hidden');
        
        var countdownElement = document.getElementById('countdown');
        var seconds = 5; // Set the number of seconds for the countdown
        
        var countdownInterval = setInterval(function() {
          seconds--;
          if (seconds <= 0) {
            clearInterval(countdownInterval);
            registrationSuccessModal.classList.add('hidden');
            
            // Redirect to adminAddUser.php after modal is closed
            window.location.href = 'adminAddPatient.php';
          } else {
            countdownElement.innerText = 'Closing in ' + seconds + ' seconds';
          }
        }, 1000); // Update every 1 second (1000 milliseconds)
      }
    });
  <?php } ?>
</script>

    </section>


    <script>

        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.getElementById('log_out').addEventListener('click', function () {
            document.getElementById('logoutModal').classList.toggle('hidden');
        });

        document.getElementById('cancelLogout').addEventListener('click', function () {
            document.getElementById('logoutModal').classList.toggle('hidden');
        });

        // Add an event listener for the "Yes" button
        document.getElementById('confirmLogout').addEventListener('click', function () {
            // Redirect to the logout page
            window.location.href = 'logout.php';
        });




        function openEditConfirmationModal() {
            document.getElementById('editConfirmationModal').classList.remove('hidden');
        }

        function closeEditConfirmationModal() {
            document.getElementById('editConfirmationModal').classList.add('hidden');
        }

        function confirmEditFormSubmission() {
            document.getElementById('editForm').submit();
        }


        function submitForm(event) {
            event.preventDefault();
            document.getElementById('editConfirmationModal').classList.remove('hidden');
        }

        function confirmEditFormSubmission() {
            closeEditConfirmationModal();
            document.getElementById('editForm').submit();
        }

        function closeEditConfirmationModal() {
            document.getElementById('editConfirmationModal').classList.add('hidden');
        }

        function openInvalidEmailModal() {
            document.getElementById('invalidEmailModal').classList.remove('hidden');
        }

        function closeInvalidEmailModal() {
            document.getElementById('invalidEmailModal').classList.add('hidden');
        }

        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function validatePassword(password) {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return regex.test(password);
        }

        function showConfirmationModal() {
            document.getElementById('confirmationforaddpatient').classList.remove('hidden');
        }

        function closeConfirmationModalforaddpatient() {
            document.getElementById('confirmationforaddpatient').classList.add('hidden');
        }

        function confirmFormSubmission() {
            closeConfirmationModalforaddpatient();
            document.getElementById('patientForm').submit();
        }

        document.querySelector('form').addEventListener('submit', function (event) {
            const emailInput = document.querySelector('input[name="email"]');
            const email = emailInput.value;

            if (!validateEmail(email)) {
                event.preventDefault();
                openInvalidEmailModal();
            } else {
                const passwordInput = document.querySelector('input[name="password"]');
                const password = passwordInput.value;

                if (!validatePassword(password)) {
                    event.preventDefault();
                    const modal = document.getElementById('passwordModal');
                    modal.classList.remove('hidden');
                } else {
                    showConfirmationModal();
                }
            }
        });

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
        }

        // Add event listener for the "yes" button in the confirmation modal
        document.getElementById('confirmationYesButton').addEventListener('click', function () {
            confirmFormSubmission();
        });




        //for the confirmation add user modal 
        document.querySelector('#patientForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting immediately

            const emailInput = document.querySelector('input[name="email"]');
            const email = emailInput.value;

            if (!validateEmail(email)) {
                openInvalidEmailModal();
            } else {
                const passwordInput = document.querySelector('input[name="password"]');
                const password = passwordInput.value;

                if (!validatePassword(password)) {
                    const modal = document.getElementById('passwordModal');
                    modal.classList.remove('hidden');
                } else {
                    showConfirmationModal();
                }
            }
        });

        document.getElementById('confirmationYesButton').addEventListener('click', function () {
            confirmFormSubmission();
        });

        function confirmFormSubmission() {
            closeConfirmationModalforaddpatient();
            document.getElementById('patientForm').submit(); // Submit the form
        }



        // Function to open the edit patient modal
        function openEditPatientModal(patientData) {

            // Populate the modal fields with patient data
            document.getElementById('editPatientId').value = patientData.user_id;
            document.getElementById('editUsername').value = patientData.username;
            document.getElementById('editFirstName').value = patientData.first_name;
            document.getElementById('editLastName').value = patientData.last_name;
            document.getElementById('editEmail').value = patientData.email;
            document.getElementById('editPosition').value = patientData.role;

            // Show the modal
            document.getElementById('editPatientModal').classList.remove('hidden');
        }

        // Function to close the edit patient modal
        function closeEditPatientModal() {
            document.getElementById('editPatientModal').classList.add('hidden');
        }

        // Function to open the delete confirmation modal
        function openDeleteConfirmationModal(userId) {
            // Set the user id in a hidden input field in the modal
            document.getElementById('deleteUserId').value = userId;
            // Show the modal
            document.getElementById('deleteConfirmationModal').classList.remove('hidden');
        }

        // Function to close the delete confirmation modal
        function closeDeleteConfirmationModal() {
            document.getElementById('deleteConfirmationModal').classList.add('hidden');
        }

        // Function to delete the patient
        function deletePatient() {
            // Get the user id from the hidden input field
            const userId = document.getElementById('deleteUserId').value;

            // Perform the deletion (you may need to use AJAX or form submission)
            window.location.href = 'deletePatient.php?user_id=' + userId;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('search').addEventListener('input', function () {
                const searchQuery = this.value.trim();
                if (searchQuery.length > 0) {
                    // Use AJAX to send the search query to the server
                    const xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                const searchResults = JSON.parse(xhr.responseText);
                                updateTable(searchResults);
                            } else {
                                console.error('Error fetching search results');
                            }
                        }
                    };

                    xhr.open('GET', `searchPatients.php?query=${searchQuery}`, true);
                    xhr.send();
                } else {
                    // If the search bar is empty, reset the table
                    resetTable();
                }
            });
        });

        function updateTable(results) {
            const tableBody = document.getElementById('patientTableBody');
            let html = '';
            if (results.length > 0) {
                results.forEach(result => {
                    html += `
                <tr class="cursor-pointer hover:bg-[#eeeeee]">
                    <td class="py-3 px-3">${result.username}</td>
                    <td class="py-3 px-2">${result.first_name}</td>
                    <td class="py-3 px-2">${result.last_name}</td>
                    <td class="py-3 px-2">${result.role}</td>
                    <td class="py-3 px-2">${result.dateAdded}</td>
                    <td class="py-3 px-2">
                       
                        <button class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm" onclick='openEditPatientModal(${JSON.stringify(result)})'>Edit</button>
                        <button class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm" onclick="openDeleteConfirmationModal(${result.user_id})">Delete</button>
                    </td>
                </tr>
            `;
                });
            } else {
                html = '<tr><td colspan="6">No results found.</td></tr>';
            }

            tableBody.innerHTML = html;
        }

        function resetTable(page) {
            const tableBody = document.getElementById('patientTableBody');
            tableBody.innerHTML = ''; // Clear the table body

            <?php
            foreach ($displayedPatients as $patient) {
                echo "
                    tableBody.innerHTML += `
                        <tr class=\"cursor-pointer hover:bg-[#eeeeee]\">
                            <td class=\"py-3 px-3\">{$patient['username']}</td>
                            <td class=\"py-3 px-2\">{$patient['first_name']}</td>
                            <td class=\"py-3 px-2\">{$patient['last_name']}</td>
                            <td class=\"py-3 px-2\">{$patient['role']}</td>
                            <td class=\"py-3 px-2\">{$patient['dateAdded']}</td>
                            <td class=\"py-3 px-2\">
                                
                                <button class=\"ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm\" onclick='openEditPatientModal(" . json_encode($patient) . ")'>Edit</button>
                                <button class=\"ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm\" onclick=\"openDeleteConfirmationModal({$patient['user_id']})\">Delete</button>
                            </td>
                        </tr>`;
                    ";
                }
            ?>
        }

        function generatePaginationControls(currentPage, totalPages) {
            const paginationContainer = document.getElementById('paginationContainer');
            paginationContainer.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.classList.add('pagination-button');
                if (i === currentPage) {
                    button.classList.add('active');
                }
                button.addEventListener('click', () => {
                    resetTable(i);
                    // Optionally, you can highlight the active button here
                });
                paginationContainer.appendChild(button);
            }
        }

        // Assuming you have the total number of pages available (totalPages) and the current page (currentPage)
        generatePaginationControls(<?php echo $page; ?>, <?php echo ceil(count($patients) / $perPage); ?>);




    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="assets/javascript/app.js"></script>
</body>

</html>