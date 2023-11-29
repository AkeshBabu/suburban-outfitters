<?php
session_start();

// Remove all session variables
$_SESSION = array();
setcookie(session_name(), '', time() - 2592000, '/');

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");

exit;
?>