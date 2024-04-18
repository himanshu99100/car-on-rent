<?php
include_once ('./db/conn.php');
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 4;
function fetchCarsFromDatabase($page, $perPage)
{
    global $conn;
    $offset = ($page - 1) * $perPage;
    $sql = "SELECT * FROM car ORDER BY car_id DESC LIMIT $offset, $perPage";
    $result = $conn->query($sql);
    $cars = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cars[] = [
                'car_id' => $row['car_id'],
                'vehicle_model' => $row['vehicle_model'],
                'body_type' => $row['body_type'],
                'transmission' => $row['transmission'],
                'fuel' => $row['fuel'],
                'vehicle_no' => $row['vehicle_no'],
                'seating_capacity' => $row['seating_capacity'],
                'rent_per_day' => $row['rent_per_day'],
                'agency_id' => $row['agency_id'],
                'image' => str_replace('../', './', $row['image']),
            ];
        }
    }
    return $cars;
}

function generateCarCard($car)
{
    return '
        <div class="col-6 my-4">
            <div class="card" style="width:25rem">
                <div class="d-flex justify-content-center">
                    <img src="' . $car['image'] . '" style="width:100%" class="card-img-top" alt="' . $car['vehicle_model'] . '">
                </div>
                <div class="card-body">
                <h5 class="card-title text-center">' . strtoupper($car['vehicle_model']) . '</h5>
                <div class="row">
                    <div class="col-12 mb-2">
                        <strong>Body Type:</strong> ' . strtoupper($car['body_type']) . '
                    </div>
                    <div class="col-12 mb-2">
                        <strong>Transmission:</strong> ' . strtoupper($car['transmission']) . '
                    </div>
                    <div class="col-12 mb-2">
                        <strong>Fuel:</strong> ' . strtoupper($car['fuel']) . '
                    </div>
                    <div class="col-12 mb-2">
                        <strong>Seating Capacity:</strong> ' . strtoupper($car['seating_capacity']) . '
                    </div>
                    <div class="col-12 mb-2">
                        <strong>Rent per Day:</strong> ' . strtoupper($car['rent_per_day']) . '
                    </div>
                    <div class="col-12 mb-2">
                        <strong>Agency ID:</strong> ' . strtoupper($car['agency_id']) . '
                    </div>
                </div>
                    <div class="d-flex justify-content-center mt-4">
                    <a class="btn btn-dark" href="./user/car_book.php?car_id=' . $car['car_id'] . '">Rent Now</a>
                    </div>
                </div>
            </div>
        </div>';
}


$cars = fetchCarsFromDatabase($page, $perPage);

$carCardsHTML = '';

foreach ($cars as $car) {
    $carCardsHTML .= generateCarCard($car);
} ?>