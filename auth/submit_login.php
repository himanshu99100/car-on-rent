<?php
// Include your database connection file
include_once ('../db/conn.php');

// Initialize variables
$email = $password = $userType = "";
$email_err = $password_err = $userType_err = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate userType
    if (empty(trim($_POST["type"]))) {
        $userType_err = "Please select your user type.";
    } else {
        $userType = trim($_POST["type"]);
    }

    // Check for errors
    if (empty($email_err) && empty($password_err) && empty($userType_err)) {
        // Determine the table name based on userType
        $tableName = ($userType === "Agency") ? "agency" : "users";
        $idColumn = ($userType === "Agency") ? "agency_id" : "user_id";

        // Prepare a SELECT statement
        $sql = "SELECT $idColumn, email, password FROM $tableName WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            // Set parameter
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $email, $hashed_password);
                    if ($stmt->fetch()) {
                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["user_type"] = $userType;
                            echo '<script>console.log($email)</script>';
                            // Redirect user to welcome page
                            header("location: ../index.php");
                            // exit;
                        } else {
                            // Password is not valid
                            $password_err = "Invalid email or password.";
                        }
                    }
                } else {
                    // Email doesn't exist
                    $email_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    $errors = [$email_err, $password_err, $userType_err];
}
?>