<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="assests/css/style.css">

    <style>
        .product img {
            width: 100%;
            height: auto;
            box-sizing: border-box;
            object-fit: cover;
        }

        .pagination a{
          color: coral;
        }

        .pagination li:hover a {
          color: #fff;
          background-color: coral;
        }
    </style>
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

    <!--Featured-->
    <section id="featured" class="my-5 py-5">
        <div class="container text-center mt-5 pb-5">
          <h3>Shop</h3>
          <hr>
          <p>Here you can check out our products</p>
          
          <!-- Search and Filter Section -->
          <div class="row mb-4">
            <div class="col-md-6">
              <form action="" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
              </form>
            </div>
            <div class="col-md-6">
              <form action="" method="GET" class="d-flex">
                <select name="price_range" class="form-select me-2" onchange="this.form.submit()">
                  <option value="">Price Range</option>
                  <option value="0-100" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '0-100') ? 'selected' : ''; ?>>0 - 100 zł</option>
                  <option value="100-200" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '100-200') ? 'selected' : ''; ?>>100 - 200 zł</option>
                  <option value="200-300" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '200-300') ? 'selected' : ''; ?>>200 - 300 zł</option>
                  <option value="300+" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '300+') ? 'selected' : ''; ?>>300+ zł</option>
                </select>
                <?php if(isset($_GET['search'])): ?>
                  <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
        <div class="row mx-auto container-fluid">
          <?php
          // Include database connection
          require_once 'server/connection.php';

          // Set items per page and get current page
          $items_per_page = 16;
          $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
          $offset = ($current_page - 1) * $items_per_page;

          // Build the query with search and price filter
          $query = "SELECT * FROM products WHERE 1=1";
          
          if (isset($_GET['search']) && !empty($_GET['search'])) {
              $search = mysqli_real_escape_string($conn, $_GET['search']);
              $query .= " AND product_name LIKE '%$search%'";
          }

          if (isset($_GET['price_range']) && !empty($_GET['price_range'])) {
              $range = $_GET['price_range'];
              switch ($range) {
                  case '0-100':
                      $query .= " AND product_price <= 100";
                      break;
                  case '100-200':
                      $query .= " AND product_price > 100 AND product_price <= 200";
                      break;
                  case '200-300':
                      $query .= " AND product_price > 200 AND product_price <= 300";
                      break;
                  case '300+':
                      $query .= " AND product_price > 300";
                      break;
              }
          }

          // Get total number of products for pagination
          $total_query = $query;
          $total_result = mysqli_query($conn, $total_query);
          $total_products = mysqli_num_rows($total_result);
          $total_pages = ceil($total_products / $items_per_page);

          // Add pagination to the query
          $query .= " LIMIT $offset, $items_per_page";
          
          // Execute the query
          $result = mysqli_query($conn, $query);

          // Display products
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                  <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                      <img class="img-fluid mb-3" src="assests/imgs/<?php echo $row['product_image']; ?>"/>
                      <div class="star">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                      </div>
                      <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                      <h4 class="p-price"><?php echo number_format($row['product_price'], 2); ?> zł</h4>
                      <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
                  </div>
                  <?php
              }
          } else {
              echo "<div class='col-12 text-center'><p>No products found.</p></div>";
          }
          ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $current_page - 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['price_range']) ? '&price_range=' . urlencode($_GET['price_range']) : ''; ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['price_range']) ? '&price_range=' . urlencode($_GET['price_range']) : ''; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $current_page + 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['price_range']) ? '&price_range=' . urlencode($_GET['price_range']) : ''; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
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