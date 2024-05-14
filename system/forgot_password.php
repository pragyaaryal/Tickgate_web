<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and new password from the form
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetchAll();

        if (count($result) > 0) {
            // Email exists, update the password
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update_stmt->execute([$new_password, $email]);

            echo "<script>alert('Password updated successfully.');</script>";
            echo "<script>window.location.href = 'login_signup.html';</script>";
            exit();
        } else {
            echo "<script>alert('Email not found.');</script>";
            echo "<script>window.location.href = 'forgot_password.html';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        echo "<script>window.location.href = 'forgot_password.html';</script>";
    }
}

$conn = null; // Close connection
?>
