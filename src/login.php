<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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
    </style>
</head>

<body>
    <!-- Header section with navigation bar -->
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
                        <a class="nav-link" href="login.php">
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

    <div class="container">
        <br>
        <br>
        <h2 class="text-center">Login</h2>
        <div class="registration-form">


            <form action="login.php" method="post">
                <div class="form-group">

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="pwd" name="pwd" required>
                </div>


                <button type="submit" class="btn btn-primary" onclick="location.href='profile.php'">Login</button>
                <br><br>
                <p style="display: flex; margin-top: 11px;"> Not yet registered with us?
                    <button style="margin-left: 25px; margin-top: -8px;" type="submit"
                            class="btn btn-primary" onclick="location.href='register.php'">Sign Up</button>
                </p>


            </form>
        </div>
    </div>
    </div>


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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
