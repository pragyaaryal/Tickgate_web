

<?php
// Include the database connection file
require_once 'db_connection.php';

// Query to count the number of rows in the "Bus" table for the current date
$sql = "SELECT COUNT(*) AS busCount 
        FROM Bus
        INNER JOIN Route ON Bus.BusNumber = Route.BusNumber
        WHERE DATE(Route.DepartureDate) = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

// Echo the count
echo $result['busCount'];
?>
