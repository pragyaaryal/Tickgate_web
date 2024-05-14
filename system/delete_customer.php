<?php
// Include the database connection file
include 'db_connection.php';

// Check if busNumber is provided in the request
if (isset($_GET['userId'])) {
    // Get the bus number from the request
    $userId = $_GET['userId'];
    try {
        // Prepare and execute the delete query
        $query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);

        // Check if the deletion was successful
        if ($stmt->rowCount() > 0) {
            echo "<script>alert(`User removed successfully.`);</script>" ;
            echo "<script>window.location.href = 'manage_customer.php';</script>";
            exit();
        } else {
            echo "<script>alert(`Failed to remove User. User ID not found.`);</script>" ;
            echo "<script>window.location.href = 'manage_customer.php';</script>";
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        error_log("Error deleting User: " . $e->getMessage());
        echo "<script>alert(`Error deleting User. Please try again later.`);</script>" ;
        echo "<script>window.location.href = 'manage_customer.php';</script>";
    }
} else {
    // If busNumber is not provided in the request, return an error message
    echo "User ID not provided.";
}

// Close the database connection
$conn = null;
?>
