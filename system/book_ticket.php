<?php
// Include database connection and set content type
include 'db_connection.php';
header('Content-Type: application/json');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        // Retrieve form data
        $routeID = isset($_POST['routeID']) ? $_POST['routeID'] : '';
        $busNumber = isset($_POST['busNumber']) ? $_POST['busNumber'] : '';
        $fromLocation = isset($_POST['fromLocation']) ? $_POST['fromLocation'] : '';
        $toLocation = isset($_POST['toLocation']) ? $_POST['toLocation'] : '';
        $numSeats = isset($_POST['numSeats']) ? $_POST['numSeats'] : '';

        // Insert reservation into database
        $stmt = $conn->prepare("INSERT INTO Reservation (user_id, RouteID, BusNumber, NumberOfSeatsReserved, status) VALUES (:user_id, :routeID, :busNumber, :numSeats, 'pending')");
        $stmt->execute(array(':user_id' => $user_id, ':routeID' => $routeID, ':busNumber' => $busNumber, ':numSeats' => $numSeats));

        // Return success message
        echo "<script>alert('Reservation successful!');</script>";
        echo json_encode(['message' => 'Reservation successful']);
    } catch (PDOException $e) {
        // Return error message
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>