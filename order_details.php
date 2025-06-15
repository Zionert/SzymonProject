<?php 
    include('server/connection.php');

    if (isset($_POST['order_details_btn']) && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];

        // Fetch order items
        $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $order_details = $stmt->get_result();

        // Fetch order status
        $status_stmt = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ?");
        $status_stmt->bind_param('i', $order_id);
        $status_stmt->execute();
        $status_result = $status_stmt->get_result();
        $order_data = $status_result->fetch_assoc();
        $order_status = $order_data['order_status'];

    } else {
        header('location: account.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assests/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white py-3 fixed-top">
    <div class="container">
        <img class="logo" src="assests/imgs/logo.png">
        <h2 class="brand">Marklin</h2> 
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.html">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.html">Contact us</a></li>
                <li class="nav-item">
                    <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
                    <a href="account.php"><i class="fa-solid fa-user"></i></a>
                </li>    
            </ul>       
        </div>
    </div>
</nav>

<!-- Order Details Section -->
<section class="orders container my-5 py-5">
    <div class="container mt-5">
        <h2 class="text-center">Order Details</h2>
        <hr>
    </div>

    <table class="table mt-5 pt-5 mx-auto text-center">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $order_details->fetch_assoc()) { ?>
            <tr>
                <td><img src="assests/imgs/<?php echo $row['product_image']; ?>" width="70" height="70"/></td>
                <td><?php echo $row['product_name']; ?></td>
                <td>$ <?php echo number_format($row['product_price'], 2); ?></td>
                <td><?php echo $row['product_quantity']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>    

    <?php if ($order_status === "not paid") { ?>
        <form>
            <input type="submit" class="btn btn-primary d-block mx-auto" value="Pay Now"/>  
        </form>
    <?php } ?>
</section>

<!-- Footer -->
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
                <div><h6 class="text-uppercase">Address</h6><p>Piłsudskiego 9</p></div>
                <div><h6 class="text-uppercase">Phone</h6><p>+48 123 456 789</p></div>
                <div><h6 class="text-uppercase">Email</h6><p>smth@gamil.com</p></div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
