<?php
session_start();

// Check if user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Fetch room details based on room_id from the query string
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Fetch the room details from the database
    $room_query = "SELECT * FROM rooms WHERE room_id = $room_id";
    $room_result = mysqli_query($conn, $room_query);
    if (!$room_result) {
        die("Error fetching room details: " . mysqli_error($conn));
    }

    $room = mysqli_fetch_assoc($room_result);
    if (!$room) {
        die("Room not found.");
    }
} else {
    die("Room ID is not specified.");
}

// Handle form submission for updating the room details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated room details from the form
    $room_name = $_POST['room_name'];
    $room_price = $_POST['room_price'];
    $tot_bed = $_POST['tot_bed'];
    $tot_bath = $_POST['tot_bath'];
    $room_description = $_POST['room_description'];
    $room_capacity = $_POST['room_capacity'];
    $room_availability = $_POST['room_availability'];
    $room_tot = $_POST['room_tot'];

    // Handle image upload
    $room_image = $room['room_image']; // Default value if no new image is uploaded
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0) {
        $image_tmp = $_FILES['room_image']['tmp_name'];
        $image_name = $_FILES['room_image']['name'];
        $image_path = "../img/" . basename($image_name);

        // Upload the image
        if (move_uploaded_file($image_tmp, $image_path)) {
            $room_image = $image_name; // Update with new image name
        } else {
            echo "Failed to upload image.";
        }
    }

    // Update the room details in the database
    $update_query = "
        UPDATE rooms
        SET room_name = '$room_name', room_price = '$room_price', tot_bed = '$tot_bed', tot_bath = '$tot_bath',
            room_description = '$room_description', room_capacity = '$room_capacity', room_availability = '$room_availability',
            room_tot = '$room_tot', room_image = '$room_image'
        WHERE room_id = $room_id
    ";

    if (mysqli_query($conn, $update_query)) {
        // Redirect with success message
        echo "<script>
                alert('Room updated successfully!');
                window.location.href = 'edit_room.php';
              </script>";
        exit;
    } else {
        die("Error updating room details: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>Edit Room Details</h1>

        <!-- Edit Room Form -->
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label for="room_name">Room Name</label>
                <input type="text" id="room_name" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" required>
            </div>
            <div>
                <label for="room_price">Room Price</label>
                <input type="number" id="room_price" name="room_price" value="<?php echo $room['room_price']; ?>" required>
            </div>
            <div>
                <label for="tot_bed">Total Beds</label>
                <input type="number" id="tot_bed" name="tot_bed" value="<?php echo $room['tot_bed']; ?>" required>
            </div>
            <div>
                <label for="tot_bath">Total Baths</label>
                <input type="number" id="tot_bath" name="tot_bath" value="<?php echo $room['tot_bath']; ?>" required>
            </div>
            <div>
                <label for="room_description">Room Description</label>
                <textarea id="room_description" name="room_description" required><?php echo htmlspecialchars($room['room_description']); ?></textarea>
            </div>
            <div>
                <label for="room_capacity">Room Capacity</label>
                <input type="number" id="room_capacity" name="room_capacity" value="<?php echo $room['room_capacity']; ?>" required>
            </div>
            <div>
                <label for="room_tot">Total Rooms</label>
                <input type="number" id="room_tot" name="room_tot" value="<?php echo $room['room_tot']; ?>" required>
            </div>
            <div>
                <label for="room_availability">Room Availability</label>
                <input type="number" id="room_availability" name="room_availability" value="<?php echo $room['room_availability']; ?>" required>
            </div>
            <div>
                <button type="submit">Update Room</button>
            </div>
        </form>
    </div>

    <footer>
        <p>&copy; DTD3033 Class D - Group 8</p>
    </footer>

</body>
</html>
