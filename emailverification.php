<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css" rel="stylesheet">
    <link href="assets/images/logo-no-bg.png" rel="icon">
    <link rel="stylesheet" href="assets/css/login.css">
        <link rel="stylesheet" href="assets/css/wave.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <title>MyLab Clinic | Email Verification page</title>
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
                        <h2 style="color:  #0126CC;"> Email Verification  </h2>
                    
                    </div>
                    <form id="emailForm" action="email.php" method="POST" class="sign-up-form">
 
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="text" id="email" placeholder="Email" name="email" class="form-control form-control-lg bg-light fs-6" required>
                        </div>
                        <div class="mb-3">
                            <input type="submit" value="Continue"  class="btn btn-lg btn-primary w-100 fs-6"  style="background-color: #0126CC; id="continueButton">
                        </div>
                      <p>Enter your email address to have a verification code to change your password.</p>
                    </form>
                </div>
            </div> 
        </div>
    </div>

    
    <div id="invalidEmailModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invalid Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The email you entered is invalid. Please try again.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeModalButton">Close</button>
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
    $(document).ready(function() {
        $('#emailForm').submit(function(e) {
            e.preventDefault();

            var email = $('#email').val();

            // Make an AJAX request to email.php to check the email
            $.post('email.php', { email: email }, function(data) {
                if (data == 'invalid_email') {
                    // Show the modal for invalid email
                    $('#invalidEmailModal').modal('show');

                    // Add click event listener to the "Close" button
                    $('#closeModalButton').click(function() {
                        $('#invalidEmailModal').modal('hide'); // Close the modal
                    });

                    // Add click event listener to the close button ("x")
                    $('#invalidEmailModal .close').click(function() {
                        $('#invalidEmailModal').modal('hide'); // Close the modal
                    });
                } else {
                    // Redirect to the codeverification.php page
                    window.location.href = 'codeverification.php?email=' + email;
                }
            });
        });
    });
</script>


</body>
</html>
