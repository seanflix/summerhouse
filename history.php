<?php
    session_start();

    include('connection.php');

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>The Tiny Summerhouse Cafe</title>
        <!-- Import Bootstrap -->
        <link rel="stylesheet" href="css/5.3.2_dist_css_bootstrap.min.css">
        <script src="js/5.3.2_dist_js_bootstrap.bundle.min.js"></script>
        <script src="js/fontawesome.js"></script>
    </head>
    <body>
        <!-- Navbar Section -->
        <nav class="sticky-top p-3" style="background-color: #141436;">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none me-lg-2">
                        <img class="rounded-pill" style="height: 40px;" src="product-images/logo.png" alt="">
                    </a>

                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="index.php" class="nav-link px-2 text-white">Home</a></li>
                        <li><a href="history.php" class="nav-link px-2 text-warning fw-semibold">Orders</a></li>
                        <li><a href="products.php" class="nav-link px-2 text-white">Products</a></li>
                        <li><a href="categories2.php" class="nav-link px-2 text-white">Categories</a></li>
                    </ul>

                    <h6 class="mb-0 me-3 text-white">Welcome, <?php echo $_SESSION['username']; ?>!</h6>
                    <div class="text-end">
                        <a href="logout.php" type="button" class="btn btn-warning">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Section -->
        <div class="container my-5">
            <div class="row w-100 mb-2 px-3">
                <div class="col">
                    <small class="text-uppercase text-secondary">Order Number</small>
                </div>
                <div class="col">
                    <small class="text-uppercase text-secondary">Customer Name</small>
                </div>
                <div class="col">
                    <small class="text-uppercase text-secondary">Payment Type</small>
                </div>
                <div class="col">
                    <small class="text-uppercase text-secondary">Ordered on</small>
                </div>
            </div>
            <?php
            $sqlOrders = "SELECT orders.id, users.username, orders.total_amount, orders.customer_name, orders.payment_type, orders.notes, orders.created_at
                        FROM orders
                        INNER JOIN users ON orders.user_id = users.id
                        ORDER BY orders.created_at DESC";

            $result = $conn->query($sqlOrders);

            if ($result->num_rows > 0) {
                while ($order = $result->fetch_assoc()) { 
                    $dateTime = new DateTime($order["created_at"]);
                    $formattedDateTime = $dateTime->format("F j, Y g:ia");
                ?>
                <div class="accordion accordion-flush border rounded-4 overflow-hidden shadow-sm mb-3" id="orderAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $order["id"]; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $order["id"]; ?>">
                            <div class="row w-100">
                                <div class="col fw-semibold text-primary fs-5">
                                    <?php echo $order["id"]; ?>
                                </div>
                                <div class="col fw-medium text-dark">
                                    <?php echo $order["customer_name"]; ?>
                                </div>
                                <div class="col fw-medium text-dark text-capitalize">
                                    <?php echo $order["payment_type"]; ?>
                                </div>
                                <div class="col fw-medium text-secondary">
                                    <small><?php echo $formattedDateTime; ?></small>
                                </div>
                            </div>
                        </button>
                        </h2>
                        <div id="collapse_<?php echo $order["id"]; ?>" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="row mb-5">
                                    <?php
                                        $orderId = $order["id"];
                                        $sqlOrderItems = "SELECT * FROM order_details
                                                        INNER JOIN products ON order_details.product_id = products.id
                                                        WHERE order_details.order_id = '$orderId'";

                                        $items = $conn->query($sqlOrderItems);

                                        if ($items->num_rows > 0) {
                                            while ($item = $items->fetch_assoc()) { ?>
                                            <div class="col-2 p-2">
                                                <div class="card rounded-4 p-2">
                                                    <img class="card-img-top rounded-3 mb-2" style="height:120px; object-fit: cover;" src="<?php echo $item["image"]; ?>" alt="">
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div>
                                                            <p class="fw-medium mb-0 lh-1"><?php echo $item["product_name"]; ?></p>
                                                            <span class="lh-1 text-secondary">x <?php echo $item["quantity"]; ?></span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <small class="me-1 text-secondary">Php</small>
                                                            <h4 class="text-primary mb-0"><?php echo $item["subtotal"]; ?></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php  }
                                        } 
                                    ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">Total Amount</h5>
                                        <h4 class="text-primary fw-bold">Php <?php echo $order["total_amount"]; ?></h4>
                                    </div>
                                    <div class="form-floating w-50">
                                        <input type="email" class="form-control" id="floatingInput" value="<?php echo (($order["notes"] == null) ? 'No notes' : $order["notes"]); ?>">
                                        <label for="floatingInput">Notes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                } 
            } else {
                echo "0 results";
            }
        ?>

        </div>
        <!-- Main Section End -->


        <!-- Import JQuery -->
        <script src="js/jquery-3.7.1.js"></script>

        <!-- Function Scripts -->
        <script>

            
        </script>
    </body>
</html>