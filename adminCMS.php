<?php
session_start();
require("connection.php");
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

// Create a database connection
try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// SQL query to retrieve doctor information
$sql = "SELECT id, first_name, last_name, email, image, specialization FROM doctors";

try {
    $result = $conn->query($sql);

    // Initialize the $doctors array to store the results
    $doctors = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error executing the query: " . $e->getMessage());
}

// Assuming you have already established a database connection
$sql_profile = "SELECT information FROM company_profile WHERE id = 1"; // Assuming ID 1 is the one you want
$result_profile = $conn->query($sql_profile);
$row_profile = $result_profile->fetch();
$profile_text_from_database = $row_profile['information'];

// Assuming you have already established a database connection
$sql_contact_info = "SELECT open_hours, contact_number, email_address FROM contact_information WHERE id = 1"; // Assuming ID 1 is the one you want
$result_contact_info = $conn->query($sql_contact_info);
$row_contact_info = $result_contact_info->fetch();
$open_hours_from_database = $row_contact_info['open_hours'];
$contact_number_from_database = $row_contact_info['contact_number'];
$email_address_from_database = $row_contact_info['email_address'];
// Assuming you have already established a database connection
$sql_about_us = "SELECT mission, vission, objectives FROM about_us WHERE id = 1"; // Assuming ID 1 is the one you want
$result_about_us = $conn->query($sql_about_us);
$row_about_us = $result_about_us->fetch();
$mission_from_database = $row_about_us['mission'];
$vision_from_database = $row_about_us['vission'];
$objectives_from_database = $row_about_us['objectives'];

$sql = "SELECT id, title, description FROM faqs"; // Removed the extra comma after 'description'
try {
    $result = $conn->query($sql);

    // Initialize the $doctors array to store the results
    $faqs = $result->fetchAll(PDO::FETCH_ASSOC);




} catch (PDOException $e) {
    die("Error executing the query: " . $e->getMessage());
}

$sql = "SELECT * FROM services"; // Removed the extra comma after 'description'
try {
    $result = $conn->query($sql);

    // Initialize the $doctors array to store the results
    $services = $result->fetchAll(PDO::FETCH_ASSOC);




} catch (PDOException $e) {
    die("Error executing the query: " . $e->getMessage());
}
// Check if a success message is set in session
if (isset($_SESSION['success_message'])) {
    echo '<script>alert("' . $_SESSION['success_message'] . '");</script>';
    unset($_SESSION['success_message']);
}


