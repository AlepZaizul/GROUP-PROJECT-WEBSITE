<?php
// Include database connection file
include 'db_connection.php'; 

$sql = "SELECT * FROM rooms";
$result = $conn->query($sql);

?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Our Rooms</h6>
            <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Rooms</span></h1>
        </div>
        <div class="row g-4">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="room-item shadow rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="<?php echo $row['room_image']; ?>" alt="<?php echo $row['room_name']; ?>"> 
                                <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">$<?php echo $row['room_price']; ?>/Night</small>
                            </div>
                            <div class="p-4 mt-2">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="mb-0"><?php echo $row['room_name']; ?></h5>
                                    <div class="ps-2">
                                        <?php 
                                            // You can implement a star rating system here based on your logic
                                            // For example:
                                            echo '<small class="fa fa-star text-primary"></small>'; 
                                            echo '<small class="fa fa-star text-primary"></small>'; 
                                            echo '<small class="fa fa-star text-primary"></small>'; 
                                            echo '<small class="fa fa-star text-primary"></small>'; 
                                            echo '<small class="fa fa-star text-primary"></small>'; 
                                        ?>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <small class="border-end me-3 pe-3"><i class="fa fa-bed text-primary me-2"></i><?php echo $row['tot_bed']; ?> Bed</small>
                                    <small class="border-end me-3 pe-3"><i class="fa fa-bath text-primary me-2"></i><?php echo $row['tot_bath']; ?> Bath</small>
                                    <small><i class="fa fa-wifi text-primary me-2"></i>Wifi</small> 
                                </div>
                                <p class="text-body mb-3"><?php echo $row['room_description']; ?></p>
                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-sm btn-primary rounded py-2 px-4" href="room_details.php?room_id=<?php echo $row['room_id']; ?>">View Detail</a> 
                                    <a class="btn btn-sm btn-dark rounded py-2 px-4" href="<?php echo isset($_SESSION['username']) ? 'booking.php' : 'login.php'; ?>">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No rooms found.";
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>

