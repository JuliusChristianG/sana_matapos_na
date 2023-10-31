<?php
session_start();

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
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Patient' ORDER BY user_id DESC");
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
    <title>Doctor's Add Patient</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <span class="link_name">Doctors Dashboard</span>
                </a>
                <span class="tooltip">Doctors Dashboard</span>
            </li>
            <li class="">
                <a href="DoctorPatientRecordsTable.php">
                    <i class="bx bx-book-open"></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>

            <li class="active">
                <a href="">
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
                        <span class="doctor_text">Doctor Account</span>
                    </span>
                </a>
            </li>

        </ul>
    </div>
    <!-- Start of main content-->
    <section class="home-section">
        <br>
        
        <div class="container my-5">
            <div class="card shadow rounded">
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">PATIENT ACCOUNTS TABLE</h4>
                </div>
                <div class="col">
                            <center>
                                <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
                            </center>
                        </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <div class="form-group">

                                 <input type="text" name="search" id="search" class="form-control rounded-pill smaller-search"
                       placeholder="Search by Last Name" style="width: 250px; height: 40px;">

                                </style>

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
                                            <a href="DoctorAddRecords.php?user_id=<?php echo $patient['user_id']; ?>">
                                                <button
                                                    class="ml-1 rounded-lg bg-blue-500 px-4 text-white hover:bg-blue-600 hover:text-white p-2 text-sm">Add
                                                    Record</button>
                                            </a>
                                            
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
                        <a href="DoctorAddRecords.php?user_id=${result.user_id}">
                            <button class="ml-1 rounded-lg bg-blue-500 px-4 text-white hover:bg-blue-600 hover:text-white p-2 text-sm">Add Record</button>
                        </a>
                        
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
                                <a href=\"DoctorAddRecords.php?user_id={$patient['user_id']}\">
                                    <button class=\"ml-1 rounded-lg bg-blue-500 px-4 text-white hover:bg-blue-600 hover:text-white p-2 text-sm\">Add Record</button>
                                </a>
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