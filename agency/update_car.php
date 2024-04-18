<?php
session_start();
// Check if agency is logged in
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
include_once ('../db/conn.php');
include_once ('./submit_car_update.php');

// Get agency ID from session
$agency_id = $_SESSION['id'];

// Fetch cars associated with the logged-in agency
$sql = "SELECT car_id, vehicle_model, body_type, transmission, fuel, vehicle_no, seating_capacity, rent_per_day FROM car WHERE agency_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agency_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car List</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <a class="nav-link" href="./add_car.php"><i class="fas fa-car"></i> Add Car</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h4 class="mb-4 text-center">Car List</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>Vechicle No.</th>
                    <th>Transmission</th>
                    <th>Fuel Type</th>
                    <th>Seating Capcity</th>
                    <th>Rent Per Day</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['car_id']; ?></td>
                            <td><?php echo $row['vehicle_model']; ?></td>
                            <td><?php echo $row['body_type']; ?></td>
                            <td><?php echo $row['vehicle_no']; ?></td>
                            <td><?php echo $row['transmission']; ?></td>
                            <td><?php echo $row['fuel']; ?></td>
                            <td><?php echo $row['seating_capacity']; ?></td>
                            <td>$<?php echo $row['rent_per_day']; ?></td>
                            <td>
                                <button class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#updateModal_<?php echo $row['car_id']; ?>">Update</button>
                                <!-- Modal -->
                                <div class="modal fade" id="updateModal_<?php echo $row['car_id']; ?>" tabindex="-1"
                                    aria-labelledby="updateModalLabel_<?php echo $row['car_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel_<?php echo $row['car_id']; ?>">
                                                    Update Car Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Add your form here with existing car details prefilled -->
                                                <form method="post">
                                                    <!-- Car ID (hidden input) -->
                                                    <input type="hidden" name="car_id" value="<?php echo $row['car_id']; ?>">

                                                    <div class="row">
                                                        <!-- Vehicle Model -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="vehicle_model" class="form-label">Vehicle Model</label>
                                                            <input type="text" class="form-control" id="vehicle_model"
                                                                name="vehicle_model"
                                                                value="<?php echo $row['vehicle_model']; ?>">
                                                        </div>

                                                        <!-- Body Type -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="body_type" class="form-label">Body Type</label>
                                                            <input type="text" class="form-control" id="body_type"
                                                                name="body_type" value="<?php echo $row['body_type']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- Vehicle Number -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="vehicle_no" class="form-label">Vehicle Number</label>
                                                            <input type="text" class="form-control" id="vehicle_no"
                                                                name="vehicle_no" value="<?php echo $row['vehicle_no']; ?>">
                                                        </div>

                                                        <!-- Transmission -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="transmission" class="form-label">Transmission</label>
                                                            <input type="text" class="form-control" id="transmission"
                                                                name="transmission" value="<?php echo $row['transmission']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- Fuel Type -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="fuel" class="form-label">Fuel Type</label>
                                                            <input type="text" class="form-control" id="fuel" name="fuel"
                                                                value="<?php echo $row['fuel']; ?>">
                                                        </div>

                                                        <!-- Seating Capacity -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="seating_capacity" class="form-label">Seating
                                                                Capacity</label>
                                                            <input type="text" class="form-control" id="seating_capacity"
                                                                name="seating_capacity"
                                                                value="<?php echo $row['seating_capacity']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- Rent Per Day -->
                                                        <div class="col-md-6 mb-3">
                                                            <label for="rent_per_day" class="form-label">Rent Per Day</label>
                                                            <input type="text" class="form-control" id="rent_per_day"
                                                                name="rent_per_day" value="<?php echo $row['rent_per_day']; ?>">
                                                        </div>
                                                    </div>

                                                    <!-- Save Changes Button -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No cars found for this agency.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

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

<?php
// Close statement and connection
$stmt->close();
$conn->close();
?>