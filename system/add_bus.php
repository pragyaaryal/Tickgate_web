<?php
include 'db_connection.php';

function fetchBusByNumber($conn, $busNumber)
{
    try {
        $sql = "SELECT * FROM Bus WHERE BusNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$busNumber]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        // Log the error and return false to indicate failure
        error_log("Error fetching bus information: " . $e->getMessage());
        return false;
    }
}

function addBusInfo($conn, $busNumber, $busType, $contactNumber, $numberOfSeats, $bookedSeats)
{
    try {
        // Check if the bus number already exists
        $existingBus = fetchBusByNumber($conn, $busNumber);
        if ($existingBus) {
            // Redirect after displaying alert
            echo "<script>alert('Bus Number already exists.');</script>";
            echo "<script>window.location.href = 'manage_bus.php';</script>";
            exit();
        }

        // If the bus number doesn't exist, proceed with adding bus information
        $freeSeats = $numberOfSeats - $bookedSeats; // Calculate free seats
        $query = "INSERT INTO Bus (BusNumber, BusType, ContactNumber, NumberOfSeats, BookedSeats, FreeSeats) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$busNumber, $busType, $contactNumber, $numberOfSeats, $bookedSeats, $freeSeats]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            // Redirect after successful addition
            echo "<script>alert('Bus added successfully.');</script>";
            echo "<script>window.location.href = 'manage_bus.php';</script>";
            exit();
        } else {
            return "Failed to add bus.";
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        error_log("Error adding bus: " . $e->getMessage());
        return "Error adding bus. Please try again later.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $busNumber = $_POST["busNumber"];
    $busType = $_POST["busType"];
    $contactNumber = $_POST["contactNumber"];
    $numberOfSeats = $_POST["numberOfSeats"];
    $bookedSeats = $_POST["bookedSeats"];

    // Call addBusInfo function
    $result = addBusInfo($conn, $busNumber, $busType, $contactNumber, $numberOfSeats, $bookedSeats);

    // Output result or error message
    if ($result !== true) {
        echo $result; // Echoing error message
    }

    // Redirect regardless of success or failure
    header("Location: manage_bus.php");
    exit();
}

// Close the database connection
$conn = null;
?>
