<?php
require_once 'checksession.php';
require_once 'conn.php';


$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

$customerId = $_SESSION['customer_id'];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    // Check if the order belongs to the logged-in customer
    $orderCheckStmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
    $orderCheckStmt->bind_param("ii", $orderId, $customerId);
    $orderCheckStmt->execute();
    $result = $orderCheckStmt->get_result();

    if ($result->num_rows > 0) {
        // Order belongs to customer, proceed to cancel
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");  // Or update the status instead of deleting
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        echo "<script>alert('Order cancelled successfully.'); window.location.href='order-history.php';</script>";
    } else {
        echo "<script>alert('You do not have permission to cancel this order.'); window.location.href='order-history.php';</script>";
    }

    $orderCheckStmt->close();
    $stmt->close();
    $conn->close();
}
?>