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

// Function to fetch all products
function fetchAllProducts($conn)
{
    $query = "SELECT product_id, product_name, price, category FROM products";
    $result = $conn->query($query);
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}
$products = fetchAllProducts($conn);


// Function to fetch all inventory items
function fetchAllInventory($conn)
{
    $query = "SELECT i.inventory_id, p.product_name, i.product_id, i.quantity, i.inventory_date, i.size 
              FROM inventory i 
              JOIN products p ON i.product_id = p.product_id";
    $result = $conn->query($query);
    $inventoryItems = [];
    while ($row = $result->fetch_assoc()) {
        $inventoryItems[] = $row;
    }
    return $inventoryItems;
}
$inventoryItems = fetchAllInventory($conn);

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
    $size = $_POST['size'] === '' ? NULL : sanitizeMySQL($conn, $_POST['size']);

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
        echo "<script>alert('New inventory item added successfully!'); window.location.href='add-product.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $productCheckStmt->close();

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="stylesheets/style.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <style>
        .registration-form {
            border: 2px solid #dee2e6 !important;
            border-radius: 5px !important;
            padding: 20px !important;
            max-width: 600px !important;
            margin: 50px auto !important;
        }

        .form-group {
            margin-bottom: 15px !important;
        }

        .registration-form label {
            margin-bottom: 5px !important;
        }

        .registration-form input[type="checkbox"] {
            margin-top: 3px !important;
        }

        .registration-form button {
            width: unset !important;
        }

        .hidden {
            display: none !important;
        }

        .btn-primary {
            background-color: black !important;
        }

        #action a {
            text-decoration: none;
            transition: 0.4s;
            padding: 6px;
            border: 0.5px solid black;
        }

        #action a:hover {
            background-color: #dadada;
            border-radius: 4%;
        }

        p {
            margin-top: 0;
            margin-bottom: 2rem;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper:after {
            content: '▼';
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 2px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        #action {
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg navbar-light ">
            <div class="container-fluid">
                <a class="navbar-brand" href="homepage.php">
                    <img src="images/Suburban-gif.gif" width="50%" height="50%">
                </a>
                <h1 style="font-weight:600;" class="mx-auto">The Official Store of Suburban Outfitters</h1>
                <ul class="navbar-nav">
                    <li id="searchBox" class="nav-item">
                        <form action="view-products.php" class="d-flex" method="GET">
                            <div class="input-group">
                                <input style="border: double;" class="form-control" type="search" name="search"
                                    placeholder="Search" aria-label="Search">
                                <button style="border: double; background-color: white;" type="submit"> <img
                                        src="https://cdn-icons-png.flaticon.com/128/2811/2811806.png" width="30px"
                                        height="30px"></button>
                            </div>
                        </form>
                    </li>
                    <li id="profileIcon" class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/64/64572.png" width="30px" height="30px">
                        </a>
                    </li>
                    <li id="wishlistIcon" class="nav-item">
                        <a class="nav-link" href="wishlist.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/4240/4240564.png" width="30px"
                                height="30px">
                        </a>
                    </li>
                    <li id="cartIcon" class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/253/253298.png" width="30px" height="30px">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Second Navbar for Categories -->
        <nav id="categories" class="navbar navbar-expand-lg navbar-light " style="background-color: ghostwhite;">
            <ul id="global-main-menu" class="nav navbar-nav navbar-collapse collapse"
                style="justify-content: center;flex-wrap:nowrap; gap: 30px;">
                <!-- Categories as list items -->
                <li class="nav-item">
                    <a class="nav-link" href="view-products.php?category=men"><strong>Men</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view-products.php?category=women"><strong>Women</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view-products.php?category=headwear"><strong>Headwear</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view-products.php?category=footwear"><strong>Footwear</strong></a>
                </li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <br>
        <br>
        <h2 class="text-center">Inventory Management</h2>
        <br>
        <br>
        <div class="selection" style="text-align:center;">
            <button class="btn btn-primary" id="btnAddProduct" onclick="showForm('addProductForm')">Add New
                Product</button>
            <button class="btn btn-primary" id="btnAddInventory" onclick="showForm('addInventoryForm')">Add Product to
                Inventory</button>
            <button class="btn btn-primary" id="btnViewProducts" onclick="showForm('productListing')">View Product
                Listing</button>
            <button class="btn btn-primary" id="btnViewInventory" onclick="showForm('productInventory')">View
                Inventory</button>
        </div>
        <div id="registerForm" class="registration-form" style="display:none;">

            <!-- Add New Product -->
            <div id="addProductForm" style="display:none;">
                <h3>Add New Product</h3>
                <form id="productForm" method="post" enctype="multipart/form-data"
                    onsubmit="return confirmProductAddition()">
                    <input type="hidden" name="adminId" value="<?php echo $adminId; ?>">

                    <div class="form-group">
                        <label for="productName">Product Name:</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">Price:</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="productPrice"
                            name="productPrice" required>
                    </div>

                    <div class="form-group">
                        <label for="productCategory">Category:</label>
                        <input type="text" class="form-control" id="productCategory" name="productCategory" required>
                    </div>

                    <div class="form-group">
                        <label for="productImage">Product Image:</label>
                        <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <button id="cancelBtn" type="button" class="btn btn-primary"
                            onclick="location.href='inventory-management.php'">Cancel</button>
                    </div>
                </form>
            </div>


            <!-- Add Product to Inventory Form -->
            <div id="addInventoryForm" style="display:none;">
                <form action="add-product-to-inventory.php" method="post"
                    onsubmit="return confirmProductAdditionToInventory()">
                    <h3>Add Product to Inventory</h3>
                    <br>
                    <div class="form-group">
                        <label for="productId">Product:</label>
                        <div class="select-wrapper">
                            <select class="form-control" id="productId" name="productId" required>
                                <option value="">Select a Product</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['product_id']; ?>">
                                        <?php echo "Product ID: " . $product['product_id'] . ' - ' . htmlspecialchars($product['product_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="inventoryDate">Inventory Date:</label>
                        <?php $today = date("Y-m-d"); ?>
                        <input type="date" class="form-control" id="inventoryDate" name="inventoryDate"
                            max="<?php echo $today; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="vendorId">Vendor ID:</label>
                        <input type="number" min="1" class="form-control" id="vendorId" name="vendorId" required>
                    </div>

                    <div class="form-group">
                        <label for="size">Size:</label>
                        <div class="select-wrapper">
                            <select class="form-control" id="size" name="size">
                                <option value="None">None</option>
                                <option value="xs">XS</option>
                                <option value="sm">SM</option>
                                <option value="md">MD</option>
                                <option value="lg">LG</option>
                                <option value="xl">XL</option>
                                <option value="2xl">2XL</option>
                                <option value="3xl">3XL</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Add Product To Inventory</button>
                        <button id="cancleBtn" style="display: block;" type="button" class="btn btn-primary"
                            onclick="location.href='inventory-management.php'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Product Listing Table -->
        <div id="productListing" style="display:none; margin-top: 50px;">
            <input type="text" id="searchProductListing" placeholder="Search in Product Listing..."
                style="width: 300px; margin-bottom: 30px;">
            <table id="productListingTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($product['price']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($product['category']); ?>
                            </td>
                            <td id="action" style="display: flex; justify-content: space-between;">
                                <a href="javascript:void(0);" style="color: black;" onclick="openUpdateProductModal(this)"
                                    data-product-id="<?php echo $product['product_id']; ?>"
                                    data-product-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                                    data-product-price="<?php echo htmlspecialchars($product['price']); ?>"
                                    data-product-category="<?php echo htmlspecialchars($product['category']); ?>">
                                    Update
                                </a>
                                <a href="javascript:void(0);"
                                    onclick="openProductDeleteConfirmation(<?php echo $product['product_id']; ?>)"
                                    style="color: red;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- View Inventory Table -->
        <div id="productInventory" style="display:none;">
            <input type="text" id="searchProductInventory" placeholder="Search in Product Inventory..."
                style="width: 300px; margin-bottom: 30px; margin-top: 50px;">
            <table id="productInventoryTable">

                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Inventory Date</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventoryItems as $item): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($item['product_name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['product_id']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['quantity']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['inventory_date']); ?>
                            </td>
                            <td>
                                <?php echo $item['size'] !== null ? htmlspecialchars($item['size']) : 'Size Not Available'; ?>
                            </td>
                            <td id="action" style="display: flex; justify-content: space-between;">
                                <a href="javascript:void(0);" style="color: black;" onclick="openUpdateInventoryModal(this)"
                                    data-inventory-id="<?php echo $item['inventory_id']; ?>"
                                    data-product-id="<?php echo $item['product_id']; ?>"
                                    data-quantity="<?php echo htmlspecialchars($item['quantity']); ?>"
                                    data-inventory-date="<?php echo htmlspecialchars($item['inventory_date']); ?>"
                                    data-size="<?php echo $item['size'] !== null ? htmlspecialchars($item['size']) : 'Size Not Available'; ?>">
                                    Update
                                </a>
                                <a href="javascript:void(0);"
                                    onclick="openInventoryDeleteConfirmation(<?php echo $item['inventory_id']; ?>)"
                                    style="color: red;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- Modal for Updating Product -->
    <div class="modal fade show" id="updateProductModal" tabindex="-1" role="dialog"
        aria-labelledby="updateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProductModalLabel">Update Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateProductForm" method="post" action="update-product.php"
                        onsubmit="return confirmProductUpdate()">
                        <input type="hidden" id="updateProductId" name="productId">
                        <div class="form-group">
                            <label for="updateProductName">Product Name:</label>
                            <input type="text" class="form-control" id="updateProductName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateProductPrice">Price:</label>
                            <input type="number" class="form-control" id="updateProductPrice" name="productPrice"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="updateProductCategory">Category:</label>
                            <input type="text" class="form-control" id="updateProductCategory" name="productCategory"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Current Image:</label>
                            <div id="currentProductImageName"></div>
                        </div>

                        <div class="form-group">
                            <label for="productImage">Update Product Image:</label>
                            <input type="file" class="form-control" id="productImage" name="productImage"
                                accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Updating Inventory -->
    <div class="modal fade" id="updateInventoryModal" tabindex="-1" role="dialog"
        aria-labelledby="updateInventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateInventoryModalLabel">Update Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateInventoryForm" method="post" action="update-inventory.php"
                        onsubmit="return confirmProductUpdateToInventory()">
                        <input type="hidden" id="updateInventoryId" name="inventoryId">
                        <div class="form-group">
                            <label for="updateInventoryQuantity">Quantity:</label>
                            <input type="number" class="form-control" id="updateInventoryQuantity" name="quantity"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="updateInventoryDate">Inventory Date:</label>
                            <input type="date" class="form-control" id="updateInventoryDate" name="inventoryDate"
                                required>
                        </div>
                        <div class="form-group">

                            <label for="updateInventorySize">Size:</label>
                            <div class="select-wrapper">
                                <select class="form-control" id="updateInventorySize" name="size">
                                    <option value="None">None</option>
                                    <option value="xs">XS</option>
                                    <option value="sm">SM</option>
                                    <option value="md">MD</option>
                                    <option value="lg">LG</option>
                                    <option value="xl">XL</option>
                                    <option value="2xl">2XL</option>
                                    <option value="3xl">3XL</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Inventory</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <br>
    <br>


    <!-- Footer -->
    <footer class="mt-auto">
        <div class="container py-4" style="text-align: center;">
            <div class="row">
                <div class="col-md-3">
                    <a href="#" id="contactusModalTrigger">Contact Us</a>
                </div>
                <div class="col-md-3">
                    <a href="#" id="LocationHoursTrigger">Location and Hours</a>
                </div>
                <div class="col-md-3">
                    <a href="#" id="privacyTermsModalTrigger">Privacy and Terms</a>
                </div>

                <div id="socialIcons" style="display: flex; justify-content: center; gap:25px;" class="col-md-3">
                    <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            <a tabindex="0" style="color: red;">Copyright 2023 © Suburban Outfitters </a>
        </div>
    </footer>


    <!-- Modal for Contact Us -->
    <div class="modal fade" id="contactUsModal" tabindex="-1" role="dialog" aria-labelledby="contactUsModalLabel"
        aria-hidden="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactUsModalLabel">Contact Us</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p>Email us at: <a href="mailto: suburbanoutfitters@gmail.com">suburbanoutfitters@gmail.com</a>
                    </p>
                    <p>Call us at: <a href="tel:+123456789">1234567890</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Location and Hours -->
    <div class="modal fade show" id="locationHoursModal" tabindex="-1" role="dialog"
        aria-labelledby="locationHoursModalLabel" aria-hidden="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationHoursModalLabel">Location and Hours</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p>We are open 24x7, anywhere and everywhere!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Privacy and Terms -->
    <div class="modal fade show" id="privacyTermsModal" tabindex="-1" role="dialog"
        aria-labelledby="privacyTermsModalLabel" style="padding-right: 17px; display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyTermsModalLabel">Privacy and Terms</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;">Created and Maintained by DevTeam @ Suburban Outfitters.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>

        document.getElementById('privacyTermsModalTrigger').addEventListener('click', function () {
            var myModal = new bootstrap.Modal(document.getElementById('privacyTermsModal'));
            myModal.show();
        });
        document.getElementById('contactusModalTrigger').addEventListener('click', function () {
            var myModal = new bootstrap.Modal(document.getElementById('contactUsModal'));
            myModal.show();
        });
        document.getElementById('LocationHoursTrigger').addEventListener('click', function () {
            var myModal = new bootstrap.Modal(document.getElementById('locationHoursModal'));
            myModal.show();
        });


        function closeModal() {
            $('#myModal').modal('hide');
            $('modal').modal('hide');
        }

    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>


    <script src="js/inventory-mgmt.js"></script>

    <script>
        document.getElementById('productForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            // AJAX to send data to the server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add-product.php', true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Product Added Successfully!');
                    window.location.href = 'inventory-management.php';
                } else {
                    alert('Error in adding product.');
                    window.location.href = 'inventory-management.php';
                }
            };

            xhr.send(formData);
        });

    </script>

    <script>
        document.getElementById('updateProductForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            formData.append('productId', document.getElementById('updateProductId').value);

            // AJAX to send data to the server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-product.php', true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Product Updated Successfully!');
                    window.location.href = 'inventory-management.php';
                } else {
                    alert('Error in updating product.');
                }
            };

            xhr.onerror = function () {
                alert('Error in request.');
            };

            xhr.send(formData); // Send the form data
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to search in a table
            function searchTable(inputId, tableId) {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById(inputId);
                filter = input.value.toUpperCase();
                table = document.getElementById(tableId);
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td");
                    for (var j = 0; j < td.length; j++) {
                        if (td[j]) {
                            txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                                break; // Stop loop once matched
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }
            }

            // Attach search function to keyup event of search inputs
            document.getElementById('searchProductListing').onkeyup = function () {
                searchTable('searchProductListing', 'productListingTable');
            };

            document.getElementById('searchProductInventory').onkeyup = function () {
                searchTable('searchProductInventory', 'productInventoryTable');
            };
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>