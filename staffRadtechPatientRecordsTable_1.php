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

        // Apply sorting if a filter is selected for inactive patients
        if ($filter === 'case_no_newest') {
            $query .= " ORDER BY case_no DESC";
        } elseif ($filter === 'case_no_oldest') {
            $query .= " ORDER BY case_no ASC";
        } elseif ($filter === 'lname_a_z') {
            $query .= " ORDER BY lname ASC";
        } elseif ($filter === 'lname_z_a') {
            $query .= " ORDER BY lname DESC";
        } elseif ($filter === 'dateAdded_newest') {
            $query .= " ORDER BY dateAdded DESC";
        } elseif ($filter === 'dateAdded_oldest') {
            $query .= " ORDER BY dateAdded ASC";
        } elseif ($filter === 'age_youngest') {
            $query .= " ORDER BY age ASC";
        } elseif ($filter === 'age_oldest') {
            $query .= " ORDER BY age DESC";
        } else {
            // Default sorting by recently added case_no in descending order
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
        $query = "SELECT * FROM patient_records WHERE lname LIKE :search AND dateAdded >= DATE_SUB(NOW(), INTERVAL 2 YEAR) LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':search', "%$search%");
        $stmt->bindValue(':limit', $patientsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $activePatients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count the total number of active patients for pagination.
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM patient_records WHERE dateAdded >= DATE_SUB(NOW(), INTERVAL 2 YEAR)");
    $countStmt->execute();
    $totalPatients = (int) $countStmt->fetchColumn();

    // Calculate the total number of pages based on the total patients and patients per page.
    $totalPages = ceil($totalPatients / $patientsPerPage);


} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title> Patient Records Table</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/table.css">
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


            <li>
                <a href="staffRadtechPatientRequestsTable.php">
                    <i class='bx bx-clipboard'></i>
                    <span class="link_name">Patient Request</span>
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
                    <div class="d-flex justify-content-end mb-3">
                        <form method="get">
                            <input type="text" name="search" class="outline outline-offset-2 text-sm rounded-md"
                                placeholder="Search Last Name" value="<?php echo htmlspecialchars($search); ?>"
                                oninput="if (this.value === '') this.form.submit();">
                        </form>


                        <div class="ml-2">
                            <a href="adminPatientRecordsTableArchived.php"
                                class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-[#0126CC] text-white hover:bg-blue-600 hover:text-white">Archived
                                Records</a>
                        </div>
                        <div class="ml-2">
                            <a href="export.php"
                                class="btn btn-success btn-lg rounded-pill px-4 py-2 text-white">Export</a>
                        </div>


                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-filter"></i>
                        <label for="filterSelect" class="mr-1">Filter By:</label>

                        <select id="filterSelect" class="outline outline-offset-0.3 text-sm rounded-md">|
                            <option value="case_no_newest">Case Number - Newest to Oldest</option>
                            <option value="case_no_oldest">Case Number - Oldest to Newest</option>
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
                                <h2 class="text-xl font-bold py-1 pl-3">PATIENT RECORD TABLE COMPLETED</h2>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="py-3 px-3 rounded-tl">User ID:</th>
                                        <th class="px-3">First Name</th>
                                        <th class="px-3">Last Name</th>
                                        <th class="px-3">Middle Name</th>
                                        <th class="px-3">Phone Number</th>
                                        <th class="px-3">Gender</th>
                                        <th class="px-3">Age</th>
                                        <th class="px-3">Date Added</th>
                                        <th class=" px-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activePatients as $patient) { ?>
                                        <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                            <td class="py-3 px-3">ID:
                                                <?php echo $patient['user_id']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['first_name']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['last_name']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['mname']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['mobileNumber']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['gender']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['age']; ?>
                                            </td>
                                            <td class="py-3 px-2">
                                                <?php echo $patient['dateAdded']; ?>
                                            </td>

                                            <form action="staffRadtechViewPatient.php" method="get">
                                                <td>
                                                    <input type="hidden" name="userID"
                                                        value="<?php echo $patient['user_id']; ?>">
                                                    <button name="viewButtonPatientRadtech" type="button"
                                                        class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm view-btn"
                                                        onclick="openMedicalHistoryModal()">View</button>

                                                </td>
                                            </form>
                            </div>
                            </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        </table>
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


        </div>
        </div>
        </div>

        <!-- Modal Medical History -->
        <div id="MedicalHistoryModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            <div class="modal-container bg-white w-3/4 md:max-w-3xl mx-auto rounded shadow-lg z-50 overflow-y-auto">

                <div class="modal-content py-4 text-left px-6">


                    <!-- Medical History Table -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Medical History</h2>
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Appointment ID</th>
                                    <th class="py-2 px-4 border-b">Status</th>
                                    <th class="py-2 px-4 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Add rows dynamically via JavaScript or PHP -->
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
        function openMedicalHistoryModal() {
            document.getElementById('MedicalHistoryModal').classList.remove('hidden');
        }

        // Function to close the Medical History Modal
        function closeMedicalHistoryModal() {
            document.getElementById('MedicalHistoryModal').classList.add('hidden');
        }








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

        // Function to perform search
        function performSearch(searchTerm) {
            const url = `adminPatientRecordsTable.php?search=${encodeURIComponent(searchTerm)}`;
            window.location.href = url;
        }

        // Attach the input event listener
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout); // Clear the previous timeout
            const searchTerm = searchInput.value;

            // Wait for 300 milliseconds after the user stops typing
            searchTimeout = setTimeout(() => {
                if (searchTerm.length >= 3) {
                    performSearch(searchTerm);
                } else if (searchTerm.length === 0 && prevSearchTerm.length > 0) {
                    // If the search term is cleared, reload the page for complete records
                    window.location.href = 'adminPatientRecordsTable.php';
                }
                prevSearchTerm = searchTerm; // Update previous search term
            }, 300);
        });

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