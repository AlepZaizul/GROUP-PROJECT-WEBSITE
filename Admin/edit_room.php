<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Fetch rooms from the database
$rooms_query = "SELECT * FROM rooms";
$rooms_result = mysqli_query($conn, $rooms_query);
if (!$rooms_result) {
    die("Error fetching rooms: " . mysqli_error($conn));
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
        <h1>Edit Rooms</h1>

        <!-- Rooms Section -->
        <div>
            <h2>Rooms</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Total Beds</th>
                        <th>Total Baths</th>
                        <th>Description</th>
                        <th>Capacity</th>
                        <th>Availability</th>
                        <th>Total Rooms</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($room = mysqli_fetch_assoc($rooms_result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                            <td><?php echo number_format($room['room_price'], 2); ?></td>
                            <td><?php echo $room['tot_bed']; ?></td>
                            <td><?php echo $room['tot_bath']; ?></td>
                            <td><?php echo htmlspecialchars($room['room_description']); ?></td>
                            <td><?php echo $room['room_capacity']; ?></td>
                            <td><?php echo $room['room_availability']; ?></td> <!-- Display room_availability from the database -->
                            <td><?php echo $room['room_tot']; ?></td>
                            <td>
                                <a href="edit_room_form.php?id=<?php echo $room['room_id']; ?>" class="edit">Edit</a>
                                <a href="delete_room.php?id=<?php echo $room['room_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="add_room.php" class="add">Add New Room</a>
        </div>

        <!-- Room Images Section -->
        <div>
            <h2>Room Images</h2>
            <table>
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Room Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Rewind the result pointer to fetch again from the start
                    mysqli_data_seek($rooms_result, 0);

                    while ($room = mysqli_fetch_assoc($rooms_result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                            <td>
                                <?php if ($room['room_image']) { ?>
                                    <img src="../img/<?php echo htmlspecialchars($room['room_image']); ?>" alt="Room Image" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                <?php } else { ?>
                                    <p>No image available</p>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="insert_image.php?id=<?php echo $room['room_id']; ?>" class="edit">Insert Image</a>
                                <a href="javascript:void(0);" class="delete" onclick="confirmDelete(<?php echo $room['room_id']; ?>);">Delete Image</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <footer>
        <p>&copy; DTD3033 Class D - Group 8</p>
    </footer>

    <script>
        function confirmDelete(roomId) {
            if (confirm('Are you sure you want to delete this image?')) {
                // Redirect to delete_image.php to handle image deletion
                window.location.href = 'delete_image.php?id=' + roomId;
            }
        }
    </script>
</body>
</html>
