<?php
session_start();
require_once 'conn.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product details
    $stmt = $conn->prepare("SELECT product_name, price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        // Add to cart or update cart in session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        $_SESSION['cart'][$productId] = [
            'name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the cart or product page
    header("Location: cart.php");
    exit;
}

// Redirect if accessed without POST data
header("Location: product-detail.php");
exit;
?>