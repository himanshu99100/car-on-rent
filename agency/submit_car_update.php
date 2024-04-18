<?php
// Include your database connection file
include_once('../db/conn.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$vehicle_model = $body_type = $fuel = $transmission = $vehicle_no = $seating_capacity = $rent_per_day = $image = "";
$vehicle_model_err = $body_type_err = $fuel_err = $transmission_err = $vehicle_no_err = $seating_capacity_err = $rent_per_day_err = $image_err = "";
    if (empty(trim($_POST["vehicle_model"]))) {
        $vehicle_model_err = "Please enter vehicle model.";
    } else {
        $vehicle_model = trim($_POST["vehicle_model"]);
    }

    // Validate body type
    if (empty(trim($_POST["body_type"]))) {
        $body_type_err = "Please enter body type.";
    } else {
        $body_type = trim($_POST["body_type"]);
    }

    // Validate fuel type
    if (empty(trim($_POST["fuel"]))) {
        $fuel_err = "Please enter fuel type.";
    } else {
        $fuel = trim($_POST["fuel"]);
    }

    // Validate transmission type
    if (empty(trim($_POST["transmission"]))) {
        $transmission_err = "Please enter transmission type.";
    } else {
        $transmission = trim($_POST["transmission"]);
    }

    // Validate vehicle number
    if (empty(trim($_POST["vehicle_no"]))) {
        $vehicle_no_err = "Please enter vehicle number.";
    } else {
        $vehicle_no = trim($_POST["vehicle_no"]);
    }

    // Validate seating capacity
    if (empty(trim($_POST["seating_capacity"]))) {
        $seating_capacity_err = "Please enter seating capacity.";
    } 
    else {
        $seating_capacity = trim($_POST["seating_capacity"]);
    }

    // Validate rent per day
    if (empty(trim($_POST["rent_per_day"]))) {
        $rent_per_day_err = "Please enter rent per day.";
    } else {
        $rent_per_day = trim($_POST["rent_per_day"]);
    }
    // Prepare an UPDATE statement
    $sql = "UPDATE car SET vehicle_model=?, body_type=?, fuel=?, transmission=?, vehicle_no=?, seating_capacity=?, rent_per_day=? WHERE car_id=?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("sssssssi", $vehicle_model, $body_type, $fuel, $transmission, $vehicle_no, $seating_capacity, $rent_per_day, $car_id);

        // Set parameters
        $vehicle_model = $_POST["vehicle_model"];
        $body_type = $_POST["body_type"];
        $fuel = $_POST["fuel"];
        $transmission = $_POST["transmission"];
        $vehicle_no = $_POST["vehicle_no"];
        $seating_capacity = $_POST["seating_capacity"];
        $rent_per_day = $_POST["rent_per_day"];
        $car_id = $_POST["car_id"];

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to a success page or display a success message
            echo '<script>alert("Car details updated successfully.");</script>';
        } else {
            // If execution failed, display an error message
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

?>
