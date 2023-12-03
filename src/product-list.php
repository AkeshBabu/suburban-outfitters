<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
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

        a {
            color: black;
            text-decoration: none;
        }

        .product-card {
            border: 3px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .pagination {
            justify-content: center;
        }

        .pagination {
            --bs-pagination-active-bg: #adb5bd !important;
            --bs-pagination-active-border-color: black;
            --bs-pagination-color: black;
            --bs-pagination-hover-color: black;
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
        <h2 style="text-align:center;">Product Catalog</h2>
        <br>
        <div class="row">
            <?php
            require_once 'conn.php';

            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            $query = "SELECT * FROM inventory";  // Adjust the query as per your schema
            $result = $conn->query($query);

            while ($product = $result->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='product-card'>";
                // Display inventory details. Adjust as per your actual column names
                echo "<p>Inventory ID: " . htmlspecialchars($product['inventory_id']) . "</p>";
                echo "<p>Product ID: " . htmlspecialchars($product['product_id']) . "</p>";
                echo "<p>Vendor ID: " . htmlspecialchars($product['vendor_id']) . "</p>";
                echo "<p>Quantity: " . htmlspecialchars($product['quantity']) . "</p>";
                echo "<p>Date: " . htmlspecialchars($product['inventory_date']) . "</p>";
                // Link to edit inventory details
                echo "<a href='product-edit.php?inventory_id=" . $product['inventory_id'] . "' class='btn btn-primary'>Edit</a>";
                echo "</div>";
                echo "</div>";
            }

            $conn->close();
            ?>
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

</body>

</html>