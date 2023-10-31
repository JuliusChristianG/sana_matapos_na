<?php
session_start();
include('connection.php'); // Include your PDO connection file.

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Staff Doctor') {
    // If the user is not an Staff Doct, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}



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
        // Build the query for loading active patients without search
        $query = "SELECT * FROM patients WHERE dateAdded >= DATE_SUB(NOW(), INTERVAL 2 YEAR)";

        // Apply sorting if a filter is selected for inactive patients
        if ($filter === 'lname_a_z') {
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
    <title>Staff-Doctor Patient Records Table</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="icon" href=assets/images/mylablogo.png type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .highlighted-text1 {
            background-color: #0126CC;

            padding: 10px;

            margin-top: 20px;

        }
    </style>
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
                <a href="staffDoctor_profilepage.php">
                    <i class="bx bx-user-circle"></i>

                    <span class="link_name">
                        <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                    </span>
                </a>
                <span class="tooltip">
                    <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                </span>
            </li>



            <li class="profile">
                <div class="profile_details">
                    <div class="profile_content">
                        <div class="designation"></div>

                    </div>
                </div>



            <li>
                <a href="staffDoctorForDiagnosisTable.php">
                <i class='bx bx-edit' ></i>
                    <span class="link_name">For Diagnosis Table</span>
                </a>
                <span class="tooltip">For Diagnosis Table</span>
            </li>
            
            <li class="active">
                <a href="staffDoctorPatientRecordsTable.php">
                <i class='bx bx-book-alt'></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>

            <li class="profile">
                <a href="logout.php">
                    <i class="bx bx-log-out" id="log_out"></i>
                    <span class="link_name">
                        <?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?>
                        <br>
                        <span class="admin_text">Staff-Doctor Account</span>
                    </span>
                </a>
            </li>

        </ul>
    </div>

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
                                        <th class="py-3 px-3 rounded-tl">Case No.</th>
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
                                            <td class="py-3 px-3">MLC -
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

                                            <form action="staffDoctorViewPatient.php" method="get">
                                                <td>
                                                    <input type="hidden" name="userID"
                                                        value="<?php echo $patient['user_id']; ?>">
                                                    <button name="viewButtonPatientDoctor" type="submit"
                                                        class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">View</button>
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
    </section>
    <script>
        // Get the search input element
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        let prevSearchTerm = '';

        // Function to perform search
        function performSearch(searchTerm) {
            const url = `staffPatientRecordsTable.php?search=${encodeURIComponent(searchTerm)}`;
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
                    window.location.href = 'staffPatientRecordsTable.php';
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