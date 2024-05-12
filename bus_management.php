<?php
// Include the database connection file
require_once 'db_connection.php';

// Function to fetch bus information from the database
function fetchBusInfo($conn)
{
    try {
        $sql = "SELECT *, NumberOfSeats - BookedSeats AS FreeSeats FROM Bus";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        // Handle database connection or query errors
        return [];
    }
}

// Function to add bus information to the database
function addBusInfo($conn, $busNumber, $busType, $numberOfSeats, $bookedSeats)
{
    try {
        // Check if the bus number already exists
        $existingBus = fetchBusByNumber($conn, $busNumber);
        if ($existingBus) {
            return "Bus number already exists. Please choose a different bus number.";
        }

        // If the bus number doesn't exist, proceed with adding bus information
        $freeSeats = $numberOfSeats - $bookedSeats; // Calculate free seats
        $sql = "INSERT INTO Bus (BusNumber, BusType, NumberOfSeats, BookedSeats, FreeSeats) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$busNumber, $busType, $numberOfSeats, $bookedSeats, $freeSeats])) {
            return "Bus added successfully.";
        } else {
            return "Failed to add bus.";
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        return "Error: " . $e->getMessage();
    }
}

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
        // Handle database connection or query errors
        return false; // Return false to indicate failure
    }
}

// Function to update bus information in the database
function updateBusInfo($conn, $busNumber, $busType, $numberOfSeats, $bookedSeats)
{
    try {
        $freeSeats = $numberOfSeats - $bookedSeats; // Calculate free seats
        $sql = "UPDATE Bus SET BusType=?, NumberOfSeats=?, BookedSeats=?, FreeSeats=? WHERE BusNumber=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$busType, $numberOfSeats, $bookedSeats, $freeSeats, $busNumber])) {
            return "Bus updated successfully.";
        } else {
            return "Failed to update bus.";
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        return "Error: " . $e->getMessage();
    }
}

// Function to delete bus information from the database
function deleteBusInfo($conn, $busNumber)
{
    try {
        $sql = "DELETE FROM Bus WHERE BusNumber=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$busNumber])) {
            return "Bus deleted successfully.";
        } else {
            return "Failed to delete bus.";
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        return "Error: " . $e->getMessage();
    }
}

// Check if form is submitted for adding, editing, or deleting bus information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['editBus'])) {
        // If edit form is submitted, update bus information
        $busNumber = $_POST['busNumber'];
        $busType = $_POST['busType'];
        $numberOfSeats = $_POST['numberOfSeats'];
        $bookedSeats = $_POST['bookedSeats'];
        echo updateBusInfo($conn, $busNumber, $busType, $numberOfSeats, $bookedSeats);
    } else if (isset($_POST['addBus'])) {
        // If add form is submitted, add new bus information
        $busNumber = $_POST['busNumber'];
        $busType = $_POST['busType'];
        $numberOfSeats = $_POST['numberOfSeats'];
        $bookedSeats = $_POST['bookedSeats'];
        echo addBusInfo($conn, $busNumber, $busType, $numberOfSeats, $bookedSeats);
    } else if (isset($_POST['deleteBus'])) {
        // If delete button is clicked, delete bus information
        $busNumber = $_POST['busNumber'];
        echo deleteBusInfo($conn, $busNumber);
    }
}

// Fetch bus information from the database
$busInfo = fetchBusInfo($conn);
echo json_encode($busInfo); // Return bus information as JSON
?>
