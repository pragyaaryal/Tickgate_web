<?php
// Include the database connection file
include 'db_connection.php';

// Check if busNumber is provided in the request
if (isset($_GET['RouteID'])) {
    // Get the bus number from the request
    $userId = $_GET['RouteID'];
    try {
        // Prepare and execute the delete query
        $query = "DELETE FROM Route WHERE RouteID = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$RouteID]);

        // Check if the deletion was successful
        if ($stmt->rowCount() > 0) {
            echo "<script>alert(`Route deleted successfully.`);</script>" ;
            echo "<script>window.location.href = 'manage_route.php';</script>";
            exit();
        } else {
            echo "<script>alert(`Failed to remove the Route. Route ID not found.`);</script>" ;
            echo "<script>window.location.href = 'manage_route.php';</script>";
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        error_log("Error deleting User: " . $e->getMessage());
        echo "<script>alert(`Error deleting Route. Please try again later.`);</script>" ;
        echo "<script>window.location.href = 'manage_route.php';</script>";
    }
} else {
    // If busNumber is not provided in the request, return an error message
    echo "Route ID not provided.";
}

// Close the database connection
$conn = null;
?>
