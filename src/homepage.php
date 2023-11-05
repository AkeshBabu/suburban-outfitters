<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

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

  <div class="container mt-5">
    <div class="top10 bannerA">
      <a href="">
        <img id="banner-top" alt="black-friday-sale" src="images/banner-top.png" width="100%">
      </a>
    </div>

    <div class="row">
      <!-- Add or change the img src, product name and price accordingly -->
      <div class="col-md-6">
        <a href="">
          <img src="images/banner2.png" alt="banner2" class="img-fluid">
        </a>

      </div>
      <div class="col-md-6">
        <a href="">
          <img src="images/banner3.png" alt="banner3" class="img-fluid">
        </a>
      </div>


      <!-- Carousel Begin -->
      <div class="container mt-5" style="margin-bottom: 25px;">
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <!-- First set of 4 images -->
            <div class="carousel-item active">
              <div class="row">
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt1"></a>
                </div>
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt2"></a>
                </div>
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt3"></a>
                </div>
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt4"></a>
                </div>
              </div>
            </div>
            <!-- Second set of 4 images -->
            <div class="carousel-item">
              <div class="row">
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt5"></a>
                </div>
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt6"></a>
                </div>
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt7"></a>
                </div>
                <div class="col-md-3">
                  <a href="#"><img src="images/shirt1.png" class="d-block w-100" alt="shirt8"></a>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
          </button>
        </div>
      </div>

      <div class="row" style="margin-top: 25px; margin-left: 0px;">
        <!-- First Large Image (1/3 width) -->
        <div class="col-md-4">
          <div style="max-width: 100%;">
            <a href="">
              <img alt="Shop Unlimited" src="images/banner5.png" width="100%">
            </a>
          </div>
        </div>
        <!-- Second Large Image (1/3 width) -->
        <div class="col-md-4">
          <div style="max-width: 100%;">
            <a href="">
              <img alt="big-fashion-sale" src="images/banner6.png" width="100%">
            </a>
          </div>
        </div>
        <!-- Third Large Image (1/3 width) -->
        <div class="col-md-4">
          <div style="max-width: 100%;">
            <a href="">
              <img alt="new-user-code" src="images/banner7.png" width="100%">
            </a>
          </div>
        </div>
      </div>

      <div class="top10 bannerA">
        <a href="">
          <img id="banner-mid" alt="haloween-sale" src="images/banner4.png" width="100%" height="350px">
        </a>
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