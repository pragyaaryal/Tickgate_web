<?php
  // Include database connection file
  include_once "db_connection.php";
  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["new_email"]) && isset($_POST["current_email"])) {
        $currentEmail = $_POST["current_email"];
        $newEmail = $_POST["new_email"];

        $sql = "UPDATE users SET email='$newEmail' WHERE email='$currentEmail'";
        if ($conn->query($sql) === TRUE) {
            echo "Email updated successfully";
        } else {
            echo "Error updating email: " . $conn->error;
        }
    }

    if (isset($_POST["current_password"]) && isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
        $currentPassword = $_POST["current_password"];
        $newPassword = $_POST["new_password"];
        $confirmPassword = $_POST["confirm_password"];

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$hashedPassword' WHERE password='$currentPassword'";
        if ($conn->query($sql) === TRUE) {
            echo "Password updated successfully";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    }
}

$conn->close();
?>
