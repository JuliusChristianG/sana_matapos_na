<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Staff Radtech') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}


// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';


include('connection.php'); // Include your PDO connection file.

function getPendingXRayRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM xrayrequest WHERE status = 'Pending'");
        $stmt->execute();
        $xrayrequestsPending = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $xrayrequestsPending;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

$xrayrequestsPending = getPendingXRayRequests($pdo);

// Calculate the number of pending requests
$pendingCount = count($xrayrequestsPending);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Staff Radtech Profile Page</title>
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
            <li class="active">
                <a href="staffRadtechProfilepage.php">
                    <i class="bx bx-user-circle"></i>

                    <span class="link_name">
                        <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                    </span>
                </a>
                <span class="tooltip">
                    <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                </span>
            </li>
           
         

            <li>
                <a href="staffRadtechPatientRecordsTable.php">
                <i class='bx bx-book-alt'></i>

                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>

            <li>
                <a href="PatientRequestsTable.php">
                    <i class='bx bx-clipboard'></i>
                    <span class="link_name">Patient Request</span>
                    <?php if ($pendingCount > 0): ?>
                        <span
                            style="background-color: red; color: white; border-radius: 50%; padding: 2px 5px; position: absolute; top: 5px; right: 5px; font-size: 12px;">
                            <?php echo $pendingCount; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <span class="tooltip">Patient Request</span>
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
                        <span class="admin_text">Staff-Radtech Account</span>
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

    <!-- Start of main content-->
    <section class="home-section">



        <div class="container light-style flex-grow-1 container-p-y">
            <br>

            <div class="col">
                <center>
                    <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
                </center>
            </div>

            <br>

            <h1 class="font-weight-bold py-3 mb-4" style="font-size: 30px;">
                Account settings
            </h1>
            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list"
                                href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-change-password">Change password</a>

                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="account-general">

                                <hr class="border-light m-0">
                                <form id="editProfile" action="editProfileRadtech.php" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">Username</label>
                                            <input name="username" type="text" class="form-control mb-1"
                                                style="width: 550px;" value="<?php echo $_SESSION['username']; ?>"
                                                pattern="[^\s]+" title="Username cannot contain spaces" required>
                                        </div>

                                        <br>
                                        <div class="form-group">
                                            <label class="form-label">First Name</label>
                                            <input name="first_name" type="text" class="form-control"
                                                style="width: 550px;" value="<?php echo $_SESSION['first_name']; ?>">
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class="form-label">Last Name</label>
                                            <input name="last_name" type="text" class="form-control"
                                                style="width: 550px;" value="<?php echo $_SESSION['last_name']; ?>">
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class="form-label">E-mail</label>
                                            <input type="text" class="form-control mb-1" style="width: 350px;" disabled
                                                value="<?php echo $_SESSION['email']; ?>">
                                        </div>
                                        <div class="form-group d-flex justify-content-end"> <!-- Add this line -->
                                            <button
                                                class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 hover:text-white"
                                                type="submit">Submit</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
                                integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm"
                                crossorigin="anonymous" />



                            <div class="tab-pane fade" id="account-change-password">
                                <form action="change_password.php" method="post" onsubmit="return validatePassword()">
                                    <div class="card-body pb-2">
                                        <div class="form-group">
                                            <label class="form-label">Current password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="current_password"
                                                    id="current_password" style="width: 550px;" pattern="[^\s]+"
                                                    placeholder="Current Password" required>

                                                <span class="input-group-text">
                                                    <i class="fas fa-eye" id="toggle_current_password"></i>
                                                </span>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">New password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="new_password"
                                                    id="new_password" style="width: 550px;" pattern="[^\s]+"
                                                    placeholder="New Password" required>

                                                <span class="input-group-text">
                                                    <i class="fas fa-eye" id="toggle_new_password"></i>
                                                </span>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Repeat new password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="confirm_password"
                                                    id="confirm_password" style="width: 550px;" pattern="[^\s]+"
                                                    placeholder="Retype New Password" required>

                                                <span class="input-group-text">
                                                    <i class="fas fa-eye" id="toggle_confirm_password"></i>
                                                </span>

                                            </div>
                                        </div>
                                        <div class="text-right mt-3">
                                            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg"
                                                id="saveChangesBtn">Save changes</button>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal for Logout Confirmation -->
                            <div id="logoutModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
                                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
                                <div
                                    class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                                    <!-- Add modal content here -->
                                    <div class="modal-content py-4 text-left px-6">
                                        <!-- Title -->
                                        <div class="text-3xl font-bold mb-2">Logging Out</div>
                                        <!-- Message -->
                                        <p class="text-gray-700 mb-6">Are you sure you want to logout?</p>
                                        <!-- Buttons -->
                                        <div class="flex justify-end items-center space-x-4">
                                            <button id="cancelLogout"
                                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Cancel</button>
                                            <!-- Add an ID to the Yes button for JavaScript handling -->
                                            <button id="confirmLogout"
                                                class="px-4 py-2 bg-red-500 text-white rounded">Yes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




    </section>



    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold">Password Changed Successfully</h2>
            </div>
            <div class="text-center">
                <button id="closeSuccessModalBtn" class="px-4 py-2 bg-blue-500 text-white rounded">Close</button>
            </div>
        </div>
    </div>
    <div id="successProfileModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold">Account Details changed Successfully!</h2>
            </div>
            <div class="text-center">
                <button id="closeSuccessProfileModalBtn" class="px-4 py-2 bg-blue-500 text-white rounded"
                    onclick="closeSuccessProfileModal()">Close</button>
            </div>
        </div>
    </div>


    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold">Error</h2>
                <p id="errorMessage" class="text-red-500"></p>
            </div>
            <div class="text-center">
                <button id="closeErrorModalBtn" class="px-4 py-2 bg-red-500 text-white rounded">Close</button>
            </div>
        </div>
    </div>

    <!-- Error Format Modal -->
    <div id="errorFormatModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold">New Password must be at least 8 characters long and include uppercase,
                    lowercase, numbers, and special characters (@$!%*?&).</h2>
                <p id="errorFormatMessage" class="text-red-500"></p>
            </div>


            <div class="text-center">
                <button id="closeErrorFormatModalBtn" class="px-4 py-2 bg-red-500 text-white rounded">Close</button>
            </div>
        </div>
    </div>

    <!-- Error Empty Modal -->
    <div id="errorEmptyModal" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold">Error</h2>
                <p id="errorEmptyMessage" class="text-red-500"></p>
            </div>
            <div class="text-center">
                <button id="closeErrorEmptyModalBtn" class="px-4 py-2 bg-red-500 text-white rounded">Close</button>
            </div>
        </div>
    </div>


    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"></script>
    <script src="assets/javascript/app.js"></script>

    <script>
        function openProfileSuccessModal() {
            document.getElementById('successProfileModal').classList.remove('hidden');
        }

        function closeSuccessProfileModal() {
            document.getElementById('successProfileModal').classList.add('hidden');
            location.reload(); // Reload the page
        }

        document.getElementById('editProfile').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting normally

            // Send the form data via AJAX
            var formData = new FormData(this);

            fetch('editProfile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        openProfileSuccessModal();

                    } else {
                        alert('Update failed'); // Display failure alert
                    }
                })
                .catch(error => console.error('Error:', error));
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Function to toggle password visibility
            function togglePasswordVisibility(inputId, toggleId) {
                var input = document.getElementById(inputId);
                var toggle = document.getElementById(toggleId);

                toggle.addEventListener('click', function () {
                    if (input.type === 'password') {
                        input.type = 'text';
                        toggle.classList.remove('fa-eye');
                        toggle.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        toggle.classList.remove('fa-eye-slash');
                        toggle.classList.add('fa-eye');
                    }
                });
            }

            // Toggle visibility for current password
            togglePasswordVisibility('current_password', 'toggle_current_password');

            // Do the same for new password and repeat new password fields
            togglePasswordVisibility('new_password', 'toggle_new_password');
            togglePasswordVisibility('confirm_password', 'toggle_confirm_password');
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


        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('saveChangesBtn').addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the form from submitting (default behavior)

                var currentPassword = document.getElementById('current_password').value;
                var newPassword = document.getElementById('new_password').value;
                var confirmPassword = document.getElementById('confirm_password').value;

                // Check if any of the fields are empty
                if (!currentPassword || !newPassword || !confirmPassword) {
                    // Show error modal for empty fields
                    document.getElementById('errorEmptyMessage').innerText = "All fields are required.";
                    document.getElementById('errorEmptyModal').classList.remove('hidden');
                    return; // Exit the function
                }

                // Validate the format of the new password
                var passwordFormat = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!newPassword.match(passwordFormat)) {
                    // Show error modal for invalid password format
                    document.getElementById('errorFormatMessage').innerText = "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters (@$!%*?&).";
                    document.getElementById('errorFormatModal').classList.remove('hidden');
                    return; // Exit the function
                }

                if (currentPassword === newPassword) {
                    // Show error modal for same passwords
                    document.getElementById('errorMessage').innerText = "Current and new passwords cannot be the same.";
                    document.getElementById('errorModal').classList.remove('hidden');
                    return; // Exit the function
                }

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'change_password.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Show the success modal
                            document.getElementById('successModal').classList.remove('hidden');

                            // Clear input fields
                            document.getElementById('current_password').value = '';
                            document.getElementById('new_password').value = '';
                            document.getElementById('confirm_password').value = '';
                        } else {
                            // Show the error modal with the message
                            document.getElementById('errorMessage').innerText = response.message;
                            document.getElementById('errorModal').classList.remove('hidden');
                        }
                    }
                };
                xhr.send('current_password=' + currentPassword + '&new_password=' + newPassword + '&confirm_password=' + confirmPassword);
            });

            // Function to hide the modals
            function hideModals() {
                document.getElementById('successModal').classList.add('hidden');
                document.getElementById('errorModal').classList.add('hidden');
                document.getElementById('errorFormatModal').classList.add('hidden');
                document.getElementById('errorEmptyModal').classList.add('hidden');
            }

            // Close modal when close button is clicked
            document.getElementById('closeSuccessModalBtn').addEventListener('click', function () {
                document.getElementById('successModal').classList.add('hidden');
            });

            document.getElementById('closeErrorModalBtn').addEventListener('click', function () {
                document.getElementById('errorModal').classList.add('hidden');
            });

            document.getElementById('closeErrorFormatModalBtn').addEventListener('click', function () {
                document.getElementById('errorFormatModal').classList.add('hidden');
            });

            document.getElementById('closeErrorEmptyModalBtn').addEventListener('click', function () {
                document.getElementById('errorEmptyModal').classList.add('hidden');
            });

        });
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Show the success modal
                    document.getElementById('successModal').classList.remove('hidden');

                    // Clear input fields
                    document.getElementById('current_password').value = '';
                    document.getElementById('new_password').value = '';
                    document.getElementById('confirm_password').value = '';
                } else {
                    // Show the error modal with the message
                    document.getElementById('errorMessage').innerText = response.message;
                    document.getElementById('errorModal').classList.remove('hidden');
                }
            }
        };


    </script>

</body>

</html>