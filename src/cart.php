<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="stylesheets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <style>
        .profile-section,
        .order-history-section {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 20px;
        }

        body {
            position: relative;
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

        footer {
            background-color: #24282c !important;
            color: #fff;
            position: fixed;
            width: 100%;
            bottom: 0px;
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
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .cart-table th {
            background-color: #f2f2f2;
        }
    </style>

</head>

<body>


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
                    <li id="cartIcon" class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/253/253298.png" width="30px" height="30px">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <br>
    <br>

    <div class="container">
        <h2 style="text-align: center;">Your Shopping Cart</h2>
        <br><br>
        <table class="cart-table" id="cart-container">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            <?php
            $totalAmount = 0;
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                foreach ($_SESSION['cart'] as $productId => $productDetails) {
                    $lineTotal = $productDetails['quantity'] * $productDetails['price'];
                    $totalAmount += $lineTotal;

                    echo "<tr>
                        <td>" . htmlspecialchars($productDetails['name']) . "</td>
                        <td>
                            <input type='number' value='" . htmlspecialchars($productDetails['quantity']) . "' min='1' onchange='updateQuantity(\"$productId\", this.value)'>
                        </td>
                        <td id='total-$productId'>$" . number_format($lineTotal, 2) . "</td>
                        <td>
                            <a href='remove-from-cart.php?productId=$productId'>Remove Item</a>
                        </td>
                      </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Your cart is empty</td></tr>";
            }
            ?>
            <tr>
                <td colspan="3">Total Order Amount:</td>
                <td id="total-amount">$
                    <?php echo number_format($totalAmount, 2); ?>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <a class="btn btn-primary" href="view-products.php">Continue Shopping</a>
        <form action="checkout.php" method="post" style="direction: rtl;">
            <button class='btn btn-primary' type="submit">Proceed to Checkout</button>
        </form>

    </div>
    <!-- Footer -->
    <footer>
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
                <div class="col-md-3">
                    <a href="https://www.instagram.com/" target=" _blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/" target=" _blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/" target=" _blank"><i class="fab fa-twitter"></i></a>
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p>Email us at: <a href="mailto: suburbanoutfitters@gmail.com">suburbanoutfitters@gmail.com</a></p>
                    <p>Call us at: <a href="tel:+123456789">1234567890</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p>We are open 24x7, anywhere and everywhere!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;">Created and Maintained by DevTeam @ Suburban Outfitters.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    </script>
</body>

</html>