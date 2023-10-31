<?php
session_start();
require("connection.php");

// Check if the email parameter is not present in the URL
if (!isset($_GET['email'])) {
    // Redirect the user to another page (e.g., login page)
    header("Location: loginform.php");
    exit(); // Terminate the script to prevent further execution
}

// Check if a user ID is provided in the URL (query parameter)
if (isset($_GET['email'])) {
    $userEmail = $_GET['email'];
    // Fetch the user's first name and last name based on the user ID
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute([$userEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Assign the fetched values to variables
    $email = $user['email'];
   
} else {
    // If no user ID is provided, set the variables to empty values
    $email= '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/css/login.css">
        <link rel="stylesheet" href="assets/css/wave.css">
        <style>
            
            .modal-success-header {
    background-color: #28a745;
}
        </style>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <title>MyLab Clinic | Forgot Password page</title>
</head>

<body style="background-color: #e4e9f7;">
    <section> 

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <div class="featured-image mb-3">
                    <img src="assets/images/mylabLogo.png" class="img-fluid" style="width: 250px;">
                </div>
                <p class="text-black fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">MyLab Clinic</p>
                <small class="text-black text-wrap text-center" style="width: 15rem;font-family: 'Courier New', Courier, monospace;">Records Management System for X-Ray Results</small>
            </div> 

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Hello! </h2>
                        <p>Reset Password for an account!</p>
                    </div>
                    <form id="forgotpasswordForm" action="email_change_password.php" method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="text" id="email" placeholder="Email" name="email" class="form-control form-control-lg bg-light fs-6" required value="<?php echo $email; ?>" readonly>
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" placeholder="New Password" name="new_password" class="form-control form-control-lg bg-light fs-6" required>
                             <span class="input-group-text" id="showPassword"><i class="fas fa-eye"></i></span>
                        </div>
                        <p class="error email-error"></p>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="Confirm_password" placeholder="Confirm Password" name="confirm_password" class="form-control form-control-lg bg-light fs-6" required>
                            <span class="input-group-text show-password" id="showConfirmPassword"><i
                                    class="fas fa-eye"></i></span>
                        </div>
                        <div class="mb-3">
                            <input type="submit" value="Confirm" id="register-btn" class="btn btn-lg btn-primary w-100 fs-6">
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
    <script>

                            document.getElementById('showPassword').addEventListener('click', function () {
                                const passwordInput = document.getElementById('password');
                                if (passwordInput.type === "password") {
                                    passwordInput.type = "text";
                                } else {
                                    passwordInput.type = "password";
                                }
                            });

                            document.getElementById('showConfirmPassword').addEventListener('click', function () {
                                const confirmPasswordInput = document.getElementById('Confirm_password');
                                if (confirmPasswordInput.type === "password") {
                                    confirmPasswordInput.type = "text";
                                } else {
                                    confirmPasswordInput.type = "password";
                                }
                            });

                        </script>
    <!-- Add this modal code at the end of your HTML body -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
  <div class="modal-header" id="modalHeader" style="color: white;">
        <h5 class="modal-title" id="alertModalLabel">Success!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content will be dynamically inserted here by JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
</section>
<!-- Modify your JavaScript code as follows -->
<script>
function showAlert(message, callback) {
    var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
        backdrop: 'static',
        keyboard: false
    });
    var modalBody = document.querySelector('.modal-body');
    modalBody.textContent = message;
    
    // Get the modal header element
    var modalHeader = document.getElementById('modalHeader');

    alertModal.show();

    var okButton = document.querySelector('#alertModal .btn-primary');
    okButton.addEventListener('click', function() {
        alertModal.hide();
        if (typeof callback === 'function') {
            callback();
        }
    });

    // Toggle the class based on the success condition
    if (message === "Password changed successfully") {
        modalHeader.classList.add('modal-success-header');
    } else {
        modalHeader.classList.remove('modal-success-header');
    }
}


function validatePasswordFields() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("Confirm_password").value;

    // Password requirement pattern
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (password !== confirmPassword) {
        showAlert("Passwords do not match. Please try again.");
        return false; // Prevent form submission
    }

    if (!password.match(passwordPattern)) {
        showAlert("Password must be 8 characters long and include upper case, lower case, numbers, and special characters.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}
document.getElementById("forgotpasswordForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var form = this;

    if (validatePasswordFields()) {
        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("Password changed successfully", function() {
                    form.reset();
                    window.location.href = 'loginform.php';
                });
            } else {
                showAlert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});


</script>

  <div>
     <div class="wave"></div>
     <div class="wave"></div>
     <div class="wave"></div>
  </div>

</body>
</html>