$conn = null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin CMS</title>

    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/sidebar.css">

    <link href="assets/images/logo-no-bg.png" rel="icon">
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="adminDashboard.php">
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

            <li class="active">
                <a href="adminCMS.php">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Content Management</span>
                </a>
                <span class="tooltip">Content Management</span>
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
                    <h4 class="text-lg font-bold py-1 pl-3">Doctors</h4>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" onclick="openAddDoctorModal()">Add Doctor</button>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                <tr>
                                    <th class="py-3 ">First Name</th>
                                    <th class="px-3 ">Last Name</th>
                                    <th class="px-3 ">Specialization</th>
                                    <th class="px-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php foreach ($doctors as $doctor): ?>
                                    <tr>
                                        <td>
                                            <?php echo $doctor['first_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $doctor['last_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $doctor['specialization']; ?>
                                        </td>
                                        <td>
                                            <button
                                                class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm"
                                                onclick='openEditDoctorModal(<?php echo json_encode($doctor); ?>)'>Edit</button>
                                            <form action="deleteDoctors.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="doctorID" value="<?php echo $doctor['id']; ?>">
                                                <button type="submit1" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                            </tbody>
                        </table>
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
                    <div class="col">
                    </div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">Company Profile</h4>
                    <div class="d-flex justify-content-end mb-3">
                    </div>
                </div>

                <div class="card-body">
                    <form action="save_profile.php" method="post"> <!-- Added a form with an action -->
                        <div class="table-responsive">
                            <!-- Add your text area here with a border -->
                            <textarea name="profile_text" rows="4" cols="50"
                                style="border: 1px solid #ccc; width: 100%; height: 100%;"><?php echo $profile_text_from_database; ?></textarea>
                            <!-- Echo the value from the database into the textarea -->
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <input type="submit"
                                class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm"
                                value="Save">
                            <!-- Changed button to a submit input -->
                    </form>
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
                    <div class="col">
                    </div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">Contact Information</h4>
                    <div class="d-flex justify-content-end mb-3">
                    </div>
                </div>

                <div class="card-body">
                    <form action="save_contact_information.php" method="post"> <!-- Added form with action -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="px-3">
                                            <textarea name="open_hours" class="form-control" rows="4"
                                                placeholder="Open Hours"><?php echo $open_hours_from_database; ?></textarea>
                                        </th>
                                        <th class="px-3">
                                            <textarea name="contact_number" class="form-control" rows="4"
                                                placeholder="Contact Number"><?php echo $contact_number_from_database; ?></textarea>
                                        </th>
                                        <th class="px-3">
                                            <textarea name="email_address" class="form-control" rows="4"
                                                placeholder="Email Address"><?php echo $email_address_from_database; ?></textarea>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <input type="submit"
                                class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm"
                                value="Save"> <!-- Added submit input -->
                    </form>
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
                    <div class="col">
                    </div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">About Us Information</h4>
                    <div class="d-flex justify-content-end mb-3">
                    </div>
                </div>

                <div class="card-body">
                    <form action="save_about_us.php" method="post"> <!-- Added form with action -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="px-3">
                                            <textarea name="mission" class="form-control" rows="4"
                                                placeholder="Mission"><?php echo $mission_from_database; ?></textarea>
                                        </th>
                                        <th class="px-3">
                                            <textarea name="vision" class="form-control" rows="4"
                                                placeholder="Vision"><?php echo $vision_from_database; ?></textarea>
                                        </th>
                                        <th class="px-3">
                                            <textarea name="objectives" class="form-control" rows="4"
                                                placeholder="Objectives"><?php echo $objectives_from_database; ?></textarea>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit"
                                class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">Save</button>
                            <!-- Changed to a submit button -->
                    </form>
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
                    <div class="col">
                    </div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">Images</h4>
                    <div class="d-flex justify-content-end mb-3">
                    </div>
                </div>
                <div class="card-body">
                    <form action="edit_images.php" method="post" enctype="multipart/form-data">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="py-3 ">Background Image Header</th>
                                        <th class="px-3 ">About Us Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <input type="hidden" name="currentBackgroundImage"
                                                    value="<?php echo $row['background_image']; ?>">
                                                <label for="backgroundImage">Choose File</label>
                                                <input type="file" class="form-control-file" id="backgroundImage"
                                                    name="backgroundImage">
                                                <?php if (!empty($row['background_image'])): ?>
                                                    <img src="<?php echo $row['background_image']; ?>"
                                                        alt="Background Image">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="hidden" name="currentAboutUsImage"
                                                    value="<?php echo $row['about_us_image']; ?>">
                                                <label for="aboutUsImage">Choose File</label>
                                                <input type="file" class="form-control-file" id="aboutUsImage"
                                                    name="aboutUsImage">
                                                <?php if (!empty($row['about_us_image'])): ?>
                                                    <img src="<?php echo $row['about_us_image']; ?>" alt="About Us Image">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit"
                                class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">Save</button>
                    </form>
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
                    <div class="col">
                    </div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">About Us Information</h4>
                    <div class="d-flex justify-content-end mb-3">
                    </div>
                </div>

                <div class="card-body">
                    <form action="save_about_us.php" method="post"> <!-- Added form with action -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                    <tr>
                                        <th class="px-3">
                                            <textarea name="mission" class="form-control" rows="4"
                                                placeholder="Mission"><?php echo $mission_from_database; ?></textarea>
                                        </th>
                                        <th class="px-3">
                                            <textarea name="vision" class="form-control" rows="4"
                                                placeholder="Vision"><?php echo $vision_from_database; ?></textarea>
                                        </th>
                                        <th class="px-3">
                                            <textarea name="objectives" class="form-control" rows="4"
                                                placeholder="Objectives"><?php echo $objectives_from_database; ?></textarea>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit"
                                class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">Save</button>
                            <!-- Changed to a submit button -->
                    </form>
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
                    <div class="col"></div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">FAQS</h4>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" onclick="openAddFAQsModal()">Add FAQS</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                <tr>
                                    <th class="py-3 ">No. </th>
                                    <th class="px-3 ">Title</th>
                                    <th class="px-3 ">Description</th>
                                    <th class="px-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($faqs as $faq): ?>
                                    <tr>
                                        <td>
                                            <?php echo $faq['id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $faq['title']; ?>
                                        </td>
                                        <td>
                                            <?php echo $faq['description']; ?>
                                        </td>
                                        <td>
                                            <button
                                                class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm"
                                                onclick='openEditFAQsModal(<?php echo json_encode($faq); ?>)'>Edit</button>


                                            <form action="delete_FAQs.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="faqID" value="<?php echo $faq['id']; ?>">
                                                <button type="submit1" class="btn btn-danger">Delete</button>
                                            </form>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>




        <div id="addFAQsModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                <!-- Form for adding a Doctor -->
                <div class="modal-content py-4 text-left px-6">
                    <h2 class="text-2xl font-semibold mb-4">Add FAQs</h2>
                    <form action="addFAQs.php" method="POST" id="addFAQsForm" enctype="multipart/form-data"
                        onsubmit="return validateForm()">
                        <div class="mb-4">
                            <label for="addFirstName" class="block mb-2 font-semibold">Title</label>
                            <input type="text" name="addTitle" id="editTitle" class="w-full px-4 py-2 border rounded-lg"
                                required>

                        </div>
                        <div class="mb-4">
                            <label for="addLastName" class="block mb-2 font-semibold">Description</label>
                            <textarea name="addDescription" id="addDescription"
                                class="w-full px-4 py-2 border rounded-lg" required> </textarea>

                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                                onclick="closeAddFAQsModal()">Cancel</button>
                            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!--SERVICES -->
        <div class="container">
            <br>
            <br>
            <div class="card shadow rounded">
                <br>
                <div class="row align-items-center">
                    <div class="col"></div>
                </div>
                <div class="card-header bg-white">
                    <h4 class="text-lg font-bold py-1 pl-3">Services</h4>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" onclick="openAddServicesModal()">Add Services</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead class="font-semibold text-md bg-[#0126CC] text-white">
                                <tr>
                                    <th class="px-3 ">No. </th>
                                    <th class="px-3 ">Title</th>
                                    <th class="px-3 ">Services 1</th>
                                    <th class="px-3 ">Services 2</th>
                                    <th class="px-3 ">Services 3</th>
                                    <th class="px-3 ">Services 4</th>
                                    <th class="px-3 ">Services 5</th>
                                    <th class="px-3 ">Services 6</th>
                                    <th class="px-3 ">Services 7</th>
                                    <th class="px-3 ">Services 8</th>
                                    <th class="px-3 ">Services 9</th>
                                    <th class="px-3 ">Services 10</th>
                                    <th class="px-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td>
                                            <?php echo $service['id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_title']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_1']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_2']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_3']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_4']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_5']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_6']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_7']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_8']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_9']; ?>
                                        </td>
                                        <td>
                                            <?php echo $service['service_10']; ?>
                                        </td>
                                        <td>
                                            <button
                                                class="ml-1 rounded-lg bg-green-500 px-4 text-white hover:bg-green-600 hover:text-white p-2 text-sm"
                                                onclick='openEditServiceModal(<?php echo json_encode($service); ?>)'>Edit</button>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>



    <div id="addServicesModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-2xl mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <!-- Form for adding a Doctor -->
            <div class="modal-content py-4 text-left px-6">
                <h2 class="text-2xl font-semibold mb-4">Add Service</h2>
                <form action="addService.php" method="POST" id="addServiceForm" enctype="multipart/form-data"
                    onsubmit="return validateForm()">
                    <div class="mb-5">
                        <label for="addTitle" class="block mb-2 font-semibold">Title</label>
                        <input type="text" name="addTitle" id="addTitle" class="w-full px-4 py-2 border rounded-lg"
                            required>
                    </div>

                    <!-- Service Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="addService1" class="block mb-2 font-semibold">Service 1</label>
                            <input type="text" name="addService1" id="addService1"
                                class="w-full px-4 py-2 border rounded-lg" required>
                        </div>

                        <div>
                            <label for="addService2" class="block mb-2 font-semibold">Service 2</label>
                            <input type="text" name="addService2" id="addService2"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService3" class="block mb-2 font-semibold">Service 3</label>
                            <input type="text" name="addService3" id="addService3"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService4" class="block mb-2 font-semibold">Service 4</label>
                            <input type="text" name="addService4" id="addService4"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService5" class="block mb-2 font-semibold">Service 5</label>
                            <input type="text" name="addService5" id="addService5"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService6" class="block mb-2 font-semibold">Service 6</label>
                            <input type="text" name="addService6" id="addService6"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService7" class="block mb-2 font-semibold">Service 7</label>
                            <input type="text" name="addService7" id="addService7"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService8" class="block mb-2 font-semibold">Service 8</label>
                            <input type="text" name="addService8" id="addService8"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService9" class="block mb-2 font-semibold">Service 9</label>
                            <input type="text" name="addService9" id="addService9"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label for="addService10" class="block mb-2 font-semibold">Service 10</label>
                            <input type="text" name="addService10" id="addService10"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                            onclick="closeAddServicesModal()">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal for edit services -->
    <div id="editServiceModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-4xl mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-6 text-left px-8">
                <h2 class="text-3xl font-semibold mb-6">Edit Service</h2>
                <form action="editService.php" method="POST" id="editServiceForm" enctype="multipart/form-data"
                    onsubmit="return validateForm()">
                    <!-- Title Field -->
                    <input type="hidden" name="editServiceId" id="editServiceId">
                    <div class="mb-6">
                        <label for="edittitle" class="block mb-2 font-semibold">Title</label>
                        <input type="text" name="edittitle" id="edittitle" class="w-full px-6 py-3 border rounded-lg"
                            required>
                    </div>
                    <div class="grid grid-cols-5 gap-6 mb-10">
                        <div>
                            <label for="editService1" class="block mb-2 font-semibold">
                                Service 1
                            </label>
                            <input type="text" name="editservice1" id="editservice1"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService2" class="block mb-2 font-semibold">
                                Service 2
                            </label>
                            <input type="text" name="editservice2" id="editservice2"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService3" class="block mb-2 font-semibold">
                                Service 3
                            </label>
                            <input type="text" name="editservice3" id="editservice3"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService4" class="block mb-2 font-semibold">
                                Service 4
                            </label>
                            <input type="text" name="editservice4" id="editservice4"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService5" class="block mb-2 font-semibold">
                                Service 5
                            </label>
                            <input type="text" name="editservice5" id="editservice5"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService6" class="block mb-2 font-semibold">
                                Service 6
                            </label>
                            <input type="text" name="editservice6" id="editservice6"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService7" class="block mb-2 font-semibold">
                                Service 7
                            </label>
                            <input type="text" name="editservice7" id="editservice7"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService8" class="block mb-2 font-semibold">
                                Service 8
                            </label>
                            <input type="text" name="editservice8" id="editservice8"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService9" class="block mb-2 font-semibold">
                                Service 9
                            </label>
                            <input type="text" name="editservice9" id="editservice9"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="editService10" class="block mb-2 font-semibold">
                                Service 10
                            </label>
                            <input type="text" name="editservice10" id="editservice10"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                            onclick="closeEditServiceModal()">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg">Save</button>
                    </div>
                </form>
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
    <!-- Edit MODAL for Doctor -->
    <div id="editDoctorModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

            <!-- Form for editing the Doctor -->
            <div class="modal-content py-4 text-left px-6">
                <h2 class="text-2xl font-semibold mb-4">Edit Doctor</h2>

                <form action="editDoctors.php" method="POST" id="editForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editDoctorId">

                    <div class="mb-4">
                        <label for="editFirstName" class="block mb-2 font-semibold">First Name</label>
                        <input type="text" name="editFirstName" id="editFirstName"
                            class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="editLastName" class="block mb-2 font-semibold">Last Name</label>
                        <input type="text" name="editLastName" id="editLastName"
                            class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="editEmail" class="block mb-2 font-semibold">Email</label>
                        <input type="email" name="editEmail" id="editEmail" class="w-full px-4 py-2 border rounded-lg"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="editSpecialization" class="block mb-2 font-semibold">Specialization</label>
                        <input type="text" name="editSpecialization" id="editSpecialization"
                            class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="fileUpload" class="block mb-2 font-semibold">Upload Image</label>
                        <input type="file" name="fileUpload" id="fileUpload" class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                            onclick="closeEditDoctorModal()">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="addDoctorModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">

        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

            <!-- Form for adding a Doctor -->
            <div class="modal-content py-4 text-left px-6">
                <h2 class="text-2xl font-semibold mb-4">Add Doctor</h2>

                <form action="addDoctor.php" method="POST" id="addDoctorForm" enctype="multipart/form-data">

                    <div class="mb-4">
                        <label for="addFirstName" class="block mb-2 font-semibold">First Name</label>
                        <input type="text" name="addFirstName" id="addFirstName"
                            class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="addLastName" class="block mb-2 font-semibold">Last Name</label>
                        <input type="text" name="addLastName" id="addLastName"
                            class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="addEmail" class="block mb-2 font-semibold">Email</label>
                        <input type="email" name="addEmail" id="addEmail" class="w-full px-4 py-2 border rounded-lg"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="addSpecialization" class="block mb-2 font-semibold">Specialization</label>
                        <input type="text" name="addSpecialization" id="addSpecialization"
                            class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="fileUpload" class="block mb-2 font-semibold">Upload Image</label>
                        <input type="file" name="fileUpload" id="fileUpload" class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                            onclick="closeAddDoctorModal()">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <!-- Edit MODAL for FAQs -->
    <div id="editFAQsModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

            <!-- Form for editing FAQs -->
            <div class="modal-content py-4 text-left px-6">
                <h2 class="text-2xl font-semibold mb-4">Edit FAQs</h2>

                <form action="editFAQs.php" method="POST" id="editFAQsForm" onsubmit="return validateForm()">

                    <input type="hidden" name="editFAQsId" id="editFAQsId">
                    <div class="mb-4">
                        <label for="edittitle" class="block mb-2 font-semibold">Title</label>
                        <input type="text" name="edit_faq_title" id="edit_faq_title"
                            class="w-full px-4 py-2 border rounded-lg" required>

                    </div>
                    <div class="mb-4">
                        <label for="editDescription" class="block mb-2 font-semibold">Description</label>
                        <textarea name="editdescription" id="editdescription" class="w-full px-4 py-2 border rounded-lg"
                            rows="8" required></textarea>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                            onclick="closeEditFAQsModal()">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    </section>
    <script>


        function openEditServiceModal(service) {
            // Populate the input fields with the service data
            document.getElementById('editServiceId').value = service.id;
            document.getElementById('edittitle').value = service.service_title;
            document.getElementById('editservice1').value = service.service_1;
            document.getElementById('editservice2').value = service.service_2;
            document.getElementById('editservice3').value = service.service_3;
            document.getElementById('editservice4').value = service.service_4;
            document.getElementById('editservice5').value = service.service_5;
            document.getElementById('editservice6').value = service.service_6;
            document.getElementById('editservice7').value = service.service_7;
            document.getElementById('editservice8').value = service.service_8;
            document.getElementById('editservice9').value = service.service_9;
            document.getElementById('editservice10').value = service.service_10;

            // Show the edit modal by removing the 'hidden' class
            document.getElementById('editServiceModal').classList.remove('hidden');
        }

        function closeEditServiceModal() {
            document.getElementById('editServiceModal').classList.add('hidden');
        }

        function openAddServicesModal() {
            document.getElementById('addServicesModal').classList.remove('hidden');
        }

        function closeAddServicesModal() {
            document.getElementById('addServicesModal').classList.add('hidden');
        }


        function openEditFAQsModal(faq) {
            // Populate the input fields with the FAQ data
            document.getElementById('editFAQsId').value = faq.id;
            document.getElementById('edit_faq_title').value = faq.title;
            document.getElementById('editdescription').value = faq.description;

            // Show the edit modal by removing the 'hidden' class
            document.getElementById('editFAQsModal').classList.remove('hidden');
        }

        function closeEditFAQsModal() {
            document.getElementById('editFAQsModal').classList.add('hidden');
        }

        function openAddFAQsModal() {
            document.getElementById('addFAQsModal').classList.remove('hidden');
        }

        function closeAddFAQsModal() {
            document.getElementById('addFAQsModal').classList.add('hidden');
        }


        function openAddDoctorModal() {
            document.getElementById('addDoctorModal').classList.remove('hidden');
        }

        function closeAddDoctorModal() {
            document.getElementById('addDoctorModal').classList.add('hidden');
        }

        function openEditDoctorModal(doctorData) {

            // Populate the modal fields with patient data
            document.getElementById('editDoctorId').value = doctorData.id;
            document.getElementById('editEmail').value = doctorData.email;
            document.getElementById('editFirstName').value = doctorData.first_name;
            document.getElementById('editLastName').value = doctorData.last_name;
            document.getElementById('editSpecialization').value = doctorData.specialization;

            // Show the modal
            document.getElementById('editDoctorModal').classList.remove('hidden');
        }

        // Function to close the edit patient modal
        function closeEditDoctorModal() {
            document.getElementById('editDoctorModal').classList.add('hidden');
        }



        function confirmEditFormSubmission() {
            document.getElementById('editForm').submit();
        }

        document.getElementById('log_out').addEventListener('click', function () {
            document.getElementById('logoutModal').classList.toggle('hidden');
        });

        document.getElementById('cancelLogout').addEventListener('click', function () {
            document.getElementById('logoutModal').classList.toggle('hidden');
        });

        // Add an event listener for the "Yes" button
        document.getElementById('confirmLogout').addEventListener('click', function () {
            // Redirect to the logout page
            window.location.href = 'logout.php';
        });

    </script>


    <script src="assets/javascript/app.js"></script>
</body>

</html>