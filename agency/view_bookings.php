<?php
session_start();
include_once ('../db/conn.php');

// Check if the agency is logged in
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
// Get the agency ID from the session
$agency_id = $_SESSION['id'];
// Fetch bookings associated with the logged-in agency and details of the users who made those bookings
$sql = "SELECT b.booking_id, b.booking_date, b.starting_date, b.end_date, u.user_id, u.name AS name, u.phone AS phone, c.rent_per_day
        FROM bookings b
        INNER JOIN users u ON b.user_id = u.user_id
        INNER JOIN car c ON b.car_id = c.car_id
        WHERE b.agency_id = ?";
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
    <title>Bookings List</title>
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
                        <a class="nav-link" href="./add_car.php"><i class="fas fa-car"></i> Add Car</a>
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
    <div class="container mt-5">
        <h4 class="mb-4 text-center">Bookings List</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Booking Date</th>
                    <th>Customer Name</th>
                    <th>Customer Phone</th>
                    <th>Booking Start Date</th>
                    <th>Booking End Date</th>
                    <th>Rent per Day</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['starting_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td>$<?php echo $row['rent_per_day']; ?></td>
                            <?php
                            // Calculate the total amount
                            $startingDate = new DateTime($row['starting_date']);
                            $endDate = new DateTime($row['end_date']);
                            $bookingDuration = $startingDate->diff($endDate)->days;
                            $totalAmount = $bookingDuration * $row['rent_per_day'];
                            ?>
                            <td>$<?php echo $totalAmount; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No bookings found for this agency.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

<?php
// Close statement and connection
$stmt->close();
$conn->close();
?>