<?php
session_start();
include_once ('./fetech_car.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Website</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            /* Hide horizontal scrollbar */
        }

        .hero-image-container {
            position: relative;
        }

        .hero-image {
            width: 100vw;
            max-height: calc(100vw * (2/4));
            object-fit: cover;
        }

        .hero-text {
            position: absolute;
            top: 20%;
            left: 50%;
            text-align: center;
            color: white;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-top: 2rem;
        }

        .hero-text p {
            font-size: 1.5rem;
            margin-bottom: 0;
        }

        .btn-view-cars {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Adjustments for smaller screens */
        @media (max-width: 768px) {
            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-text p {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ml-10" href=""><i class="fas fa-home"></i></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style='margin-right:4rem'>
                    <?php if (isset($_SESSION['id'])): ?>
                        <?php if ($_SESSION['user_type'] === 'User'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./user/my_bookings.php"><i class="fas fa-car"></i> View Cars Booking</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./auth/logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a>
                            </li>
                        <?php elseif ($_SESSION['user_type'] === 'Agency'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./agency/view_bookings.php"><i class="fas fa-calendar-days"></i> View
                                    Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./agency/add_car.php"><i class="fas fa-car"></i> Add Car</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./agency/update_car.php"><i class="fas fa-calendar-days"></i> Update
                                    Car</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./auth/logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./auth/registration.php"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>

            </div>
        </div>
    </nav>



    <!-- Hero image with text -->
    <div class="hero-image-container">
        <img class="hero-image" src='./assets/hero-img.jpg'>
        <div class="hero-text">
            <h1>Welcome to our Car Rental Service</h1>
        </div>
    </div>
    <h3 class="text-center my-5">View Available Cars</h3>
    <!-- card  -->
    <div class="d-flex justify-content-evenly ">
        <div class="row">
            <?php
            if (!empty($carCardsHTML)) {
                echo '' . $carCardsHTML . '';
            } else {
                echo '<p>No cars available.</p>';
            }
            ?>
        </div>
    </div>
    <!-- pagination -->
    <?php
    $totalCars = $conn->query("SELECT COUNT(*) FROM car")->fetch_row()[0];
    $totalPages = ceil($totalCars / $perPage);

    echo '<div class="d-flex justify-content-center">';
    echo '<nav aria-label="Page navigation example">
        <ul class="pagination">';
    echo '<li class="page-item"><a class="page-link" href="?page=' . max(1, $page - 1) . '">Previous</a></li>';

    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i
            . '">' . $i . '</a></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="?page=' .
        min($totalPages, $page + 1) . '">Next</a></li>';
    echo '</ul>
</nav>';
    echo '</div>'; ?>
    <!-- procedure -->
    <div class="container mb-10">
        <h2 class="text-center">How it Works</h2>
        <div class="row">
            <div class="col-4 mt-2 text-center">
                <h4>Choose Your Car</h4>
                <i class="fas fa-car fa-3x"></i>
                <p class="mt-2">Browse through our selection of cars and choose the one that suits your
                    needs.</p>
            </div>
            <div class="col-4 mt-2 text-center">
                <h4>Book Your Car</h4>
                <i class="fas fa-calendar-check fa-3x"></i>
                <p class="mt-2">Reserve your selected car by booking it online through our website.</p>
            </div>
            <div class="col-4 mt-2 text-center">
                <h4>Pick Up Your Car</h4>
                <i class="fas fa-key fa-3x"></i>
                <p class="mt-2">Visit our rental location to pick up your reserved car at the scheduled
                    time.</p>
            </div>
        </div>
    </div>
    <footer class="footer bg-dark text-white">
        <div class="d-flex justify-content-center">
            <div>
                <p>&copy; 2024 Car Rental System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/2a7e320444.js" crossorigin="anonymous"></script>
</body>

</html>