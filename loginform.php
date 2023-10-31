<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="assets/images/logo-no-bg.png" rel="icon">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/wave.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <title>MyLab Clinic | Login page</title>
</head>
<body style="background-color: #e4e9f7;">

<section>
    

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <div class="featured-image mb-3">
                    <img src="assets/images/mylabLogo.png" class="img-fluid" style="width: 250px;">
                </div>
            
                <small class="text-black text-wrap text-center" style="width: 15rem;font-family: 'Courier New', Courier, monospace;">Records Management System for X-Ray Results</small>
            </div> 

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2 style="color:  #0126CC;">Welcome! </h2>
                        <p>Log-in to your account</p>
                    </div>
                     <form action="login.php" method="POST" onsubmit="return validateLoginForm()">
                       <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="username1" name="username" class="form-control form-control-lg bg-light fs-6" placeholder="Username">
                        </div>
                        <div class="input-group mb-1">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password1" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" >
                      <span class="input-group-text" id="showPassword"><i class="fas fa-eye"></i></span>
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                            </div>
                            <div class="forgot">
                                <small><a href="emailverification.php">Forgot Password?</a></small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                  <input type="submit" value="Login" class="btn btn-lg btn-primary w-100 fs-6" id="loginButton" style="background-color: #0126CC;">

</div>


                        </div>
                        <div class="row">
                            <small>Don't have account? <a href="signup.php">Sign Up</a></small>
                        </div>
                        
                        <div class="row">
                           <small>Want to know more about MyLab Clinic? <a href="/">Home</a></small>
                           <small>
    Need help? 
    <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Help</a>
</small>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>

 
 <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0126CC; color: white;">
                <h5 class="modal-title" id="helpModalLabel">Help</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <h1>Can't Log-in?</h1>
                <p>1. Your account may not be verified yet, check your email inbox and click the verification link.</p>
                <p>2. Username or password might be incorrect, double check and try again.</p>
                <p>3. Forgotten your password? Click the forgot password link and reset your password.</p>
               
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="validationMessageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
              <div class="modal-header" style="background-color: #dc3545; color: white;">
                <h5 class="modal-title" id="exampleModalLabel">Log-in Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="validationMessageText">The username and password you entered isn’t connected to an account! Please try again!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script>
document.getElementById('showPassword').addEventListener('click', function () {
         const passwordInput = document.getElementById('password1');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });

    function validateLoginForm() {
        const usernameInput = document.getElementById("username1");
        const passwordInput = document.getElementById("password1");

        if (usernameInput.value.trim() === "" || passwordInput.value.trim() === "") {
            let modal = new bootstrap.Modal(document.getElementById('validationMessageModal'));
            modal.show(); // Show the modal manually
            return false;
        }
        // Send form data to server for validation
        const formData = new FormData();
        formData.append('username', usernameInput.value);
        formData.append('password', passwordInput.value);

        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                // Redirect to appropriate dashboard
                window.location.href = data.redirect;
            } else {
                // Invalid username or password, display error message
                let modal = new bootstrap.Modal(document.getElementById('validationMessageModal'));
                modal.show();
            }
        })
        .catch(error => console.error('Error:', error));

        return false; // Prevent form submission
    }
    
    

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
