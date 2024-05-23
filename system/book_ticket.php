<?php
// Include the database connection file
include 'db_connection.php';

try {
    // Start the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    // Get the user ID from the session
    $userID = $_SESSION['user_id'];

    // Get the JSON input from the request
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON');
    }

    // Check if input data is valid
    if (!isset($input['routeID'], $input['busNumber'], $input['fromLocation'], $input['toLocation'], $input['numSeats'])) {
        throw new Exception('Invalid input');
    }

    // Extract booking details
    $routeID = $input['routeID'];
    $busNumber = $input['busNumber'];
    $fromLocation = $input['fromLocation'];
    $toLocation = $input['toLocation'];
    $numSeats = (int)$input['numSeats'];

    if ($numSeats <= 0) {
        throw new Exception('Number of seats must be greater than zero');
    }

    // Check if there are enough free seats
    $stmt = $conn->prepare("SELECT FreeSeats FROM Bus WHERE BusNumber = :busNumber");
    $stmt->execute([':busNumber' => $busNumber]);
    $bus = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bus || $bus['FreeSeats'] < $numSeats) {
        throw new Exception('Not enough free seats');
    }

    // Insert the reservation
    $query = "INSERT INTO Reservation (user_id, RouteID, BusNumber, NumberOfSeatsReserved, status)
              VALUES (:userID, :routeID, :busNumber, :numSeats, 'pending')";

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
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
