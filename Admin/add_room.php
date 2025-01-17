<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $room_name = mysqli_real_escape_string($conn, $_POST['room_name']);
    $room_price = floatval($_POST['room_price']);
    $tot_bed = intval($_POST['tot_bed']);
    $tot_bath = intval($_POST['tot_bath']);
    $room_description = mysqli_real_escape_string($conn, $_POST['room_description']);
    $room_capacity = intval($_POST['room_capacity']);
    $room_tot = intval($_POST['room_tot']);
    $room_availability = isset($_POST['room_availability']) ? 1 : 0;

    // Handle file upload
    $room_image = null;
    if (!empty($_FILES['room_image']['name'])) {
        $target_dir = "../img/";
        $room_image = basename($_FILES['room_image']['name']);
        $target_file = $target_dir . $room_image;
        
        if (move_uploaded_file($_FILES['room_image']['tmp_name'], $target_file)) {
            echo "Image uploaded successfully.";
        } else {
            $room_image = null;
            echo "Error uploading image.";
        }
    }

    // Insert the new room into the database
    $sql = "INSERT INTO rooms (room_name, room_price, tot_bed, tot_bath, room_description, room_capacity, room_tot, room_availability, room_image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiisiiis", $room_name, $room_price, $tot_bed, $tot_bath, $room_description, $room_capacity, $room_tot, $room_availability, $room_image);

    if ($stmt->execute()) {
        echo "<script>alert('Room added successfully!'); window.location.href='edit_room.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <div class="container">
        <h1>Add New Room</h1>
        <form action="add_room.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="room_name">Room Name:</label>
                <input type="text" id="room_name" name="room_name" required>
            </div>
            <div>
                <label for="room_price">Room Price:</label>
                <input type="number" id="room_price" name="room_price" step="0.01" required>
            </div>
            <div>
                <label for="tot_bed">Total Beds:</label>
                <input type="number" id="tot_bed" name="tot_bed" required>
            </div>
            <div>
                <label for="tot_bath">Total Baths:</label>
                <input type="number" id="tot_bath" name="tot_bath" required>
            </div>
            <div>
                <label for="room_description">Description:</label>
                <textarea id="room_description" name="room_description" required></textarea>
            </div>
            <div>
                <label for="room_capacity">Capacity:</label>
                <input type="number" id="room_capacity" name="room_capacity" required>
            </div>
            <div>
                <label for="room_tot">Total Rooms:</label>
                <input type="number" id="room_tot" name="room_tot" required>
            </div>
            <div>
                <label for="room_availability">Available:</label>
                <input type="checkbox" id="room_availability" name="room_availability">
            </div>
            <div>
                <label for="room_image">Room Image:</label>
                <input type="file" id="room_image" name="room_image" accept="image/*">
            </div>
            <div>
                <button type="submit">Add Room</button>
            </div>
        </form>
    </div>
</body>
</html>
