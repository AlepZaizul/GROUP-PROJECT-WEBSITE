<?php
session_start();
include 'db_connection.php'; // Ensure this file establishes the database connection

// Get the POST data from the form
$room_id = $_POST['room_id'];
$room_name = $_POST['room_name'];
$room_price = $_POST['room_price'];
$name = $_POST['name'];
$email = $_POST['email'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$adults = $_POST['adults'];
$children = $_POST['children'];
$special_request = $_POST['special_request'];

// Fetch user details (Address, Phone, State) from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT address, phone, state FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_details = $result->fetch_assoc();
$stmt->close();

// Calculate the total number of days
$checkin_date = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$interval = $checkin_date->diff($checkout_date);
$total_days = $interval->days; // Get the difference in days

// Calculate the total price
$total_price = $room_price * $total_days;
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

        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Booking Confirmation</h6>
                    <h1 class="mb-5">Confirm Your Booking</h1>
                </div>

                <!-- Booking Detail Start -->
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <h3>Room: <?php echo htmlspecialchars($room_name); ?></h3>
                        <p><strong>Price: </strong>RM<?php echo number_format($room_price, 2); ?>/Night</p>
                        <p><strong>Total Price for <?php echo $total_days; ?> Day(s): </strong>RM<?php echo number_format($total_price, 2); ?></p>
                        <p><strong>Check-in Date: </strong><?php echo htmlspecialchars($checkin); ?></p>
                        <p><strong>Check-out Date: </strong><?php echo htmlspecialchars($checkout); ?></p>
                        <p><strong>Adults: </strong><?php echo htmlspecialchars($adults); ?></p>
                        <p><strong>Children: </strong><?php echo htmlspecialchars($children); ?></p>
                        <p><strong>Special Request: </strong><?php echo htmlspecialchars($special_request); ?></p>
                    </div>
                    <div class="col-lg-6">
                        <h3>Your Details</h3>
                        <p><strong>Name: </strong><?php echo htmlspecialchars($name); ?></p>
                        <p><strong>Email: </strong><?php echo htmlspecialchars($email); ?></p>

                        <!-- Address, Phone, State -->
                        <div class="form-group mb-3">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" value="<?php echo htmlspecialchars($user_details['state']); ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" value="<?php echo htmlspecialchars($user_details['address']); ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" value="<?php echo htmlspecialchars($user_details['phone']); ?>">
                        </div>
                    </div>
                </div>
                <!-- Booking Detail End -->

                <!-- Total Price Section Start -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-6 text-center">
                        <h2><strong>Total Price: RM<?php echo number_format($total_price, 2); ?></strong></h2>
                        <form action="book_payment.php" method="post">
                            <!-- Include all necessary form fields to process the booking -->
                            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                            <input type="hidden" name="room_name" value="<?php echo $room_name; ?>">
                            <input type="hidden" name="room_price" value="<?php echo $room_price; ?>">
                            <input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
                            <input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
                            <input type="hidden" name="adults" value="<?php echo $adults; ?>">
                            <input type="hidden" name="children" value="<?php echo $children; ?>">
                            <input type="hidden" name="special_request" value="<?php echo $special_request; ?>">

                            <button type="submit" class="btn btn-primary py-3 px-5">Confirm Booking</button>
                        </form>
                    </div>
                </div>
                <!-- Total Price Section End -->

                <!-- Footer Start -->
                <?php include 'footer.php'; ?>
                <!-- Footer End -->

                <!-- JavaScript Libraries -->
                <?php include 'js_lib.php'; ?>

                <!-- Template Javascript -->
                <script src="js/main.js"></script>
            </div>
        </div>
    </div>
</body>

</html>
