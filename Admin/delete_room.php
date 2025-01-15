<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_query = "DELETE FROM rooms WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
