<?php
$mysqli_hostname = 'localhost';
$mysqli_username = 'root';
$mysqli_password = '';
$mysqli_database = 'mylabclinic';

$mysqli = new mysqli($mysqli_hostname, $mysqli_username, $mysqli_password, $mysqli_database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_FILES['backgroundImage']['size'] > 0) {
    $background_image = $_FILES['backgroundImage']['name'];
    $target_background_image = "assets/images/" . basename($background_image);
    move_uploaded_file($_FILES["backgroundImage"]["tmp_name"], $target_background_image);
} else {
    $target_background_image = $_POST['currentBackgroundImage'];
}

if ($_FILES['aboutUsImage']['size'] > 0) {
    $about_us_image = $_FILES['aboutUsImage']['name'];
    $target_about_us_image = "assets/images/" . basename($about_us_image);
    move_uploaded_file($_FILES["aboutUsImage"]["tmp_name"], $target_about_us_image);
} else {
    $target_about_us_image = $_POST['currentAboutUsImage'];
}

$sql = "UPDATE homepage_images SET 
            background_image='$target_background_image', 
            about_us_image='$target_about_us_image' 
        WHERE id=1";

if ($mysqli->query($sql) === TRUE) {
    header("Location: AdminCMS.php");
} else {
    echo '<script>alert("Error: ' . $sql . ' ' . $mysqli->error . '");</script>';
}

$mysqli->close();
?>
