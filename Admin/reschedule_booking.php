<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Get booking ID from URL
$booking_id = $_GET['id'];

// Fetch current booking details
$query = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get new check-in and check-out dates from the form
    $new_check_in = $_POST['check_in_date'];
    $new_check_out = $_POST['check_out_date'];

    // Update booking with new dates
    $update_query = "UPDATE bookings SET check_in_date = ?, check_out_date = ? WHERE booking_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $new_check_in, $new_check_out, $booking_id);
    $update_result = $update_stmt->execute();

    if ($update_result) {
        // Redirect to the dashboard after successful update
        header("Location: admin_dashboard.php?message=Booking%20rescheduled%20successfully");
    } else {
        die("Error updating booking: " . mysqli_error($conn));
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Booking</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>Reschedule Booking</h1>

        <form method="POST" action="reschedule_booking.php?id=<?php echo $booking_id; ?>">
            <div>
                <label for="check_in_date">New Check-in Date</label>
                <input type="date" id="check_in_date" name="check_in_date" value="<?php echo $booking['check_in_date']; ?>" required>
            </div>
            <div>
                <label for="check_out_date">New Check-out Date</label>
                <input type="date" id="check_out_date" name="check_out_date" value="<?php echo $booking['check_out_date']; ?>" required>
            </div>
            <div>
                <button type="submit" class="save">Save Changes</button>
            </div>
        </form>
    </div>

    <footer>
        <p>&copy; DTD3033 Class D - Group 8</p>
    </footer>
</body>
</html>
