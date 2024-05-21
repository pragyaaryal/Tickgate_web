<?php
// Include the database connection file
require_once 'db_connection.php';

// Query to count the number of customers who made reservations for today
$sql = "SELECT COUNT(DISTINCT user_id) AS today_customer_count
        FROM Reservation
        INNER JOIN Route ON Reservation.RouteID = Route.RouteID
        WHERE DATE(Route.DepartureDate) = CURDATE()";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

// Echo the count
echo $result['today_customer_count'];
?>
