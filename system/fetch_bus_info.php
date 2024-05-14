<?php
// Include the database connection file
require_once 'db_connection.php';

// Function to fetch bus information from the database
function fetchBusInfo($conn)
{
    try {
        $sql = "SELECT *, NumberOfSeats - BookedSeats AS FreeSeats FROM Bus";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        // Handle database connection or query errors
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}

// Fetch bus information from the database
$busInfo = fetchBusInfo($conn);

// Check if there was an error during the database operation
if (isset($busInfo['error'])) {
    // If there was an error, output the error message as JSON
    echo json_encode(['error' => $busInfo['error']]);
} else {
    // If no error occurred, encode the bus information as JSON and output it
    echo json_encode($busInfo);
}
?>
