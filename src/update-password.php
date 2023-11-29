<?php
require_once 'checksession.php';
require_once 'conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Check if the new password and confirm new password match
    if ($newPassword !== $confirmNewPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE customer SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashedPassword, $_SESSION['email']);

    if ($stmt->execute()) {
        echo "<script>alert('Password updated successfully.'); window.location.href='profile.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>