<?php
include('db_connection.php');

// Fetch all bookings
$sql = "SELECT bookings.id, bookings.booking_date, bookings.check_in_date, bookings.check_out_date, bookings.status, rooms.name AS room_name, users.username AS user_name
        FROM bookings
        JOIN rooms ON bookings.room_id = rooms.id
        JOIN users ON bookings.user_id = users.id";
$result = $conn->query($sql);
?>

<div class="container">
    <h2>All Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Room</th>
                <th>Booking Date</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['user_name'] . "</td>";
                    echo "<td>" . $row['room_name'] . "</td>";
                    echo "<td>" . $row['booking_date'] . "</td>";
                    echo "<td>" . $row['check_in_date'] . "</td>";
                    echo "<td>" . $row['check_out_date'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No bookings found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
