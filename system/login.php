<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include_once "db_connection.php";

    if (isset($_POST["login"])) {
        // Login process
        $email = $_POST["email"];
        $password = $_POST["password"];
        $user_type = $_POST["user_type"];

        // Query the database to check if the user exists
        $sql = "SELECT * FROM users WHERE email = :email AND password = :password AND user_type = :user_type";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":user_type", $user_type);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Store user data in session variables
            $_SESSION["user_id"] = $user["user_id"]; // Updated key to "user_id"
            $_SESSION["username"] = $user["username"];
            $_SESSION["user_type"] = $user["user_type"];
        
            // Redirect based on user type
            if ($_SESSION["user_type"] == "admin") {
                // Redirect to admin_dashboard.php if user is admin
                echo "<script>alert('Admin logged in');</script>";
                echo "<script>window.location.href = 'admin_dashboard.php';</script>";
                exit();
            } else {
                // Redirect to user_dashboard.php if user is not admin
                echo "<script>alert('User logged in');</script>";
                echo "<script>window.location.href = 'user_dashboard.php';</script>";
                exit();
            }
        }}
}
?>