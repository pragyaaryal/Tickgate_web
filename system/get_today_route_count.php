<?php
// Include the database connection file
require_once 'db_connection.php';

// Query to count the number of routes for today
$sql = "SELECT COUNT(*) AS today_route_count
        FROM Route
        WHERE DATE(DepartureDate) = CURDATE()";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

// Echo the count
echo $result['today_route_count'];
?>
