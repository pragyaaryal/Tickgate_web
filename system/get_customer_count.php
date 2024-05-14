<?php
// Include the database connection file
require_once 'db_connection.php';

// Query to count the number of rows in the "Bus" table
$sql = "SELECT COUNT(*) AS customer_count FROM users WHERE user_type = 'customer'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

// Echo the count
echo $result['customer_count'];
?>