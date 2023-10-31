<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

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


if(isset($_GET['appointment_id']) && isset($_GET['user_id'])) {
    $appointmentId = $_GET['appointment_id'];
    $userId = $_GET['user_id'];
    // Use $appointmentId and $userId to fetch the referral image from the database.
    // Assuming $referralImage is the URL of the referral image.
} else {
    $referralImage = "";
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Staff Radtech Patient Requests Table</title>
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



            <li class="">
                <a href="staffRadtechPatientRecordsTable.php">
                    <i class="bx bx-book-open"></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>


            <li class="active">
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

                    <div class="card-body">
                        <div class="text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">Patient Referral Image</h2>
                            </div>
                        </div>
                        <?php if (!empty($referralImage)): ?>
                            <img src="<?= $referralImage ?>" alt="Referral Image">
                        <?php else: ?>
                            <p>No referral image specified.</p>
                        <?php endif; ?>
                    </div>
                </div>



    </section>





    <script src="assets/javascript/app.js"></script>
</body>

</html>