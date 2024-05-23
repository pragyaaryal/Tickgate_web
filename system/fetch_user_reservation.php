<?php
// Include the database connection file
require_once 'db_connection.php';

// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch user reservations
function fetchUserReservations($conn, $user_id) {
    // Query to fetch reservations and associated details
    $sql = "SELECT u.username AS Username, 
                   r.NumberOfSeatsReserved, 
                   r.status, 
                   rt.DepartureDate, 
                   rt.DepartureTime, 
                   rt.FromLocation, 
                   rt.Destination, 
                   r.BusNumber, 
                   b.BusType, 
                   b.ContactNumber
            FROM Reservation r
            INNER JOIN Route rt ON r.RouteID = rt.RouteID
            INNER JOIN Bus b ON r.BusNumber = b.BusNumber
            INNER JOIN users u ON r.user_id = u.user_id
            WHERE r.user_id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    // Get the result set
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception('Get result failed: ' . $stmt->error);
    }

    // Fetch reservations data
    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    // Return the reservations array
    return $reservations;
}

try {
    // Fetch user reservations
    $reservations = fetchUserReservations($conn, $user_id);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($reservations);

} catch (Exception $e) {
    // Log the error to a file
    error_log($e->getMessage(), 3, 'error_log.log');

    // Send error response
    http_response_code(500);
    $response = ['error' => 'An error occurred while fetching reservations.'];
    echo json_encode($response);
}
?>
