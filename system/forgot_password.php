<?php
include 'db_connection.php';

// Create an associative array to hold the response data
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and new password from form
    $email = $_POST["email"];
    $newPassword = $_POST["password"];

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $updateQuery = "UPDATE Users SET password = ? WHERE email = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
    mysqli_stmt_execute($stmt);

    // Check if the update was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Password updated successfully
        $response["success"] = true;
        $response["message"] = "Password updated successfully.";
    } else {
        // Email not found or password update failed
        $response["success"] = false;
        $response["message"] = "Error updating password. Please try again.";
    }
}

// Send the response as JSON
header("Content-type: application/json");
echo json_encode($response);
?>
