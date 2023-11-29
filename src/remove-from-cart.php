<?php
require_once 'conn.php';
require_once 'checksession.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Remove the product from the cart
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit;
}

// Redirect if accessed without productId
header("Location: cart.php");
exit;
?>