<?php
include_once ('../db/conn.php');

// Check if car_id is provided in the URL
if (!isset($_GET['car_id'])) {
    header("Location: ./index.php");
    exit();
}
// Get the car_id from the URL
$car_id = $_GET['car_id'];

// Query the database to get the agency_id associated with the car_id
$sql = "SELECT agency_id FROM car WHERE car_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
// Fetch the result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $agency_id = $row['agency_id'];
} else {
    // Redirect if car_id is not found in the database
    header("Location: ./index.php");
    exit();
}

// Fetch car and agency details using JOIN operation
$sql = "SELECT car.*, agency.* FROM car
        INNER JOIN agency ON car.agency_id = agency.agency_id
        WHERE car.car_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the car with the given car_id exists
if ($result->num_rows === 0) {
    // Redirect or display an error message
    header("Location: ./error.php");
    exit();
}

// Fetch car and agency details
$row = $result->fetch_assoc();
$car = $row; // Assigning car details
$agency = $row; // Assigning agency details


// Function to generate HTML for car details card with booking inputs
function generateCarDetailsCard($car, $agency)
{
    return '
        <div class="card">
            <img src=".' . $car['image'] . '" class="card-img-top" alt="' . $car['vehicle_model'] . '">
            <div class="card-body">
            <h5 class="card-title text-center my-2"><strong>Vehicle Model:</strong> ' . $car['vehicle_model'] . '</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-text"><strong>Body Type:</strong> ' . $car['body_type'] . '</p>
                        <p class="card-text"><strong>Transmission:</strong> ' . $car['transmission'] . '</p>
                        <p class="card-text"><strong>Fuel Type:</strong> ' . $car['fuel'] . '</p>
                        <p class="card-text"><strong>Seating Capacity:</strong> ' . $car['seating_capacity'] . '</p>
                        <p class="card-text"><strong>Rent per Day:</strong> ' . $car['rent_per_day'] . '</p>
                    </div>
                    <div class="col-md-6">
                        <p class="card-text"><strong>Agency:</strong> ' . $agency['name'] . '</p>
                        <p class="card-text"><strong>Address:</strong> ' . $agency['district'] . ', ' . $agency['state'] . '</p>
                        <p class="card-text"><strong>Phone:</strong> ' . $agency['phone'] . '</p>
                        <p class="card-text"><strong>Email:</strong> ' . $agency['email'] . '</p>
                    </div>
                </div>
                <form method="POST">
                <input type="hidden" name="car_id" value="' . $car['car_id'] . '">
                <div class="row my-4 ">
                <div class="col-6">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="col-6">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-dark">Book Now</button></div>
            </form>
            </div>
        </div>';
}

// Initialize variables
$start_date_err = $end_date_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get today's date
    $today = date("Y-m-d");

    // Validate start date
    if (empty(trim($_POST["start_date"]))) {
        $start_date_err = "Please select a start date.";
    } else {
        $start_date = trim($_POST["start_date"]);
        if ($start_date < $today) {
            $start_date_err = "Start date cannot be in the past.";
        }
    }

    // Validate end date
    if (empty(trim($_POST["end_date"]))) {
        $end_date_err = "Please select an end date.";
    } else {
        $end_date = trim($_POST["end_date"]);
        if ($end_date <= $start_date) {
            $end_date_err = "End date must be after the start date.";
        }
    }

    // If no errors, insert data into bookings table
    if (empty($start_date_err) && empty($end_date_err)) {
        // Get car_id and agency_id
        $car_id = $_POST["car_id"];
        // Prepare an INSERT statement for bookings table
        $sql = "INSERT INTO bookings (car_id, agency_id, user_id, booking_date, starting_date, end_date) VALUES (?, ?, ?, NOW(), ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("iiiss", $car_id, $agency_id, $_SESSION["id"], $start_date, $end_date);

            // Execute statement
            if ($stmt->execute()) {
                // Redirect to success page or any other page
                header("location: ../index.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    $errors = [$start_date_err, $end_date_err];
}


?>