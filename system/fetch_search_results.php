<?php
// Include the database connection file
include 'db_connection.php';

try {
    // Retrieve search parameters
    $from = isset($_GET['from']) ? trim($_GET['from']) : '';
    $to = isset($_GET['to']) ? trim($_GET['to']) : '';
    $date = isset($_GET['date']) ? trim($_GET['date']) : '';

    // Debugging statements
    error_log("From: $from, To: $to, Date: $date");

    // Validate input
    if (empty($from) || empty($to) || empty($date)) {
        throw new Exception('Invalid input');
    }

    // Prepare and execute the query
    $query = "SELECT r.RouteID, r.DepartureDate, r.DepartureTime, r.FromLocation, r.Destination, b.BusNumber, b.BusType, b.FreeSeats, b.ContactNumber
              FROM Route r
              INNER JOIN Bus b ON b.BusNumber = r.BusNumber
              WHERE r.FromLocation = :from AND r.Destination = :to AND r.DepartureDate = :date";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':from' => $from,
        ':to' => $to,
        ':date' => $date
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
