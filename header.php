<div class="container-fluid bg-dark px-0">
    <div class="row gx-0">
        <div class="col-lg-3 bg-dark d-none d-lg-block">
            <a href="index.html" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                <h1 class="m-0 text-primary text-uppercase">Grand TM</h1>
            </a>
        </div>
        <div class="col-lg-9">
            <div class="row gx-0 bg-white d-none d-lg-flex">
                <div class="col-lg-7 px-5 text-start">
                    <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                        <i class="fa fa-envelope text-primary me-2"></i>
                        <p class="mb-0">grandtmhotel@gmail.com</p>
                    </div>
                    <div class="h-100 d-inline-flex align-items-center py-2">
                        <i class="fa fa-phone-alt text-primary me-2"></i>
                        <p class="mb-0">+010 883 2184</p>
                    </div>
                </div>
                <div class="col-lg-5 px-5 text-end">
                    <div class="d-inline-flex align-items-center py-2">
                        <?php if (isset($_SESSION['username'])): ?>
                            <!-- If user is logged in -->
                            <a class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                            <a class="me-3" href="setting.php"><i class="fas fa-cog"></i></a>
                            <a class="btn btn-primary" href="logout.php">Log Out</a>
                        <?php else: ?>
                            <!-- If user is not logged in -->
                            <a class="btn btn-primary" href="login.php">Sign In</a>&nbsp
                            <a class="btn btn-primary" href="register.php">Sign Up</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                <a href="index.html" class="navbar-brand d-block d-lg-none">
                    <h1 class="m-0 text-primary text-uppercase">Hotelier</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <?php 
                        // Get the current file name (e.g., "index.php", "about.php")
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        ?>
                        <a href="index.php" class="nav-item nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">Home</a>
                        <a href="room.php" class="nav-item nav-link <?php echo $currentPage === 'room.php' ? 'active' : ''; ?>">Rooms</a>
                        <a href="about.php" class="nav-item nav-link <?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">About</a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="team.php" class="dropdown-item">Our Team</a>
                                <a href="contact.php" class="dropdown-item">Contact</a>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['username'])): ?>
                            <!-- Display setting link only if the user is logged in -->
                            <a href="my_book.php" class="nav-item nav-link <?php echo $currentPage === 'my_book.php' ? 'active' : ''; ?>">My Bookings</a>
                            <a href="setting.php" class="nav-item nav-link <?php echo $currentPage === 'setting.php' ? 'active' : ''; ?>">Setting</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
