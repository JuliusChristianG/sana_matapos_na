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
require("connection.php");


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
        $query = "SELECT * FROM patient_records WHERE dateAdded <= DATE_SUB(NOW(), INTERVAL 2 YEAR)";

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
            $query .= " ORDER BY case_no DESC";
        }


        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $patientsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $inactivePatients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Sort the $inactivePatients array by dateAdded in descending order (additional sorting)
        usort($inactivePatients, function ($a, $b) {
            return strtotime($b['dateAdded']) - strtotime($a['dateAdded']);
        });
    } else {
        // Build the search query for inactive patients
        $query = "SELECT * FROM patient_records WHERE lname LIKE :search AND dateAdded <= DATE_SUB(NOW(), INTERVAL 2 YEAR) LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':search', "%$search%");
        $stmt->bindValue(':limit', $patientsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $inactivePatients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count the total number of inactive patients for pagination.
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM patient_records WHERE dateAdded <= DATE_SUB(NOW(), INTERVAL 2 YEAR)");
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
    <title>Admin Patient Records Table Archived</title>
    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">

    <!-- Stylesheets.css -->
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/table.css">
  
    <!-- Boxicons for sidebar Icons -->
 <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <!-- Tailwind -->
    <script src="//cdn.tailwindcss.com"></script>
    
    <!-- Icons -->
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>


    <!-- Website Icon -->
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
            
            <li class="active">
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
                    <h4 class="text-lg font-bold py-1 pl-3">PATIENT RECORD TABLE ARCHIVED</h4>
                    <div class="d-flex justify-content-end mb-3">
                        <form method="get">
                            <input type="text" name="search" class="outline outline-offset-2 text-sm rounded-md"
                                placeholder="Search Last Name" value="<?php echo htmlspecialchars($search); ?>"
                                oninput="if (this.value === '') this.form.submit();">
                        </form>


                        <div class="ml-2">
                            <a href="adminPatientRecordsTable.php"
                                class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-[#0126CC] text-white hover:bg-blue-600 hover:text-white">Active
                                Records</a>
                        </div>
                        <div class="ml-2">
                            <a href="exportarchivedrecords.php"
                                class="btn btn-success btn-lg rounded-pill px-4 py-2 text-white">Export</a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-filter"></i>
                        <label for="filterSelect" class="mr-1">Filter By:</label>

                        <select id="filterSelect" class="outline outline-offset-0.3 text-sm rounded-md">
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
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
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
                                    <th class="rounded-tr px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require("connection.php");
                                foreach ($inactivePatients as $patient) {
                                    ?>
                                    <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                        <td class="py-3 px-3">MLC -
                                            <?php echo $patient['case_no']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $patient['fname']; ?>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php echo $patient['lname']; ?>
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
                                        <form action="adminViewPatientArchived.php" method="get">
                                            <td>
                                                <input type="hidden" name="caseNo"
                                                    value="<?php echo $patient['case_no']; ?>">
                                                <button name ="viewButtonArchived" type="submit"
                                                    class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">View</button>
                                            </td>
                                        </form>
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



        // Get the search input element
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        let prevSearchTerm = '';

        // Function to perform search
        function performSearch(searchTerm) {
            const url = `adminPatientRecordsTableArchived.php?search=${encodeURIComponent(searchTerm)}`;
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
                    window.location.href = 'adminPatientRecordsTableArchived.php';
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

    <script src="assets/javascript/app.js"></script>
</body>

</html>