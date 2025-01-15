<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM rooms WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $room = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $update_query = "UPDATE rooms SET name='$name', description='$description', price='$price' WHERE id=$id";
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
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

        <div class="container py-5">
            <h1>Edit Room</h1>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Room Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $room['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $room['description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $room['price']; ?>" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Room</button>
            </form>
        </div>

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
