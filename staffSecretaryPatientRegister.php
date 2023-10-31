<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}


include('connection.php');


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



try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch patients data with 'Patient' role from the database.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Patient'");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Patient Registration</title>
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

    <style>
        .highlighted-text1 {
            background-color: #0126CC;

            padding: 10px;

            margin-top: 20px;

        }
    </style>
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
            <a href="staffSecretaryProfilepage.php">
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
                <a href="staffSecretaryPatientRegister.php">
                    <i class='bx bxs-user-account'></i>
                    <span class="link_name">Patient Registration</span>
                </a>
                <span class="tooltip">Patient Registration</span>
            </li>

            <li>
                <a href="staffSecretaryPatientRequestsTable.php">
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
                        <span class="admin_text">Staff Radtech Account</span>
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


                    <div class="card-body">
                        <div class="card-body text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">Patient Registration</h2>
                            </div>
                            <br>
                            <form id="signupForm" action="registerSecretary.php" method="POST" class="row g-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label"><b>Username</b></label>
                                    <input type="text" name="username" id="username" required
                                        class="form-control rounded-pill" placeholder="Username">
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label"><b>Password</b></label>
                                    <input type="password" name="password" id="password" required
                                        class="form-control rounded-pill" placeholder="Password">
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label"><b>Confirm Password</b></label>
                                    <input type="password" name="confirm_password" id="confirm_password" required
                                        class="form-control rounded-pill" placeholder=" Confirm Password">
                                </div>
                                <div class="col-md-6">
                                    <label for="fname" class="form-label"><b>First Name</b></label>
                                    <input type="text" name="fname" id="fname" required
                                        class="form-control rounded-pill" placeholder="First Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="form-label"><b>Last Name</b></label>
                                    <input type="text" name="lname" id="lname" required
                                        class="form-control rounded-pill" placeholder="Last Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="middlename" class="form-label"><b>Middle Name</b></label>
                                    <input type="text" name="mname" id="mname" required
                                        class="form-control rounded-pill" placeholder="Middle Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label"><b>Email</b></label>
                                    <input type="text" name="email" id="email" required
                                        class="form-control rounded-pill" placeholder="Email">
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label"><b>Address</b></label>
                                    <input type="text" pattern="^[^0-9]+$" id="address" placeholder="Address"
                                        name="address" class="form-control rounded-pill" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="age" class="form-label"><b>Age</b></label>
                                    <input type="number" pattern="^[^0-9]+$" id="age" placeholder="Age" name="age"
                                        class="form-control rounded-pill" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="birthday" class="form-label"><b>Birthday</b></label>
                                    <input type="date" name="birthday" id="birthday" required
                                        class="form-control rounded-pill" placeholder="Birthday">
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label"><b>Gender</b></label>
                                    <select name="gender" required class="form-control rounded-pill">
                                        <option disabled selected value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="mobileNum" class="form-label"><b>Phone Number</b></label>
                                    <input type="tel" name="mobileNum" maxlength="11" required
                                        class="form-control rounded-pill" placeholder="Mobile Number">
                                </div>

                                <div class="col-12 text-center mt-3">
                                    <button
                                        class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-lg"
                                        type="submit" value="Register" id="register-btn">Register</button>
                                </div>
                            </form>

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
        <div class="container my-5">
            <div class="card shadow rounded">
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3"></h4>


                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="text-center">
                                <div class="highlighted-text1 text-white">
                                    <h2 class="text-xl font-bold py-1 pl-3">Patient Accounts</h2>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="px-3 text-center">Username</th>
                                        <th class="px-3 text-center">First Name</th>
                                        <th class="px-3 text-center">Last Name</th>

                                        <th class="px-3 text-center">Date Added</th>
                                        <th class="rounded-tr px-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                                <div class="text-center">
                                                    <?php echo $patient['username']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['first_name']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['last_name']; ?>
                                            </td>

                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['dateAdded']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <button
                                                        class="create-request-button ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm"
                                                        data-user-id="<?= $patient['user_id'] ?>"
                                                        data-first-name="<?= $patient['first_name'] ?>"
                                                        data-last-name="<?= $patient['last_name'] ?>">
                                                        Create Request
                                                    </button>

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
                            echo '<a href="?table=' . ($currentTable - 1) . $filterParam . '" class="btn btn-primary" style="vertical-align: middle;"><i class="fas fa-chevron-left"></i></a>';
                        }
                        echo '<span class="page-number" style="vertical-align: middle;"> Page ' . $currentTable . ' of ' . $totalTables . '</span>';
                        if ($currentTable < $totalTables) {
                            echo '<a href="?table=' . ($currentTable + 1) . $filterParam . '" class="btn btn-primary" style="vertical-align: middle;"><i class="fas fa-chevron-right"></i></a>';
                        }
                        echo '</div>';
                        ?>
                    </div>
                </div>
            </div>

            <div id="passwordModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

                <div
                    class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

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



    </section>

    <script>
        document.querySelectorAll('.create-request-button').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.userId;
                const firstName = this.dataset.firstName;
                const lastName = this.dataset.lastName;

                // Get the current date and time
                const now = new Date();
                const formattedDate = now.toISOString().slice(0, 19).replace('T', ' '); // Format: YYYY-MM-DD HH:MM:SS

                // Send the data via AJAX to the server
                fetch('StaffSecretaryXrayRequest.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `user_id=${userId}&first_name=${firstName}&last_name=${lastName}&appointment_schedule=${formattedDate}`
                })
                    .then(response => response.text())
                    .then(data => {
                        alert(data); // Display the server response (you can handle it differently)
                    })
                    .catch(error => console.error('Error:', error));
            });
        });


    </script>

    <script>
        document.querySelector('form').addEventListener('submit', onSubmitForm);
        function validatePassword(password) {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return regex.test(password);
        }

        function onSubmitForm(event) {
            const passwordInput = document.querySelector('input[name="password"]');
            const password = passwordInput.value;

            if (!validatePassword(password)) {
                event.preventDefault();

                const modal = document.getElementById('passwordModal');
                modal.classList.remove('hidden');
            }
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
        }







    </script>

    <script src="assets/javascript/app.js"></script>
</body>

</html>