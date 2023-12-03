<?php
session_start();
require_once 'conn.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['product_id'], $_POST['quantity'], $_POST['selectedSize'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $selectedSize = $_POST['selectedSize'] ?: 'None';

    // Fetch product details
    $stmt = $conn->prepare("SELECT product_name, price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        // Initialize cart if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Create a unique key for each product-size combination
        $cartKey = $productId . '-' . $selectedSize;

        // Check if this product-size combination already exists in the cart
        if (isset($_SESSION['cart'][$cartKey])) {
            // Update quantity if it already exists
            $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
        } else {
            // Add new item to cart
            $_SESSION['cart'][$cartKey] = [
                'productId' => $productId,
                'name' => $product['product_name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'size' => $selectedSize,
            ];
        }
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