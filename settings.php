<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginandregister.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Settings</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/admindashboard.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   
</head>


<!-- start of sidebar-->

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
                <a href="#">
                    <i class="bx bx-user-circle"></i>

                    <span class="link_name"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
                </a>
                <span class="tooltip"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
            </li>
            <li class="">
                <a href="admindashboard.php">
                    <i class="bx bx-grid-alt"></i>
                    <span class="link_name">Admin Dashboard</span>
                </a>
                <span class="tooltip">Admin Dashboard</span>
            </li>
            <li class="">
                <a href="adminPatientRecordsTable.php">
                    <i class="bx bx-book-open"></i>
                    <span class="link_name">Patient Records</span>
                </a>
                <span class="tooltip">Patient Records</span>
            </li>
            <li class="">
                <a href="adminAddUser.php">
                    <i class="bx bx-user"></i>
                    <span class="link_name">User Accounts</span>
                </a>
                <span class="tooltip">User Accounts</span>
            </li>

            <li class="">
                <a href="adminAddPatient.php">
                    <i class="bx bx-user-plus"></i>
                    <span class="link_name">Patient Accounts</span>
                </a>
                <span class="tooltip">Patient Accounts</span>
            </li>

            <li class="">
                <a href="#">
                    <i class="bx bx-folder"></i>
                    <span class="link_name">Audit Trail</span>
                </a>
                <span class="tooltip">Audit Trail</span>
            </li>

            <li class="active">
                <a href="">
                    <i class="bx bx-cog"></i>
                    <span class="link_name">Settings</span>
                </a>
                <span class="tooltip">Settings</span>
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
            <span class="admin_text">Admin Account</span>
        </span>
    </a>
</li>

        </ul>
    </div>
    <section class="home-section">
        <section id="content">

    <main>


    </main>

        </section>



        <script src="assets/javascript/app.js"></script>
</body>

</html>