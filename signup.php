<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/wave.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="assets/images/logo-no-bg.png" rel="icon">
    <title>MyLab Clinic | Sign-up page</title>
</head>



<body style="background-color: #e4e9f7;">

    <section>

        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="row border rounded-5 p-3 bg-white shadow box-area">
                <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                    <div class="featured-image mb-3">
                        <img src="assets/images/mylabLogo.png" class="img-fluid" style="width: 250px;">
                    </div>

                    <small class="text-black text-wrap text-center"
                        style="width: 15rem;font-family: 'Courier New', Courier, monospace;">Records Management System
                        for
                        X-Ray Results</small>
                </div>

                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text mb-4">
                            <h2 style="color:  #0126CC;">Hello! </h2>
                            <p>Register for an account!</p>
                        </div>
                        <form id="signupForm" action="register.php" method="POST" class="sign-up-form"
                            onsubmit="return validateSignupForm()">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" pattern="^[^0-9]+$" id="fname" placeholder="First Name" name="fname"
                                    class="form-control form-control-lg bg-light fs-6" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" pattern="^[^0-9]+$" id="mname" placeholder="Middle Name" name="mname"
                                    class="form-control form-control-lg bg-light fs-6" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" pattern="^[^0-9]+$" id="lname" placeholder="Last Name" name="lname"
                                    class="form-control form-control-lg bg-light fs-6" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="number" pattern="^[^0-9]+$" id="age" placeholder="Age and Birthdate"
                                    name="age" class="form-control form-control-lg bg-light fs-6" required>
                                <input type="date" name="birthday" id="birthday" required
                                    class="form-control form-control-lg bg-light fs-6" placeholder="Birthday">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" id="username" placeholder="Username" name="username"
                                    class="form-control form-control-lg bg-light fs-6" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" id="email" placeholder="Email" name="email"
                                    class="form-control form-control-lg bg-light fs-6" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" id="password" placeholder="Example Password: Patient2023!"
                                    name="password" class="form-control form-control-lg bg-light fs-6" required>
                                <span class="input-group-text" id="showPassword"><i class="fas fa-eye"></i></span>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" id="confirm_password" placeholder="Confirm Password"
                                    name="confirm_password" class="form-control form-control-lg bg-light fs-6" required>
                                <span class="input-group-text show-password" id="showConfirmPassword"><i
                                        class="fas fa-eye"></i></span>
                            </div>
                            
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" pattern="^[^0-9]+$" id="address" placeholder="Address" name="address"
                                    class="form-control form-control-lg bg-light fs-6" required>
                            </div>
                           
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                <select name="gender" required class="form-control form-control-lg bg-light fs-6">
                                    <option disabled selected value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="tel" name="mobileNum" maxlength="11" required
                                    class="form-control form-control-lg bg-light fs-6" placeholder="Mobile Number">
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
                                    const confirmPasswordInput = document.getElementById('confirm_password');
                                    if (confirmPasswordInput.type === "password") {
                                        confirmPasswordInput.type = "text";
                                    } else {
                                        confirmPasswordInput.type = "password";
                                    }
                                });

                            </script>


                            <div style="display: flex; justify-content: center;" class="col-span-2">
                                <input type="checkbox" id="agree" name="agree" style="margin-right: 6px;" disabled>

                                <p id="modal-btn" class="text-xs cursor-pointer" data-bs-toggle="modal"
                                    data-bs-target="#dataPrivacyModal"><u style="color: blue;">Read the information
                                        about Data Privacy Policy</u></p>
                                <p
                                    style="color: rgb(0, 0, 0); font-size: 16px; margin-top: 10px; margin-bottom: 10px; text-align: center;">
                                </p>
                            </div>
                            <div class=" mb-3">
                                <input type="submit" value="Register" id="register-btn"
                                    class="btn btn-lg btn-primary w-100 fs-6" style="background-color: #0126CC;>
                        </div>
                        <div class=" row">
                                <small>Already have an account? <a href="loginform.php">Log-in</a></small><br>

                                <small>
                                    Need help in registration?
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Help</a><br>
                                </small>

                                <small>Want to know more about MyLab Clinic? <a href="/">Home</a></small><br>
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
                        <h1>How to Register?</h1>
                        <p>1. Enter your first name and last name.</p>
                        <p>2. Enter your desired username it should not contain spaces.</p>
                        <p>3. Enter your active email address. </p>
                        <p>4. Enter your password for <u><b>this website,</b></u> it should contain Capital letters,
                            Small letters, Numbers, and special characters (Ex. @$!%*?&).</p>
                        <p>5. Read the Information about Data Privacy Policy and Click the confirm button. </p>




                        <h2>After a Successful registration</h2>
                        <p> Check your email inbox for the verification link to Log-in your account. </p>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="dataPrivacyModal" tabindex="-1" aria-labelledby="dataPrivacyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0126CC; color: white;">
                        <h5 class="modal-title" id="dataPrivacyModalLabel">Data Privacy Policy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h2 class="font-bold text-lg">Consent Form</h2>

                        <p class="ml-3">In compliance to RA 10173 or the Data Protection Act of 2012 (DPA of 2012) and
                            its
                            Implementing Rules and Regulations, we are detailing here the processing of the data you
                            will
                            provide to us.</p>


                        <h2 class="font-bold text-lg mt-3">Purpose</h2>

                        <p class="ml-3"> The collection of personal data serves several critical purposes. Name is
                            gathered
                            to accurately identify and maintain patient medical histories and X-ray records,
                            ensuring records are correctly associated with individuals. Address aids in demographic
                            analysis, contact for updates or emergencies, and billing. Age is crucial for medical
                            assessment
                            and
                            treatment planning. Birthday allows for personalized healthcare experiences and age-related
                            screenings. Birthplace can be medically relevant in considering environmental factors or
                            specific health
                            conditions. Civil Status impacts family planning decisions and insurance coverage. Gender is
                            essential for gender-specific medical considerations. Mobile Number is vital for appointment
                            notifications
                            and emergency contact. Religion may be medically relevant, impacting care and
                            accommodations.
                            Lastly, Occupation offers insights into lifestyle and potential occupational hazards. It is
                            important to collect
                            and manage this data in compliance with privacy regulations, ensuring patient consent,
                            confidentiality, and data security are prioritized throughout the process.</p>




                        <h2 class="font-bold text-lg mt-3">Personal Information</h2>
                        <p class="ml-3">The following are the personal data that the we may need to collect: </p>
                        <ul class="ml-6 list-disc">
                            <li>Name</li>
                            <li>Address</li>
                            <li>Age</li>
                            <li>Birthday</li>
                            <li>Birth Place</li>
                            <li>Mobile Number</li>
                            <li>Occupation</li>
                            <li>Region</li>
                            <li>Phone number</li>
                        </ul>

                        <h2 class="font-bold text-lg mt-3">Data Subject’s Rights</h2>
                        <p class="ml-3">Under RA 10173, the following are some of the rights the data subject may
                            exercise,
                            (for the full list of rights see https://www.privacy.gov.ph/know-your-rights/):
                        <ul class="ml-6 list-disc">
                            <li> Right to be informed on the collection and processing of personal data through this
                                consent
                                form;</li>
                            <li> Right to object on the processing of personal data or to restrict the processing of
                                personal data upon request; </li>
                            <li> Right to access the personal data collected and processed upon request; and </li>
                            <li> Right to withdraw his or her consent, (allowable period on which the data subject may
                                withdraw his/her consent) after collection. Non-receipt of any notice of withdrawal of
                                consent within the prescribed period shall be deemed acceptance of the consent
                                guidelines.
                            </li>

                            <br>To exercise data subjects right and for data privacy concerns or inquiries, please
                            communicate with us through:
                            mylabcliinc@gmail.com
                            </p>

                            <p><br>This is the data privacy policy. Read it carefully and then confirm your acceptance
                                by <br>
                                clicking the<b><u>"I Confirm" </u></b>button below.</p>

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="confirm-privacy" class="btn btn-primary"
                            style="background-color: #0126CC" data-bs-dismiss="modal">I
                            Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="validationMessageModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545; color: white;">
                        <h5 class="modal-title" id="exampleModalLabel">Sign-up Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="validationMessageText"></p>
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
        // Get references to the age and birthday input fields
        const ageInput = document.getElementById('age');
        const birthdayInput = document.getElementById('birthday');

        // Add an event listener to the age input field
        ageInput.addEventListener('input', function () {
            // Calculate the birthdate based on the entered age
            const age = parseInt(ageInput.value);
            if (!isNaN(age)) {
                const today = new Date();
                const birthdate = new Date(today.getFullYear() - age, today.getMonth(), today.getDate());
                const formattedDate = formatDate(birthdate);
                birthdayInput.value = formattedDate;
            }
        });

        // Helper function to format a date as "YYYY-MM-DD"
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        function validateSignupForm() {
            const firstNameInput = document.getElementById("fname");
            const lastNameInput = document.getElementById("lname");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const confirmPasswordInput = document.getElementById("confirm_password");
            const agreeCheckbox = document.getElementById("agree");

            // Reset previous error messages
            document.getElementById('validationMessageText').textContent = "";

            // Check if any of the fields are empty
            if (firstNameInput.value.trim() === "" || lastNameInput.value.trim() === "" ||
                passwordInput.value.trim() === "" || confirmPasswordInput.value.trim() === "" ||
                emailInput.value.trim() === "") {
                document.getElementById('validationMessageText').textContent = "Please fill in all required fields.";
                return false; // Prevent form submission
            }

            // Check if the data privacy checkbox is checked
            if (!agreeCheckbox.checked) {
                document.getElementById('validationMessageText').textContent = "Please read and confirm the Data Privacy Policy.";
                return false; // Prevent form submission
            }

            // Check if passwords match
            if (passwordInput.value !== confirmPasswordInput.value) {
                document.getElementById('validationMessageText').textContent = "Password and Confirm Password do not match.";
                return false; // Prevent form submission
            }

            // Password validation
            const passwordStrengthRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordStrengthRegex.test(passwordInput.value)) {
                document.getElementById('validationMessageText').textContent = "Password must be 8 characters long and include upper case, lower case, numbers, and special characters. (Ex. @$!%*?&)";
                return false; // Prevent form submission
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                document.getElementById('validationMessageText').textContent = "Please enter a valid email address.";
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }

        document.getElementById('signupForm').addEventListener('submit', function (event) {
            if (!validateSignupForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
                $('#validationMessageModal').modal('show'); // Show validation modal
            }
        });

        document.getElementById('modal-btn').addEventListener('click', function () {
            $('#dataPrivacyModal').modal('show');
        });

        document.getElementById('confirm-privacy').addEventListener('click', function () {
            document.getElementById('agree').checked = true;
            $('#dataPrivacyModal').modal('hide');
        });


    </script>






    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pzjw8f+ua8Jw5TIq9zF08+orHre5m7KQfivmB/wq5Fo5R6u6f8hYCEp2pr/8xb2D"
        crossorigin="anonymous"></script>

</body>

</html>