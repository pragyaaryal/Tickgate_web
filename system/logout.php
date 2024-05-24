<?php
session_start();
session_destroy(); // Destroy the session
header('Location: login_signup.html'); // Redirect to login page
exit();
?>
