<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head_class.php';?>
</head>

<body>
    <div class="container-xxl bg-white p-0">

        <!-- Header Start -->
            <?php include 'header.php';?>
        <!-- Header End -->

<body>
    <div class="container-xxl bg-white p-0">

        <!-- Room Start -->
            <?php include 'room_content.php';?>
        <!-- Room End -->  



        <!-- Footer Start -->
            <?php include 'footer.php';?>
        <!-- Footer End -->

        <!-- JavaScript Libraries -->
            <?php include 'js_lib.php';?>

        <!-- Template Javascript -->
            <script src="js/main.js"></script>
</body>

</html>