<?php
require_once 'checksession.php';
require_once 'conn.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['inventoryId'])) {
    $inventoryId = $_GET['inventoryId'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM inventory WHERE inventory_id = ?");
    $stmt->bind_param("i", $inventoryId);

    if ($stmt->execute()) {
        echo "<script>
            alert('Product Deleted from Inventory Successfully!');
            window.location.href = 'inventory-management.php';
      </script>";
    } else {
        echo "Error deleting inventory item: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>