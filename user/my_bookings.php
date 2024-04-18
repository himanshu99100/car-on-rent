<?php
session_start();
include_once ('../db/conn.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>
    alert('You are not logged Redirecting to the home page...');
    window.location.href = '../auth/login.php';
    </script>";
}
if ($_SESSION['user_type'] !== 'User') {
    echo "<script>
    alert('You are already logged in. Redirecting to the home page...');
    window.location.href = '../index.php';
    </script>";
    exit();
}
// Get user ID from session
$user_id = $_SESSION['id'];

// Fetch bookings for the current user along with agency details
$sql = "SELECT b.*, u.name AS user_name, c.vehicle_model,c.body_type, c.vehicle_no, a.name AS agency_name, a.email As agency_email,a.phone AS agency_phone
        FROM bookings b
        INNER JOIN users u ON b.user_id = u.user_id
        INNER JOIN car c ON b.car_id = c.car_id
        INNER JOIN agency a ON b.agency_id = a.agency_id
        WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking</title>
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
                        <a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h4 class="mb-4 text-center">Bookings</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Booking No.</th>
                    <th>Vehicle Model</th>
                    <th>Vehicle Number</th>
                    <th>Vehicle Type</th>
                    <th>Agency Name</th>
                    <th>Agency Mobile</th>
                    <th>Agency Email</th>
                    <th>Booking Date and Time</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo $row['vehicle_model']; ?></td>
                            <td><?php echo $row['vehicle_no']; ?></td>
                            <td><?php echo $row['body_type']; ?></td>
                            <td><?php echo $row['agency_name']; ?></td>
                            <td><?php echo $row['agency_phone']; ?></td>
                            <td><?php echo $row['agency_email']; ?></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td><?php echo $row['starting_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No bookings found for this user.</td>
                    </tr>
                <?php endif; ?>
                <?php
                // Close statement and connection
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <footer class="footer fixed-bottom bg-dark text-white">
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