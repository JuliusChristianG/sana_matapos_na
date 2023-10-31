<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
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

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

    // Calculate the offset to retrieve the appropriate set of patients for the current page.
    $patientsPerPage = 10;
    $pageNumber = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($pageNumber - 1) * $patientsPerPage;

    if (empty($search)) {
        $query = "SELECT *
                  FROM patients 
                  WHERE is_recorded = 1 
                  AND dateAdded >= DATE_SUB(NOW(), INTERVAL 2 YEAR)";

        if ($filter === 'dateAdded_newest') {
            $query .= " ORDER BY dateAdded DESC";
        } elseif ($filter === 'dateAdded_oldest') {
            $query .= " ORDER BY dateAdded ASC";
        } elseif ($filter === 'age_youngest') {
            $query .= " ORDER BY age ASC";
        } elseif ($filter === 'age_oldest') {
            $query .= " ORDER BY age DESC";
        } elseif ($filter === 'lname_a_z') {
            $query .= " ORDER BY last_name ASC";
        } elseif ($filter === 'lname_z_a') {
            $query .= " ORDER BY last_name DESC";
        } else {
            // Default sorting by recently added user_id in descending order
            $query .= " ORDER BY user_id DESC";
        }

        $query .= " LIMIT :limit OFFSET :offset";


        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $patientsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $activePatients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Build the search query for active patients
        $query = "SELECT * FROM patient_findings WHERE lname LIKE :search AND date_consulted >= DATE_SUB(NOW(), INTERVAL 2 YEAR) LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':search', "%$search%");
        $stmt->bindValue(':limit', $patientsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $activePatients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count the total number of active patients for pagination.
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM patient_findings WHERE date_consulted >= DATE_SUB(NOW(), INTERVAL 2 YEAR)");
    $countStmt->execute();
    $totalPatients = (int) $countStmt->fetchColumn();

    // Calculate the total number of pages based on the total patients and patients per page.
    $totalPages = ceil($totalPatients / $patientsPerPage);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}


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
    <title> Patient Records Table Archived</title>
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
            <li class="">
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


            <li class="active">
                <a href="staffRadtechPatientRecordsTable.php">
                    <i class='bx bx-book-alt'></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>



            <li class="">
                <a href="staffRadtechPatientRequestsTable.php">
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
    <section class="home-section">
        <div class="container">
            <br>
            <br>
            <div class="card shadow rounded">
                <br>
                <div class="row align-items-center">
                    <div class="col">
                        <center>
                            <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
                        </center>
                    </div>
                </div>
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between mb-3"> <!-- Adjusted this line -->

                        <div class="form-group ml-2"> <!-- Adjusted this line -->
                            <input type="text" name="search" id="search"
                                class="form-control rounded-pill smaller-search" placeholder="Search"
                                style="width: 250px; height: 40px;">
                            <br>
                        </div>

                        <div class="d-flex"> <!-- Added a div container for Export and Archive buttons -->
                            <div class="ml-2">
                                <a href="export.php"
                                    class="btn btn-success btn-lg rounded-pill px-4 py-2 text-white">Export</a>
                            </div>
                            <div class="ml-2">
                                <a href="staffRadtechPatientRecordsTableArchived.php"
                                    class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-[#0126CC] text-white hover:bg-blue-600 hover:text-white">Archived
                                    Records</a>
                            </div>
                        </div>

                    </div>


                    <div class="flex items-center">
                        <i class="fas fa-filter"></i>
                        <label for="filterSelect" class="mr-1">Filter By:</label>

                        <select id="filterSelect" class="outline outline-offset-0.3 text-sm rounded-md">|

                            <option value="dateAdded_newest">Date Added - Newest to Oldest</option>
                            <option value="dateAdded_oldest">Date Added - Oldest to Newest</option>
                            <option value="age_youngest">Age - Youngest to Oldest</option>
                            <option value="age_oldest">Age - Oldest to Youngest</option>
                            <option value="lname_a_z">Surname - A to Z</option>
                            <option value="lname_z_a">Surname - Z to A</option>
                        </select>
                    </div>


                    <div class="card-body">
                        <div class="text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">PATIENT RECORD TABLE ACTIVE</h2>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="py-3 px-3 rounded-tl text-center">User ID:</th>
                                        <th class="px-3 text-center">First Name</th>
                                        <th class="px-3 text-center">Last Name</th>
                                        <th class="px-3 text-center">Middle Name</th>
                                        <th class="px-3 text-center">Phone Number</th>
                                        <th class="px-3 text-center">Gender</th>
                                        <th class="px-3 text-center">Age</th>
                                        <th class="px-3 text-center">Date Added</th>
                                        <th class=" px-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="patientTableBody">


                                    <?php foreach ($activePatients as $patient) { ?>
                                        <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                            <td class="py-3 px-3">
                                                <div class="text-center">ID:
                                                    <?php echo $patient['user_id']; ?>
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
                                                    <?php echo $patient['mname']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['mobileNumber']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['gender']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['age']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="text-center">
                                                    <?php echo $patient['dateAdded']; ?>
                                            </td>


                                            <td>
                                                <input type="hidden" name="user_id"
                                                    value="<?php echo $patient['user_id']; ?>">
                                                <div class="text-center">
                                                    <button name="viewButtonPatientRadtech" type="button"
                                                        class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm view-btn"
                                                        data-user-id="<?php echo $patient['user_id']; ?>"
                                                        onclick="openMedicalHistoryModal(this)">
                                                        View
                                                    </button>




                                            </td>




                            </div>
                            </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                        </table>
                    </div>
                    <div id="noResultsMessage" class="text-center text-danger bg-gray-200 p-3 mb-3"
                        style="display: none;">
                        No results found.
                    </div>

                    <div class="text-center mt-3">
                        <?php
                        $filterParam = isset($filter) ? "&filter=$filter" : ""; // Include the filter parameter if it's set.
                        
                        if (isset($pageNumber) && $pageNumber > 1):
                            ?>
                            <a href="?page=<?php echo $pageNumber - 1; ?><?php echo $filterParam; ?>"
                                class="btn btn-primary"><i class="fas fa-chevron-left"></i></a>
                        <?php endif; ?>

                        <span>Page
                            <?php echo isset($pageNumber) ? $pageNumber : 1; ?> of
                            <?php echo isset($totalPages) ? $totalPages : 1; ?>
                        </span>

                        <?php if (isset($pageNumber) && isset($totalPages) && $pageNumber < $totalPages): ?>
                            <a href="?page=<?php echo $pageNumber + 1; ?><?php echo $filterParam; ?>"
                                class="btn btn-primary"><i class="fas fa-chevron-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


        <style>
            /* Medical History Modal */
            #MedicalHistoryModal {
                /* Add your modal styles here */
            }

            .modal-overlay {
                /* Add overlay styles here */
            }

            .modal-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            .modal-content {
                /* Add content styles here */
            }

            .modal-close-btn {
                position: absolute;
                top: 10px;
                right: 10px;
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
            }

            /* Table Header Color */
            table th {
                background-color: #0126CC;
                /* Set the desired color */
                color: #ffffff;
                /* Set the text color */
            }
        </style>

        <style>
            .modal-content {
                /* Add padding, margin, font size, etc. as needed */
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('search');
                const noResultsMessage = document.getElementById('noResultsMessage');

                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.trim().toLowerCase();
                    filterTable(searchTerm);
                });
            });

            function filterTable(searchTerm) {
                const rows = document.querySelectorAll('#patientTableBody tr');
                let found = false;

                rows.forEach(function (row) {
                    const firstName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const lastName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                    if (firstName.includes(searchTerm) || lastName.includes(searchTerm)) {
                        row.style.display = '';
                        found = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (found) {
                    noResultsMessage.style.display = 'none';
                } else {
                    noResultsMessage.style.display = 'block';
                }
            }
        </script>



        <!-- Modal Medical History -->
        <div id="MedicalHistoryModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            <div class="modal-container bg-white w-3/4 md:max-w-3xl mx-auto rounded shadow-lg z-50 overflow-y-auto">

                <div class="modal-content py-4 text-left px-6">
                    <?php
                    // Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
                    $dsn = 'mysql:host=localhost;dbname=mylabclinic';
                    $username = 'root';
                    $password = '';

                    try {
                        $pdo = new PDO($dsn, $username, $password);
                        // Set PDO error mode to exception.
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Check if user_id is provided in the URL
                        if (isset($_GET['user_id'])) {
                            $user_id = $_GET['user_id'];

                            // Define the SQL query
                            $query = "SELECT appointment_id, user_id, xray_type, date_consulted FROM patient_findings WHERE user_id = :user_id";

                            // Prepare and execute the query
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':user_id', $user_id);
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            // Handle the case where user_id is not provided in the URL.
                            echo "User ID is missing.";
                        }
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                        die();
                    }
                    ?>


                    <!-- Medical History Table -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Medical History</h2>

                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Appointment ID</th>
                                    <th class="py-2 px-4 border-b">X Ray Type</th>
                                    <th class="py-2 px-4 border-b">Date Consulted</th>
                                    <th class="py-2 px-4 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Use PHP to dynamically populate the table rows -->
                                <?php foreach ($results as $result) { ?>
                                    <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                        <td class="py-3 px-3">ID -
                                            <?php echo $result['appointment_id']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $result['xray_type']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $result['date_consulted']; ?>
                                        </td>
                                        <td class="px-3">
                                            <form action="staffRadtechViewPatient.php" method="get"
                                                class="flex items-center">
                                                <input type="hidden" name="user_id"
                                                    value="<?php echo $result['user_id']; ?>">
                                                <input type="hidden" name="appointmentID"
                                                    value="<?php echo $result['appointment_id']; ?>">
                                                <button name="viewButtonPatientRadtech" type="submit"
                                                    class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm view-btn">
                                                    View</button>
                                            </form>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                            onclick="closeMedicalHistoryModal()">Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <script>
        // Medical History Modal function to open 
        function openMedicalHistoryModal(button) {
            const user_id = button.getAttribute('data-user-id');

            // Get the modal and overlay elements
            const modal = document.getElementById('MedicalHistoryModal');
            const overlay = modal.querySelector('.modal-overlay');

            // Show the modal and overlay
            modal.classList.remove('hidden');
            overlay.classList.remove('hidden');

            // Use AJAX to fetch data based on user_id
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `fetch_medical_history.php?user_id=${user_id}`, true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Load the response into the modal content
                    const modalContent = modal.querySelector('.modal-content');
                    modalContent.innerHTML = xhr.responseText;
                }
            };

            xhr.send();
        }



        // Function to close the Medical History Modal
        function closeMedicalHistoryModal() {
            document.getElementById('MedicalHistoryModal').classList.add('hidden');
        }

        // Add an event listener to the view button to open the modal
        document.querySelectorAll('.view-btn').forEach(item => {
            item.addEventListener('click', event => {
                openModal();
            });
        });

        // Add an event listener to the modal close button to close the modal
        document.querySelectorAll('.modal-close-btn').forEach(item => {
            item.addEventListener('click', event => {
                closeModal();
            });
        });
        // Get the search input element
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        let prevSearchTerm = '';




        // Update filter select with the chosen filter
        const filterSelect = document.getElementById('filterSelect');
        const currentFilter = "<?php echo $filter; ?>"; // Get the current filter from PHP
        if (currentFilter) {
            filterSelect.value = currentFilter;
        }

        // Attach the filter change event listener
        filterSelect.addEventListener('change', () => {
            const selectedFilter = filterSelect.value;
            const currentUrl = new URL(window.location.href);

            currentUrl.searchParams.set('filter', selectedFilter);
            window.location.href = currentUrl.toString();
        });
    </script>
    <script>
        document.getElementById('exportButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default behavior of the link
            // Send an AJAX request to the export script
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'export_to_csv.php', true);
            xhr.send();
        });
    </script>


    <script src="assets/javascript/app.js"></script>
</body>

</html>