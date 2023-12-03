<?php
require_once 'conn.php'; 
require_once 'checksession.php';

$customerId = $_POST['customerId'] ?? '';
$productId = $_POST['productId'] ?? '';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($productId && $customerId) {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $customerId, $productId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo "Wishlist item removed";
?>
