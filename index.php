<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="assests/css/style.css">
</head>
<body>

    <!--Navbar-->
    <nav class="navbar navbar-expand-lg bg-white py-3 fixed-top">
        <div class="container">
          <img class="logo" src="assests/imgs/logo.png">
          <h2 class="brand">Marklin</h2> 
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link" href="shop.html">Shop</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="contact.html">Contact us</a>
              </li>

              <li class="nav-item">
                <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
                <a href="account.php"><i class="fa-solid fa-user"></i></a>
              </li>    
            </ul>       
          </div>
        </div>
    </nav>


      <!--Home-->
      <section id="home">
        <div class="container">
          <h5>NEW ARRIVALS</h5>
          <h1><span>Best Prices</span> This Season</h1>
          <p>Our shop offers the products for the most affordable prices</p>
          <button>Shop Now</button>
        </div>
      </section>

      <!--Brand-->
      <section id="brand" class="container">
        <div class="row mx-auto">
          <img class="img-fluid col-lg-3 col-sm-12" src="assests/imgs/brand1.jpg">
          <img class="img-fluid col-lg-3 col-sm-12" src="assests/imgs/brand2.png">
          <img class="img-fluid col-lg-3 col-sm-12" src="assests/imgs/brand3.jpg">
          <img class="img-fluid col-lg-3 col-sm-12" src="assests/imgs/brand4.jpg">
        </div>
      </section>
      <!--New-->
      <section id="new" class="w-100">
        <div class="row p-0 m-0">
          <!--One-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assests/imgs/1.webp"/>
            <div class="details">
              <h2>Extreamely Awesome Shoes</h2>
              <button class="text-uppercase">Shop Now</button>
            </div>
          </div>
          <!--Two-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assests/imgs/2.webp"/>
            <div class="details">
              <h2>Extreamely Awesome Jacket</h2>
              <button class="text-uppercase">Shop Now</button>
            </div>
          </div>
          
          <!--Three-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assests/imgs/3.webp"/>
            <div class="details">
              <h2>50% OFF Watches</h2>
              <button class="text-uppercase">Shop Now</button>
            </div>
          </div>

        </div>
      </section>


      <!--Featured-->
      <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 pb-5">
          <h3>Our Featured</h3>
          <hr>
          <p>Here you can chekc out our new featured products</p>
        </div>
        <div class="row mx-auto container-fluid">

        <?php include('server/get_featured_products.php'); ?>

        <?php while($row = $featured_products->fetch_assoc()){?>

          <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assests/imgs/<?php echo $row['product_image'];?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="p-price">zł <?php echo $row['product_price']; ?></h4>
            <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
          </div>

          <?php } ?>
        </div>
      </section>

      <!--Banner-->
      <section id="banner" class="my-5 py-5">
        <div class="container">
          <h4>SUMMER IS HERE!</h4>
          <h1>Summer Collection <br> UP to 30% OFF</h1>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </section>

      <!--Footer-->
      <footer class="mt-5 py-2">
      <div class="container text-center pt-5">
        <div class="row justify-content-center">
          <div class="footer-one col-lg-3 col-md-6 col-sm-12">
            <img class="logo" src="assests/imgs/logo.png"/>
            <p class="pt-3">We provide the best products for the most affordable prices</p>
          </div>
          <div class="footer-one col-lg-3 col-md-6 col-sm-12">
            <h5 class="pb-2">Featured</h5>
            <ul class="text-uppercase list-unstyled">
              <li><a href="#">men</a></li>
              <li><a href="#">women</a></li>
              <li><a href="#">boys</a></li>
              <li><a href="#">girls</a></li>
              <li><a href="#">new arrivals</a></li>
              <li><a href="#">clothes</a></li>
            </ul>
          </div>
          <div class="footer-one col-lg-3 col-md-6 col-sm-12">
            <h5 class="pb-3">Contact us</h5>
            <div>
              <h6 class="text-uppercase">Address</h6>
              <p>Piłsudskiego 9</p>
            </div>
            <div>
              <h6 class="text-uppercase">Phone</h6>
              <p>+48 123 456 789</p>
            </div>
            <div>
              <h6 class="text-uppercase">Email</h6>
              <p>smth@gamil.com</p>
            </div>
          </div>
        </div>
      </div>
      </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>