<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
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
    </style>

</head>

<body>


    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="homepage.php">
                    <img src="images/Suburban-gif.gif" width="50%" height="50%">
                </a>
                <h1 class="mx-auto">The Official Store of Suburban Outfitters</h1>
                <ul class="navbar-nav">
                    <li id="searchBox" class="nav-item">
                        <form class="d-flex">
                            <div class="input-group">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                            </div>
                        </form>
                    </li>
                    <li id="profileIcon" class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="https://cdn-icons-png.flaticon.com/128/64/64572.png" width="30px" height="30px">
                        </a>
                    </li>
                    <li id="cartIcon" class="nav-item">
                        <a class="nav-link" href="#">
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
        <div class="profile-section">
            <h2>Profile Details</h2>
            <div class="profile-details">
                <p>First Name: <span id="firstName">Chandler</span></p>
                <p>Last Name: <span id="lastName">Bing</span></p>
                <p>Email: <span id="email">chandler.bing@sarcastic.com</span></p>
                <p>Phone Number: <span id="phone">123-456-7890</span></p>
                <p>Shipping Address: <span id="shippingAddress">1234 Street, NY City, USA</span></p>
            </div>
        </div>

        <div class="order-history-section">
            <h2>Order History</h2>
            <div class="order-history">
                <ul>
                    <li><a href="order-details.html?orderId=123" target="_blank">Order ID: 123 - Date: 2023-01-01 -
                            Status: Delivered</a></li>
                    <li><a href="order-details.html?orderId=456" target="_blank">Order ID: 456 - Date: 2023-01-15 -
                            Status: Shipped</a></li>
                    <li><a href="order-details.html?orderId=789" target="_blank">Order ID: 789 - Date: 2023-02-01 -
                            Status: Processing</a></li>
                </ul>
            </div>
        </div>

        <br>

        <!-- Update and Delete Profile Buttons -->
        <div class="profile-actions">
            <button onclick="location.href='update-profile.php'" class="btn btn-primary">Update Profile</button>
            <button onclick="confirmDelete()" class="btn btn-danger">Delete Profile</button>
        </div>
    </div>

    <br>
    <br>
    <footer>
        <div class="container py-4" style="text-align: center;">
            <div class="row">
                <div class="col-md-3">
                    <a href="#">Contact Us</a>
                </div>
                <div class="col-md-3">
                    <a href="#">Location and Hours</a>
                </div>
                <div class="col-md-3">
                    <a href="#">Privacy and Terms</a>
                </div>
                <div class="col-md-3">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>


    <script>
        function confirmDelete() {
            var response = confirm("Are you sure you want to delete your profile? This action cannot be undone.");
            if (response) {
                // profile deletion process
                alert('Profile deletion is not available yet.');
            }
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>