<?php
session_start();
include 'db_connection.php'; // Ensure this file establishes the database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the user's information
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Handle form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated user details from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $state = $_POST['state'];
    $address = $_POST['address'];

    // Update the user information in the database
    $sql_update = "UPDATE users SET username = ?, email = ?, full_name = ?, phone = ?, state = ?, address = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('ssssssi', $username, $email, $full_name, $phone, $state, $address, $user_id);
    $stmt_update->execute();

    // Check if update was successful
    if ($stmt_update->affected_rows > 0) {
        // Set a success message in the session to be displayed after the page reload
        $_SESSION['update_success'] = true;
        // Redirect to refresh the page
        header("Location: setting.php");
        exit;
    } else {
        echo "<div class='alert alert-warning'>No changes were made.</div>";
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

        <div class="container-xxl py-5">
            <div class="container">
                <h2>Account Settings</h2>

                <!-- Success Message Popup -->
                <?php if (isset($_SESSION['update_success']) && $_SESSION['update_success']): ?>
                    <script>
                        // Show the success message as a popup
                        alert('Your information has been updated successfully!');
                    </script>
                    <?php unset($_SESSION['update_success']); // Clear the success message after displaying it ?>
                <?php endif; ?>

                <form action="setting.php" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select id="state" name="state" class="form-control" required>
                            <option value="">-- Select your state --</option>
                            <?php
                            $states = ["Johor", "Kedah", "Kelantan", "Melaka", "Negeri Sembilan", "Pahang", "Perak", "Perlis", "Pulau Pinang", "Sabah", "Sarawak", "Selangor", "Terengganu", "Kuala Lumpur", "Labuan", "Putrajaya"];
                            foreach ($states as $state_option) {
                                $selected = $user['state'] === $state_option ? 'selected' : '';
                                echo "<option value=\"$state_option\" $selected>$state_option</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
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
