<?php
// Include the database connection file
include 'db_connection.php';

try {
    // Get the JSON input from the request
    $input = json_decode(file_get_contents('php://input'), true);

    // Extract booking details
    $routeID = $input['routeID'];
    $busNumber = $input['busNumber'];
    $fromLocation = $input['fromLocation'];
    $toLocation = $input['toLocation'];
    $numSeats = $input['numSeats'];

    // Get the user ID (assuming user is logged in and user ID is stored in session)
    session_start();
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }
    $userID = $_SESSION['user_id'];

    $query = "INSERT INTO Reservation (ReservationID, user_id, RouteID, BusNumber, NumberOfSeatsReserved, status)
    VALUES (NULL, :userID, :routeID, :busNumber, :numSeats, 'pending')";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':userID' => $userID,
        ':routeID' => $routeID,
        ':busNumber' => $busNumber,
        ':numSeats' => $numSeats
    ]);


    // Update the free seats in the Bus table
    $updateQuery = "UPDATE Bus SET FreeSeats = FreeSeats - :numSeats WHERE BusNumber = :busNumber";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->execute([
        ':numSeats' => $numSeats,
        ':busNumber' => $busNumber
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>