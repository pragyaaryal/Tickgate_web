<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $busNumber = $_POST['busNumber'];
    $busType = $_POST['busType'];
    $numberOfSeats = $_POST['numberOfSeats'];
    $bookedSeats = $_POST['bookedSeats'];
    // Calculate FreeSeats
    $freeSeats = $numberOfSeats - $bookedSeats;

    // Update the record in the database using prepared statement
    $sql = "UPDATE bus SET BusType=?, NumberOfSeats=?, BookedSeats=?, FreeSeats=? WHERE BusNumber=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiis", $busType, $numberOfSeats, $bookedSeats, $freeSeats, $busNumber);

    if ($stmt->execute()) {
        // Redirect back to the page after update
        header("Location: manage_bus.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

$conn->close();
?>
