<?php
session_start();
include 'db_connection.php'; // Ensure this file establishes the database connection

// Fetch team members from the database
$sql = "SELECT name, matric FROM team";
$result = $conn->query($sql);
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
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Our Team</h6>
                    <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Staffs</span></h1>
                </div>
                <div class="row g-4">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                                // Set the image for each team member based on their name
                                $image = '';
                                switch (strtolower($row['name'])) {
                                    case 'muhammad muqri bin mohamad shaberi':
                                        $image = 'muq.jpg';
                                        break;
                                    case 'muhammad alif bin zaizul':
                                        $image = 'alep.jpg';
                                        break;
                                    case 'h m morshed riyad':
                                        $image = 'morsyed.jpg';
                                        break;
                                    default:
                                        $image = 'team-1.jpg'; // Fallback image if the name doesn't match
                                        break;
                                }

                                // Check if the image exists in the img folder
                                if (!file_exists("img/$image")) {
                                    $image = 'default.jpg'; // Use default if the image doesn't exist
                                }
                            ?>
                            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="rounded shadow overflow-hidden">
                                    <div class="position-relative">
                                        <img class="img-fluid" src="img/<?php echo $image; ?>" alt="Team Member">
                                    </div>
                                    <div class="text-center p-4 mt-3">
                                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($row['name']); ?></h5>
                                        <small><?php echo htmlspecialchars($row['matric']); ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center">No team members found.</p>
                    <?php endif; ?>
                </div>
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
