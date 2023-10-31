<?php
session_start();
require("connection.php");
// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Staff') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}



// Check if a user ID is provided in the URL (query parameter)
if (isset($_GET['user_id'])) {
  $userId = $_GET['user_id'];
  // Fetch the user's first name and last name based on the user ID
  $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
  $stmt->execute([$userId]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Assign the fetched values to variables
  $firstName = $user['first_name'];
  $lastName = $user['last_name'];
} else {
  // If no user ID is provided, set the variables to empty values
  $firstName = '';
  $lastName = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Staff Add Patient Record</title>

    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/js/bootstrap.bundle.css" rel="stylesheet">
    <!-- styles css -->
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons for sidebar Icons -->
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c99b1c4a5.js" crossorigin="anonymous"></script>
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
        <a href="staffprofilepage.php">
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
        <a href="staffDashboard.php">
          <i class="bx bx-grid-alt"></i>
          <span class="link_name">Staff Dashboard</span>
        </a>
        <span class="tooltip">Staff Dashboard</span>
      </li>
      <li class="">
        <a href="staffPatientRecordsTable.php">
          <i class="bx bx-book-open"></i>
          <span class="link_name">Patient Records</span>
        </a>
        <span class="tooltip">Patient Records</span>
      </li>


      <li class="active">
        <a href="staffAddPatient.php">
          <i class="bx bx-user-plus"></i>
          <span class="link_name">Patient Accounts</span>
        </a>
        <span class="tooltip">Patient Accounts</span>
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
            <span class="staff_text">Staff Account</span>
          </span>
        </a>
      </li>

    </ul>
  </div>

  <!-- end of side bar-->

  <section class="home-section">

    <br>
    <div class="container">
      <div class="card shadow rounded">
        <div class="card-header bg-white">
          <!-- Rounded pill label for User ID -->
          <span class="rounded-pill border border-black bg-white text-black px-2 py-1 position-absolute"
            style="top: 50px; left: 20px;">User ID:
            <?php echo $userId; ?>
          </span>

          <!-- End of User ID label -->
          <div class="row align-items-center">
            <div class="col">
              <center>
                <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
              </center>
            </div>
          </div>
        </div>
        <div class="card-body">
          <h4 class="text-lg font-bold py-1 pl-3">ADD PATIENT RECORD</h4>
          <hr class="mb-4">

          <form method="POST" action="addPatientRecordsStaff.php?user_id=<?php echo $userId; ?>" class="row g-3">

            <div class="col-md-6">
              <label for="fname" class="form-label"><b>First Name</b></label>
              <input type="text" name="fname" required class="form-control rounded-pill" placeholder="First Name"
                value="<?php echo $firstName; ?>" readonly>
            </div>
            <div class="col-md-6">
              <label for="lname" class="form-label"><b>Last Name</b></label>
              <input type="text" name="lname" required class="form-control rounded-pill" placeholder="Last Name"
                value="<?php echo $lastName; ?>" readonly>
            </div>
            <div class="col-md-6">
              <label for="lname" class="form-label"><b>Middle Name</b></label>
              <input type="text" name="mname" required class="form-control rounded-pill" placeholder="Middle Name">
            </div>
            <div class="col-md-6">
              <label for="lname" class="form-label"><b>Address</b></label>
              <input type="text" name="address" required class="form-control rounded-pill" placeholder="Address">
            </div>
            <div class="col-md-6">
              <label for="lname" class="form-label"><b>Age</b></label>
              <input type="number" name="age" required class="form-control rounded-pill" placeholder="Age">
            </div>
            <div class="col-md-6">
              <label for="lname" class="form-label"><b>Birthday</b></label>
              <input type="date" name="birthday" required class="form-control rounded-pill" placeholder="Birthday">
            </div>
         
           
            <div class="col-md-6">
              <label for="gender" class="form-label"><b>Gender</b></label>
              <select name="gender" required class="form-select rounded-pill">
                <option disabled selected value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="lname" class="form-label"><b>Mobile Number</b></label>
              <input type="tel" name="mobileNum" maxlength="11" required class="form-control rounded-pill"
                placeholder="Mobile Number">
            </div>
        


            <div class="col-12 mt-3 d-flex justify-content-between">
              <div>
                <a href="staffAddPatient.php" class="btn btn-secondary btn-lg rounded-pill px-4 py-2">Back</a>
              </div>
              <div>
                <input type="hidden" name="patientAcc" value="<?php echo $userId; ?>">
                <button
                  class="btn btn-primary btn-lg rounded-pill px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 hover:text-white"
                  type="submit">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <script src="assets/javascript/app.js"></script>
</body>

</html>