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
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch doctors data with 'Doctor' role from the database.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Doctor' ORDER BY dateAdded DESC");
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Add Doctors</title>

    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">
    <!-- styles css -->
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons for sidebar Icons -->

    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
    <link rel="icon" href=assets/images/mylablogo.png type="image/x-icon">


<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">



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
                    <h4 class="text-lg font-bold py-1 pl-3">Doctor's Information</h4>
                    <hr class="mb-4">

                    <form action="addDoctors.php" method="POST" class="row g-3" id="addDoctorForm">

                        <div class="col-md-6">
                            <label for="username" class="form-label"><b>Username</b></label>
                            <input type="text" name="username" required class="form-control rounded-pill"
                                placeholder="Username">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label"><b>Password</b></label>
                            <input type="password" name="password" required class="form-control rounded-pill"
                                placeholder="Password">
                        </div>
                        <div class="col-md-6">
                            <label for="fname" class="form-label"><b>First Name</b></label>
                            <input type="text" name="fname" required class="form-control rounded-pill"
                                placeholder="First Name">
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label"><b>Last Name</b></label>
                            <input type="text" name="lname" required class="form-control rounded-pill"
                                placeholder="Last Name">
                        </div>

                       <div class="col-md-6">
                            <label for="email" class="form-label"><b>Email</b></label>
                            <input type="email" name="email" class="form-control rounded-pill" placeholder="Email"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label"><b>Position</b></label>
                            <select name="position" required class="form-select rounded-pill">
                                <option value="Doctor" selected>Doctor</option>
                            </select>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" onclick="checkFieldsAndOpenConfirmationModal()"
                                class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 hover:text-white">Add
                                Doctor</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container my-5">

            <div class="card shadow rounded">
                <div class="card-header bg-white">

                    <h4 class="text-lg font-bold py-1 pl-3">Doctors List</h4>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                <tr>
                                    
                                    <th class="px-3">Username</th>
                                    <th class="px-3">First Name</th>
                                    <th class="px-3">Last Name</th>
                                    
                                    <th class="px-3">Email</th>
                                    <th class="px-3">Date Added</th>
                                    
                                    <th class="rounded-tr px-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                foreach ($doctors as $doctor) { ?>
                                    <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                        <td class="py-3 px-3">
                                            <?php echo $doctor['username']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $doctor['first_name']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $doctor['last_name']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $doctor['email']; ?>
                                        </td>
                                        
                                        <td class="py-3 px-2">
                                            <?php echo $doctor['dateAdded']; ?>
                                        </td>
                                        
                                        <td class="py-3 px-2">

                                            <button
                                                class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm"
                                                onclick='openEditDoctorModal(<?php echo json_encode($doctor); ?>)'>Edit</button>

                                            </a>
                                            <button
                                                class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm"
                                                onclick="openDeleteConfirmationModal(<?php echo $doctor['user_id']; ?>)">Delete</button>

                                            </a>


                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- edit CONFIRMATION MODAL -->
        <div id="editDoctorModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">

            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

                <!-- Form for editing the patient -->
                <div class="modal-content py-4 text-left px-6">
                    <h2 class="text-2xl font-semibold mb-4">Edit Doctor's Information</h2>

                    <!-- Add your form for editing the patient here -->
                    <form action="editUser.php" method="POST" id="editForm">
                        <input type="hidden" name="editDoctorId" id="editDoctorId" value="">
                        
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


                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                                onclick="closeEditDoctorModal()">Cancel</button>
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

                <p class="mb-8">Are you sure you want to delete this doctor?</p>
                <input type="hidden" id="deleteUserId" value="">

                <div class="flex justify-center">
                    <button class="bg-red-500 text-white py-2 px-4 rounded-lg mr-2"
                        onclick="deleteDoctor()">Delete</button>
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

        <!-- Confirmation Modal for Add User -->
        <div id="confirmationforadduser"
            class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-700 bg-opacity-75">
            <div class="modal-box bg-white p-6 rounded shadow-lg w-1/2 text-center">
                <h2 class="text-xl font-bold mb-4">Confirm Details</h2>
                <p class="text-gray-700 mb-4">Are you sure all details are correct?</p>
                <button id="confirmationYesButton" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Yes</button>

                <button onclick="closeConfirmationModalforadduser()"
                    class="bg-red-500 text-white py-2 px-4 rounded-lg">No</button>
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

        <div id="confirmationForAddDoctor"
            class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-700 bg-opacity-75">
            <div class="modal-box bg-white p-6 rounded shadow-lg w-1/2 text-center">
                <h2 class="text-xl font-bold mb-4">Confirm Details</h2>
                <p class="text-gray-700 mb-4">Are you sure all details are correct?</p>
                <button id="confirmationYesButtonAddDoctor"
                    class="bg-blue-500 text-white py-2 px-4 rounded-lg">Yes</button>
                <button onclick="closeConfirmationModalForAddDoctor()"
                    class="bg-red-500 text-white py-2 px-4 rounded-lg">No</button>
            </div>
        </div>
        <div id="errorModal"
            class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-700 bg-opacity-75">
            <div class="modal-box bg-white p-6 rounded shadow-lg w-1/2 text-center">
                <h2 class="text-xl font-bold mb-4">Error</h2>
                <p class="text-gray-700 mb-4">Please fill in all fields.</p>
                <button onclick="closeErrorModal()" class="bg-blue-500 text-white py-2 px-4 rounded-lg">OK</button>
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





        document.querySelector('form').addEventListener('submit', function (event) {
            const fname = document.getElementsByName('fname')[0].value;
            const lname = document.getElementsByName('lname')[0].value;
           
            const email = document.getElementsByName('email')[0].value;

            if (fname !== '' && lname !== '' && specialization !== '' && email !== '') {
                event.preventDefault(); // Prevent default form submission

                // Open the confirmation modal
                openConfirmationModalForAddDoctor();
            }
        });

        document.getElementById('confirmationYesButtonAddDoctor').addEventListener('click', function () {
            document.getElementById('addDoctorForm').submit();
        });

        function openConfirmationModalForAddDoctor() {
            document.getElementById('confirmationForAddDoctor').classList.remove('hidden');
        }

        function closeConfirmationModalForAddDoctor() {
            document.getElementById('confirmationForAddDoctor').classList.add('hidden');
        }

        document.getElementById('confirmationYesButtonAddDoctor').addEventListener('click', function () {
            document.getElementById('addDoctorForm').submit();
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

        function closeEditConfirmationModal() {
            document.getElementById('editConfirmationModal').classList.add('hidden');
        }
        document.querySelector('form').addEventListener('submit', onSubmitForm);

        // Function to open the edit doctor modal
        function openEditDoctorModal(doctorData) {
            // Populate the modal fields with doctor data
            document.getElementById('editDoctorId').value = doctorData.user_id;
            document.getElementById('editUsername').value = doctorData.username;
            document.getElementById('editFirstName').value = doctorData.first_name;
            document.getElementById('editLastName').value = doctorData.last_name;
            document.getElementById('editEmail').value = doctorData.email;


            // Show the modal
            document.getElementById('editDoctorModal').classList.remove('hidden');
        }

        // Function to close the edit doctor modal
        function closeEditDoctorModal() {
            document.getElementById('editDoctorModal').classList.add('hidden');
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
        function deleteDoctor() {
            // Get the user id from the hidden input field
            const userId = document.getElementById('deleteUserId').value;

            // Perform the deletion (you may need to use AJAX or form submission)
            window.location.href = 'deleteDoctor.php?user_id=' + userId;
        }
        
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
                    <button class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm" onclick='openEditDoctorModal(${JSON.stringify(result)})'>Edit</button>

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
                                    <td class="py-3 px-3"><?= $doctors['username'] ?></td>
                                    <td class="py-3 px-2"><?= $doctors['first_name'] ?></td>
                                    <td class="py-3 px-2"><?= $doctors['last_name'] ?></td>
                                    <td class="py-3 px-2"><?= $doctors['role'] ?></td>
                                    <td class="py-3 px-2"><?= $doctors['dateAdded'] ?></td>
                                    <td class="py-3 px-2">
                                        <button class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm" onclick='openEditUserModal(<?= json_encode($doctors); ?>)'>Edit</button>
                                        <button class="ml-1 rounded-lg bg-red-500 px-4 text-white hover:bg-red-600 hover:text-white p-2 text-sm" onclick="openDeleteConfirmationModal(<?= $doctors['user_id'] ?>)">Delete</button>
                                    </td>
                                </tr>`;
            <?php endforeach; ?>
        }
    </script>

    <script src="assets/javascript/app.js"></script>
</body>

</html>