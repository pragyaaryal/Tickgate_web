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
    $ticketPrice = $_POST['ticketPrice'];

    try {
        // Prepare and execute SQL statement to insert data into the database
        $query = "INSERT INTO Route (RouteID, FromLocation, Destination, DepartureDate, DepartureTime, TicketPrice, BusNumber) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$routeID, $startingPoint, $droppingPoint, $departureDate, $departureTime, $ticketPrice, $busNumber]);

        // Check if the insertion was successful
        if($stmt->rowCount() > 0) {
            echo "<script>alert('Route added successfully');</script>" ;
            echo "<script>window.location.href = 'manage_route.php';</script>";
        } else {
            echo "<script>alert('Error adding route');</script>";
        }
    } catch (PDOException $e) {
        // Handle database errors
        // echo "Error: " . $e->getMessage();
        echo "<script>alert('The bus Number doesnt exist.');</script>" ;
    }
}

// Close the database connection
$conn = null;
?>
