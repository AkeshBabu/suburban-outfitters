<?php
require_once 'checksession.php';
require_once 'conn.php';

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['redirect_after_login'] = 'cart.php';
    header("Location: login.php");
    exit();
}

// Check if the cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Cart is empty.");
}

// Start the database connection
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Begin transaction
$conn->begin_transaction();

try {
    $userId = $_SESSION['customer_id'];
    $orderDate = date("Y-m-d H:i:s");
    $status = "Placed";
    $totalAmount = array_sum(array_map(function ($item) {
        return $item['quantity'] * $item['price']; }, $_SESSION['cart']));

    // Insert the order into 'orders' table
    $insertOrder = $conn->prepare("INSERT INTO orders (customer_id, order_date, total_amount, status) VALUES (?, ?, ?, ?)");
    $insertOrder->bind_param("isds", $userId, $orderDate, $totalAmount, $status);
    $insertOrder->execute();
    $orderId = $conn->insert_id;

    // Prepare data for orderline
    $productIds = array_keys($_SESSION['cart']);
    $quantities = array_column($_SESSION['cart'], 'quantity');
    $quantityString = implode(",", $quantities);

    // Insert into orderline table
    $insertDetail = $conn->prepare("INSERT INTO orderline (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $productIdsString = implode(",", $productIds);
    $insertDetail->bind_param("iss", $orderId, $productIdsString, $quantityString);
    $insertDetail->execute();

    // Commit transaction and clear the cart
    $conn->commit();
    $_SESSION['cart'] = [];

    echo "<script>
    var confirmation = confirm('Confirm your order?');
    if (confirmation) {
        alert('Order Placed Successfully!');
        window.open('order-receipt.php?orderId=$orderId', '_blank');
        window.location.href = 'order-history.php';
    } else {
        // User canceled the order
        alert('Order was not placed.');
    }
  </script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>