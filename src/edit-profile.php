<?php
require_once 'checksession.php';
require_once 'conn.php';


$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

// Get the current user's email from the session
$currentUserEmail = $_SESSION['email'];


// Fetch user details
$currentUserEmail = $_SESSION['email'];
$selecteduserDetails = null;

if ($currentUserEmail) {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->bind_param("s", $currentUserEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $selecteduserDetails = $result->fetch_assoc();
    }
    $stmt->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $firstName = $conn->real_escape_string($_POST['fname']);
    $lastName = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $billingAddress = isset($_POST['sameAddress']) && $_POST['sameAddress'] ? $address : $conn->real_escape_string($_POST['billingAddress']);

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE customer SET first_name=?, last_name=?, email=?, phone=?, shipping_address=?, billing_address=? WHERE email=?");
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phone, $address, $billingAddress, $currentUserEmail);

    // Check if the new email already exists in the customer table
    $emailCheckStmt = $conn->prepare("SELECT email FROM customer WHERE email = ? AND email != ?");
    $emailCheckStmt->bind_param("ss", $email, $currentUserEmail);
    $emailCheckStmt->execute();
    $emailCheckResult = $emailCheckStmt->get_result();

    if ($emailCheckResult->num_rows > 0) {
        echo "<script>alert('This email is already in use by another account.'); window.location.href='edit-profile.php';</script>";
    } else {
        // Proceed with the update if the email is not in use
        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            echo "<script>alert('Profile updated successfully.'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Profile was NOT updated. Please try again!.'); window.location.href='edit-profile.php';</script>";
        }
    }

    // Close statement and connection
    $emailCheckStmt->close();

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="stylesheets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        <h2 class="text-center">Update your Profile</h2>
        <div class="registration-form">
            <form action="edit-profile.php" method="post">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" class="form-control" id="fname" name="fname"
                        value="<?= $selecteduserDetails['first_name'] ?? '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" class="form-control" id="lname" name="lname"
                        value="<?= $selecteduserDetails['last_name'] ?? '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="<?= $selecteduserDetails['email'] ?? '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="<?= $selecteduserDetails['phone'] ?? '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Full Shipping Address:</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="<?= $selecteduserDetails['shipping_address'] ?? '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="billingAddress">Full Billing Address:</label>
                    <input type="text" class="form-control" id="billingAddress" name="billingAddress"
                        value="<?= $selecteduserDetails['billing_address'] ?? '' ?>">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>

            <br>

            <!-- Update Password Section -->
            <div class="update-password-section">

                <div id="passwordUpdateFields" style="display: block;">
                    <br>
                    <h3>Update Password</h3>
                    <br>
                    <form action="update-password.php" method="post">
                        <div class="form-group">
                            <label for="newPassword">New Password:</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>

                        <div class="form-group">
                            <label for="confirmNewPassword">Confirm New Password:</label>
                            <input type="password" class="form-control" id="confirmNewPassword"
                                name="confirmNewPassword" required>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Update
                                Password</button>
                            <button id="cancleBtn" style="display: block;" type="button" class="btn btn-primary"
                                onclick="location.href='profile.php'">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
        function togglePasswordUpdate() {
            var passwordUpdateFields = document.getElementById('passwordUpdateFields');
            var updatePasswordBtn = document.getElementById('updatePasswordBtn');

            if (passwordUpdateFields.style.display === 'none') {
                passwordUpdateFields.style.display = 'block';
                updatePasswordBtn.style.display = 'none';
            } else {
                passwordUpdateFields.style.display = 'none';
                updatePasswordBtn.style.display = 'block';
            }
        }

        function toggleBillingAddress() {
            var billingAddressDiv = document.getElementById('billingAddress');
            var sameAddressCheckbox = document.getElementById('sameAddress');
            billingAddressDiv.style.display = sameAddressCheckbox.checked ? 'none' : 'block';
        }

        window.onload = function () {
            const params = new URLSearchParams(window.location.search);
            if (params.get('registered') === 'true') {
                //  popup message
                alert('Successfully registered');
                history.replaceState(null, '', 'homepage.php');
            }
        };
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>