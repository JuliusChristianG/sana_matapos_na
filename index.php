<?php
$dsn = 'mysql:host=localhost;dbname=mylabClinic';
$username = 'root';
$password = '';

try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Retrieve the company profile text from the database
  $stmt = $pdo->prepare("SELECT information FROM company_profile WHERE id = 1"); // Assuming you have a column named 'company_profile'
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $profile_text_from_database = $row['information']; // Assuming 'company_profile' is the column name
// Retrieve the About Us information from the database
  $stmtAboutUs = $pdo->prepare("SELECT * FROM about_us WHERE id = 1");
  $stmtAboutUs->execute();
  $rowAboutUs = $stmtAboutUs->fetch(PDO::FETCH_ASSOC);

  $mission = $rowAboutUs['mission'];
  $vission = $rowAboutUs['vission'];
  $objectives = $rowAboutUs['objectives'];

  $stmt = $pdo->prepare("SELECT * FROM contact_information WHERE id = 1"); // Assuming you have a row with id 1 for contact information
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $open_hours = $row['open_hours'];
  $contact_number = $row['contact_number'];
  $email_address = $row['email_address'];

  // Fetch doctors data
  $stmt = $pdo->prepare("SELECT * FROM doctors");
  $stmt->execute();
  $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Retrieve homepage_images data
  $stmt = $pdo->prepare("SELECT * FROM homepage_images WHERE id = 1");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $background_image = $row['background_image'];
  $about_us_image = $row['about_us_image'];

  // Retrieve homepage_images data
  $stmt = $pdo->prepare("SELECT * FROM homepage_images WHERE id = 1");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $hero_bg = $row['background_image'];
  $about_bg = $row['about_us_image'];



  // Retrieve services
  $stmt = $pdo->prepare("SELECT * FROM services");
  $stmt->execute();
  $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $pdo->prepare("SELECT title, description FROM faqs");
  $stmt->execute();
  $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>MyLab Clinical Laboratory</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <link href="assets/images/logo-no-bg.png" rel="icon">
  <link href="assets/images/logo-no-bg.png" rel="apple-touch-icon">

  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">


  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">



  <style>
    body {
      font-family: "Open Sans", sans-serif;
      color: #444444;
    }

    a {
      color: #106eea;
      text-decoration: none;
    }

    a:hover {
      color: #3b8af2;
      text-decoration: none;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-family: "Roboto", sans-serif;
    }

    /*--------------------------------------------------------------
# Preloader
--------------------------------------------------------------*/
    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 9999;
      overflow: hidden;
      background: #fff;
    }

    #preloader:before {
      content: "";
      position: fixed;
      top: calc(50% - 30px);
      left: calc(50% - 30px);
      border: 6px solid #106eea;
      border-top-color: #e2eefd;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: animate-preloader 1s linear infinite;
    }

    @keyframes animate-preloader {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /*--------------------------------------------------------------
# Back to top button
--------------------------------------------------------------*/
    .back-to-top {
      position: fixed;
      visibility: hidden;
      opacity: 0;
      right: 15px;
      bottom: 15px;
      z-index: 996;
      background: #106eea;
      width: 40px;
      height: 40px;
      border-radius: 4px;
      transition: all 0.4s;
    }

    .back-to-top i {
      font-size: 28px;
      color: #fff;
      line-height: 0;
    }

    .back-to-top:hover {
      background: #3284f1;
      color: #fff;
    }

    .back-to-top.active {
      visibility: visible;
      opacity: 1;
    }

    /*--------------------------------------------------------------
# Disable aos animation delay on mobile devices
--------------------------------------------------------------*/
    @media screen and (max-width: 768px) {
      [data-aos-delay] {
        transition-delay: 0 !important;
      }
    }

    /*--------------------------------------------------------------
# Top Bar
--------------------------------------------------------------*/
    #topbar {
      background: #106eea;
      height: 40px;
      font-size: 14px;
      transition: all 0.5s;
      color: #fff;
      padding: 0;
    }

    #topbar .contact-info i {
      font-style: normal;
      color: #fff;
    }

    #topbar .contact-info i a,
    #topbar .contact-info i span {
      padding-left: 5px;
      color: #fff;
    }

    #topbar .contact-info i a {
      line-height: 0;
      transition: 0.3s;
      transition: 0.3s;
    }

    #topbar .contact-info i a:hover {
      color: #fff;
      text-decoration: underline;
    }

    /*--------------------------------------------------------------
# Header
--------------------------------------------------------------*/
    #header {
      background: #fff;
      transition: all 0.5s;
      z-index: 997;
      height: 86px;
      box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
    }

    #header.fixed-top {
      height: 70px;
    }

    #header .logo {
      font-size: 30px;
      margin: 0;
      padding: 0;
      line-height: 1;
      font-weight: 600;
      letter-spacing: 0.8px;
      font-family: "Poppins", sans-serif;
    }

    #header .logo a {
      color: #222222;
    }

    #header .logo a span {
      color: #106eea;
    }

    #header .logo img {
      max-height: 40px;
    }



    .scrolled-offset {
      margin-top: 70px;
    }

    /*--------------------------------------------------------------
# Navigation Menu
--------------------------------------------------------------*/
    /**
* Desktop Navigation 
*/
    .navbar {
      padding: 0;
    }

    .navbar ul {
      margin: 0;
      padding: 0;
      display: flex;
      list-style: none;
      align-items: center;
    }

    .navbar li {
      position: relative;
    }

    .navbar>ul>li {
      white-space: nowrap;
      padding: 10px 0 10px 28px;
    }

    .navbar a,
    .navbar a:focus {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 3px;
      font-size: 15px;
      font-weight: 600;
      color: #222222;
      white-space: nowrap;
      transition: 0.3s;
      position: relative;
    }

    .navbar a i,
    .navbar a:focus i {
      font-size: 12px;
      line-height: 0;
      margin-left: 5px;
    }

    .navbar>ul>li>a:before {
      content: "";
      position: absolute;
      width: 100%;
      height: 2px;
      bottom: -6px;
      left: 0;
      background-color: #106eea;
      visibility: hidden;
      width: 0px;
      transition: all 0.3s ease-in-out 0s;
    }

    .navbar a:hover:before,
    .navbar li:hover>a:before,
    .navbar .active:before {
      visibility: visible;
      width: 100%;
    }

    .navbar a:hover,
    .navbar .active,
    .navbar .active:focus,
    .navbar li:hover>a {
      color: #106eea;
    }

    .navbar .dropdown ul {
      display: block;
      position: absolute;
      left: 28px;
      top: calc(100% + 30px);
      margin: 0;
      padding: 10px 0;
      z-index: 99;
      opacity: 0;
      visibility: hidden;
      background: #fff;
      box-shadow: 0px 0px 30px rgba(127, 137, 161, 0.25);
      transition: 0.3s;
    }

    .navbar .dropdown ul li {
      min-width: 200px;
    }

    .navbar .dropdown ul a {
      padding: 10px 20px;
      font-weight: 400;
    }

    .navbar .dropdown ul a i {
      font-size: 12px;
    }

    .navbar .dropdown ul a:hover,
    .navbar .dropdown ul .active:hover,
    .navbar .dropdown ul li:hover>a {
      color: #106eea;
    }

    .navbar .dropdown:hover>ul {
      opacity: 1;
      top: 100%;
      visibility: visible;
    }

    .navbar .dropdown .dropdown ul {
      top: 0;
      left: calc(100% - 30px);
      visibility: hidden;
    }

    .navbar .dropdown .dropdown:hover>ul {
      opacity: 1;
      top: 0;
      left: 100%;
      visibility: visible;
    }

    @media (max-width: 1366px) {
      .navbar .dropdown .dropdown ul {
        left: -90%;
      }

      .navbar .dropdown .dropdown:hover>ul {
        left: -100%;
      }
    }

    /**
* Mobile Navigation 
*/
    .mobile-nav-toggle {
      color: #222222;
      font-size: 28px;
      cursor: pointer;
      display: none;
      line-height: 0;
      transition: 0.5s;
    }

    .mobile-nav-toggle.bi-x {
      color: #fff;
    }

    @media (max-width: 991px) {
      .mobile-nav-toggle {
        display: block;
      }

      .navbar ul {
        display: none;
      }
    }

    .navbar-mobile {
      position: fixed;
      overflow: hidden;
      top: 0;
      right: 0;
      left: 0;
      bottom: 0;
      background: rgba(9, 9, 9, 0.9);
      transition: 0.3s;
      z-index: 999;
    }

    .navbar-mobile .mobile-nav-toggle {
      position: absolute;
      top: 15px;
      right: 15px;
    }

    .navbar-mobile ul {
      display: block;
      position: absolute;
      top: 55px;
      right: 15px;
      bottom: 15px;
      left: 15px;
      padding: 10px 0;
      background-color: #fff;
      overflow-y: auto;
      transition: 0.3s;
    }

    .navbar-mobile a,
    .navbar-mobile a:focus {
      padding: 10px 20px;
      font-size: 15px;
      color: #222222;
    }

    .navbar-mobile>ul>li {
      padding: 0;
    }

    .navbar-mobile a:hover:before,
    .navbar-mobile li:hover>a:before,
    .navbar-mobile .active:before {
      visibility: hidden;
    }

    .navbar-mobile a:hover,
    .navbar-mobile .active,
    .navbar-mobile li:hover>a {
      color: #106eea;
    }

    .navbar-mobile .getstarted,
    .navbar-mobile .getstarted:focus {
      margin: 15px;
    }

    .navbar-mobile .dropdown ul {
      position: static;
      display: none;
      margin: 10px 20px;
      padding: 10px 0;
      z-index: 99;
      opacity: 1;
      visibility: visible;
      background: #fff;
      box-shadow: 0px 0px 30px rgba(127, 137, 161, 0.25);
    }

    .navbar-mobile .dropdown ul li {
      min-width: 200px;
    }

    .navbar-mobile .dropdown ul a {
      padding: 10px 20px;
    }

    .navbar-mobile .dropdown ul a i {
      font-size: 12px;
    }

    .navbar-mobile .dropdown ul a:hover,
    .navbar-mobile .dropdown ul .active:hover,
    .navbar-mobile .dropdown ul li:hover>a {
      color: #106eea;
    }

    .navbar-mobile .dropdown>.dropdown-active {
      display: block;
    }

    /*--------------------------------------------------------------
# Hero Section
--------------------------------------------------------------*/
    #hero {
      width: 100%;
      height: 75vh;
      background: url("<?php echo $hero_bg; ?>") top left;
      background-size: cover;
      position: relative;
    }




    #hero:before {
      content: "";
      background: rgba(255, 255, 255, 0.6);
      position: absolute;
      bottom: 0;
      top: 0;
      left: 0;
      right: 0;
    }

    #hero .container {
      position: relative;
    }

    #hero h1 {
      margin: 0;
      font-size: 48px;
      font-weight: 700;
      line-height: 56px;
      color: #222222;
      font-family: "Poppins", sans-serif;
    }

    #hero h1 span {
      color: #106eea;
    }

    #hero h2 {
      color: #555555;
      margin: 5px 0 30px 0;
      font-size: 24px;
      font-weight: 400;
    }



    #about-bg {
      width: 100%;
      height: 75vh;
      background: url("<?php echo $about_bg; ?>") top left;
      background-size: cover;
      position: relative;
    }


    #about-bg:before {
      content: "";
      background: rgba(255, 255, 255, 0.6);
      position: absolute;
      bottom: 0;
      top: 0;
      left: 0;
      right: 0;
    }

    #about-bg .container {
      position: relative;
    }

    @media (min-width: 1024px) {
      #hero {
        background-attachment: fixed;
      }
    }

    @media (max-width: 768px) {
      #hero {
        height: 100vh;
      }

      #hero h1 {
        font-size: 28px;
        line-height: 36px;
      }

      #hero h2 {
        font-size: 18px;
        line-height: 24px;
        margin-bottom: 30px;
      }

      #hero .btn-get-started,
      #hero .btn-watch-video {
        font-size: 13px;
      }
    }

    @media (max-height: 500px) {
      #hero {
        height: 120vh;
      }
    }

    /*--------------------------------------------------------------
# Sections General
--------------------------------------------------------------*/
    section {
      padding: 60px 0;
      overflow: hidden;
    }

    .section-bg {
      background-color: #f6f9fe;
    }

    .section-title {
      text-align: center;
      padding-bottom: 30px;
    }

    .section-title h2 {
      font-size: 13px;
      letter-spacing: 1px;
      font-weight: 700;
      padding: 8px 20px;
      margin: 0;
      background: #e7f1fd;
      color: #106eea;
      display: inline-block;
      text-transform: uppercase;
      border-radius: 50px;
    }

    .section-title h3 {
      margin: 15px 0 0 0;
      font-size: 32px;
      font-weight: 700;
    }

    .section-title h3 span {
      color: #106eea;
    }

    .section-title p {
      margin: 15px auto 0 auto;
      font-weight: 600;
    }

    @media (min-width: 1024px) {
      .section-title p {
        width: 50%;
      }
    }

    /*--------------------------------------------------------------
# Breadcrumbs
--------------------------------------------------------------*/
    .breadcrumbs {
      padding: 20px 0;
      background-color: #f1f6fe;
      min-height: 40px;
    }

    .breadcrumbs h2 {
      font-size: 24px;
      font-weight: 300;
      margin: 0;
    }

    @media (max-width: 992px) {
      .breadcrumbs h2 {
        margin: 0 0 10px 0;
      }
    }

    .breadcrumbs ol {
      display: flex;
      flex-wrap: wrap;
      list-style: none;
      padding: 0;
      margin: 0;
      font-size: 14px;
    }

    .breadcrumbs ol li+li {
      padding-left: 10px;
    }

    .breadcrumbs ol li+li::before {
      display: inline-block;
      padding-right: 10px;
      color: #6c757d;
      content: "/";
    }

    @media (max-width: 768px) {
      .breadcrumbs .d-flex {
        display: block !important;
      }

      .breadcrumbs ol {
        display: block;
      }

      .breadcrumbs ol li {
        display: inline-block;
      }
    }

    /*--------------------------------------------------------------
# Featured Services
--------------------------------------------------------------*/
    .featured-services .icon-box {
      padding: 30px;
      position: relative;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 0 29px 0 rgba(68, 88, 144, 0.12);
      transition: all 0.3s ease-in-out;
      border-radius: 8px;
      z-index: 1;
    }

    .featured-services .icon-box::before {
      content: "";
      position: absolute;
      background: #cbe0fb;
      right: 0;
      left: 0;
      bottom: 0;
      top: 100%;
      transition: all 0.3s;
      z-index: -1;
    }

    .featured-services .icon-box:hover::before {
      background: #106eea;
      top: 0;
      border-radius: 0px;
    }

    .featured-services .icon {
      margin-bottom: 15px;
    }

    .featured-services .icon i {
      font-size: 48px;
      line-height: 1;
      color: #106eea;
      transition: all 0.3s ease-in-out;
    }

    .featured-services .title {
      font-weight: 700;
      margin-bottom: 15px;
      font-size: 18px;
    }

    .featured-services .title a {
      color: #111;
    }

    .featured-services .description {
      font-size: 15px;
      line-height: 28px;
      margin-bottom: 0;
    }

    .featured-services .icon-box:hover .title a,
    .featured-services .icon-box:hover .description {
      color: #fff;
    }

    .featured-services .icon-box:hover .icon i {
      color: #fff;
    }

    /*--------------------------------------------------------------
# About
--------------------------------------------------------------*/
    .about .content h3 {
      font-weight: 600;
      font-size: 26px;
    }

    .about .content ul {
      list-style: none;
      padding: 0;
    }

    .about .content ul li {
      display: flex;
      align-items: flex-start;
      margin-bottom: 35px;
    }

    .about .content ul li:first-child {
      margin-top: 35px;
    }

    .about .content ul i {
      background: #fff;
      box-shadow: 0px 6px 15px rgba(16, 110, 234, 0.12);
      font-size: 24px;
      padding: 20px;
      margin-right: 15px;
      color: #106eea;
      border-radius: 50px;
    }

    .about .content ul h5 {
      font-size: 18px;
      color: #555555;
    }

    .about .content ul p {
      font-size: 15px;
    }

    .about .content p:last-child {
      margin-bottom: 0;
    }


    /*--------------------------------------------------------------
# Services
--------------------------------------------------------------*/
    .services .icon-box {
      width: 700px;
      text-align: center;
      border: 1px solid #e2eefd;
      padding: 80px 20px;
      transition: all ease-in-out 0.3s;
      background: #fff;
    }

    .services .icon-box .icon {
      margin: 0 auto;
      width: 64px;
      height: 64px;
      background: #f1f6fe;
      border-radius: 4px;
      border: 1px solid #deebfd;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      transition: ease-in-out 0.3s;
    }

    .services .icon-box .icon i {
      color: #3b8af2;
      font-size: 28px;
      transition: ease-in-out 0.3s;
    }

    .services .icon-box h4 {
      font-weight: 700;
      margin-bottom: 15px;
      font-size: 24px;
    }

    .services .icon-box h4 a {
      color: #222222;
      transition: ease-in-out 0.3s;
    }

    .services .icon-box p {
      line-height: 24px;
      font-size: 14px;
      margin-bottom: 0;
    }

    .services .icon-box:hover {
      border-color: #fff;
      box-shadow: 0px 0 25px 0 rgba(16, 110, 234, 0.1);
    }

    .services .icon-box:hover h4 a,
    .services .icon-box:hover .icon i {
      color: #106eea;
    }

    .services .icon-box:hover .icon {
      border-color: #106eea;
    }

    /*--------------------------------------------------------------
# Team
--------------------------------------------------------------*/
    .team {
      padding: 60px 0;
    }

    .team .member {
      margin-bottom: 20px;
      overflow: hidden;
      border-radius: 4px;
      background: #fff;
      box-shadow: 0px 2px 15px rgba(16, 110, 234, 0.15);
    }

    .team .member .member-img {
      position: relative;
      overflow: hidden;
    }

    .team .member .member-info {
      padding: 25px 15px;
    }

    .team .member .member-info h4 {
      font-weight: 700;
      margin-bottom: 5px;
      font-size: 18px;
      color: #222222;
    }

    .team .member .member-info span {
      display: block;
      font-size: 13px;
      font-weight: 400;
      color: #aaaaaa;
    }

    .team .member .member-info p {
      font-style: italic;
      font-size: 14px;
      line-height: 26px;
      color: #777777;
    }


    /*--------------------------------------------------------------
# Frequently Asked Questions
--------------------------------------------------------------*/
    .faq {
      padding: 60px 0;
    }

    .faq .faq-list {
      padding: 0;
      list-style: none;
    }

    .faq .faq-list li {
      border-bottom: 1px solid #d4e5fc;
      margin-bottom: 20px;
      padding-bottom: 20px;
    }

    .faq .faq-list .question {
      display: block;
      position: relative;
      font-family: #106eea;
      font-size: 18px;
      line-height: 24px;
      font-weight: 400;
      padding-left: 25px;
      cursor: pointer;
      color: #0d58ba;
      transition: 0.3s;
    }

    .faq .faq-list i {
      font-size: 16px;
      position: absolute;
      left: 0;
      top: -2px;
    }

    .faq .faq-list p {
      margin-bottom: 0;
      padding: 10px 0 0 25px;
    }

    .faq .faq-list .icon-show {
      display: none;
    }

    .faq .faq-list .collapsed {
      color: black;
    }

    .faq .faq-list .collapsed:hover {
      color: #106eea;
    }

    .faq .faq-list .collapsed .icon-show {
      display: inline-block;
      transition: 0.6s;
    }

    .faq .faq-list .collapsed .icon-close {
      display: none;
      transition: 0.6s;
    }

    /*--------------------------------------------------------------
# Contact
--------------------------------------------------------------*/
    .contact .info-box {
      color: #444444;
      text-align: center;
      box-shadow: 0 0 30px rgba(214, 215, 216, 0.3);
      padding: 20px 0 30px 0;
    }

    .contact .info-box i {
      font-size: 32px;
      color: #106eea;
      border-radius: 50%;
      padding: 8px;
      border: 2px dotted #b3d1fa;
    }

    .contact .info-box h3 {
      font-size: 20px;
      color: #777777;
      font-weight: 700;
      margin: 10px 0;
    }

    .contact .info-box p {
      padding: 0;
      line-height: 24px;
      font-size: 14px;
      margin-bottom: 0;
    }



    @keyframes animate-loading {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }



    /*--------------------------------------------------------------
# Footer
--------------------------------------------------------------*/
    #footer {
      background: #fff;
      padding: 0 0 30px 0;
      color: #444444;
      font-size: 14px;
      background: #f1f6fe;
    }

    #footer .footer-top {
      padding: 60px 0 30px 0;
      background: #fff;
    }

    #footer .footer-top .footer-contact {
      margin-bottom: 30px;
    }

    #footer .footer-top .footer-contact h3 {
      font-size: 24px;
      margin: 0 0 15px 0;
      padding: 2px 0 2px 0;
      line-height: 1;
      font-weight: 700;
    }

    #footer .footer-top .footer-contact h3 span {
      color: #106eea;
    }

    #footer .footer-top .footer-contact p {
      font-size: 14px;
      line-height: 24px;
      margin-bottom: 0;
      font-family: "Roboto", sans-serif;
      color: #777777;
    }

    #footer .footer-top h4 {
      font-size: 16px;
      font-weight: bold;
      color: #444444;
      position: relative;
      padding-bottom: 12px;
    }

    #footer .footer-top .footer-links {
      margin-bottom: 30px;
    }

    #footer .footer-top .footer-links ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #footer .footer-top .footer-links ul i {
      padding-right: 2px;
      color: #106eea;
      font-size: 18px;
      line-height: 1;
    }

    #footer .footer-top .footer-links ul li {
      padding: 10px 0;
      display: flex;
      align-items: center;
    }

    #footer .footer-top .footer-links ul li:first-child {
      padding-top: 0;
    }

    #footer .footer-top .footer-links ul a {
      color: #777777;
      transition: 0.3s;
      display: inline-block;
      line-height: 1;
    }

    #footer .footer-top .footer-links ul a:hover {
      text-decoration: none;
      color: #106eea;
    }

    .login-box h1 {
      text-align: center;
      font-size: 36px;
      margin-bottom: 40px;
    }

    .login-box label {
      display: block;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 18px;
      border: none;
      border-radius: 5px;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    }

    .login-box input[type="button"] {
      background-color: #4CAF50;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 18px;
      border-radius: 5px;
      cursor: pointer;
    }


    /*--------------------------------------------------------------
# Login/patient
--------------------------------------------------------------*/

    .login-box {
      width: 900px;
      background-color: #fff;
      padding: 40px;
      margin: 100px auto;
      border-radius: 5px;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    }

    .login-box h1 {
      text-align: center;
      font-size: 36px;
      margin-bottom: 40px;
    }

    .login-box label {
      display: block;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 18px;
      border: none;
      border-radius: 5px;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    }

    .login-box input[type="button"] {
      background-color: #0126CC;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 18px;
      border-radius: 5px;
      cursor: pointer;
    }

    .logo {
      width: 250px;
      margin-bottom: 20px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    /* Add this code to your CSS file */
    @media (max-width: 991px) {
      .navbar ul {
        display: none;
      }

      .navbar-mobile {
        display: block;
        /* Add this line to make sure it's visible */
        background: rgba(9, 9, 9, 0.9);
      }

      .navbar-mobile.active {
        display: block;
      }

      .navbar-mobile ul {
        display: block;
      }
    }
  </style>
</head>

<body>


  <!-- ======= Header ======= -->

  <header id="header" class="d-flex align-items-center">

    </div>
    <div class="container d-flex align-items-center justify-content-between">
      <img src="assets/images/logo-no-bg.png" alt="logo" class="img-fluid" style="  max-width: 70px; height: auto;">


      <div class="container">

        <h1 class="logo"><a href="index.php"> MyLab <span>Clinical Laboratory</span></a></h1>

      </div>
      <div class="mobile-nav-toggle">
        <i class="bi bi-list"></i>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">About Us</a></li>
          <li><a class="nav-link scrollto" href="#services">Services</a></li>
          <li><a class="nav-link scrollto" href="#team">Careers</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contact Us</a></li>
          <li><a class="nav-link scrollto" href="loginform.php">Log-in</a></li>

          <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Background Image  ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container" data-aos="zoom-out" data-aos-delay="100">

      <h1>Fully Automated </h1>
      <h1><span> Clinical Laboratory</span></h1>
      <h2>My Lab Clinical Laboratory is equipped with modern and fully automated machines
        that gives our patient a faster, more reliable, more accurate, and high quality test results.</h2>

    </div>
  </section><!-- End of Background Image  -->



  <main id="main">


    <!-- Start of Featured Services Section  -->
    <section id="featured-services" class="featured-services">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-clock"></i></div>
              <h4 class="title"><a href="">Open Hours</a></h4>
              <p class="description">The clinic is always open at <b>
                  <?php echo $open_hours; ?>
                </b>, you can easily
                visit the
                clinic wihout an appointment!</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
              <div class="icon"><i class="bi bi-facebook"></i></div>
              <h4 class="title"><a href="">Facebook Account</a></h4>
              <p class="description">The official Facebook page of MyLab Clinical Laboratory!</p>
              <a href="https://www.facebook.com/MyLabClinicalLaboratorySanJuanBatangas" button type="button"
                class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">
                Visit Us
              </a>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
              <div class="icon"><i class="bi bi-lightbulb-fill"></i></div>
              <h4 class="title"><a href="">FAQs</a></h4>
              <p class="description">View the FAQs of MyLab Clinical Laboratory! Easily view questions that are
                frequently asked in the Clinic!</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
              <div class="icon"><i class="bi bi-geo-alt-fill"></i></div>
              <h4 class="title"><a href="">Location of MyLab Clinic</a></h4>
              <p class="description">MyLab Clinical Laboratory is located at <b>Luna St., Población,
                  San Juan, Batangas.</b></p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Featured Services Section -->


    <!-- ======= About Section of MyLab ======= -->

    <section id="about" class="about section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>About MyLab</h2>
          <h3>Get to know <span> Us!</span></h3>



        </div>

        <section id="about-bg" class="d-flex align-items-center">
          <div class="container" data-aos="zoom-out" data-aos-delay="100">


          </div>
        </section>


        <div class="container">

          <h1 class="text-start"><br> Mission </h1>
          <p>
            <?php echo $mission; ?>
          </p>


          <br>
          <h1 class="text-start">Vision</h1>
          <p class="text-start">
            <?php echo $vission; ?>
          </p>

          <h1 class="text-start"><br> Objectives</h1>
          <ul>
            <li>
              <?php echo $objectives; ?>
            </li>

          </ul>
        </div>

        <br>



        <div class="container">
          <div class="section-title">

            <h3>Values<span></span></h3>

          </div>

          <div class="container">


            <section id="featured-services" class="featured-services">
              <div class="container" data-aos="fade-up">

                <div class="row">
                  <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="icon"><i class=""></i></div>
                      <h4 class="title"><a href="">Quality</a></h4>
                      <p class="description fixed-size">We are committed to excellence in all we do.</p>
                    </div>
                  </div>

                  <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                      <div class="icon"><i class=""></i></div>
                      <h4 class="title"><a href="">Integrity</a></h4>
                      <p class="description">We conduct our business with highest level of ethics, honesty, and
                        professionalism.</p>
                    </div>
                  </div>

                  <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
                      <div class="icon"><i class=""></i></div>
                      <h4 class="title"><a href="">Respect</a></h4>
                      <p class="description"> In our clinic, we treat everyone with dignity. </p>
                    </div>
                  </div>


                  <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
                      <div class="icon"><i class=""></i></div>
                      <h4 class="title"><a href="">Accountability</a></h4>
                      <p class="description">We accept responsibility for our actions and commitment.</p>
                    </div>
                  </div>

                  <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0 mx-auto mt-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
                      <div class="icon"><i class=""></i></div>
                      <h4 class="title"><a href="">Responsiveness</a></h4>
                      <p class="description">We will meet the expectation of patient and needs of our staff. </p>
                    </div>
                  </div>
                </div>
              </div>



            </section>

            <!-- Company Profile Section  -->

            <div class="container">
              <div class="section-title">
                <h3>Company <span>Profile</span></h3>
              </div>

              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-justify">
                    <p>
                      <?php echo $profile_text_from_database; ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
    </section><!-- End of Company Profile Section -->


    <!--  Services Section  -->
    <!-- Services Section -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Services</h2>
          <h3>Our <span>Services Offered</span></h3>
        </div>

        <div class="row">
          <?php $counter = 0; ?>
          <?php foreach ($services as $service): ?>
            <div class="col-md-4 col-12 mb-4 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
              <div class="icon-box">
                <h4><a href="">
                    <?php echo $service['service_title']; ?>
                  </a></h4>
                <ul style="list-style-type: none;">
                  <?php
                  for ($i = 1; $i <= 10; $i++) {
                    $serviceField = 'service_' . $i;
                    if (!empty($service[$serviceField])) {
                      echo '<li>' . $service[$serviceField] . '</li>';
                    }
                  }
                  ?>
                </ul>
              </div>
            </div>

            <?php $counter++; ?>
            <?php if ($counter % 3 === 0): ?>
              <div class="col-12 mb-4"></div><!-- Add space after every three cards on all screens -->
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </section>



    <!-- Doctor's Information -->
    <section id="team" class="team section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Doctors</h2>
          <h3>Our <span>Hardworking Doctors</span></h3>
          <p>Embark on a journey towards better health and well-being with our team of expert doctors. Visit our clinic
            today and experience the exceptional care that awaits you at our clinic.</p>
        </div>

        <div class="row">
          <?php foreach ($doctors as $doctor): ?>
            <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
              <div class="member">
                <div class="member-img">
                  <img src="<?php echo $doctor['image']; ?>" class="img-fluid" alt=""
                    style="height: 400px; width: 400px;">
                </div>
                <div class="member-info">
                  <h4>
                    <?php echo $doctor['first_name'] . ' ' . $doctor['last_name']; ?>
                  </h4>
                  <span>
                    <?php echo $doctor['specialization']; ?>
                  </span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </section><!-- End Team Section -->

    <section id="faq" class="faq section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>F.A.Q</h2>
          <h3>Frequently Asked <span>Questions</span></h3>
        </div>

        <div class="row justify-content-center">
          <div class="col-xl-10">
            <ul class="faq-list">
              <?php foreach ($faqs as $index => $faq): ?>
                <li>
                  <div data-bs-toggle="collapse" class="collapsed question" href="#faq<?php echo $index + 1; ?>">
                    <?php echo $faq['title']; ?> <i class="bi bi-chevron-down icon-show"></i><i
                      class="bi bi-chevron-up icon-close"></i>
                  </div>
                  <div id="faq<?php echo $index + 1; ?>" class="collapse" data-bs-parent=".faq-list">
                    <p>
                      <?php echo $faq['description']; ?>
                    </p>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section  -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Contact</h2>
          <h3><span>Contact Us</span></h3>

        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-3">
            <div class="info-box mb-4">
              <i class="bx bx-map"></i>
              <h3>Our Address</h3>
              <p>Gen Luna St., Población,
                San Juan, Batangas</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box mb-4">
              <i class="bx bx-envelope"></i>
              <h3>Email Us</h3>
              <p>
                <?php echo $email_address; ?>
              </p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-phone-call"></i>
              <h3>Phone Number</h3>
              <p>
                <?php echo $contact_number; ?>
              </p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class='bx bxl-facebook-circle'></i>
              <h3>Facebook</h3>
              <a href="https://www.facebook.com/MyLabClinicalLaboratorySanJuanBatangas" button type="button"
                class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">
                Visit Us
              </a>
            </div>
          </div>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">

          <div class="d-flex justify-content-center align-items-center ">
            <iframe class="mb-4 mb-lg-0"
              src="https://maps.google.com/maps?q=Gen Luna St., Población,                  San Juan, Batangas&t=k&z=18&ie=UTF8&iwloc=&output=embed"
              frameborder="0" style="border:0; width: 100%; height: 384px;" allowfullscreen></iframe>
          </div>



        </div>

      </div>

      </div>
    </section>

  </main>


  <footer id="footer">




    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">

            <h3>MyLab <span>Clinical Laboratory</span></h3>


            <img src="assets/images/logo-no-bg.png" alt="logo" class="img-fluid"
              style="max-width: 100px; height: auto;">


            <p>

              <strong>Phone:
                <?php echo $contact_number; ?>
              </strong> <br>
              <strong>Email:</strong>
              <?php echo $email_address; ?><br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">

          </div>

          <div class="col-lg-3 col-md-6 footer-links">

          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
              <li><a class="nav-link scrollto" href="#about">About Us</a></li>
              <li><a class="nav-link scrollto" href="#services">Services</a></li>




              <li><a class="nav-link scrollto" href="#team">Careers</a></li>
              <li><a class="nav-link scrollto" href="#contact">Contact Us</a></li>
              <li><a class="nav-link scrollto" href="loginform.php">Log-in</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>


    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center mt-5">
            <p>&copy; 2023 All rights reserved. MyLab Clinical Laboratory</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>


    <script src="./assets/javascript/main.js"></script>

</body>

</html>