<?php
// Include the database connection file
include 'db_connection.php';

// Function to fetch bus information by bus number
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

// Function to add bus information to the database
function addBusInfo($conn, $busNumber, $busType, $numberOfSeats, $bookedSeats)
{
    try {
        // Check if the bus number already exists
        $existingBus = fetchBusByNumber($conn, $busNumber);
        if ($existingBus) {
            echo "<script>alert(`Bus Number already exists.`);</script>" ;
            echo "<script>window.location.href = 'manage_bus.php';</script>";
        }

        // If the bus number doesn't exist, proceed with adding bus information
        $freeSeats = $numberOfSeats - $bookedSeats; // Calculate free seats
        $query = "INSERT INTO Bus (BusNumber, BusType, NumberOfSeats, BookedSeats, FreeSeats) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$busNumber, $busType, $numberOfSeats, $bookedSeats, $freeSeats]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            // Redirect after successful addition
            echo "<script>alert(`Bus added successfully`);</script>" ;
            echo "<script>window.location.href = 'manage_bus.php';</script>";
            exit();
        } else {
            return "Failed to add bus.";
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        echo error_log("Error adding bus: " . $e->getMessage());
        echo "Error adding bus. Please try again later.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $busNumber = $_POST["busNumber"];
    $busType = $_POST["busType"];
    $numberOfSeats = $_POST["numberOfSeats"];
    $bookedSeats = $_POST["bookedSeats"];

    // Call addBusInfo function
    $result = addBusInfo($conn, $busNumber, $busType, $numberOfSeats, $bookedSeats);

    // Output result or error message
    if ($result === true) {
        echo "Bus added successfully";
        echo "<script>window.location.href = 'manage_bus.php';</script>";
    } else {
        echo  $result;
        echo "<script>window.location.href = 'manage_bus.php';</script>";
    }
}

// Close the database connection
$conn = null;
?>
