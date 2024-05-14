
<?php
// Include the database connection file
include 'db_connection.php';

// Check if the reservationId and status are set
if (isset($_POST['reservationId'], $_POST['status'])) {
    $reservationId = $_POST['reservationId'];
    $newStatus = $_POST['status'];

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
        echo json_encode(['success' => false]);
    }
} else {
    // Return error response if reservationId or status is not set
    echo json_encode(['success' => false]);
}

// Close the database connection
$conn = null;
?>