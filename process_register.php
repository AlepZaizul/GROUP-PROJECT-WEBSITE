<?php
// Include database connection
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the query to check if username or email already exists
    $check_username_sql = "SELECT * FROM users WHERE username = ?";
    $check_email_sql = "SELECT * FROM users WHERE email = ?";

    $stmt_username = $conn->prepare($check_username_sql);
    $stmt_email = $conn->prepare($check_email_sql);

    $stmt_username->bind_param("s", $username);
    $stmt_email->bind_param("s", $email);

    // Execute the username query
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();

    if (mysqli_num_rows($result_username) > 0) {
        echo "Username is already taken!";
        exit;
    }

    // Close the result set before executing the next query
    $result_username->free();

    // Execute the email query
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();

    if (mysqli_num_rows($result_email) > 0) {
        echo "Email is already registered!";
        exit;
    }

    // Close the result set before continuing
    $result_email->free();

    // Insert the user data into the database
    $insert_sql = "INSERT INTO users (username, email, full_name, phone, password, address, state, role) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";

    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("ssssssss", $username, $email, $full_name, $phone, $hashed_password, $address, $state);

    if ($stmt_insert->execute()) {
        echo "Registration successful!";
        // Redirect to login page or another page
        header('Location: login.php');
    } else {
        echo "Error: " . $stmt_insert->error;
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
