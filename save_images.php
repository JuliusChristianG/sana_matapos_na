<?php
$mysqli_hostname = 'localhost';
$mysqli_username = 'root';
$mysqli_password = '';
$mysqli_database = 'mylabclinic';

$mysqli = new mysqli($mysqli_hostname, $mysqli_username, $mysqli_password, $mysqli_database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$current_background_image = $_POST['currentBackgroundImage'];
$current_about_us_image = $_POST['currentAboutUsImage'];

if ($_FILES['backgroundImage']['size'] > 0) {
    $background_image = $_FILES['backgroundImage']['name'];
    $target_background_image = "../images/" . basename($background_image);
    move_uploaded_file($_FILES["backgroundImage"]["tmp_name"], $target_background_image);
} else {
    $target_background_image = "../images/" . $current_background_image;
}

if ($_FILES['aboutUsImage']['size'] > 0) {
    $about_us_image = $_FILES['aboutUsImage']['name'];
    $target_about_us_image = "../images/" . basename($about_us_image);
    move_uploaded_file($_FILES["aboutUsImage"]["tmp_name"], $target_about_us_image);
} else {
    $target_about_us_image = "../images/" . $current_about_us_image;
}

$sql = "UPDATE homepage_images SET 
            background_image='$target_background_image', 
            about_us_image='$target_about_us_image' 
        WHERE id=1";

if ($mysqli->query($sql) === TRUE) {
    echo '<script>alert("Images updated successfully!");</script>';
} else {
    echo '<script>alert("Error: ' . $sql . ' ' . $mysqli->error . '");</script>';
}

$mysqli->close();
?>
