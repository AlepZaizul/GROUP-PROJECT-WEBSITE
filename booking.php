<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_details = null;

// Fetch the user's details from the database
$sql = "SELECT full_name, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_details = $result->fetch_assoc();
}
$stmt->close();

// Fetch room details based on room_id from query parameter
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$room_details = null;

if ($room_id > 0) {
    $sql = "SELECT r.room_name, r.room_price, r.room_image, r.room_description, r.tot_bed, r.tot_bath, r.room_capacity, 
                   GROUP_CONCAT(CONCAT(rb.bed_count, ' ', a.bed_type) SEPARATOR ' + ') AS bed_details
            FROM rooms r
            LEFT JOIN room_beds rb ON r.room_id = rb.room_id
            LEFT JOIN amenities a ON rb.bed_type_id = a.id
            WHERE r.room_id = ?
            GROUP BY r.room_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $room_details = $result->fetch_assoc();
    }
    $stmt->close();
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

        <!-- Booking Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Room Booking</h6>
                    <h1 class="mb-5">Book A <span class="text-primary text-uppercase">Best Room in Town</span></h1>
                </div>

                <!-- Display room details if available -->
                <?php if ($room_details): ?>
                    <div class="row mb-5">
                        <div class="col-lg-6">
                            <img class="img-fluid rounded" src="img/<?php echo $room_details['room_image']; ?>" alt="<?php echo $room_details['room_name']; ?>">
                        </div>
                        <div class="col-lg-6">
                            <h3><?php echo $room_details['room_name']; ?></h3>
                            <p><?php echo $room_details['room_description']; ?></p>
                            <p><strong>Price: </strong>RM<?php echo $room_details['room_price']; ?>/Night</p>
                            <p><strong>Beds: </strong><?php echo $room_details['tot_bed']; ?> Bed(s) (<?php echo $room_details['bed_details']; ?>)</p>
                            <p><strong>Baths: </strong><?php echo $room_details['tot_bath']; ?></p>
                            <p><strong>Capacity: </strong><?php echo $room_details['room_capacity']; ?> Adults</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center text-danger mb-5">
                        <h4>Room details not found. Please try again.</h4>
                    </div>
                <?php endif; ?>

                <!-- Booking form -->
                <div class="row g-5">
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
                    <div class="col-lg-6">
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <form action="book_confirm.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" placeholder="Your Name" 
                                            value="<?php echo htmlspecialchars($user_details['full_name']); ?>" name="name">
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" placeholder="Your Email" 
                                            value="<?php echo htmlspecialchars($user_details['email']); ?>" name="email">
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating date" id="date3" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="checkin" placeholder="Check In" 
                                            data-target="#date3" data-toggle="datetimepicker" name="checkin"/>
                                        <label for="checkin">Check In</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating date" id="date4" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="checkout" placeholder="Check Out" 
                                            data-target="#date4" data-toggle="datetimepicker" name="checkout"/>
                                        <label for="checkout">Check Out</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="select1" name="adults">
                                            <?php if ($room_details): ?>
                                                <?php for ($i = 1; $i <= $room_details['room_capacity']; $i++): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> Adult</option>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                        </select>
                                        <label for="select1">Select Adult</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="select2" name="children">
                                            <option value="0">1 Child</option>
                                            <option value="1">2 Child</option>
                                            <option value="2">3 Child</option>
                                        </select>
                                        <label for="select2">Select Child</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Special Request" id="message" style="height: 100px" name="special_request"></textarea>
                                        <label for="message">Special Request</label>
                                    </div>
                                </div>
                                <!-- Hidden fields to pass the room details -->
                                <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                                <input type="hidden" name="room_name" value="<?php echo $room_details['room_name']; ?>">
                                <input type="hidden" name="room_price" value="<?php echo $room_details['room_price']; ?>">

                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Book Now</button>
                                </div>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Booking End -->

        <!-- Footer Start -->
        <?php include 'footer.php'; ?>
        <!-- Footer End -->

        <!-- JavaScript Libraries -->
        <?php include 'js_lib.php'; ?>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </div>
</body>

</html>
