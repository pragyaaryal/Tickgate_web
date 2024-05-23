<?php
// Start session
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    
    // SQL query to fetch reservations for the logged-in user
    $sql = "SELECT r.*, b.BusType, b.ContactNumber, rt.FromLocation, rt.Destination, rt.DepartureDate, rt.DepartureTime
            FROM Reservation r
            JOIN users u ON r.user_id = u.user_id
            JOIN Route rt ON r.RouteID = rt.RouteID
            JOIN Bus b ON r.BusNumber = b.BusNumber
            WHERE r.user_id = $user_id";

    try {
        // Execute the SQL query
        $result = $conn->query($sql);

        // Check if there are any reservations
        if ($result->num_rows > 0) {
            // Initialize an array to hold reservation data
            $reservations = array();

            // Fetch data and store in the reservations array
            while ($row = $result->fetch_assoc()) {
                $reservations[] = $row;
            }

            // Output reservations data as JSON
            echo json_encode($reservations);
        } else {
            // No reservations found
            echo json_encode(array('message' => 'No reservations found'));
        }
    } catch (Exception $e) {
        // Log or echo the error message
        echo json_encode(array('error' => $e->getMessage()));
    }
} else {
    // User not logged in
    echo json_encode(array('message' => 'User not logged in'));
}
?>
