<?php 


  session_start();

  if(isset($_POST['add_to_cart'])) {

    if(isset($_SESSION['cart'])) {

      $product_array_ids = array_column($_SESSION['cart'], "product_id");
      if( !in_array($_POST['product_id'], $product_array_ids)  ) {       
          
        $product_id = $_POST['product_id']; 
        
        $product_array = array (
            'product_id' => $_POST['product_id'],        
            'product_name' => $_POST['product_name'],        
            'product_price' => $_POST['product_price'],        
            'product_image' => $_POST['product_image'],        
            'product_quantity' => $_POST['product_quantity'],        
          );
          
          $_SESSION['cart'][] = $product_array;

      } else {
          echo '<script>alert("Product was already added to cart")</script>';
      }

    } else {
       
        $product_array = array (
            'product_id' => $_POST['product_id'],        
            'product_name' => $_POST['product_name'],        
            'product_price' => $_POST['product_price'],        
            'product_image' => $_POST['product_image'],        
            'product_quantity' => $_POST['product_quantity'],        
          );
        
        $_SESSION['cart'][] = $product_array;


      }


      calculateTotalCart();


  } else if(isset($_POST['remove_product'])) {

  $product_id = $_POST['product_id'];

  foreach ($_SESSION['cart'] as $key => $value) {
    if ($value['product_id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      break;
    }
  }
  calculateTotalCart();

} else if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['product_id'] == $product_id) {
            $_SESSION['cart'][$key]['product_quantity'] = $product_quantity;
            break;
        }
    }

    calculateTotalCart();

} else {
    // header('location: index.php');
  }

  $total = 0;

  function calculateTotalCart() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $key => $value) {
            $product = $_SESSION['cart'][$key];
            $price = $product['product_price'];
            $quantity = $product['product_quantity'];
            $total += ($price * $quantity);
        }
    }
    $_SESSION['total'] = $total;
  }

  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    calculateTotalCart();
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

    <!--Havbar-->
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

     


    <!--Cart-->
    <section class="cart container my-5 py-5">
        <div class="container mt-5">
            <h2 class="font-weight-bolde">Your Cart</h2>
            <hr>
        </div>

        <table class="mt-5 pt-5">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach($_SESSION['cart'] as $key => $value){ ?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="assests/imgs//<?php echo $value['product_image']; ?>"/>
                        <div>
                            <p><?php echo $value['product_name']; ?></p>
                            <small><span>zł </span><?php echo $value['product_price']; ?></small>
                            <br>
                            <form method="POST" action="cart.php">
                              <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                              <input type="submit" name="remove_product" class="remove-btn" href="#" value="Remove" />
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <form method="POST" action="cart.php">
                      <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                      <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>"/>
                      <input type="submit" value="edit" name="edit_quantity" class="edit-btn" />
                    </form>
                </td>
                <td>
                    <span>zł</span>
                    <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price']; ?></span>
                </td>
            </tr>        

            <?php } ?>
        </table>    

        <div class="cart-total">
            <table>
                <tr>
                    <td>Total</td>
                    <td>zł <?php echo isset($_SESSION['total']) ? $_SESSION['total'] : 0; ?></td>
                </tr>
            </table>
        </div>

        <div class="checkout-container">
            <form method="POST" action="checkout.php">
              <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout"/>
            </form>
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
</body>
</html>
