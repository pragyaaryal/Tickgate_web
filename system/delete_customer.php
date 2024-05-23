<?php
// Include the database connection file
require_once 'db_connection.php';

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    try {
        // Prepare and execute the delete query
        $query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);

        // Check if the deletion was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to remove user. User ID not found.']);
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        error_log("Error deleting user: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error deleting user. The user has a reservation. Please try again later.']);
    }
} else {
    // If userId is not provided in the request, return an error message
    echo json_encode(['success' => false, 'error' => 'User ID not provided.']);
}

// Close the database connection
$conn = null;
?>
