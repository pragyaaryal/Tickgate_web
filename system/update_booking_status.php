<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'db_connection.php';

// Check if the reservationId and status are set
if (isset($_POST['reservationId'], $_POST['status'])) {
    $reservationId = $_POST['reservationId'];
    $newStatus = $_POST['status'];

    try {
        // Prepare and execute the update query
        $query = "UPDATE Reservation SET status = :status WHERE ReservationID = :reservationId";
        $statement = $conn->prepare($query);
        $statement->bindParam(':status', $newStatus);
        $statement->bindParam(':reservationId', $reservationId);

        if ($statement->execute()) {
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Return error response
            echo json_encode(['success' => false, 'message' => 'Failed to execute statement.']);
        }
    } catch (PDOException $e) {
        // Return error response with exception message
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Return error response if reservationId or status is not set
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

// Close the database connection
$conn = null;
?>
