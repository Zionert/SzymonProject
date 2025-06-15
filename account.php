<?php 

  session_start();
  include('server/connection.php');
  if(!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit();
  } 

  if(isset($_GET['logout'])) {
    if(isset($_SESSION['logged_in'])) {
      unset($_SESSION['logged_in']);
      unset($_SESSION['user_name']);
      unset($_SESSION['user_email']);
      header('location: login.php');
      exit();
    }
  }


  if(isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    if ($password !== $confirmPassword) {
        header('location: account.php?error=Passwords do not match');
        exit();
    }

    if (strlen($password) < 6) {
        header('location: account.php?error=Password must be at least 6 characters');
        exit();
    } else {
        $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
        $stmt->bind_param('ss',$password, $user_email);

        if($stmt->execute()) {
          header('location: account.php?message=password has been updated');
        } else {
          header('location: account.php?error=password could not update password');
        }
      }
  }



  //Orders
  if(isset($_SESSION['logged_in'])) {

    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();
    
  }

  $order_id = $_SESSION['order_id'];
  $stmt = $conn->prepare("SELECT order_cost FROM orders WHERE order_id = ?");
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $order = $result->fetch_assoc();
  $order_total = $order ? $order['order_cost'] : 0;

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


    <!--Account-->
    <section class="my-5 py-5">
        <div class="row container mx-auto">
            <div class="text-center mt-3 pt-5 col-lg col-md-12 col-sm-12">
                <p class="text-center" style="color:green;"><?php if(isset($_GET['register_success'])) { echo $_GET['register_success']; } ?></p>
                <p class="text-center" style="color:green;"><?php if(isset($_GET['login_success'])) { echo $_GET['login_success']; } ?></p>
                <h4>Account info</h4>
                <hr class="mx-auto">
                <div class="account-info">
                    <p>Name: <span><?php if(isset($_SESSION['user_name'])) { echo $_SESSION['user_name']; } ?></span></p>
                    <p>Email: <span><?php if(isset($_SESSION['user_email'])) { echo $_SESSION['user_email']; } ?></span></p>
                    <p><a href="orders" id="orders-btn">Your orders</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12">
                <form id="account-form" method="POST" action="account.php">
                    <p class="text-center" style="color:red;"><?php if(isset($_GET['error'])) { echo $_GET['error']; } ?></p>
                    <p class="text-center" style="color:green;"><?php if(isset($_GET['message'])) { echo $_GET['message']; } ?></p>
                    <h3>Change Password</h3>
                    <hr class="mx-auto">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Confirm Password" required/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn"/>
                    </div>
                </form>
            </div>
        </div>
    </section>



    <!--Orders-->
    <section class="orders container my-5 py-3">
        <div class="container mt-2">
            <h2 class="font-weight-bolde text-center">Your Orders</h2>
            <hr>
        </div>

        <div class="container mt-5">
            <h2 class="text-center">Your Orders</h2>
            <hr>
            <?php if(isset($_GET['error'])) { ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php 
                        switch($_GET['error']) {
                            case 'invalid_payment':
                                echo 'Invalid payment attempt. Please try again.';
                                break;
                            case 'invalid_order':
                                echo 'Invalid order. Please try again.';
                                break;
                            case 'not_logged_in':
                                echo 'Please log in to access this page.';
                                break;
                            default:
                                echo 'An error occurred. Please try again.';
                        }
                    ?>
                </div>
            <?php } ?>
        </div>

        <table class="mt-5 pt-5">
            <tr>
                <th>Order id</th>
                <th>Orders cost</th>
                <th>Order status</th>
                <th>Order date</th>
                <th>Order details</th>
            </tr>

        <?php while($row = $orders->fetch_assoc()) {  ?>
            <tr>
                <td>
                  <span><?php echo $row['order_id']; ?></span>
                </td>
                <td>
                  <span><?php echo $row['order_cost']; ?></span>
                </td>
                <td>
                  <span><?php echo $row['order_status']; ?></span>
                </td>
                <td>
                  <span><?php echo $row['order_date']; ?></span>
                </td>
                <td>
                  <form method="POST" action="order_details.php">
                    <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status" />
                    <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id"/>
                    <input class="btn order-details-btn" name="order_details_btn" type="submit" value="details"/>
                  </form>
                </td>
            </tr>
        <?php } ?>
        </table>    
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
