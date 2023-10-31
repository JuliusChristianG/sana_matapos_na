<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: loginform.php');
    exit();
}

// Check if the user's role is "Admin"
if ($_SESSION['role'] !== 'Patient') {
    // If the user is not an admin, you can redirect them to an error page or another appropriate page.
    header('Location: unauthorized.php'); // Change "unauthorized.php" to the desired page.
    exit();
}
// Replace 'hostname', 'database_name', 'username', and 'password' with your actual database credentials.
$dsn = 'mysql:host=localhost;dbname=u651313594_mylabClinic';
$username = 'u651313594_mylabsanjuan';
$password = 'Mylabsanjuan23';

try {
  $pdo = new PDO($dsn, $username, $password);
  // Set PDO error mode to exception.
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Check if a specific patient's case number is provided in the URL.
  if (isset($_GET['caseNo'])) {
    // Fetch the specific patient's data from the patient_records table based on the case number.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user=?");
    $stmt->bindParam(':caseNo', $_GET['caseNo'], PDO::PARAM_STR);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
      // Handle the case when the patient with the given case number does not exist.
      echo "Patient not found.";
      die();
    }
  }
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
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/sidebar.css">

  <script src="https://cdn.tailwindcss.com"></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        <a href="patient_profilepagenorecord.php">
          <i class="bx bx-user-circle"></i>

          <span class="link_name"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
        </a>
        <span class="tooltip"><?php echo $_SESSION['first_name'], ' ', $_SESSION['last_name'] ?></span>
      </li>
      <li class="active">
        <a href="patientdashboard.php">
          <i class="bx bx-grid-alt"></i>
          <span class="link_name">My Record</span>
        </a>
        <span class="tooltip">My Record</span>
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
            <span class="admin_text">Patient Account</span>
          </span>
        </a>
      </li>

    </ul>
  </div>
  
  <section class="home-section">
    <!-- Your original code starts here -->
    <div class="w-full h-screen px-10 py-5 bg-white flex">
      <div class="w-full">
        <div class="w-full bg-white h-[100px]">
          <div class="container">
            <div class="card my-4">
              <div class="card-header bg-white">
                <div class="row align-items-center">
                  <div class="col">
                    <center>
                      <img src="assets/images/mylabLogo.png" height="90px" width="90px" class="ml-3">
                    </center>
                  </div>
                  <div class="col-span-6"></div>
                </div>
              </div>
              <div class="card-body">
             
    <div class="pb-1 flex justify-between"> <!-- Use flex to align buttons to the right and add space between them -->
        <div>
            
        </div>
              <div class="card-body">
                <div>
                  <p class="justify-center flex pt-5 text-3xl font-bold">PATIENT RECORD</p>
                  <p class="justify-center flex text-md pt-3 font-bold">Welcome to MyLab Clinical Laboratory! To have your patient record and X-Ray results, please visit our clinic! </p>
                </div>
                <div class="grid grid-cols-6">
                  <div class="col-span-4 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-4 pl-[5px]">
                    <div class="py-5 font-bold text-[#0126CC]">PATIENT NAME:</div>
                    <div class="col-span-2 uppercase py-5 text-[#0126CC]"><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?></div>
                  </div>

                  <div class="col-span-2 rounded-lg border border-l-[7px] border-[#0126CC] border-offset-2 mt-5 mr-5 grid grid-cols-2 pl-[5px]">
                    <div class="py-5 font-bold text-[#0126CC]">CASE NO:</div>
                    <div class="uppercase py-5 justify-center items-center flex text-[#0126CC]">MLC - </div>
                  </div>
                </div>

                <div class="grid grid-cols-3 my-4">
                  <div class="rounded-lg bg-gray-200 p-4">
                    <div class="py-1 font-bold text-[#0126CC]">DETAILS</div>
                    <hr class="my-2">
                    <p class="text-sm font-bold text-[#5b5b5b] pt-[5px]">Address</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Age</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Birthday</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Birthplace</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Civil Status</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Gender</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Mobile Number</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Religion</p>
                    <p class="text-md font-bold pb-1">No Record</p>
                    <p class="text-sm font-bold text-[#5b5b5b]">Occupation</p>
                    <p class="text-md font-bold pb-1">No Record</p>


                  </div>

                  <div class="col-span-2 rounded-lg bg-gray-200 p-4 ml-4">
                    <div class="py-1 font-bold text-[#0126CC]">PATIENT FINDINGS</div>
                    <hr class="my-2">

                    <div class="grid grid-cols-2 grid-rows-2 mt-3">
                      <div class="text-sm ml-10 border border-l-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>X-ray Image Result:</b> </div>
                      <div class="text-sm mr-10 border border-r-[2px] border-t-[2px] border-[#5b5b5b] py-1 pl-2"><b>Date Consulted:</b></div>
                      <div class="text-sm ml-10 border border-l-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Final Diagnosis:</b></div>
                      <div class="text-sm mr-10 border border-r-[2px] border-b-[2px] border-[#5b5b5b] py-1 pl-2"><b>Issued By:</b></div>

                    </div>

                    <div class="justify-center items-center flex mt-5">
                      <div class="h-[150px] w-[150px] bg-[#bcbcbc] items-center rounded-lg">
                        <p class="text-white text-sm justify-center flex mt-[50px]">No image uploaded</p>
                      </div>
                    </div>
                  </div>
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
  <!-- Your original code ends here -->

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

    // Function to add the "active" class to the current page's list item
    function setActivePage() {
      const currentPath = window.location.pathname;
      const links = document.querySelectorAll('.nav-list li a');

      links.forEach(link => {
        const linkPath = link.getAttribute('href');

        if (currentPath === linkPath) {
          link.closest('li').classList.add('active');
          if (sidebar.classList.contains("open")) {
            link.querySelector('i').classList.add('active-icon');
          }
        }
      });
    }

    // Function to toggle the active class for the icon when the sidebar is opened or closed
    function toggleActiveIcon() {
      const activeLink = document.querySelector('.nav-list li.active a');
      if (activeLink && sidebar.classList.contains("open")) {
        activeLink.querySelector('i').classList.add('active-icon');
      } else {
        const activeIcon = document.querySelector('.nav-list li a i.active-icon');
        if (activeIcon) {
          activeIcon.classList.remove('active-icon');
        }
      }
    }

    window.onload = function() {
      const sidebar = document.querySelector(".sidebar");
      const closeBtn = document.querySelector("#btn");
      const searchBtn = document.querySelector(".bx-search");

      closeBtn.addEventListener("click", function() {
        sidebar.classList.toggle("open");
        menuBtnChange();
        toggleActiveIcon();
      });

      searchBtn.addEventListener("click", function() {
        sidebar.classList.toggle("open");
        menuBtnChange();
        toggleActiveIcon();
      });

      function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
          closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
          closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
      }

      // Call the function to set the active page when the page loads
      setActivePage();
    };
  </script>

  <script>
    window.onload = function() {
      const sidebar = document.querySelector(".sidebar");
      const closeBtn = document.querySelector("#btn");
      const searchBtn = document.querySelector(".bx-search");

      closeBtn.addEventListener("click", function() {
        sidebar.classList.toggle("open")
        menuBtnChange()
        toggleActiveIcon();
      });

      searchBtn.addEventListener("click", function() {
        sidebar.classList.toggle("open")
        menuBtnChange()
        toggleActiveIcon();
      });

      function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
          closeBtn.classList.replace("bx-menu", "bx-menu-alt-right")
        } else {
          closeBtn.classList.replace("bx-menu-alt-right", "bx-menu")
        }
      }
    }
  </script>
</body>

</html>