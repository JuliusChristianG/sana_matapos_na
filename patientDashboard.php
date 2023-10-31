<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
  header('Location: loginform.php');
  exit();
}

// Check if the user's role is "Patient"
if ($_SESSION['role'] !== 'Patient') {
  // If the user is not a patient, you can redirect them to an error page or another appropriate page.
  header('Location: loginform.php'); // Change "unauthorized.php" to the desired page.
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

  // Define the SQL query
  $query = "SELECT appointment_id, user_id, xray_type, date_consulted FROM patient_findings WHERE user_id = :userId";

  // Prepare and execute the query
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':userId', $_SESSION['userID']);
  $stmt->execute();
  $activePatients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <title>My Patient Record</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/sidebar.css">
  <style>
        .highlighted-text1 {
            background-color: #0126CC;

            padding: 10px;

            margin-top: 20px;

        }
    </style>
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
        <a href="patient_profilepage.php<?php echo $_SESSION['userID']; ?>">
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
        <a href="patientDashboard.php">
        <i class='bx bx-book-open'></i>
          <span class="link_name">My Record</span>
        </a>
        <span class="tooltip">My Record</span>
      </li>

      <li class="">
        <a href="patientrequestform.php?userID=<?php echo $_SESSION['user_id']; ?>">
          <i class='bx bx-clipboard'></i>
          <span class="link_name">X-Ray Request</span>
        </a>
        <span class="tooltip">X-Ray Request</span>
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
            <span class="admin_text">Patient Account</span>
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
  
          <div class="d-flex justify-content-end mb-3">




          </div>

          <?php
          // Assuming you have a valid session with 'user_id'
          $userId = $_SESSION['user_id'];

          $sql = "SELECT appointment_id, user_id, xray_type, date_consulted FROM patient_findings WHERE user_id = $userId";

          try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
          }
          ?>


          <div class="card-body">
            <div class="text-center">
              <div class="highlighted-text1 text-white">
                <h2 class="text-xl font-bold py-1 pl-3">Medical History</h2>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover table-condensed">
                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                  <tr>

                    <th class="px-3 text-center">Case no:</th>
                    <th class="px-3 text-center">X-Ray Type</th>
                    <th class="px-3 text-center">Date Consulted</th>
                    <th class="px-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($result as $row) {
                    // Loop through the result array
                    ?>
                    <tr class="cursor-pointer hover:bg-[#eeeeee]">
                      <td class="py-3 px-3">
                        <div class="text-center">MLC -
                          <?php echo $row['appointment_id']; ?>
                      </td>
                      <td class="py-3 px-2">
                        <div class="text-center">
                          <?php echo $row['xray_type']; ?>
                      </td>
                      <td class="py-3 px-2">
                        <div class="text-center">
                          <?php echo $row['date_consulted']; ?>
                      </td>
                      <td>
                        <form action="patientViewRecord.php" method="get">
                          <div class="text-center">
                            <input type="hidden" name="appointmentID" value="<?php echo $row['appointment_id']; ?>">
                            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                            <button name="viewButtonPatient" type="submit"
                              class="ml-1 rounded-lg bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm">View</button>
                        </form>
                      </td>
                    </tr>
                    <?php
                  }
                  ?>

                </tbody>
              </table>
            </div>

          </div>

        </div>
      </div>
    </div>
    </div>
    </div>
  </section>
  <script src="assets/javascript/app.js"></script>
</body>

</html>