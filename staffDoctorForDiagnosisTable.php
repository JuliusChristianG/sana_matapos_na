<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

include('connection.php'); // Include  PDO connection file.

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

function getForDiagnosisXRayRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM xrayrequest WHERE status = 'for diagnosis'");
        $stmt->execute();
        $xrayrequestsForDiagnosis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $xrayrequestsForDiagnosis;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

$xrayrequestsForDiagnosis = getForDiagnosisXRayRequests($pdo);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Staff-Doctor For Diagnosis Table</title>
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

            <li class="active">
                <a href="staffDoctorForDiagnosisTable.php">
                <i class='bx bx-edit' ></i>
                    <span class="link_name">For Diagnosis Table</span>
                </a>
                <span class="tooltip">For Diagnosis Table</span>
            </li>



            <li>
                <a href="staffDoctorPatientRecordsTable.php">
                <i class='bx bx-book-alt'></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
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
                        <span class="admin_text">Staff-Doctor Account</span>
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

                    <div class="card-body">
                        <div class="text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">Patients for X-Ray Diagnosis/Impression</h2>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="px-3 text-center">First Name</th>
                                        <th class="px-3 text-center">Last Name</th>
                                        <th class="px-3 text-center">Appointment Date</th>
                                        <th class="px-3 text-center">Appointment Time</th>
                                        <th class="px-3 text-center">Status</th>
                                        <th class="px-3 text-center">Referral</th>
                                        <th class="px-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($xrayrequestsForDiagnosis as $request): ?>
                                        <tr>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <?= $request['fname'] ?>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <?= $request['lname'] ?>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <?= date('Y-m-d', strtotime($request['appointment_schedule'])) ?>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <?= date('h:i:s A', strtotime($request['appointment_schedule'])) ?>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <span style='background-color: yellow'>
                                                        <?= $request['status'] ?>

                                                    </span>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <button
                                                        class="text-blue-500 hover:text-blue-700  view-image-btn p-2 bg-blue-100 rounded-md"
                                                        data-image="<?= $request['referral_image'] ?>">
                                                        View
                                                    </button>
                                                </div>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <form action="staffDoctorViewPatient.php" method="get">
                                                        <input type="hidden" name="appointmentID"
                                                            value="<?= $request['appointment_id'] ?>">
                                                        <input type="hidden" name="user_id"
                                                            value="<?= $request['user_id'] ?>">
                                                        <button type="submit3" class="btn btn-success" name=""
                                                            value="">View</button>

                                                    </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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

    </section>


    <script src="assets/javascript/app.js"></script>


</body>

</html>