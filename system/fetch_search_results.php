<?php
// Include the database connection file
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    // Get search parameters from the form
    $from = $_POST["from"];
    $to = $_POST["to"];
    $date = $_POST["date"];

    // Fetch data from the database based on search parameters
    $sql = "SELECT r.*, b.BusType, b.FreeSeats 
            FROM Route r 
            JOIN Bus b ON r.BusNumber = b.BusNumber 
            WHERE r.FromLocation = ? AND r.Destination = ? AND r.DepartureDate = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $from, $to, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display searched results
    while ($row = $result->fetch_assoc()) {
        echo "<div class='ticket'>";
        echo "<div class='ticket-info'>";
        echo "<div class='date-time'>";
        echo "<p><strong>Date:</strong> " . $row['DepartureDate'] . "</p>";
        echo "<p><strong>Time:</strong> " . $row['DepartureTime'] . "</p>";
        echo "</div>";
        echo "<div class='from-to'>";
        echo "<p><strong>From:</strong> " . $row['FromLocation'] . "</p>";
        echo "<p><strong>To:</strong> " . $row['Destination'] . "</p>";
        echo "<p><strong>Driver Contact:</strong> " . $row['ContactNumber'] . "</p>";
        echo "</div>";
        echo "<div class='bus-details'>";
        echo "<p><strong>Bus Number:</strong> " . $row['BusNumber'] . "</p>";
        echo "<p><strong>Bus Type:</strong> " . $row['BusType'] . "</p>";
        echo "<p><strong>Free Seats:</strong> " . $row['FreeSeats'] . "</p>";
        echo "</div>";
        echo "<div class='bookingdetails'>";
        echo "<label for='numbeofseatsreserved'>Book Seats</label>";
        echo "<select name='numbeofseatsreserved' id='numbeofseatsreserved' aria-placeholder='Number of Seats'>";
        for ($i = 1; $i <= $row['FreeSeats']; $i++) {
            echo "<option value='$i'>$i Seat" . ($i > 1 ? "s" : "") . "</option>";
        }
        echo "</select>";
        echo "<button class='book-button'>Book Ticket</button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    // Close prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
