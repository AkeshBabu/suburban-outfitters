<?php
session_start();
require_once 'conn.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['cartKey'])) {
    $cartKey = $_GET['cartKey'];

    // Remove the product from the cart using cartKey
    if (isset($_SESSION['cart'][$cartKey])) {
        unset($_SESSION['cart'][$cartKey]);
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit;
}

// Redirect if accessed without cartKey
header("Location: cart.php");
exit;
?>