<?php
// Include your database connection file
include_once ('../db/conn.php');
// Initialize variables
$name = $state = $district = $email = $phone = $password = $userType = "";
$name_err = $state_err = $district_err = $email_err = $phone_err = $password_err = $userType_err = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate state
    if (empty(trim($_POST["state"]))) {
        $state_err = "Please enter your state.";
    } else {
        $state = trim($_POST["state"]);
    }

    // Validate district
    if (empty(trim($_POST["district"]))) {
        $district_err = "Please enter your district.";
    } else {
        $district = trim($_POST["district"]);
    }
     // Validate userType
     if (empty(trim($_POST["userType"]))) {
        $userType_err = "Please select your user type.";
    } else {
        $userType = trim($_POST["userType"]);
        $tableName = ($userType === "Agency") ? "agency" : "users";
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);

        // Check if the email already exists
        $sql_check_email = "SELECT email FROM $tableName WHERE email = ?";
        if ($stmt_check_email = $conn->prepare($sql_check_email)) {
            $stmt_check_email->bind_param("s", $param_email_check);
            $param_email_check = $email;
            $stmt_check_email->execute();
            $stmt_check_email->store_result();

            if ($stmt_check_email->num_rows > 0) {
                $email_err = "Email already exists.";
            }
            $stmt_check_email->close();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

   

    // Check for errors before inserting into database
    if (empty($email_err) && empty($name_err) && empty($state_err) && empty($district_err) && empty($phone_err) && empty($password_err) && empty($userType_err)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO $tableName (name, state, district, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $param_name, $param_state, $param_district, $param_email, $param_phone, $param_password);
            // Set parameters
            $param_name = $name;
            $param_state = $state;
            $param_district = $district;
            $param_email = $email;
            $param_phone = $phone;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                session_start();
                $id = $stmt->insert_id;
                $_SESSION['id'] = $id;
                $_SESSION['user_type'] = $userType;
                header("Location: ../index.php");
                } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    } else {
        // Display error messages as Bootstrap alerts
        $errors = [
            "name" => $name_err,
            "state" => $state_err,
            "district" => $district_err,
            "email" => $email_err,
            "phone" => $phone_err,
            "password" => $password_err,
            "userType" => $userType_err
        ];
    }

    // Close connection
    $conn->close();
}
?>