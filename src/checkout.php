<?php
session_start();
$loggedIn = isset($_SESSION['customer_id']);
if (!$loggedIn) {
    header("Location: profile.php");
    exit();
}

// Redirect to a different page if the cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php"); // Redirect to cart page or home page
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="stylesheets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <style>
        .profile-section,
        .order-history-section {
            border: 3px solid #ddd;
            margin-bottom: 20px;
            padding: 20px;
        }

        .profile-details p,
        .order-history li {
            padding: 10px 0;
        }

        .order-history ul {
            list-style-type: none;
            padding: 0;
        }

        .order-history li a {
            display: block;
            padding: 10px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .order-history li a:hover {
            background-color: #f9f9f9;
            border-color: #ddd;
        }

        .profile-actions {
            text-align: center;
            padding-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 10px;
            border: none;
            color: white;
            background-color: #000000;
        }

        .btn-primary {
            background-color: #000000 !important;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .order-history-section li {
            display: flex;
        }

        .order-history-section li a {
            margin-right: 60px;
            text-align: left;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th,
        .cart-table td {
            border: 2px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .cart-table th {
            background-color: #f2f2f2;
        }


        .payment-container {
            border: 2px solid #ccc;
            padding: 20px;
            margin-top: 20px;
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

    <br>
    <br>
    <div class="container">
        <h1 style="text-align: center;">Checkout</h1>
        <form style="direction: ltr;">
            <a href="cart.php" style="position:relative; top: -25px; color:black;">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
        </form>
        <br><br>

        <?php
        $totalAmount = 0;
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            echo "<table class='cart-table'>
            <tr>
                <th>Product Name</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price Per Item</th>
                <th>Total</th>
            </tr>";

            foreach ($_SESSION['cart'] as $productId => $productDetails) {
                $lineTotal = $productDetails['quantity'] * $productDetails['price'];
                $totalAmount += $lineTotal;

                // Check if 'size' key exists for each product
                $productSize = isset($productDetails['size']) ? htmlspecialchars($productDetails['size']) : 'N/A';

                echo "<tr>
                <td>" . htmlspecialchars($productDetails['name']) . "</td>
                <td>" . $productSize . "</td> 
                <td>" . htmlspecialchars($productDetails['quantity']) . "</td>
                <td>$" . htmlspecialchars(number_format($productDetails['price'], 2)) . "</td>
                <td id='total-$productId'>$" . number_format($lineTotal, 2) . "</td>
              </tr>";
            }

            echo "</table>";
            echo "<br><h5 style='direction: rtl;'><strong>Total Order Amount: $" . htmlspecialchars(number_format($totalAmount, 2)) . "</strong></h5>"; 
        } else {
            echo "<tr><td colspan='4'>Your cart is empty</td></tr>";
        }
        ?>


        <br>


        <!-- Payment method selection -->

        <div class="payment-container" style="display:flex;">
            <div id="pay-method">
                <h2>Select Payment Method:</h2>
                <br>
                <label>
                    <input type="radio" name="paymentMethod" value="creditCard"> Credit Card
                </label>
                <br>
                <br>
                <label>
                    <input type="radio" name="paymentMethod" value="debitCard"> Debit Card
                </label>
                <br>
                <br>
                <label>
                    <input type="radio" name="paymentMethod" value="paypal"> PayPal
                </label>
                <br>
                <br>
                <label>
                    <input type="radio" name="paymentMethod" value="giftCard"> Gift Card
                </label>
            </div>
            <br><br>

            <!-- Payment method forms (hidden by default) -->
            <div id="creditCardForm" style="display: none;">
                <h3>Credit Card Details</h3>
                <br>
                <form action="create-order.php" method="post" class="payment-form">
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="cardNumber">Card Number:</label>
                        <input type="text" id="cardNumber" name="cardNumber" required style="width: 100%;">
                    </div>
                    <br>
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="expirationDate">Expiration Date:</label>
                        <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YY" required
                            style="width: 65px;">
                    </div>
                    <br>
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv" required style="width: 50px;">
                    </div>
                    <br><br>
                    <div style="display: flex; justify-content: space-between; align-items: center; gap:50px">
                        <button class="btn btn-primary" type="submit">Place Order</button>
                        <a style="color:red;" href="javascript:void(0);" onclick="showPaymentOptions()">Back to Payment
                            Options</a>
                    </div>
                </form>
            </div>
            <div id="debitCardForm" style="display: none;">
                <br>
                <h3>Debit Card Details</h3>
                <br>
                <form action="create-order.php" method="post" class="payment-form">
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="debitCardNumber">Debit Card Number:</label>
                        <input type="text" id="debitCardNumber" name="debitCardNumber" required style="width: 100%;">
                    </div>
                    <br>
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="debitExpirationDate">Expiration Date:</label>
                        <input type="text" id="debitExpirationDate" name="debitExpirationDate" placeholder="MM/YY"
                            required style="width: 65px;">
                    </div>
                    <br>
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="debitCvv">CVV:</label>
                        <input type="text" id="debitCvv" name="debitCvv" required style="width: 50px;">
                    </div>
                    <br><br>
                    <div style="display: flex; justify-content: space-between; align-items: center; gap:50px">
                        <button class="btn btn-primary" type="submit">Place Order</button>
                        <a style="color:red;" href="javascript:void(0);" onclick="showPaymentOptions()">Back to Payment
                            Options</a>
                    </div>
                </form>
            </div>
            <div id="paypalForm" style="display: none;">
                <h3>PayPal Details</h3>
                <br>
                <p>Log in to your PayPal account to complete the payment.</p>
                <div style="display: flex; justify-content: space-between; align-items: center; gap:50px">
                    <a href="https://www.paypal.com" target="_blank" class="btn btn-primary">Log In to PayPal</a>
                    <a style="color:red;" href="javascript:void(0);" onclick="showPaymentOptions()">Back to Payment
                        Options</a>
                </div>
            </div>
            <div id="giftCardForm" style="display: none;">
                <h3>Gift Card Details</h3>
                <br>
                <form action="create-order.php" method="post" class="payment-form">
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="giftCardNumber">Gift Card Number:</label>
                        <input type="text" id="giftCardNumber" name="giftCardNumber" required style="width: 100%;">
                    </div>
                    <br>
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <label for="giftCardPin">Gift Card PIN:</label>
                        <input type="text" id="giftCardPin" name="giftCardPin" required style="width: 70px;">
                    </div>
                    <br><br>
                    <div style="display: flex; justify-content: space-between; align-items: center; gap:50px">
                        <button class="btn btn-primary" type="submit">Place Order</button>
                        <a style="color:red;" href="javascript:void(0);" onclick="showPaymentOptions()">Back to Payment
                            Options</a>
                    </div>
                </form>
            </div>
        </div>
        <br>


    </div>
    <br><br>

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
        document.addEventListener('DOMContentLoaded', (event) => {
            // Select all forms with the class 'payment-form'
            document.querySelectorAll('.payment-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    // Show confirmation dialog
                    let confirmSubmission = confirm('Are you sure you want to place the order?');
                    if (!confirmSubmission) {
                        // Prevent form submission if user cancels
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    <script>

        function updateQuantity(productId, quantity) {
            // Create a FormData object and append data
            var formData = new FormData();
            formData.append('productId', productId);
            formData.append('quantity', quantity);

            // AJAX request to the server
            fetch('update-cart-quantity.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the line total for the changed product
                        document.getElementById('total-' + productId).textContent = '$' + data.lineTotal;

                        // Update the grand total
                        document.getElementById('total-amount').textContent = '$' + data.grandTotal;
                    }
                })
                .catch(error => console.error('Error:', error));

        }

        function showPaymentOptions() {
            // Hide all payment method forms
            document.getElementById("creditCardForm").style.display = "none";
            document.getElementById("debitCardForm").style.display = "none";
            document.getElementById("paypalForm").style.display = "none";
            document.getElementById("giftCardForm").style.display = "none";
            // Show the Payment Method selection
            document.getElementById("pay-method").style.display = "block";
        }
    </script>

    <script>
        // JavaScript code to show/hide payment method forms based on selection
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        paymentMethods.forEach(function (method) {
            method.addEventListener('change', function () {
                const methodValue = this.value;
                const paymentForms = document.querySelectorAll('.payment-container > div');
                paymentForms.forEach(function (form) {
                    form.style.display = 'none';
                });

                const selectedForm = document.getElementById(methodValue + 'Form');
                if (selectedForm) {
                    selectedForm.style.display = 'block';
                }
            });
        });
    </script>

</body>

</html>