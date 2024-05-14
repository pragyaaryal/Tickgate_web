<?php

// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $busNumber = $_POST['busNumber'];
    $busType = $_POST['busType'];
    $numberOfSeats = $_POST['numberOfSeats'];
    $bookedSeats = $_POST['bookedSeats'];

    // Update the record in the database
    $sql = "UPDATE bus SET BusType='$busType', NumberOfSeats='$numberOfSeats', BookedSeats='$bookedSeats' WHERE BusNumber='$busNumber'";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the page after update
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
