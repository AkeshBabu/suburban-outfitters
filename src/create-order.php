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

try {
    $userId = $_SESSION['customer_id'];
    $orderDate = date("Y-m-d H:i:s");
    $status = "Placed";
    $totalAmount = array_sum(array_map(function ($item) {
        return $item['quantity'] * $item['price'];
    }, $_SESSION['cart']));
    $_SESSION['order_items'] = [];

    // Insert the order into 'orders' table
    $insertOrder = $conn->prepare("INSERT INTO orders (customer_id, order_date, total_amount, status) VALUES (?, ?, ?, ?)");
    $insertOrder->bind_param("isds", $userId, $orderDate, $totalAmount, $status);
    $insertOrder->execute();
    $orderId = $conn->insert_id;

    // Loop through each item in the cart to handle order details and inventory updates
    foreach ($_SESSION['cart'] as $productId => $productDetails) {
        $quantity = $productDetails['quantity'];
        $productSize = isset($productDetails['size']) ? htmlspecialchars($productDetails['size']) : 'N/A';
        $productTotal = $quantity * $productDetails['price'];

        // Insert into orderline table with size
        $insertDetail = $conn->prepare("INSERT INTO orderline (order_id, product_id, quantity, size, total_amount) VALUES (?, ?, ?, ?, ?)");
        $insertDetail->bind_param("iiisd", $orderId, $productId, $quantity, $productSize, $productTotal);
        $insertDetail->execute();

        // Update inventory
        if ($productSize === 'None') {
            $stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?");
            $stmt->bind_param("ii", $quantity, $productId);
        } else {
            $stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE product_id = ? AND size = ?");
            $stmt->bind_param("iis", $quantity, $productId, $productSize);
        }
        if (!$stmt->execute()) {
            echo "Error updating inventory: " . $conn->error;
        }

        // Store product details for the receipt
        $_SESSION['order_items'][] = [
            'productId' => $productId,
            'name' => $productDetails['name'],
            'size' => $productDetails['size'],
            'quantity' => $productDetails['quantity'],
            'price' => $productDetails['price'],
            'total' => $productDetails['quantity'] * $productDetails['price']
        ];
    }


    echo " <script> document.getElementById('orderForm').onsubmit = function(event) { if (!confirm('Are you sure you want to place the order?')) { event.preventDefault(); }};</script>";
    // Commit transaction and clear the cart
    $conn->commit();
    $_SESSION['cart'] = [];

    echo "<script>
    alert('Order Placed Successfully!');
    window.open('order-receipt.php?orderId=' + $orderId, '_blank');
    window.location.href = 'order-history.php';
    </script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>