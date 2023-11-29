<?php
session_start();

$response = ['success' => false];

if (isset($_POST['productId'], $_POST['quantity'])) {
    $productId = $_POST['productId'];
    $quantity = intval($_POST['quantity']);

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;

        // Recalculate line total and grand total
        $lineTotal = $quantity * $_SESSION['cart'][$productId]['price'];
        $grandTotal = 0;
        foreach ($_SESSION['cart'] as $id => $details) {
            $grandTotal += $details['quantity'] * $details['price'];
        }

        $response = [
            'success' => true,
            'lineTotal' => number_format($lineTotal, 2),
            'grandTotal' => number_format($grandTotal, 2)
        ];
    }
}

echo json_encode($response);
?>