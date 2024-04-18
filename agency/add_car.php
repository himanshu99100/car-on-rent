<?php
session_start();
if (!isset($_SESSION['id'])) {
    echo "<script>
    alert('Login to Access this Page');
    window.location.href = '../auth/loginindex.php';
    </script>";
    exit();
}
if ($_SESSION['user_type'] !== 'Agency') {
    echo "<script>
    alert('Only Agent can Access');
    window.location.href = '../index.php';
    </script>";
    exit();
}
include ('./submit_add_car.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .dropdown-item.text-white:hover {
            color: #000 !important;
            background-color: #fff;
        }

        .custom-container {
            width: 50%;
            margin: auto;
            border: 2px solid gray;
            padding: 20px
        }

        @media (max-width: 768px) {
            .custom-container {
                width: 70%;
            }
        }

        @media (max-width: 575.98px) {
            .custom-container {
                width: 90%;
                margin: 20px
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ml-10" href="../index.php"><i class="fas fa-home"></i></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style='margin-right:4rem'>
                <li class="nav-item">
                                <a class="nav-link" href="./view_bookings.php"><i class="fas fa-calendar-days"></i> View
                                    Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="update_car.php"><i class="fas fa-calendar-days"></i> Update Car</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="custom-container mt-5">
        <h2>Add Car</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($errors as $error): ?>
                    <?php if (!empty($error)): ?>
                        <div><?php echo $error; ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="vehicle_model">Vehicle Model:</label>
                        <input type="text" class="form-control" id="vehicle_model" name="vehicle_model"
                            value="<?php echo isset($vehicle_model) ? $vehicle_model : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="body_type">Body Type:</label>
                        <input type="text" class="form-control" id="body_type" name="body_type"
                            value="<?php echo isset($body_type) ? $body_type : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="fuel">Fuel Type:</label>
                        <input type="text" class="form-control" id="fuel" name="fuel"
                            value="<?php echo isset($fuel) ? $fuel : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="transmission">Transmission Type:</label>
                        <input type="text" class="form-control" id="transmission" name="transmission"
                            value="<?php echo isset($transmission) ? $transmission : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="vehicle_number">Vehicle Number:</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                            value="<?php echo isset($vehicle_number) ? $vehicle_number : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="seating_capacity">Seating Capacity:</label>
                        <input type="number" class="form-control" id="seating_capacity" name="seating_capacity"
                            value="<?php echo isset($seating_capacity) ? $seating_capacity : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="rent_per_day">Rent per Day:</label>
                        <input type="number" class="form-control" id="rent_per_day" name="rent_per_day"
                            value="<?php echo isset($rent_per_day) ? $rent_per_day : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                    <div class="form-group">
                        <label for="car_image">Car Image:</label>
                        <input type="file" class="form-control" id="car_image" name="image" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-dark" style="width:5rem ">Submit</button>
            </div>
        </form>

    </div>
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