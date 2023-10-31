<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code Error</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href=assets/images/mylablogo.png type="image/x-icon">
    <link rel="stylesheet" href="assets/css/confirmEmail.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

</head>
<body>
    <div class="container">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box text-center">
                <div class="featured-image mb-3">
                    <img src="assets/images/mylabLogo.png" class="img-fluid">
                </div>
                <div class="check-mark-image mb-3">
                    <img src="assets/images/exmark.png" class="img-fluid">
                </div>
                <p class="text-black fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">
                    <div><?php echo "Verification code does not match. Please check again."; ?></div>
                    <div id="countdown"></div>
                </p>
            </div>
        </div>
    </div>

    <script>
        function countdown() {
            var count = 5; // Set the initial count value
            var countdownElement = document.getElementById('countdown');

            var countdownInterval = setInterval(function() {
                countdownElement.textContent = 'Redirecting in ' + count + ' seconds...';
                count--;

                if (count === -1) {
                    clearInterval(countdownInterval);
                     window.history.back(); // Go back to previous page
                }
            }, 1000); // Update every 1 second
        }

        countdown(); // Start the countdown immediately
    </script>
</body>

</html>