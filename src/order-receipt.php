<?php
require_once 'conn.php';
require_once 'checksession.php';

if (!isset($_GET['orderId']) || empty($_GET['orderId'])) {
    die('Order ID is required.');
}

$orderId = intval($_GET['orderId']);

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch order details
$orderQuery = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$orderQuery->bind_param("i", $orderId);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
if ($orderResult->num_rows > 0) {
    $order = $orderResult->fetch_assoc();
} else {
    die("Order not found.");
}

// Fetch customer details
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, shipping_address, billing_address FROM customer WHERE customer_id = ?");
$stmt->bind_param("i", $order['customer_id']);
$stmt->execute();
$customerResult = $stmt->get_result();
if ($customerResult->num_rows > 0) {
    $customer = $customerResult->fetch_assoc();
} else {
    die("Customer details not found.");
}

// Fetch orderline details with product names
$lineItemsQuery = $conn->prepare("SELECT product_id, quantity FROM orderline WHERE order_id = ?");
$lineItemsQuery->bind_param("i", $orderId);
$lineItemsQuery->execute();
$lineItem = $lineItemsQuery->get_result()->fetch_assoc();

$productIds = explode(',', $lineItem['product_id']);
$quantities = explode(',', $lineItem['quantity']);


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
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

        .receipt-container {
            font-family: Arial, sans-serif;
            max-width: 950px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            text-align: center;
        }

        .details-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .customer-details,
        .order-details {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            margin: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {

            header,
            footer,
            #printReceipt {
                display: none;
                /* Hide elements during printing */
            }

            .receipt-container {
                width: 100%;
            }
        }

        .receipt-container {
            text-align: center;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .receipt-header h2 {
            margin: 0;
            text-align: center;
        }

        .logo-container img {
            max-height: 160px;
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

    <div class="receipt-container">
        <div class="receipt-header">
            <div style=" text-align: center; position: relative; left: 360px;">
                <h2>Order Receipt</h2>
            </div>
            <div class="logo-container" style=" position: relative; left: -50px;">
                <img src="images/logo.jpg" alt="Logo">
            </div>
        </div>

        <div class="details-container">
            <div class="customer-details" style="text-align:left;">
                <h4>Customer Details:</h4>
                <p>Name:
                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                </p>
                <p>Email:
                    <?php echo htmlspecialchars($customer['email']); ?>
                </p>
                <p>Phone:
                    <?php echo htmlspecialchars($customer['phone']); ?>
                </p>
                <p>Shipping Address:
                    <?php echo htmlspecialchars($customer['shipping_address']); ?>
                </p>
                <p>Billing Address:
                    <?php echo htmlspecialchars($customer['billing_address']); ?>
                </p>
            </div>
            <div class="order-details" style="text-align:left;">
                <?php
                // Fetch orderline details with product names and sizes
                $lineItemsQuery = $conn->prepare("
        SELECT ol.product_id, ol.quantity, ol.size, p.product_name, p.price
        FROM orderline ol
        INNER JOIN products p ON ol.product_id = p.product_id
        WHERE ol.order_id = ?
    ");
                $lineItemsQuery->bind_param("i", $orderId);
                $lineItemsQuery->execute();
                $lineItemsResult = $lineItemsQuery->get_result();

                if ($lineItemsResult->num_rows > 0) {
                    echo "<table id='orderTable'>
                <tr>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                    <th>Total Amount</th>
                </tr>";

                    while ($item = $lineItemsResult->fetch_assoc()) {
                        $totalAmount = $item['quantity'] * $item['price'];
                        echo "<tr data-product-id='" . $item['product_id'] . "'>";
                        echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['size']) . "</td>"; // Display size
                        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                        echo "<td>$" . htmlspecialchars(number_format($item['price'], 2)) . "</td>";
                        echo "<td>$" . htmlspecialchars(number_format($totalAmount, 2)) . "</td>";
                        echo "</tr>";
                    }

                    echo "</table><br>";
                    echo "<p><strong>Total Order Amount: $" . htmlspecialchars(number_format($order['total_amount'], 2)) . "</strong></p>";
                } else {
                    echo "<p>Order details not found.</p>";
                }
                ?>
            </div>
        </div>
        <br>
        <div style="text-align:center;">
            <button class="btn btn-primary" id="printReceipt">Print Receipt</button>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        document.getElementById('printReceipt').addEventListener('click', () => {
            window.print();
        });
    </script>


</body>

</html>

<?php
$conn->close();
?>