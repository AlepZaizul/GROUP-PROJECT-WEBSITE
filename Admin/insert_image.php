<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

if (isset($_POST['submit'])) {
    $room_id = $_GET['id']; // Get room ID from URL
    $image = $_FILES['room_image'];

    // Check if the image is uploaded and is valid
    if ($image['error'] === 0) {
        // Get image details
        $image_name = $image['name'];
        $image_tmp = $image['tmp_name'];
        $image_size = $image['size'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Allow only certain image extensions (jpg, jpeg, png)
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (in_array(strtolower($image_ext), $allowed_extensions)) {
            // Check image size (max 5MB)
            if ($image_size <= 5000000) {
                // Generate a unique name for the image
                $new_image_name = uniqid('', true) . '.' . $image_ext;
                $upload_dir = '../img/';
                $upload_path = $upload_dir . $new_image_name;

                // Move the uploaded image to the directory
                if (move_uploaded_file($image_tmp, $upload_path)) {
                    // Update the room's image in the database
                    $update_query = "UPDATE rooms SET room_image = ? WHERE room_id = ?";
                    $stmt = mysqli_prepare($conn, $update_query);
                    mysqli_stmt_bind_param($stmt, 'si', $new_image_name, $room_id);

                    if (mysqli_stmt_execute($stmt)) {
                        echo "Image uploaded successfully.";
                        header("Location: edit_room.php");
                    } else {
                        echo "Error updating image in the database: " . mysqli_error($conn);
                    }
                } else {
                    echo "Failed to upload the image.";
                }
            } else {
                echo "Image size should not exceed 5MB.";
            }
        } else {
            echo "Invalid image type. Only JPG, JPEG, and PNG are allowed.";
        }
    } else {
        echo "Error uploading image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Room Image</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>Insert Room Image</h1>

        <form action="insert_image.php?id=<?php echo $_GET['id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="room_image">Choose Image:</label>
            <input type="file" name="room_image" id="room_image" required><br><br>
            <button type="submit" name="submit">Upload Image</button>
        </form>
        <br>
        <a href="edit_room.php">Back to Room List</a>
    </div>

    <footer>
        <p>&copy; DTD3033 Class D - Group 8</p>
    </footer>
</body>
</html>
