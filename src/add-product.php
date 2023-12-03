<?php

require_once 'checksession.php';
require_once 'conn.php';

function sanitizeMySQL($connection, $var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}

function resizeImage($filePath, $width, $height)
{
    list($originalWidth, $originalHeight) = getimagesize($filePath);
    $newImage = imagecreatetruecolor($width, $height);
    $sourceImage = imagecreatefrompng($filePath);
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
    imagepng($newImage, $filePath);
    imagedestroy($newImage);
    imagedestroy($sourceImage);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and validate the data
    $productName = sanitizeMySQL($conn, $_POST['productName']);
    $productPrice = sanitizeMySQL($conn, $_POST['productPrice']);
    $productCategory = sanitizeMySQL($conn, $_POST['productCategory']);
    $email = $_SESSION['email'];

    // Fetch admin_id from admin table
    $stmt = $conn->prepare("SELECT admin_id FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die("Admin not found.");
    }
    $row = $result->fetch_assoc();
    $adminId = $row['admin_id'];

    // Insert product data
    $stmt = $conn->prepare("INSERT INTO products (admin_id, product_name, price, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $adminId, $productName, $productPrice, $productCategory);

    if ($stmt->execute()) {
        $newProductId = $conn->insert_id; // Get the new product ID

        $targetDirectory = __DIR__ . '/images/products/'; // Directory for product images
        $defaultImagePath = __DIR__ . '/images/default.png'; // Path to default image
        $newImagePath = $targetDirectory . $newProductId . '.png';

        // Ensure the target directory exists
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
            // Move the uploaded file
            if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $newImagePath)) {
                echo "Failed to move uploaded file.";
            }
            // Resize the image
            resizeImage($newImagePath, 1024, 1024);
        } else {
            // Copy the default image
            if (!copy($defaultImagePath, $newImagePath)) {
                echo "Failed to set default image.";
            }
        }

        echo "<script>
            alert('Product Added Successfully!');
            window.open('product-detail.php?productId=$newProductId', '_blank');
            window.location.href = 'inventory-management.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Function to resize image
    function resizeImage($filePath, $width, $height)
    {
        list($originalWidth, $originalHeight) = getimagesize($filePath);
        $newImage = imagecreatetruecolor($width, $height);
        $sourceImage = imagecreatefrompng($filePath);
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
        imagepng($newImage, $filePath);
        imagedestroy($newImage);
        imagedestroy($sourceImage);
    }

    $stmt->close();
    $conn->close();
}
?>