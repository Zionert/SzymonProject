<?php 
session_start();
include('server/connection.php');

if(isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit();
}



if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate password confirmation
    if ($password !== $confirmPassword) {
        header('location: register.php?error=Passwords do not match');
        exit();
    }

    // Validate password length
    if (strlen($password) < 6) {
        header('location: register.php?error=Password must be at least 6 characters');
        exit();
    }

    // Check if user already exists
    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->fetch();
    $stmt1->close();

    if ($num_rows > 0) {
        header('location: register.php?error=User with this email already exists');
        exit();
    } else {
        // Insert user with plaintext password (NOT RECOMMENDED)
        $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['logged_in'] = true;
            header('location: account.php?register_success=You registered successfully');
        } else {
            header('location: register.php?error=Could not create an account');
        }
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

    <!--Register-->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Register</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form id="register-form" method="POST" action="register.php">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required/>
                </div>    
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="register-email" name="email" placeholder="Email" required/>
                </div>    
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required/>
                </div>    
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required/>
                </div>    
                <div class="form-group">
                    <input type="submit" class="btn" name="register" id="register-btn" value="Register"/>
                </div>    
                <div class="form-group">
                    <a id="login-url" href="login.php" class="btn">Login</a>
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
