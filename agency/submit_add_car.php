<?php

// Include your database connection file
include_once('../db/conn.php');

// Initialize variables
$vehicle_model = $body_type = $fuel = $transmission = $vehicle_number = $seating_capacity = $rent_per_day = $image = "";
$vehicle_model_err = $body_type_err = $fuel_err = $transmission_err = $vehicle_number_err = $seating_capacity_err = $rent_per_day_err = $image_err = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate vehicle model
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
    if (empty(trim($_POST["vehicle_number"]))) {
        $vehicle_number_err = "Please enter vehicle number.";
    } else {
        $vehicle_number = trim($_POST["vehicle_number"]);
    }

    // Validate seating capacity
    if (empty(trim($_POST["seating_capacity"]))) {
        $seating_capacity_err = "Please enter seating capacity.";
    } else {
        $seating_capacity = trim($_POST["seating_capacity"]);
    }

    // Validate rent per day
    if (empty(trim($_POST["rent_per_day"]))) {
        $rent_per_day_err = "Please enter rent per day.";
    } else {
        $rent_per_day = trim($_POST["rent_per_day"]);
    }

    // Get agency ID from session
    $agency_id = $_SESSION["id"];

    // File upload handling
    if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        // Define a directory where uploaded image will be saved
        $target_dir = "../assets/uploads/";

        // Generate a unique name for the uploaded image
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Check if file already exists
        if (file_exists($target_file)) {
            $image_err = "Sorry, file already exists.";
        } else {
            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            } else {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Check for any errors before inserting data into database
    if (empty($vehicle_model_err) && empty($body_type_err) && empty($fuel_err) && empty($transmission_err) && empty($vehicle_number_err) && empty($seating_capacity_err) && empty($rent_per_day_err) && empty($image_err)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO car (vehicle_model, body_type, fuel, transmission, vehicle_no, seating_capacity, rent_per_day, image, agency_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssssi", $param_vehicle_model, $param_body_type, $param_fuel, $param_transmission, $param_vehicle_number, $param_seating_capacity, $param_rent_per_day, $param_image, $param_agency_id);

            // Set parameters
            $param_vehicle_model = $vehicle_model;
            $param_body_type = $body_type;
            $param_fuel = $fuel;
            $param_transmission = $transmission;
            $param_vehicle_number = $vehicle_number;
            $param_seating_capacity = $seating_capacity;
            $param_rent_per_day = $rent_per_day;
            $param_image = $image;
            $param_agency_id = $agency_id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to index page or any other page after successful insertion
                echo '<script>alert("Car sucessfully add");</script>';
                header("Location:../index.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Create an array of errors
    $errors = [
        "vehicle_model" => $vehicle_model_err,
        "body_type" => $body_type_err,
        "fuel" => $fuel_err,
        "transmission" => $transmission_err,
        "vehicle_number" => $vehicle_number_err,
        "seating_capacity" => $seating_capacity_err,
        "rent_per_day" => $rent_per_day_err,
        "image" => $image_err
    ];
}

// Close connection
$conn->close();
?>
