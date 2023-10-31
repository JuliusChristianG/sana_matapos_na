<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}
require('connection.php');
// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
    exit();
}

$search = "";
try {
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch patients data with 'Patient' role from the database.
    $stmt = $pdo->prepare("SELECT * FROM userlog");
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Audit Trail</title>

 <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">
    <!-- styles css -->
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/table.css">
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

            <li class="">
                <a href="adminAddUser.php">
                    <i class="bx bx-user"></i>
                    <span class="link_name">User Accounts</span>
                </a>
                <span class="tooltip">User Accounts</span>
            </li>


            <li class="active">
                <a href="adminAuditTrail.php">
                    <i class="bx bx-folder"></i>
                    <span class="link_name">Audit Trail</span>
                </a>
                <span class="tooltip">Audit Trail</span>
            </li>

            <li class="">
                <a href="adminCMS.php">
                    <i class="bx bx-user"></i>
                    <span class="link_name">Control Management</span>
                </a>
                <span class="tooltip">User Accounts</span>
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
                      <h4 class="text-lg-center font-bold py-1 pl-3">USER LOGS</h4>
                </div>
              
                 <div class="card-header bg-white">
                  
                    <div class="d-flex justify-content-end mb-3">
                        <form method="get">
                           <input type="text" id="search" class="outline outline-offset-2 text-sm rounded-md"
    placeholder="Search Username" value="<?php echo htmlspecialchars($search); ?>"
    oninput="liveSearch();">

                        </form>
                        </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                <tr>
                                    
                                    <th class="py-3 px-2">Username</th>
                                    <th class="py-3 px-2">Date</th>
                                    <th class="py-3 px-2">Time</th>
                                    <th class="py-3 px-2">Role</th>
                                    <th class="py-3 px-2">Action Made</th>
                                </tr>
                            </thead>
                            <tbody>

                        <tbody id="tableBody">
                     <?php
                        // Sort the $logs array by 'date' in descending order and then by 'logID' in descending order (most recent first)
                        usort($logs, function ($a, $b) {
                            $dateComparison = strtotime($b['date']) - strtotime($a['date']);
                            
                            // If dates are the same, sort by 'logID'
                            if ($dateComparison === 0) {
                                return $b['logID'] - $a['logID'];
                            }
                            
                            return $dateComparison;
                        });
                        
                        foreach ($logs as $log): ?>
                            <tr class="cursor-pointer hover:bg-[#eeeeee]">
                                
                                <td class="py-3 px-3">
                                    <?php echo $log['uname']; ?>
                                </td>
                                <td class="py-3 px-2">
                                    <?php echo $log['date']; ?>
                                </td>
                                <td class="py-3 px-2">
                                    <?php echo $log['time']; ?>
                                </td>
                                <td class="py-3 px-2">
                                    <?php echo $log['role']; ?>
                                </td>
                                <td class="py-3 px-2">
                                    <?php echo $log['action']; ?>
                                </td>
                            </tr>
                        <?php endforeach;
                        ?>
                            </tbody>

                        </table>
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
                <!-- Add an ID to the Yes button for JavaScript handling -->
                <button id="confirmLogout" class="px-4 py-2 bg-red-500 text-white rounded">Yes</button>
            </div>
        </div>
    </div>
</div>
    </section>
<script>
    function liveSearch() {
        var searchValue = document.getElementById('search').value;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    document.getElementById('tableBody').innerHTML = xhr.responseText;
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };
        xhr.open('GET', 'search_logs.php?search=' + searchValue, true);
        xhr.send();
    }
</script>


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
</script>
    <script src="assets/javascript/app.js"></script>
</body>

</html>