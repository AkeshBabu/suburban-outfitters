<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['email'])) {
	header("Location: login.php");
	exit();
}

$customerId = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

?>