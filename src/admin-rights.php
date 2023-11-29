<?php

require_once 'checksession.php';
require_once 'conn.php';


// Check if the user's email is in the admin table
$currentUserEmail = $_SESSION['email'] ?? null;

if ($currentUserEmail) {
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    $adminCheckStmt = $conn->prepare("SELECT email FROM admin WHERE email = ?");
    $adminCheckStmt->bind_param("s", $currentUserEmail);
    $adminCheckStmt->execute();
    $result = $adminCheckStmt->get_result();

    if ($result->num_rows == 0) {
        // Not an admin, redirect to unauthorized page
        header("Location: unauthorized.php");
        exit;
    }
    $adminCheckStmt->close();
} else {
    // No user email in session, redirect to unauthorized page
    header("Location: unauthorized.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    require_once 'conn.php';

    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    // Get all emails from admin table
    $adminEmails = [];
    $adminQuery = $conn->query("SELECT email FROM admin");
    while ($admin = $adminQuery->fetch_assoc()) {
        $adminEmails[] = $admin['email'];
    }

    // Process each checked admin
    $checkedAdmins = isset($_POST['admins']) ? $_POST['admins'] : [];
    foreach ($checkedAdmins as $email) {
        if (!in_array($email, $adminEmails)) {
            // Add to admin if not already an admin
            $stmt = $conn->prepare("INSERT INTO admin (first_name, last_name, email, phone) SELECT first_name, last_name, email, phone FROM customer WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
        }
    }

    // Remove unchecked admins
    foreach ($adminEmails as $email) {
        if (!in_array($email, $checkedAdmins)) {
            // Remove from admin
            $stmt = $conn->prepare("DELETE FROM admin WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
        }
    }

    $stmt->close();
    $conn->close();
    echo "<script>alert('Administrator updates processed successfully!'); window.location.href = 'profile.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Privileges</title>
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


        .btn-primary {
            background-color: black !important;
        }

        footer {
            background-color: #24282c !important;
            color: #fff;
            position: fixed;
            width: 100%;
            bottom: 0px;
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

    <div class="container">
        <br>
        <br>
        <h2 class="text-center">Administrator Privileges</h2>
        <div class="registration-form">


            <!-- PHP Script to Fetch and Display Customers -->
            <?php
            require_once 'conn.php';
            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error)
                die("Connection failed: " . $conn->connect_error);

            // Fetch all customers
            $result = $conn->query("SELECT * FROM customer");

            echo "<form action='admin-rights.php' method='post' onsubmit='return confirmAdminChanges()'>";
            echo "<table class='table table-bordered'>";
            echo "<thead><tr><th>Name</th><th>Email</th><th>Select</th></tr></thead><tbody>";

            while ($row = $result->fetch_assoc()) {
                $email = $row['email'];

                // Check if the email exists in the admin table
                $adminCheckStmt = $conn->prepare("SELECT email FROM admin WHERE email = ?");
                $adminCheckStmt->bind_param("s", $email);
                $adminCheckStmt->execute();
                $adminResult = $adminCheckStmt->get_result();
                $isAdmin = $adminResult->num_rows > 0;

                // Skip the currently logged-in admin from form processing
                if ($email === $_SESSION['email']) {
                    $isAdmin = true; // The currently logged-in admin remains an admin
                }

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($email) . "</td>";
                echo "<td><input type='checkbox' name='admins[]' value='" . htmlspecialchars($email) . "' " . ($isAdmin ? 'checked' : '') . "></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
            echo "<br>";
            echo "<div style='display: flex; justify-content: space-between; align-items: center; margin-top: 20px;'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>Update Privileges</button>";
            echo "<button id='cancelBtn' style='display: block;' type='button' class='btn btn-primary' onclick=\"location.href='profile.php'\">Cancel</button>";
            echo "</div>";
            echo "</form>";

            $result->free();
            $conn->close();
            ?>

        </div>
    </div>

    <br>
    <br>
    
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
        function confirmAdminChanges() {
            return confirm('Are you sure you want to update administrator rights?');
        }
    </script>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>