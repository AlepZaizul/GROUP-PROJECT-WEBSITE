<?php
session_start();
include 'db_connection.php'; // Ensure this file establishes the database connection

// Fetch total number of rooms
$sql = "SELECT SUM(room_tot) AS total_rooms FROM rooms";
$result = $conn->query($sql);
$total_rooms = 0;

if ($result && $row = $result->fetch_assoc()) {
    $total_rooms = $row['total_rooms'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head_class.php'; ?>
</head>

<body>
    <div class="container-xxl bg-white p-0">

        <!-- Header Start -->
        <?php include 'header.php'; ?>
        <!-- Header End -->

        <body>
            <div class="container-xxl bg-white p-0">

                <!-- About Start -->
                <div class="container-xxl py-5">
                    <div class="container">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-6">
                                <h6 class="section-title text-start text-primary text-uppercase">About Us</h6>
                                <h1 class="mb-4">Welcome to <span class="text-primary text-uppercase">Grand Tanjong Malim Hotel</span></h1>
                                <p class="mb-4">"Experience the true essence of Tanjong Malim at Grand Tanjong Malim Hotel. Our hotel is more than just a place to stay; it's a gateway to the local culture and traditions. Discover the unique charm of our town while enjoying the comforts of home."</p>
                                <div class="row g-3 pb-4">
                                    <div class="col-sm-4 wow fadeIn" data-wow-delay="0.1s">
                                        <div class="border rounded p-1">
                                            <div class="border rounded text-center p-4">
                                                <i class="fa fa-hotel fa-2x text-primary mb-2"></i>
                                                <h2 class="mb-1" data-toggle="counter-up"><?php echo $total_rooms; ?></h2>
                                                <p class="mb-0">Rooms</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 wow fadeIn" data-wow-delay="0.3s">
                                        <div class="border rounded p-1">
                                            <div class="border rounded text-center p-4">
                                                <i class="fa fa-users-cog fa-2x text-primary mb-2"></i>
                                                <h2 class="mb-1" data-toggle="counter-up">20</h2>
                                                <p class="mb-0">Staffs</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 wow fadeIn" data-wow-delay="0.5s">
                                        <div class="border rounded p-1">
                                            <div class="border rounded text-center p-4">
                                                <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                                <h2 class="mb-1" data-toggle="counter-up">505</h2>
                                                <p class="mb-0">Reviews</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-primary py-3 px-5 mt-2" href="room.php">Book Now</a>
                            </div>
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <div class="col-6 text-end">
                                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s" src="img/about-1.jpg" style="margin-top: 25%;">
                                    </div>
                                    <div class="col-6 text-start">
                                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s" src="img/about-2.jpg">
                                    </div>
                                    <div class="col-6 text-end">
                                        <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s" src="img/about-3.jpg">
                                    </div>
                                    <div class="col-6 text-start">
                                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s" src="img/about-4.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- About End -->

                <!-- Footer Start -->
                <?php include 'footer.php'; ?>
                <!-- Footer End -->

                <!-- JavaScript Libraries -->
                <?php include 'js_lib.php'; ?>

                <!-- Template Javascript -->
                <script src="js/main.js"></script>
</body>

</html>
