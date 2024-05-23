<?php
// Include the database connection file
require_once 'db_connection.php';

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $userType = $data['userType'];
    $contactNumber = $data['contactNumber'];

    try {
        // Prepare the SQL INSERT statement
        $sql = "INSERT INTO users (username, email, password, user_type, contact_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email, $password, $userType, $contactNumber]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add customer.']);
        }
    } catch (PDOException $e) {
        // Log the error and return an error message
        error_log("Error adding customer: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error adding customer. Please try again later.']);
    }
} else {
    // If no data is provided in the request, return an error message
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

// Close the database connection
$conn = null;
?>
