<?php
session_start();
if (isset($_POST['productId'], $_POST['newSize'])) {
    $productId = $_POST['productId'];
    $newSize = $_POST['newSize'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['size'] = $newSize;
    }
}

echo "Cart size updated";
?>