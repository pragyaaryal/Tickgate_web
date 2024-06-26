<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include_once "db_connection.php";

    // Get signup form data including the contact number
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $contact_number = $_POST["contact_number"];

    // Validate form data (e.g., check if passwords match, etc.)
    if ($password !== $confirm_password) {
        echo "<script>alert('Error: Passwords do not match');</script>";
        echo "<script>window.location.href = 'login_signup.html';</script>";
        exit();
    }

    // Check if username or email already exists in the database
    $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "<script>alert('Error: Username or email already exists');</script>";
        echo "<script>window.location.href = 'login_signup.html';</script>";
        exit();
    }

    // Insert new user into the database
    $user_type = 'customer'; // Set user type to 'customer' by default
    $sql = "INSERT INTO users (username, email, password, user_type, contact_number) VALUES (:username, :email, :password, :user_type, :contact_number)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password); // Store password as provided
    $stmt->bindParam(":user_type", $user_type);
    $stmt->bindParam(":contact_number", $contact_number);
    $stmt->execute();

    // Show success message using JavaScript alert
    echo "<script>alert('Signup successful!');</script>";
    echo "<script>window.location.href = 'login_signup.html';</script>";
    exit();
}

// Redirect to login page if accessed directly
header("Location: login_signup.html");
exit();
?>
