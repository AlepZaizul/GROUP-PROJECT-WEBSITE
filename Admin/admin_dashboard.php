<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit;
}

include 'db_connection.php'; // Include database connection

// Fetch rooms and services from the database
$rooms_query = "SELECT * FROM rooms";
$rooms_result = mysqli_query($conn, $rooms_query);

$services_query = "SELECT * FROM services";
$services_result = mysqli_query($conn, $services_query);
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

        <!-- Admin Dashboard Start -->
        <div class="container py-5">
            <h1>Admin Dashboard</h1>
            <div class="row">
                <div class="col-md-6">
                    <h2>Rooms</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($room = mysqli_fetch_assoc($rooms_result)) { ?>
                                <tr>
                                    <td><?php echo $room['name']; ?></td>
                                    <td><?php echo $room['price']; ?></td>
                                    <td>
                                        <a href="edit_room.php?id=<?php echo $room['id']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="delete_room.php?id=<?php echo $room['id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="add_room.php" class="btn btn-primary">Add New Room</a>
                </div>
                <div class="col-md-6">
                    <h2>Services</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($service = mysqli_fetch_assoc($services_result)) { ?>
                                <tr>
                                    <td><?php echo $service['name']; ?></td>
                                    <td><?php echo $service['price']; ?></td>
                                    <td>
                                        <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="add_service.php" class="btn btn-primary">Add New Service</a>
                </div>
            </div>
        </div>
        <!-- Admin Dashboard End -->

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
