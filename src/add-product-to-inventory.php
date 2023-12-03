<?php

require_once 'checksession.php';
require_once 'conn.php';


// Check if the user's email is in the admin table
$currentUserEmail = $_SESSION['email'] ?? null;

if ($currentUserEmail) {
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    $adminCheckStmt = $conn->prepare("SELECT admin_id, email FROM admin WHERE email = ?");
    $adminCheckStmt->bind_param("s", $currentUserEmail);
    $adminCheckStmt->execute();
    $result = $adminCheckStmt->get_result();

    if ($result->num_rows == 0) {

        header("Location: unauthorized.php");
        exit;
    } else {
        $row = $result->fetch_assoc();
        $adminId = $row['admin_id'];
    }
    $adminCheckStmt->close();
} else {
    // No user email in session, redirect to unauthorized page
    header("Location: unauthorized.php");
    exit;
}


function sanitizeMySQL($connection, $var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and validate the data
    $productId = sanitizeMySQL($conn, $_POST['productId']);
    $quantity = sanitizeMySQL($conn, $_POST['quantity']);
    $inventoryDate = sanitizeMySQL($conn, $_POST['inventoryDate']);
    $vendorId = sanitizeMySQL($conn, $_POST['vendorId']);
    $size = sanitizeMySQL($conn, $_POST['size']);

    // Check if product ID exists
    $productCheckQuery = "SELECT COUNT(*) FROM products WHERE product_id = ?";
    $productCheckStmt = $conn->prepare($productCheckQuery);
    $productCheckStmt->bind_param("i", $productId);
    $productCheckStmt->execute();
    $productCheckResult = $productCheckStmt->get_result()->fetch_row()[0];

    if ($productCheckResult == 0) {
        echo "<script>alert('Product not found!'); window.location.href='add-product.php';</script>";
        $productCheckStmt->close();
        $conn->close();
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO inventory (product_id, vendor_id, quantity, inventory_date, size) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $productId, $vendorId, $quantity, $inventoryDate, $size);

    // Execute and check for success
    if ($stmt->execute()) {
        echo "<script>
            alert('Product Added to Inventory Successfully!');
            window.location.href = 'inventory-management.php';
      </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $productCheckStmt->close();

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>