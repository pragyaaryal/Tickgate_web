<?php
// Include the database connection file
require_once 'db_connection.php';

// Start the session (assuming sessions are used to store user information)
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, return error message
    $response = array('error' => 'User not logged in');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Stop further execution
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to fetch reservations for the logged-in user, joining Reservation table with other tables
$sql = "SELECT r.*, b.BusType, b.ContactNumber, rt.FromLocation, rt.Destination, rt.DepartureDate, rt.DepartureTime
        FROM Reservation r
        JOIN users u ON r.user_id = u.user_id
        JOIN Route rt ON r.RouteID = rt.RouteID
        JOIN Bus b ON r.BusNumber = b.BusNumber
        WHERE r.user_id = $user_id";
$result = $conn->query($sql);

// Array to store reservations
$reservations = [];

if ($result) {
    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetching reservations data
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    } else {
        // If no reservations found, return appropriate message
        $response = array('message' => 'No reservations found for user ID: ' . $user_id);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; // Stop further execution
    }
} else {
    // If there's an error executing the query, return error message
    $response = array('error' => 'Error executing query: ' . $conn->error);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Stop further execution
}

// Sending JSON response with reservations data
header('Content-Type: application/json');
echo json_encode($reservations);
?>
