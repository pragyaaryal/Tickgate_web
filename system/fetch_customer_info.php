<?php
// Include the database connection file
require_once 'db_connection.php';

// Function to fetch customer information from the database
function fetchCustomerInfo($conn)
{
    try {
        // Fetch customer information
        $sql = "SELECT user_id, username, email, password, created_at, user_type, contact_number FROM users WHERE user_type = 'customer'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        // Handle database connection or query errors
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}

// Fetch customer information from the database
$customerInfo = fetchCustomerInfo($conn);

// Check if there was an error during the database operation
if (isset($customerInfo['error'])) {
    // If there was an error, output the error message as JSON
    echo json_encode(['error' => $customerInfo['error']]);
} else {
    // If no error occurred, encode the customer information as JSON and output it
    echo json_encode($customerInfo);
}
?>
