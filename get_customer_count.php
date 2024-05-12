<?php
// Include the database connection file
include 'db_connection.php';

// Query to get the count of customers (users) from the users table
$query = "SELECT COUNT(*) AS customer_count FROM users WHERE user_type = 'customer'";
$result = mysqli_query($conn, $query);

if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    // Echo the customer count
    echo $row['customer_count'];
} else {
    // Echo an error message if the query fails
    echo "Error fetching customer count";
}

// Close the database connection
mysqli_close($conn);
?>
