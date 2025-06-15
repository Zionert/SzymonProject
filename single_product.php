<?php 

include('server/connection.php');

if(isset($_GET['product_id'])) {
  $product_id = $_GET['product_id'];
  
  $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
  $stmt->bind_param("i",$product_id);
  $stmt->execute();

  $product = $stmt->get_result();
} else {
  header('location: index.php');
}

?>


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
                <a class="nav-link" href="shop.php">Shop</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact us</a>
              </li>

              <li class="nav-item">
                <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
                <a href="account.php"><i class="fa-solid fa-user"></i></a>
              </li>    
            </ul>       
          </div>
        </div>
    </nav>


    <!--Single product-->
    <section class="container single_product my-5 pt-5">
        <div class="row mt-5">

          <?php while($row = $product->fetch_assoc()) {?>

            <div class="col-lg-5 col-md-6 col-sm-12">
                <img id="mainImg" class="img-fluid w-100" src="assests/imgs/<?php echo $row['product_image']; ?>"/>
                <div class="small-img-group">
                    <div class="small-img-col">
                        <img src="assests/imgs/<?php echo $row['product_image']; ?>" width="100%" class="small-img"/>
                    </div>
                    <div class="small-img-col">
                        <img src="assests/imgs/<?php echo $row['product_image2']; ?>" width="100%" class="small-img"/>
                    </div>
                    <div class="small-img-col">
                        <img src="assests/imgs/<?php echo $row['product_image3']; ?>" width="100%" class="small-img"/>
                    </div>
                    <div class="small-img-col">
                        <img src="assests/imgs/<?php echo $row['product_image4']; ?>" width="100%" class="small-img"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <h6><?php echo $row['product_category']; ?></h6>
                <h3 class="py-4"><?php echo $row['product_name']; ?></h3>
                <h2>zł <?php echo $row['product_price']; ?></h2>

              <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id'] ?>"/>
                <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>"/>
                <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>"/>
                <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>"/>

                <input type="number" name="product_quantity" value="1"/>
                <button type="submit" name="add_to_cart" class="buy-btn">Add To Cart</button>
              </form>


                <h4 class="mt-5 mb-5">Product details</h4>
                <span><?php echo $row['product_description']; ?> </span>
            </div>

        <?php } ?>

        </div>
    </section>

    
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
    
    <script>
        let mainImg = document.getElementById("mainImg")
        let smallImg = document.getElementsByClassName("small-img")

        for(let i = 0; i<4; i++) {
            smallImg[i].onclick = function() 
            {
                mainImg.src = smallImg[i].src
            }
        }

    </script>


</body>
</html>