<?php
// Include the database connection file
require_once 'db_connection.php';

// Query to count the number of rows in the "Reservation" table for the current date
$sql = "SELECT COUNT(*) AS bookingCount 
        FROM Reservation 
        INNER JOIN Route ON Reservation.RouteID = Route.RouteID 
        WHERE DATE(Route.DepartureDate) = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

// Echo the count
echo $result['bookingCount'];
?>
