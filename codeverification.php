<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/images/logo-no-bg.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/wave.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <title>MyLab Clinic | Code Verification page</title>
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
                        <p>Verification Code</p>
                    </div>
                    <form id="codeForm" action="code.php" method="POST" class="verification-code" onsubmit="return ValidateVerificationCode()">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="number" id="code" placeholder="Code" name="code" class="form-control form-control-lg bg-light fs-6" required>
                    </div>
                        <input type="submit" value="Submit" class="btn btn-lg btn-primary w-100 fs-6"  style="background-color: #0126CC; id="submitBtn">
                        <div class="d-flex justify-content-center mt-2">
                            <div id="timer"></div>
                        </div>
                        <div class="text-center mt-2">
                        <p style="color: blue; text-decoration: underline; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#confirmationModal">Resend Code</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0126CC; color: white;">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to resend the code?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" style="background-color: #0126CC" onclick="resendCode(); $('#confirmationModal').modal('hide'); $('#successAfterResendModal').modal('show');">Resend Code</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="successAfterResendModal" tabindex="-1" aria-labelledby="successAfterResendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #28a745; color: white;">
                <h5 class="modal-title" id="successAfterResendModalLabel">Success!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Verification code has been resent. Please check your email.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="background-color: #0126CC" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- "Code Expired" modal -->
<div class="modal fade" id="codeExpiredModal" tabindex="-1" aria-labelledby="codeExpiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Add modal-dialog-centered class to center the modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="codeExpiredModalLabel">Code Expired</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                The verification code has expired. Please request a new code.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">CLOSE</button> <!-- Use btn-danger class for a red button -->
            </div>
        </div>
    </div>
</div>


</section>

  <div>
     <div class="wave"></div>
     <div class="wave"></div>
     <div class="wave"></div>
  </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua8Jw5TIq9zF08+orHre5m7KQfivmB/wq5Fo5R6u6f8hYCEp2pr/8xb2D" crossorigin="anonymous"></script>
<script>
// JavaScript code
var timer;
var countdown = 60; // 5 minutes = 5 * 60 seconds

function startTimer() {
    var display = document.getElementById('timer');
    var minutes, seconds;
    
    timer = setInterval(function() {
        minutes = Math.floor(countdown / 60);
        seconds = countdown % 60;
        
        display.textContent = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        
        if (countdown <= 0) {
            clearInterval(timer);
            display.textContent = '00:00';
            // Handle code expiration here
            codeExpired();
        } else {
            countdown--;
        }
    }, 1000);
}

function codeExpired() {
    // Display the "Code Expired" modal
    $('#codeExpiredModal').modal('show');

    // Update the 'verification_code' in the database to NULL
    $.post("codetimer.php", { clear_code: true }, function(response) {
        if (response === 'success') {
            // Code cleared successfully
        } else {
            // Handle error here
            console.error('Error clearing code');
        }
    });
}

function resendCode() {
    // Clear the timer and start a new one
    clearInterval(timer);
    countdown = 60;
    startTimer();

    // Send the request to resend the code
    $.post("resendCode.php", { resend_code: true }, function(response) {
        // Check the response from the server
        if (response === 'success') {
            // Open the success modal
            $('#successAfterResendModal').modal('show');

            // Close all modals
            $('#confirmationModal').modal('hide');
            $('#successModal').modal('hide');
        }
    }).fail(function() {
        // Handle any error here (if needed)
        console.error('Error sending code');
    });
}
// Start the timer when the page loads
startTimer();
</script>
</body>
</html>
