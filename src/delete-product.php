<?php
require_once 'checksession.php';
require_once 'conn.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo "<script>
                    alert('Product Deleted Successfully!');
                    window.location.href = 'inventory-management.php';
              </script>";
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>