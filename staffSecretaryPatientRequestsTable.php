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

function getApprovedXRayRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM xrayrequest WHERE status = 'Approved'");
        $stmt->execute();
        $xrayrequestsApproved = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $xrayrequestsApproved;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

$xrayrequestsApproved = getApprovedXRayRequests($pdo);


function getForXRayRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM xrayrequest WHERE status = 'For Xray'");
        $stmt->execute();
        $xrayrequestsforXray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $xrayrequestsforXray;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

$xrayrequestsforXray = getForXRayRequests($pdo);





function getCanceledXRayRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM xrayrequest WHERE status IN ('Canceled', 'No show')");
        $stmt->execute();
        $xrayrequestsCanceled = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $xrayrequestsCanceled;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}



$xrayrequestsCanceled = getCanceledXRayRequests($pdo);

function getDeclinedXRayRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM xrayrequest WHERE status = 'Declined'");
        $stmt->execute();
        $xrayrequestsDeclined = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $xrayrequestsDeclined;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

$xrayrequestsDeclined = getDeclinedXRayRequests($pdo);
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
          

            <li>
                <a href="staffSecretaryPatientRegister.php">
                    <i class='bx bxs-user-account'></i>
                    <span class="link_name">Patient Registration</span>
                </a>
                <span class="tooltip">Patient Request</span>
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
                        <span class="admin_text">Staff-Secretary Account</span>
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
                                <h2 class="text-xl font-bold py-1 pl-3">Pending Patient Request</h2>
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
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($xrayrequestsPending as $request): ?>
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
        <div id="imageModal"
            class="modal fixed hidden inset-0 z-50 overflow-auto bg-gray-900 bg-opacity-30 flex justify-center items-center">
            <div class="modal-content bg-white w-1/3 p-4 rounded shadow-lg text-center">
                <div class="modal-header">
                    <h2 class="text-lg font-bold">Referral Image</h2>
                    <button id="closeModal" class="text-gray-700 hover:text-red-500 absolute top-2 right-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Referral Image" class="mx-auto mb-4 max-w-full h-auto">
                </div>
            </div>
        </div>


        <div class="container">
            <br>
            <br>
            <div class="card shadow rounded">
                <br>
                <div class="row align-items-center">

                </div>
                <div class="card-header bg-white">

                    <div class="card-body">
                        <div class="text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">Approved Patient Request</h2>
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
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($xrayrequestsApproved as $request): ?>
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


                                            <td class='px-3' style='text-align: center;'>
                                                <div class="text-center">
                                                    <span
                                                        style='display: flex; align-items: center; justify-content: center;'>
                                                        <span style='color: black; margin-right: 10px;'>
                                                            <?= $request['status'] ?>
                                                        </span>
                                                        <span
                                                            style='background-color: green; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;'>
                                                            &#10003;
                                                        </span>
                                                    </span>
                                            </td>
                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <button
                                                        class="text-blue-500 hover:text-blue-700 view-image-btn p-2 bg-blue-100 rounded-md"
                                                        data-image="<?= $request['referral_image'] ?>">
                                                        View
                                                    </button>
                                                </div>
                                            </td>


                                            
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class=" text-center mt-3">
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

        <div class="container">
            <br>
            <br>
            <div class="card shadow rounded">
                <br>
                <div class="row align-items-center">

                </div>
                <div class="card-header bg-white">


                    <div class="card-body">
                        <div class="text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">Canceled/No Show Patient Request</h2>
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
                                        <th class=" px-3 text-center">Referral</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($xrayrequestsCanceled as $request): ?>
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
                                                    <span style='display: inline-block; position: relative;'>
                                                        <?= $request['status'] ?>
                                                        <div
                                                            style='position: absolute; bottom: 0; left: 0; width: 100%; height: 5px; background-color: gray;'>
                                                        </div>
                                                    </span>
                                            </td>

                                            <td class='px-3'>
                                                <div class="text-center">
                                                    <button
                                                        class="text-blue-500 hover:text-blue-700  view-image-btn p-2 bg-blue-100 rounded-md"
                                                        data-image="<?= $request['referral_image'] ?>">
                                                        View
                                                    </button>
                                            </td>



                                        </tr>
                                    <?php endforeach; ?>
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

        <div class="container">
            <br>
            <br>
            <div class="card shadow rounded">
                <br>
                <div class="row align-items-center">

                </div>
                <div class="card-header bg-white">


                    <div class="card-body">
                        <div class="text-center">
                            <div class="highlighted-text1 text-white">
                                <h2 class="text-xl font-bold py-1 pl-3">Declined Patient Request</h2>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="px-3">First Name</th>
                                        <th class="px-3">Last Name</th>

                                        <th class="px-3">Appointment Date</th>
                                        <th class="px-3">Appointment Time</th>

                                        <th class="px-3">Status</th>
                                        <th class="px-3">Image of Referral </th>
                                        <th class=" px-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($xrayrequestsDeclined as $request): ?>
                                        <tr>
                                            <td class='px-3'>
                                                <?= $request['fname'] ?>
                                            </td>
                                            <td class='px-3'>
                                                <?= $request['lname'] ?>
                                            </td>


                                            <td class='px-3'>
                                                <?= date('Y-m-d', strtotime($request['appointment_schedule'])) ?>
                                            </td>
                                            <td class='px-3'>
                                                <?= date('h:i:s A', strtotime($request['appointment_schedule'])) ?>
                                            </td>

                                            <td class='px-3' style='text-align: center;'>
                                                <span style='display: flex; align-items: center; justify-content: center;'>
                                                    <span style='color: black;'>
                                                        <?= $request['status'] ?>
                                                    </span>
                                                    <span
                                                        style='background-color: red; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; margin-left: 10px;'>
                                                        &#10008;
                                                    </span>
                                                </span>
                                            </td>

                                            <td class='px-3'>
                                                <button
                                                    class="text-blue-500 hover:text-blue-700  view-image-btn p-2 bg-blue-100 rounded-md"
                                                    data-image="<?= $request['referral_image'] ?>">
                                                    View
                                                </button>
                                            </td>




                                            <td class='px-3'>
                                                <form action="update_approval.php" method="POST">
                                                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                                    <button type="submit" class="btn btn-success"
                                                        name="approve">button</button>
                                                </form>

                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
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


    <script src="assets/javascript/app.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var viewImageButtons = document.querySelectorAll('.view-image-btn');
            var modalImage = document.getElementById('modalImage');
            var closeModalButton = document.getElementById('closeModal');
            var modal = document.getElementById('imageModal');

            viewImageButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var imageUrl = this.getAttribute('data-image');
                    modalImage.src = imageUrl;
                    modal.classList.remove('hidden');
                });
            });

            closeModalButton.addEventListener('click', function () {
                modal.classList.add('hidden');
            });
        });
    </script>

</body>

</html>