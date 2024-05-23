<?php
// edit_bus.php

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickgate";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $busNumber = $data['busNumber'];
    $contactNumber = $data['contactNumber'];
    $busType = $data['busType'];
    $numberOfSeats = $data['numberOfSeats'];
    $bookedSeats = $data['bookedSeats'];
    $freeSeats = $numberOfSeats - $bookedSeats;

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE Bus SET ContactNumber=?, BusType=?, NumberOfSeats=?, BookedSeats=?, FreeSeats=? WHERE BusNumber=?");
    $stmt->bind_param("ssiiis", $contactNumber, $busType, $numberOfSeats, $bookedSeats, $freeSeats, $busNumber);

    // Execute the statement
    if ($stmt->execute()) {
        http_response_code(200);
    } else {
        http_response_code(500);
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
