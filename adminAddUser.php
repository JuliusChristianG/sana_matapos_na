<?php
session_start();

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
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users data with 'Staff' and 'Admin' roles from the database who are verified.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role IN ('Staff', 'Admin', 'Doctor') AND is_verified = 1 AND is_deleted = 0 ORDER BY user_id DESC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Add Users</title>



    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">
    <!-- styles css -->
    <link rel="stylesheet" href="assets/css/sidebar.css">


    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons for sidebar Icons -->
    <link href="assets/HomePage_Assets/boxicons/css/boxicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
    <link href="assets/images/logo-no-bg.png" rel="icon">

    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">



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

            <li class="">
                <a href="adminAddPatient.php">
                    <i class="bx bx-user-plus"></i>
                    <span class="link_name">Patient Accounts</span>
                </a>
                <span class="tooltip">Patient Accounts</span>
            </li>
            <li class="active">
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
                    <h4 class="text-lg font-bold py-1 pl-3">ADD USER</h4>
                    <hr class="mb-4">

                    <form id="addUserForm" action="addUsers.php" method="POST" class="row g-3">
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
                            <input type="text" name="email" required class="form-control rounded-pill"
                                placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label"><b>Position</b></label>
                            <select name="position" required class="form-select rounded-pill">
                                <?php if ($user['role'] == 'Admin') { ?>
                                    <option value="Admin" selected>Admin</option>
                                    <option value="Staff Doctor">Staff Doctor</option>
                                    <option value="Staff Radtech">Staff Radtech</option>
                                    <option value="Staff Secretary">Staff Secretary</option>
                                <?php } elseif ($user['role'] == 'Staff Doctor') { ?>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff Doctor" selected>Staff Doctor</option>
                                    <option value="Staff Radtech">Staff Radtech</option>
                                    <option value="Staff Secretary">Staff Secretary</option>
                                <?php } elseif ($user['role'] == 'Staff Radtech') { ?>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff Doctor">Staff Doctor</option>
                                    <option value="Staff Radtech" selected>Staff Radtech</option>
                                    <option value="Staff Secretary">Staff Secretary</option>
                                <?php } elseif ($user['role'] == 'Staff Secretary') { ?>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff Doctor">Staff Doctor</option>
                                    <option value="Staff Radtech">Staff Radtech</option>
                                    <option value="Staff Secretary" selected>Staff Secretary</option>
                                <?php } else { ?>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff Doctor">Staff Doctor</option>
                                    <option value="Staff Radtech">Staff Radtech</option>
                                    <option value="Staff Secretary">Staff Secretary</option>
                                <?php } ?>
                            </select>
                        </div>


                        <div class="col-12 text-center mt-3">
                            <button
                                class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 hover:text-white"
                                type="submit">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <div class="card shadow rounded">
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">USER ACCOUNTS TABLE</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <div class="form-group">

                                <input type="text" name="search" id="search"
                                    class="form-control rounded-pill smaller-search" placeholder="Search by Last Name"
                                    style="width: 250px; height: 40px;">

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
                            <tbody id="userTableBody">
                                <?php
                                // Assuming $users is an array containing all user records
                                
                                $usersPerPage = 10; // Number of users per table
                                $totalUsers = count($users); // Total number of users
                                
                                // Calculate the current table and total tables based on the current page
                                $currentTable = isset($_GET['table']) ? (int) $_GET['table'] : 1;
                                $totalTables = ceil($totalUsers / $usersPerPage);

                                // Define your filter parameter if needed
                                $filterParam = isset($filter) ? "&filter=$filter" : "";

                                // Calculate the start and end indices for the current table
                                $startIndex = ($currentTable - 1) * $usersPerPage;
                                $endIndex = min($startIndex + $usersPerPage, $totalUsers);

                                // Display the user table
                                for ($i = $startIndex; $i < $endIndex; $i++) {
                                    $user = $users[$i];
                                    ?>
                                    <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                        <td class="py-3 px-3">
                                            <?php echo $user['username']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $user['first_name']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $user['last_name']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $user['role']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $user['dateAdded']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <button
                                                class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm"
                                                onclick='openEditUserModal(<?= json_encode($user); ?>)'>Edit</button>
                                            <button
                                                class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm"
                                                onclick="openDeleteConfirmationModal(<?= $user['user_id']; ?>)">Delete</button>
                        </div>


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



        <!-- DELETE CONFIRMATION MODAL -->
        <div id="deleteConfirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">

                <h2 class="text-2xl font-semibold mb-4">Confirm Deletion</h2>

                <p class="mb-8">Are you sure you want to delete this user?</p>
                <input type="hidden" id="deleteUserId1" value="">

                <div class="flex justify-center">
                    <button class="bg-red-500 text-white py-2 px-4 rounded-lg mr-2"
                        onclick="deleteUser()">Delete</button>
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


        <!-- EDIT POP UP MODAL CONTENT -->
        <div id="editPatientModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                <div class="modal-content py-4 text-left px-6">
                    <h2 class="text-2xl font-semibold mb-4">Edit User</h2>
                    <form action="editUser.php" method="POST" id="editForm">
                        <input type="hidden" name="user_id" id="editUserId" value="">
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
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                                <option value="Doctor">Doctor</option>
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








        <!-- CONFIRMATION MODAL -->
        <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">
                <h2 class="text-2xl font-semibold mb-4">Confirm Changes</h2>
                <p class="mb-8">Are you sure the details are final?</p>

                <button class="bg-green-500 text-white py-2 px-4 rounded-lg mr-2" onclick="submitForm(event)">I
                    Confirm</button>

                <button class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                    onclick="closeConfirmationModal()">Cancel</button>
            </div>
        </div>



        <!-- DELETE CONFIRMATION MODAL -->
        <div id="deleteConfirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">

                <h2 class="text-2xl font-semibold mb-4">Confirm Deletion</h2>

                <p class="mb-8">Are you sure you want to delete this user?</p>
                <input type="hidden" id="deleteUserId" value="">

                <div class="flex justify-center">
                    <button class="bg-red-500 text-white py-2 px-4 rounded-lg mr-2"
                        onclick="deleteUser()">Delete</button>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                        onclick="closeDeleteConfirmationModal()">Cancel</button>
                </div>
            </div>
        </div>
        <div id="addUserConfirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 text-center p-5">
                <h2 class="text-2xl font-semibold mb-4">Confirm User Addition</h2>
                <p class="mb-8">Are you sure you want to add this user?</p>

                <button class="bg-green-500 text-white py-2 px-4 rounded-lg mr-2" onclick="confirmAddUser()">I
                    confirm</button>

                <button class="bg-blue-500 text-white py-2 px-4 rounded-lg"
                    onclick="closeAddUserConfirmationModal()">Cancel</button>
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
            <?php if (isset($_GET['registration_success']) && $_GET['registration_success'] == 'true') { ?>
                document.addEventListener('DOMContentLoaded', function () {
                    var registrationSuccessModal = document.getElementById('registrationSuccessModal');
                    if (registrationSuccessModal) {
                        registrationSuccessModal.classList.remove('hidden');

                        var countdownElement = document.getElementById('countdown');
                        var seconds = 5; // Set the number of seconds for the countdown

                        var countdownInterval = setInterval(function () {
                            seconds--;
                            if (seconds <= 0) {
                                clearInterval(countdownInterval);
                                registrationSuccessModal.classList.add('hidden');

                                // Redirect to adminAddUser.php after modal is closed
                                window.location.href = 'adminAddUser.php';
                            } else {
                                countdownElement.innerText = 'Closing in ' + seconds + ' seconds';
                            }
                        }, 1000); // Update every 1 second (1000 milliseconds)
                    }
                });
            <?php } ?>
        </script>

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


    </section>
    <script src="assets/javascript/app.js"></script>
    <script>



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

        function openAddUserConfirmationModal() {
            document.getElementById('addUserConfirmationModal').classList.remove('hidden');
        }

        function closeAddUserConfirmationModal() {
            document.getElementById('addUserConfirmationModal').classList.add('hidden');
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
                    openAddUserConfirmationModal();
                    event.preventDefault(); // Prevent the form from submitting prematurely
                }
            }
        });

        function validatePassword(password) {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return regex.test(password);
        }

        function confirmAddUser() {
            document.getElementById('addUserForm').submit();
            closeAddUserConfirmationModal();
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
        }

        // Function to open the edit patient modal
        function openEditUserModal(userData) {


            // Populate the modal fields with patient data
            document.getElementById('editUserId').value = userData.user_id;
            document.getElementById('editUsername').value = userData.username;
            document.getElementById('editFirstName').value = userData.first_name;
            document.getElementById('editLastName').value = userData.last_name;
            document.getElementById('editEmail').value = userData.email;
            document.getElementById('editPosition').value = userData.role;


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

        // Function to delete the user
        function deleteUser() {
            // Get the user id from the hidden input field
            const userId = document.getElementById('deleteUserId').value;

            // Perform the deletion (you may need to use AJAX or form submission)
            window.location.href = 'deleteUser.php?user_id=' + userId;
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

                    xhr.open('GET', `searchUsers.php?query=${searchQuery}`, true);
                    xhr.send();
                } else {
                    // If the search bar is empty, reset the table
                    resetTable();
                }
            });
        });

        function updateTable(results) {
            const tableBody = document.getElementById('userTableBody');
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
                    <button class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm" onclick='openEditUserModal(${JSON.stringify(result)})'>Edit</button>

      <button class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm" onclick="openDeleteConfirmationModal(<?= $user['user_id']; ?>)">Delete</button>
                </tr>
            `;
                });
            } else {
                html = '<tr><td colspan="6">No results found.</td></tr>';
            }

            tableBody.innerHTML = html;
        }
        function resetTable() {
            const tableBody = document.getElementById('userTableBody');
            tableBody.innerHTML = ''; // Clear the table body

            <?php foreach ($users as $user): ?>
                tableBody.innerHTML += `
                                        <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                            <td class="py-3 px-3"><?= $user['username'] ?></td>
                                            <td class="py-3 px-2"><?= $user['first_name'] ?></td>
                                            <td class="py-3 px-2"><?= $user['last_name'] ?></td>
                                            <td class="py-3 px-2"><?= $user['role'] ?></td>
                                            <td class="py-3 px-2"><?= $user['dateAdded'] ?></td>
                                            <td class="py-3 px-2">
                                                <button class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm" onclick='openEditUserModal(<?= json_encode($user); ?>)'>Edit</button>
                                                <button class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm" onclick="openDeleteConfirmationModal(<?= $user['user_id'] ?>)">Delete</button>
                                            </td>
                                        </tr>`;
            <?php endforeach; ?>
        }
    </script>


</body>

</html>