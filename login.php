<?php
require("connection.php"); // Assuming you have a database connection established using MySQLi.

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $loginQuery = "SELECT username, password, role, user_id, first_name, last_name, email FROM users WHERE username = ? 
              UNION 
              SELECT username, password, role, user_id, first_name, last_name, email FROM patients WHERE username = ?";
    $stmt = $mysqli->prepare($loginQuery);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['password'];

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $stored_hashed_password)) {
            $role = $row['role'];

            // You might want to consider using a more secure way to handle sessions and redirects.
            session_start();
            date_default_timezone_set('Asia/Manila');
            $_SESSION['authenticated'] = true;
            $_SESSION['role'] = $role;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];

            $date = date("Y-m-d");
            $time = date("H:i:s");
            $login = "Logged in the system";

            if ($role === 'Patient') {
                // Redirect to patientCheck.php
                $response = ['valid' => true, 'redirect' => 'patientDashboard.php'];
                echo json_encode($response);
                exit;
            } else {
                // Redirect based on other roles
                $redirect = '';
                if ($role === 'Admin') {
                    $redirect = 'adminDashboard.php';
                } elseif ($role === 'Staff Radtech') {
                    $redirect = 'staffRadtechPatientRequestsTable.php';
                } elseif ($role === 'Staff Doctor') {
                    $redirect = 'staffDoctorForDiagnosisTable.php';
                } elseif ($role === 'Staff Secretary') {
                    $redirect = 'staffSecretaryDashboard.php';
                }

                $response = ['valid' => true, 'redirect' => $redirect];
                echo json_encode($response);
                exit;
            }
        }

        // Return a JSON response indicating invalid login
        $response = ['valid' => false];
        echo json_encode($response);
        exit;
    }
}
?>