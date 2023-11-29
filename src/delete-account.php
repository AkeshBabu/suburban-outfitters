<?php
require_once 'checksession.php';
require_once 'conn.php';

if (!isset($_SESSION['email'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email']; // Assuming the user's email is stored in the session

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the delete query
$stmt = $conn->prepare("DELETE FROM customer WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>alert('Profile successfully deleted.');</script>";
} else {
    echo "<script>alert('An error occurred. Profile not deleted.');</script>";
}

$stmt->close();
$conn->close();

// Clear and destroy session
$_SESSION = array();
setcookie(session_name(), '', time() - 42000, '/');
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
?>