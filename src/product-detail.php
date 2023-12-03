<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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

        p {
            margin-top: 0;
            margin-bottom: 2rem;
        }

        .container {
            width: 60%;
        }

        .product-detail-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border: 3px solid #ddd;
            padding: 20px;
        }

        .product-detail {
            flex: 0.5;
            padding: 20px;
            text-align: right;
        }

        .product-image {
            flex: 0.5;
            text-align: center;

            padding: 10px;
        }

        .product-image img {
            max-width: 100%;
            max-height: 400px;
            margin: 0 auto;
            display: block;
        }

        .size-btn.selected {
            background-color: black;
            color: white;
            border: 3px solid #adb5bd;
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
        <br><br>
        <h1 style="text-align:center;">Product Details</h1>
        <br><br>
        <div class="product-detail-container">

            <div class="product-detail">
                <?php
                require_once 'conn.php';


                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch the product ID from the URL query parameter
                $productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;

                $isInWishlist = false;
                $isLoggedIn = isset($_SESSION['customer_id']);

                if ($isLoggedIn && $productId) {
                    $wishlistCheckStmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE customer_id = ? AND product_id = ?");
                    $wishlistCheckStmt->bind_param("ii", $_SESSION['customer_id'], $productId);
                    $wishlistCheckStmt->execute();
                    $result = $wishlistCheckStmt->get_result();
                    $row = $result->fetch_row();

                    if ($row[0] > 0) {
                        $isInWishlist = true;
                    }

                    $wishlistCheckStmt->close();
                }

                if ($productId) {
                    // SQL query to fetch sizes with quantity > 0
                    $sizesStmt = $conn->prepare("SELECT DISTINCT size FROM inventory WHERE product_id = ? AND quantity > 0");
                    $sizesStmt->bind_param("i", $productId);
                    $sizesStmt->execute();
                    $sizesResult = $sizesStmt->get_result();

                    $sizes = [];
                    while ($sizeRow = $sizesResult->fetch_assoc()) {
                        $sizes[] = $sizeRow['size'];
                    }
                    $sizesStmt->close();

                    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                    $stmt->bind_param("i", $productId);
                    $stmt->execute();
                    $result = $stmt->get_result();



                    if ($product = $result->fetch_assoc()) {
                        echo "<h1>" . htmlspecialchars($product['product_name']) . "</h1>";
                        // Wishlist button - different behavior based on login status
                        if ($isLoggedIn) {
                            echo "<button style='background: transparent; border: none;' id='addToWishlist' onclick='toggleWishlist(this, " . $productId . ", " . $_SESSION['customer_id'] . ")'>";
                            if ($isInWishlist) {
                                echo "<p>Add to Wishlist <i class='fas fa-heart' style='font-size: 20px;'></i></p>"; // filled heart
                            } else {
                                echo "<p>Add to Wishlist <i class='far fa-heart' style='font-size: 20px;'></i></p>"; // empty heart
                            }
                            echo "</button>";
                        } else {
                            // For non-logged-in users, redirect to login page
                            echo "<button style='background: transparent; border: none;' id='addToWishlist' onclick='window.location.href=\"login.php\"'>";
                            echo "<p>Add to Wishlist <i class='far fa-heart' style='font-size: 20px;'></i></p>"; // empty heart
                            echo "</button>";
                        }

                    }
                    echo "<h2>$" . htmlspecialchars($product['price']) . "</h2><br>";
                    echo "<p>Category: " . htmlspecialchars($product['category']) . "</p>";

                    if (!empty($sizes)) {
                        // Check if the product has actual sizes or a 'None' placeholder
                        if ($sizes[0] != 'None') {
                            echo "<p style='margin-bottom: 10px;'>Select Size: </p>";
                            foreach ($sizes as $size) {
                                echo "<button style='margin:1px;' type='button' class='size-btn' onclick='selectSize(this, \"$size\")'>$size</button> ";
                            }
                        } else {
                            // For products that don't have size variations
                            echo "<input style='width:70px; text-align:center;' disabled id='selectedSize' name='selectedSize' value='None'>";
                        }

                        echo "<form id='addToCartForm' action='add-to-cart.php' method='post'>";
                        echo "<input type='hidden' name='product_id' value='" . $product['product_id'] . "'>";
                        echo "<input type='hidden' id='selectedSize' name='selectedSize' value=''>";
                        echo "<br><label for='quantity'>Quantity: </label>";
                        echo "<input style='margin-left:20px; width: 60px;' type='number' id='quantity' name='quantity' value='1' min='1'><br><br>";
                        echo "<button class='btn btn-primary' type='submit' id='addToCartButton'>Add to Cart</button>";
                        echo "</form>";
                    } else {
                        // Product is out of stock
                        echo "<h5><strong>Product is out of Stock.</strong></h5>";
                    }

                } else {
                    echo "<p>Product not found.</p>";
                }

                $stmt->close();


                $conn->close();
                ?>
            </div>
            <div class="product-image">
                <img src="images/products/<?php echo htmlspecialchars($product['product_id']); ?>.png"
                    alt="<?php echo htmlspecialchars($product['product_name']); ?>">
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

                    <p>Email us at: <a href="mailto: suburbanoutfitters@gmail.com">suburbanoutfitters@gmail.com</a></p>
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
        // JavaScript to open the Privacy and Terms modal
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
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to close the modal with the ID "myModal"
        function closeModal() {
            $('#myModal').modal('hide');
        }
    </script>

    <script>
        function toggleWishlist(element, productId, customerId) {
            let icon = element.querySelector('i');
            let action = icon.classList.contains('far') ? 'add' : 'remove';

            // Toggle the icon
            icon.classList.toggle('far');
            icon.classList.toggle('fas');

            // Send AJAX request to update wishlist
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update-wishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    console.log("Wishlist updated");
                    // Additional logic upon successful update
                }
            }
            xhr.send("productId=" + productId + "&action=" + action + "&customerId=" + customerId);
        }
    </script>
    <script>

        function addToCart(productId, price) {
            let cart = JSON.parse(localStorage.getItem('cart')) || {};
            let quantity = parseInt(document.getElementById('quantity').value);

            if (!cart[productId]) {
                cart[productId] = { quantity: quantity, price: price };
            } else {
                cart[productId].quantity += quantity;
            }

            // Save the updated cart back to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            alert('Product added successfully!'); window.location.href = 'homepage.php';
            console.log("Cart:", cart); // For debugging
        }

        function addToWishlist(productId) {

            alert('Added to wishlist!');
        }
    </script>

    <script>
        let selectedSize = '';

        function selectSize(element, size) {
            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('selected'));
            element.classList.add('selected');
            selectedSize = size;
            document.getElementById('selectedSize').value = size;
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('addToCartForm').addEventListener('submit', function (e) {
                let sizeInput = document.getElementById('selectedSize').value;
                if (sizeInput !== 'None' && !selectedSize) {
                    alert('Please select a size before adding to cart.');
                    e.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>

</body>

</html>