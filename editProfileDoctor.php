<?php
session_start(); // Start the session to access session variables

// Assuming you have a database connection established already
include('connection.php'); // Include your database connection file

// Assuming you have a "users" table with columns: user_id, username, first_name, last_name, email
$user_id = $_SESSION['user_id']; // Adjust this based on your session variable storing the user's ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the new values from the form
    $newUsername = $_POST['username'];
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];

    // Update the user's information in the database
    $updateQuery = "UPDATE users SET username = '$newUsername', first_name = '$newFirstName', last_name = '$newLastName' WHERE user_id = $user_id";

    if (mysqli_query($mysqli, $updateQuery)) {
        // Update was successful

        // Update the session variables with the new values
        $_SESSION['username'] = $newUsername;
        $_SESSION['first_name'] = $newFirstName;
        $_SESSION['last_name'] = $newLastName;

        header("Location: doctorprofilepage.php"); // Redirect to the profile page after a successful update
        exit();
    } else {
        // Handle the case where the update fails
        die("Update failed: " . mysqli_error($mysqli));
    }
}

// Close the database connection (always recommended)
mysqli_close($mysqli);
?>
