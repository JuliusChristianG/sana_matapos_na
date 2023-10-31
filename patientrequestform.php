<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
  header('Location: loginform.php');
  exit();
}
$dsn = 'mysql:host=localhost;dbname=mylabclinic';
$username = 'root';
$password = '';
try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if (isset($_GET['userID'])) {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE user_id = :userID");
    $stmt->bindParam(':userID', $_GET['userID'], PDO::PARAM_STR);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
      echo "Patient not found.";
      die();
    }

    // Fetch the X-ray request status for the current logged-in user
    $stmtStatus = $pdo->prepare("SELECT * FROM xrayrequest WHERE user_id = :userID");
    $stmtStatus->bindParam(':userID', $_GET['userID'], PDO::PARAM_STR);
    $stmtStatus->execute();
    $xrayStatus = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);
  } else {
    echo "User ID number not provided.";
    die();
  }
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $purpose = $_POST["purpose"];
  $xraytype = $_POST["xraytype"];
  $appointment_schedule = $_POST["appointment_schedule"];
  $status = $_POST['status'];
  $userID = $_SESSION['user_id'];

  try {
    $insertRequestStmt = $pdo->prepare("INSERT INTO xrayrequest (fname, lname, date_sched, status, date_created, purpose) VALUES (:fname, :lname, :appointment_schedule, :status, NOW(), :purpose)");
    $insertRequestStmt->bindParam(':fname', $patient['fname'], PDO::PARAM_STR);
    $insertRequestStmt->bindParam(':lname', $patient['lname'], PDO::PARAM_STR);
    $insertRequestStmt->bindParam(':appointment_schedule', $appointment_schedule, PDO::PARAM_STR);
    $insertRequestStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $insertRequestStmt->bindParam(':purpose', $purpose, PDO::PARAM_STR);
    $insertRequestStmt->execute();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <title> Patient X-Ray Request </title>
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
        <a href="patient_profilepage.php?userID=<?php echo $patient['user_id']; ?>">
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
        <a href="patientDashboard.php<?php echo $_SESSION['userID']; ?>">
        <i class='bx bx-book-open'></i>
          <span class="link_name">My Record</span>
        </a>
        <span class="tooltip">My Record</span>
      </li>

      <li class="active">
        <a href="patientrequestform.php?userID=<?php echo $patient['user_id']; ?>">
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
  <!-- Start of main content-->
  <section class="home-section">
    <br>
    <div class="container">
      <div class="card shadow rounded">
        <div class="card-header bg-white">
          <div class="row align-items-center">
            <div class="col">
              <center>
                <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
              </center>
            </div>
          </div>

          <div class="card-body text-center">
            <div class="highlighted-text1 text-white">
              <h2 class="text-xl font-bold py-1 pl-3">X-Ray Request</h2>
            </div>

            <hr class="mb-4">
            <form action="xrayrequest.php?userID=<?php echo $_GET['userID']; ?>" method="POST" class="row g-3"
              enctype="multipart/form-data">
              <div class="col-md-6">
                <label for="referral_image" class="form-label"><b>Upload Image of Referral</b></label>
                <input type="file" name="image" class="form-control" required id="image">
              </div>

              <div class="col-md-6">
                <label for="appointment_schedule" class="form-label"><b>Appointment Schedule</b></label>
                <input type="datetime-local" name="appointment_schedule" class="form-control rounded-pill" required>
              </div>

              <input type="hidden" name="status" value="Pending">
              <input type="hidden" name="user_id" value="<?php echo $_GET['userID']; ?>">

              <div class="col-12 text-center mt-3">
                <button class="ml-1 rounded-pill bg-[#0126CC] px-4 text-white hover:bg-[#6257b4] text-white p-2 text-sm"
                  type="submit">Submit Request</button>
              </div>

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

        </div>
        <div class="card-header bg-white">
          <h4 class="text-lg font-bold py-1 pl-3"></h4>
          <div class="d-flex justify-content-end mb-3">


          </div>
          <div class="card-body">
            <div class="text-center">
              <div class="highlighted-text1 text-white">
                <h2 class="text-xl font-bold py-1 pl-3">X-Ray Request History</h2>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover table-condensed">
                <thead class="font-semibold text-md bg-[#0126CC] text-white">
                  <tr>

                    <th class="px-3 text-center">Appointment Date</th>
                    <th class="px-3 text-center">Appointment Time</th>
                    <th class="px-3 text-center">Referral Image</th>
                    <th class="px-3 text-center">Message</th>
                    <th class="px-3 text-center">Status</th>

                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($xrayStatus as $status): ?>
                    <tr>
                      <td class="px-3">
                        <div class="text-center">
                          <?php echo date('M-d-D-Y', strtotime($status['appointment_schedule'])); ?>
                      </td>
                      <td class="px-3">
                        <div class="text-center">
                          <?php echo date('h:i A', strtotime($status['appointment_schedule'])); ?>
                        </div>
                      </td>
                      <td class="px-3">
                        <div class="text-center">

                          <button class="text-blue-500 hover:text-blue-700 view-image-btn p-2 bg-blue-100 rounded-md"
                            onclick="openImageViewModal('<?php echo $status['referral_image']; ?>')">View
                          </button>

                      </td>
                      <td class="px-3">
                        <div class="text-center">

                          <button class="text-blue-500 hover:text-blue-700 view-image-btn p-2 bg-blue-100 rounded-md"
                            onclick="openMessageViewModal('<?php echo $status['message']; ?>')">View
                          </button>
                          <div class="text-center">

                      </td>
                      <td class="px-3">
                        <div class="text-center">
                          <?php echo $status['status']; ?>
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
                <a href="?page=<?php echo $pageNumber - 1; ?><?php echo $filterParam; ?>" class="btn btn-primary"><i
                    class="fas fa-chevron-left"></i></a>
              <?php endif; ?>

              <span>Page
                <?php echo isset($pageNumber) ? $pageNumber : 1; ?> of
                <?php echo isset($totalPages) ? $totalPages : 1; ?>
              </span>

              <?php if (isset($pageNumber) && isset($totalPages) && $pageNumber < $totalPages): ?>
                <a href="?page=<?php echo $pageNumber + 1; ?><?php echo $filterParam; ?>" class="btn btn-primary"><i
                    class="fas fa-chevron-right"></i></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

 <!-- Modal for Viewing Image -->
<div id="imageViewModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    <div class="modal-container bg-white w-3/4 md:max-w-3xl mx-auto rounded shadow-lg z-50 overflow-y-auto">

        <div class="modal-content py-4 text-left px-6">
            <h2 class="text-2xl font-semibold mb-4">Referral Image</h2>
            <div class="flex justify-center"> 
            <img id="imageView" src="" alt="Referral Image" class="w-1/2"> <!-- Set a fixed width here -->
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
                    onclick="closeImageViewModal()">Close
                </button>
            </div>
        </div>
    </div>
</div>
  <!-- Modal for Viewing Image -->
  <div id="MessageViewModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    <div class="modal-container bg-white w-3/4 md:max-w-3xl mx-auto rounded shadow-lg z-50 overflow-y-auto">

      <div class="modal-content py-4 text-left px-6">
        <h2 class="text-2xl font-semibold mb-4">Message from MyLab Clinic</h2>
        <div id="messageContent"></div>
        <div class="mt-4 flex justify-end">
          <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2"
            onclick="closeMessageViewModal()">Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>

    <?php
    if (isset($message) && !empty($message)) {
      echo "alert('$message');
                  
                  ";
      header("Location: patientrequestform.php?userID=" . $_GET['userID']);
    }
    ?>
  </script>

  <script>

    function openMessageViewModal(message) {
      const messageContent = document.getElementById('messageContent');

      if (message.trim() === '') {
        messageContent.innerHTML = 'There is no message.';
      } else {
        messageContent.innerHTML = message;
      }

      document.getElementById('MessageViewModal').classList.remove('hidden');
    }


    function closeMessageViewModal() {
      document.getElementById('MessageViewModal').classList.add('hidden');
    }

    function openImageViewModal(imageSource) {
      document.getElementById('imageView').src = imageSource;
      document.getElementById('imageViewModal').classList.remove('hidden');
    }

    function closeImageViewModal() {
      document.getElementById('imageViewModal').classList.add('hidden');
    }
  </script>
  <script src="assets/javascript/app.js"></script>
</body>

</html>