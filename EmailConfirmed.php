<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href=assets/images/mylablogo.png type="image/x-icon">
    <link rel="stylesheet" href="assets/css/confirmEmail.css">
     <link rel="stylesheet" href="assets/css/wave.css">
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
                    <?php
                    session_start();
                    require_once 'connection.php'; // Include your database connection file

                    $token = $_GET["token"];

                    // Retrieve user ID associated with the token
                    $sql = "SELECT id FROM email_confirmations WHERE token = ?";
                    try {
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$token]);
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $user_id = $result["id"];

                            // Mark the user as verified
                            $sql = "UPDATE users SET is_verified = 1 WHERE user_id = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$user_id]);

                            // Delete the confirmation token
                            $sql = "DELETE FROM email_confirmations WHERE id = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$user_id]);

                            echo '<img src="assets/images/check.png" class="img-fluid">';
                            echo "Email confirmed successfully. You can now <a href='loginform.php'>log in</a>.";
                        } else {
                            echo "Invalid token.";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div>
       <div class="wave"></div>
       <div class="wave"></div>
       <div class="wave"></div>
    </div>
</body>
</html>
