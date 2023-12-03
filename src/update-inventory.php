<?php
require_once 'checksession.php';
require_once 'conn.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and validate data from POST
    $inventoryId = $_POST['inventoryId'];
    $quantity = $_POST['quantity'];
    $inventoryDate = $_POST['inventoryDate'];
    $size = $_POST['size'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE inventory SET quantity = ?, inventory_date = ?, size = ? WHERE inventory_id = ?");
    $stmt->bind_param("issi", $quantity, $inventoryDate, $size, $inventoryId);

    // Execute and check for success
    if ($stmt->execute()) {
        echo "<script>
                    alert('Inventory Updated Successfully!');
                    window.location.href = 'inventory-management.php';
              </script>";
    } else {
        echo "Error updating inventory: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
