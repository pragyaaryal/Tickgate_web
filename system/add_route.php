<?php
// Include the database connection file
include 'db_connection.php';

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Retrieve form data and sanitize it
    $routeID = $_POST['RouteID'];
    $startingPoint = $_POST['startingPoint'];
    $droppingPoint = $_POST['droppingPoint'];
    $departureDate = $_POST['departureDate'];
    $departureTime = $_POST['departureTime'];
    $busNumber = $_POST['busNumber'];
    $driverContact = $_POST['driverContact'];

    // Prepare and execute SQL statement to insert data into the database
    $query = "INSERT INTO Route (RouteID, FromLocation, Destination, DepartureDate, DepartureTime, ContactNumber, BusNumber) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$routeID, $startingPoint, $droppingPoint, $departureDate, $departureTime, $driverContact, $busNumber]);

    // Check if the insertion was successful
    if($stmt->rowCount() > 0) {
        echo "Route added successfully.";
        echo "<script>window.location.href = 'route_management.php';</script>";

    } else {
        echo "Error adding route.";
    }
}

// Close the database connection
$conn = null;
?>
