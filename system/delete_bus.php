<?php
// Include the database connection file
include 'db_connection.php';

// Check if busNumber is provided in the request
if (isset($_GET['busNumber'])) {
    // Get the bus number from the request
    $busNumber = $_GET['busNumber'];
    try {
        // Prepare and execute the delete query
        $query = "DELETE FROM Bus WHERE BusNumber = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$busNumber]);

        // Check if the deletion was successful
        if ($stmt->rowCount() > 0) {
            echo "<script>alert(`Bus deleted successfully.`);</script>" ;
            echo "<script>window.location.href = 'manage_bus.php';</script>";
            exit();
        } else {
            echo "<script>alert(`Failed to delete bus. Bus number not found.`);</script>" ;
            echo "<script>window.location.href = 'manage_bus.php';</script>";
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        error_log("Error deleting bus: " . $e->getMessage());
        echo "<script>alert(`Error deleting bus. Please try again later.`);</script>" ;
        echo "<script>window.location.href = 'manage_bus.php';</script>";
    }
} else {
    // If busNumber is not provided in the request, return an error message
    echo "Bus number not provided.";
}

// Close the database connection
$conn = null;
?>
