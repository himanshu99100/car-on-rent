<?php
include_once ('../db/conn.php');
function fetchBookingsFromDatabase($agency_id) {
    global $conn;
    $sql = "SELECT * FROM bookings WHERE agency_id = $agency_id";
    $result = $conn->query($sql);
    $bookings = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = [
                'booking_id' => $row['booking_id'],
                'vehicle_number' => $row['vehicle_number'],
                'customer_name' => $row['customer_name'],
                'customer_mobile' => $row['customer_mobile'],
                'customer_email' => $row['customer_email'],
                'booking_datetime' => $row['booking_datetime'],
                'vehicle_model' => $row['vehicle_model'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
            ];
        }
    }
    return $bookings;
}

// Assuming the agency_id is stored in the session variable
$agency_id = $_SESSION['id'];

$bookings = fetchBookingsFromDatabase($agency_id);

?>
