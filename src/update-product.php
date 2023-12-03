<?php
require_once 'checksession.php';
require_once 'conn.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you're using POST method in your form
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productCategory = $_POST['productCategory'];



    // Prepare and bind
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, category = ? WHERE product_id = ?");
    $stmt->bind_param("sdsi", $productName, $productPrice, $productCategory, $productId);

    // Execute and check for success
    if ($stmt->execute()) {

        // Check if an image file is provided
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
            $targetDirectory = __DIR__ . '/images/products/';
            $imagePath = $targetDirectory . $productId . '.png';

            // Move and resize the new image
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $imagePath)) {
                resizeImage($imagePath, 1024, 1024);
            } else {
                echo "Failed to move uploaded file.";
            }
        }
        echo "<script>
            alert('Product Updated Successfully!');
            window.open('product-detail.php?productId=$productId', '_blank');
            window.location.href = 'inventory-management.php';
      </script>";
    } else {
        echo "Error updating product: " . $conn->error;
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