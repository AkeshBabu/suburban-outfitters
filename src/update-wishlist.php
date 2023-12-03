<?php
require_once 'checksession.php';
require_once 'conn.php';

$customerId = $_POST['customerId'] ?? '';
$productId = $_POST['productId'] ?? '';
$action = $_POST['action'] ?? '';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($action == 'add') {
    // Add to wishlist
    $stmt = $conn->prepare("INSERT INTO wishlist (customer_id, product_id, date_added) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $customerId, $productId);
    $stmt->execute();
} elseif ($action == 'remove') {
    // Remove from wishlist
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $customerId, $productId);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Wishlist updated";
?>