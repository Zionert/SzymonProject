<?php 

  session_start();

  include('server/connection.php');

  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('location: account.php');
    exit();
  }

  if(isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT user_id,user_name,user_email,user_password FROM users WHERE user_email = ? AND user_password = ? LIMIT 1");
    $stmt->bind_param('ss', $email,$password);
    if($stmt->execute()) {
      $stmt->bind_result($user_id,$user_name,$user_email,$user_password);
      $stmt->store_result();
      if($stmt->num_rows() == 1) {
        $row = $stmt->fetch();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_email'] = $user_email;
        $_SESSION['logged_in'] = true;

        header('location: account.php?login_success=logged in successfully');
      } else {
        header('location: login.php?error=could not verfiy account');
      }
    } else {
        header('location: login.php?error=something went wrong');
      }
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

    <!--Login-->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Login</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form id="login-form" action="login.php" method="POST">
              <p style="color: red" class="text-center"><?php if(isset($_GET['error'])) { echo $_GET['error']; } ?></p>  
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required/>
                </div>    
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required/>
                </div>    
                <div class="form-group">
                    <input type="submit" class="btn" name="login_btn" id="login-btn" value="Login"/>
                </div>    
                <div class="form-group">
                    <a id="register-url" href="register.php" class="btn">Register</a>
                </div>    
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
