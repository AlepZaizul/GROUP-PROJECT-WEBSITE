<?php
include('db_connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch room details
    $sql = "SELECT * FROM rooms WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "Room not found!";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    
    // Handle photo upload
    $photo = $room['photo'];  // Keep the existing photo by default
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = 'uploads/rooms/';
        $photo = $upload_dir . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Update the room details in the database
    $update_sql = "UPDATE rooms SET name='$name', description='$description', price='$price', availability='$availability', photo='$photo' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        echo "Room updated successfully!";
    } else {
        echo "Error updating room: " . $conn->error;
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data">
    <label for="name">Room Name:</label>
    <input type="text" name="name" value="<?php echo $room['name']; ?>" required><br>

    <label for="description">Description:</label>
    <textarea name="description" required><?php echo $room['description']; ?></textarea><br>

    <label for="price">Price:</label>
    <input type="number" name="price" value="<?php echo $room['price']; ?>" required><br>

    <label for="availability">Availability:</label>
    <input type="checkbox" name="availability" <?php echo $room['availability'] == 1 ? 'checked' : ''; ?>><br>

    <label for="photo">Room Photo:</label>
    <input type="file" name="photo"><br>

    <button type="submit">Update Room</button>
</form>

