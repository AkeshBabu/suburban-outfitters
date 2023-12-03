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
            max-height: 100%;
            height: 400px;
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
        <h2 style="text-align:center;">Product Catalog</h2>
        <br>
        <div class="row">
            <?php
            require_once 'conn.php';

            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve category and search term from URL parameters
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

            // Define how many products per page
            $productsPerPage = 6;
            $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $offset = ($currentPage - 1) * $productsPerPage;


            // Determine the SQL query based on the category and search term
            if ($category) {
                $query = "SELECT * FROM products WHERE category = '$category'";
                $countQuery = "SELECT COUNT(*) FROM products WHERE category = '$category'";
            } elseif ($search) {
                $query = "SELECT * FROM products WHERE product_name LIKE '%$search%'";
                $countQuery = "SELECT COUNT(*) FROM products WHERE product_name LIKE '%$search%'";
            } else {
                $query = "SELECT * FROM products";
                $countQuery = "SELECT COUNT(*) FROM products";
            }

            // Append LIMIT and OFFSET for pagination
            $query .= " LIMIT $productsPerPage OFFSET $offset";

            // Execute query for pagination count
            $totalProductsResult = $conn->query($countQuery);
            $totalProducts = $totalProductsResult->fetch_row()[0];
            $totalPages = ceil($totalProducts / $productsPerPage);

            // Fetch products based on the query
            $result = $conn->query($query);

            // Display products
            while ($product = $result->fetch_assoc()) {
                $imagePath = "images/products/" . htmlspecialchars($product['product_id']) . ".png";
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='product-card'>";
                echo "<a href='product-detail.php?productId=" . $product['product_id'] . "'><img src='" . $imagePath . "' alt='" . htmlspecialchars($product['product_name']) . "'></a>";
                echo "<h3><a href='product-detail.php?productId=" . $product['product_id'] . "'>" . htmlspecialchars($product['product_name']) . "</a></h3>";
                echo "<p>Product ID: " . htmlspecialchars($product['product_id']) . "</p>";
                echo "<p><strong>$" . htmlspecialchars($product['price']) . "</strong></p>";
                echo "<p>Category: " . htmlspecialchars($product['category']) . "</p>";
                echo "</div>";
                echo "</div>";
            }

            // Create a base URL for pagination links
            $baseUrl = 'view-products.php?';
            if ($category) {
                $baseUrl .= 'category=' . urlencode($category) . '&';
            }
            if ($search) {
                $baseUrl .= 'search=' . urlencode($search) . '&';
            }

            // Pagination Controls with Previous and Next Buttons
            echo "<nav aria-label='Page navigation'>";
            echo "<ul class='pagination justify-content-center'>";

            // Previous Button
            $prevPage = max(1, $currentPage - 1);
            echo "<li class='page-item " . ($currentPage == 1 ? "disabled" : "") . "'>
      <a class='page-link' href='" . $baseUrl . "page=$prevPage' aria-label='Previous'>
          <span aria-hidden='true'>&laquo;</span>
      </a>
   </li>";

            // Page Numbers
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class='page-item" . ($i == $currentPage ? " active" : "") . "'>
          <a class='page-link' href='" . $baseUrl . "page=$i'>$i</a>
       </li>";
            }

            // Next Button
            $nextPage = min($totalPages, $currentPage + 1);
            echo "<li class='page-item " . ($currentPage == $totalPages ? "disabled" : "") . "'>
      <a class='page-link' href='" . $baseUrl . "page=$nextPage' aria-label='Next'>
          <span aria-hidden='true'>&raquo;</span>
      </a>
   </li>";

            echo "</ul>";
            echo "</nav>";

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

</body>

</html>