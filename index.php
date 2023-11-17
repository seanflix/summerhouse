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
                        <li><a href="index.php" class="nav-link px-2 text-warning fw-semibold">Home</a></li>
                        <li><a href="history.php" class="nav-link px-2 text-white">Orders</a></li>
                        <li><a href="products.php" class="nav-link px-2 text-white">Products</a></li>
                        <li><a href="categories2.php" class="nav-link px-2 text-white">Categories</a></li>
                        <li><a href="users.php" class="nav-link px-2 text-white">Users</a></li>
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
            <div class="row">
                <div class="col-8">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="dropdown">
                            <button id="categoryButton" class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                All Categories
                            </button>
                            <ul class="dropdown-menu" id="categoryDropdown">
                                <!-- Categories will be dynamically populated here -->
                            </ul>
                        </div>
                        <form class="d-flex" role="search">
                            <input id="searchInput" class="form-control" type="search" placeholder="Search" aria-label="Search">
                        </form>
                    </div>
                    <div class="row" id="productContainer">
                        <!-- Products will be dynamically populated here -->
                    </div>
                </div>
                <div class="col-4">
                    <div class="sticky-top card rounded-4 overflow-hidden border-0 shadow" style="top: 100px;">
                        <div class="border-bottom p-4">
                            <h4 class="mb-0">Order List</h4>
                            <small class="mb-0 text-secondary">Employee: <?php echo $_SESSION['username']; ?></small>
                        </div>
                        <div id="orderList">
                            <!-- Orders will be dynamically populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Order Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-uppercase text-secondary">Product</small>
                                </div>
                                <div class="col-3">
                                    <small class="text-uppercase text-secondary">Quantity</small>
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    <small class="text-uppercase text-secondary">Subtotal</small>
                                </div>
                            </div>
                            <div id="orderConfirmList">
                                <!-- Order list will be dynamically populated here -->
                            </div>
                        </div>
                        <div class="mb-3 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Total</h5>
                                <h5 id="totalAmountFinal" class="mb-0 text-success">
                                    <!-- Total price -->
                                </h5>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="payment_type" aria-label="Floating label select example">
                                <option value="cash" selected>Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank transfer">Bank Transfer</option>
                                <option value="card">Card</option>
                            </select>
                            <label for="floatingSelect">Payment Method</label>
                        </div>
                        <div id="cust_field" class="form-floating mb-3">
                            <input type="text" class="form-control" id="customer_name" placeholder="John Doe">
                            <label for="customer_name">Customer Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="order_notes" style="height: 100px" placeholder="Enter notes here"></textarea>
                            <label for="order_notes">Notes</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button onClick="placeOrder()" class="btn btn-success">Place Order</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fa-solid fa-mug-hot text-success my-4" style="font-size: 70px;"></i>
                            <h3 id="success_message" class="text-center">
                                <!-- Success message -->
                            </h3>
                            <p id="order_number" class="text-secondary">
                                <!-- Order number -->
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="link" :class="isOnPage">Homepage</div>


        <script>
            function isOnPage() {
                // ....
                let onPage = document.getElementByClass("link")
                return onPage ? 'text-blue' : 'text-white';
            }
        </script> -->
        <!-- Main Section End -->






        <!-- Import JQuery -->
        <script src="js/jquery-3.7.1.js"></script>

        <!-- Function Scripts -->
        <script>

            // Order list array
            let orderItems = [];
            let products = [];
            let displayedProducts = [];

            // Run on page load
            $(document).ready(function () {
                fetchCategories();
                fetchProducts('','All Categories');
                updateOrderList();
                
                // Category dropdown event listener
                $('#categoryDropdown').on('click', 'a', function () {
                    var categoryId = $(this).data('category-id');
                    var categoryName = $(this).data('category-name');
                    fetchProductsByCategory(categoryId, categoryName);
                });

                $('#searchInput').on('input', function () {
                    const searchQuery = $(this).val().trim();
                    searchProducts(searchQuery);
                });
            });

            
            // Get all categories
            function fetchCategories() {
                $.ajax({
                    url: 'api/get_categories.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function (categories) {
                        const categoryDropdown = document.getElementById('categoryDropdown');
                        let htmlString = `
                                <li>
                                    <a class="dropdown-item" href="#" data-category-id="" data-category-name="All Categories">All Categories</a>
                                </li>
                        `;
                        categories.forEach(function (category) {
                            htmlString += `
                                <li>
                                    <a class="dropdown-item" href="#" data-category-id="${category.id}" data-category-name="${category.category_name}">${category.category_name}</a>
                                </li>
                            `;
                        });
                        categoryDropdown.innerHTML = htmlString;
                    },
                    error: function () {
                        console.error('Failed to fetch categories.');
                    }
                });
            }

            // Get all products data
            function fetchProducts(categoryId, categoryName) {
                $.ajax({
                    url: 'api/get_products.php',
                    method: 'GET',
                    data: { categoryId: categoryId },
                    dataType: 'json',
                    success: function (productSets) {
                        products = productSets
                        console.log(products);
                        fetchProductsByCategory('','All Categories');
                    },
                    error: function () {
                        console.error('Failed to fetch products.');
                    }
                });
            }
            
            // Filter products
            function fetchProductsByCategory(categoryId, categoryName) {
                let filteredProducts;
                
                if (categoryId !== "") {
                    filteredProducts = products.filter(product => parseInt(product.category_id) === categoryId);
                    displayedProducts = filteredProducts;
                } else {
                    filteredProducts = products;
                    displayedProducts = filteredProducts;
                }

                $('#categoryButton').text(categoryName);
                const productContainer = document.getElementById('productContainer');
                let htmlString = '';
                filteredProducts.forEach(function (product) {
                    htmlString += `
                        <div class="col-md-6 col-lg-4 col-xl-3 pb-4">
                            <div class="card rounded-4 overflow-hidden border-0 shadow h-100">
                                <div class="ratio ratio-4x3">
                                    <img src="${product.image}" class="object-fit-cover" alt="Product Image">
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h5 class="card-title">${product.product_name}</h5>
                                    <p class="card-text">Php ${product.price}</p>
                                    <button class="btn btn-primary fw-medium rounded-pill" onclick="addToOrder(${product.id}, 1)">
                                        Add to List
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                productContainer.innerHTML = htmlString;
            }

            // Search products
            function searchProducts(searchQuery) {
                const lowercaseQuery = searchQuery.toLowerCase();

                const filteredProducts = displayedProducts.filter(product =>
                    product.product_name.toLowerCase().includes(lowercaseQuery)
                );

                displayFilteredProducts(filteredProducts);
            }

            // Display search results
            function displayFilteredProducts(filteredProducts) {
                const productContainer = document.getElementById('productContainer');
                let htmlString = '';

                filteredProducts.forEach(function (product) {
                    htmlString += `
                        <div class="col-md-6 col-lg-4 col-xl-3 pb-4">
                            <div class="card rounded-4 overflow-hidden border-0 shadow h-100">
                                <div class="ratio ratio-4x3">
                                    <img src="${product.image}" class="card-img-top" alt="Product Image">
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h5 class="card-title">${product.product_name}</h5>
                                    <p class="card-text">Php ${product.price}</p>
                                    <button class="btn btn-primary btn-sm rounded-pill" onclick="addToOrder(${product.id}, 1)">
                                        Add to Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                productContainer.innerHTML = htmlString;
            }


            // Add Product Item to { orderItems } array
            function addToOrder(productId, quantity) {
                // Find product from ordered items if exists
                let existingOrderItem = orderItems.find(item => item.productId === productId);
                // if existingOrderItem is "TRUE"
                if (existingOrderItem) {
                    existingOrderItem.quantity += quantity;
                    existingOrderItem.subtotal = existingOrderItem.quantity * getProductPrice(productId);
                } else {
                    //if "FALSE"
                    let product = products.find(item => item.id === productId.toString());
                    if (product) {
                        let subtotal = parseInt(product.price) * quantity;
                        let orderItem = { productId, quantity, subtotal };
                        orderItems.push(orderItem);
                        console.log(orderItems);
                    }
                }
                updateOrderList();
            }

            // Remove Product Item from { orderItems } array
            function removeOrder(productId) {
                orderItems = orderItems.filter(item => item.productId !== productId);
                updateOrderList();
            }

            // Update Order List
            function updateOrderList() {
                const orderListElement = document.getElementById('orderList');
                let htmlString = '';

                orderItems.forEach(item => {
                    const product = products.find(prod => prod.id === item.productId.toString());

                    htmlString += `
                        <div class="border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0">${product.product_name}</h6>
                                <small class="text-secondary mb-0">Php ${product.price}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center mr-2" style="width: 115px;">
                                    <button class="border-0 bg-transparent" type="button" onclick="decrementQuantity(${product.id})">
                                        <i class="fa-solid fa-circle-minus text-tertiary" style="font-size: 25px;"></i>
                                    </button>
                                    <input type="text" class="form-control text-center fw-bold" value="${item.quantity}" readonly>
                                    <button class="border-0 bg-transparent" type="button" onclick="incrementQuantity(${product.id})">
                                        <i class="fa-solid fa-circle-plus text-tertiary" style="font-size: 25px;"></i>
                                    </button>
                                </div>
                                <button class="btn" as="button" onclick="removeOrder(${product.id})">
                                    <i class="fa-regular fa-trash-can text-danger"></i>
                                </button>
                            </div>
                        </div>
                    `;

                });

                if(orderItems.length > 0) {
                    htmlString += `
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center p-2">
                                <h5 class="mb-0">Total</h5>
                                <p id="totalAmount" class="text-primary fw-medium fs-5 mb-0"></p>
                            </div>
                            <button onClick="proceedCheckout()" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-success w-100 mt-3">Checkout</button>
                        </div>
                    `;
                } else {
                    htmlString += `
                        <div class="text-center p-5">
                            <p class="fs-3 text-secondary fw-light">
                                Empty
                            </p>
                        </div>
                    `;
                }

                orderListElement.innerHTML = htmlString;
                updateTotalAmount();
            }

            // Update Total Amount
            function updateTotalAmount() {
                const totalAmountElement = document.getElementById('totalAmount');
                const totalAmount = orderItems.reduce((total, item) => total + item.subtotal, 0);
                if(orderItems.length > 0) {
                    totalAmountElement.textContent = `Php ${totalAmount}`;
                }
            }

            // Add quantity to each added item
            function incrementQuantity(productId) {
                const orderItem = orderItems.find(item => item.productId === productId);
                if (orderItem) {
                    orderItem.quantity += 1;
                    orderItem.subtotal = orderItem.quantity * getProductPrice(productId);
                    updateOrderList();
                }
            }

            // Decrease quantity to each added item
            function decrementQuantity(productId) {
                const orderItem = orderItems.find(item => item.productId === productId);
                if (orderItem && orderItem.quantity > 1) {
                    orderItem.quantity -= 1;
                    orderItem.subtotal = orderItem.quantity * getProductPrice(productId);
                    updateOrderList();
                }
            }

            // Get product price
            function getProductPrice(productId) {
                const product = products.find(item => item.id === productId.toString());
                return product ? product.price : 0;
            }

            // Get product name
            function getProductName(productId) {
                const product = products.find(item => item.id === productId.toString());
                return product ? product.product_name : null;
            }

            function proceedCheckout() {
                let totalAmountElement = document.getElementById('totalAmountFinal');
                let totalAmount = orderItems.reduce((total, item) => total + item.subtotal, 0);
                if(orderItems.length > 0) {
                    totalAmountElement.textContent = `Php ${totalAmount}`;
                }

                const orderConfirmListElement = document.getElementById('orderConfirmList');
                let htmlString = '';

                orderItems.forEach(item => {
                    htmlString += `
                    <div class="row align-items-center pt-3">
                        <div class="col-6">
                            <h6 class="mb-0 lh-1">${getProductName(item.productId)}</h6>
                            <small class="text-secondary">Php ${getProductPrice(item.productId)}</small>
                        </div>
                        <div class="col-3">
                            <p class="mb-0">x ${item.quantity}</p>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <p class="mb-0">Php ${item.subtotal}</p>
                        </div>
                    </div>
                    `;
                });

                orderConfirmListElement.innerHTML = htmlString;

                // Opening of modal for Bootstrap Modals (alternative)
                // $('#staticBackdrop').modal('show');
            }

            // Place Order
            function placeOrder() {
                let paymentType = document.getElementById('payment_type').value;
                let customerName = document.getElementById('customer_name').value;
                let orderNotes = document.getElementById('order_notes').value;
                console.log({
                        orders: orderItems,
                        payment: paymentType,
                        customer: customerName,
                        notes: orderNotes
                    });
                $.ajax({
                    url: 'api/place_order.php',
                    method: 'GET',
                    data: {
                        orders: orderItems,
                        payment: paymentType,
                        customer: customerName,
                        notes: orderNotes
                    },
                    dataType: 'json',
                    success: function (response) {
                        if(response.error){
                            const custField = document.getElementById('cust_field');
                            htmlString = `
                            <input type="text" class="form-control is-invalid" id="customer_name" placeholder="John Doe">
                            <label for="customer_name">Customer Name is required</label>`;
                            custField.innerHTML = htmlString;
                        } else {
                            $('#staticBackdrop').modal('hide');
                            $('#successModal').modal('show');
                            $('#success_message').text(response.message)
                            $('#order_number').text('Order number: '+response.order_number)
                            console.log(response);
                        }

                        // response.error ?
                        // (
                        //     const custField = document.getElementById('cust_field');
                        //     htmlString = `
                        //     <input type="text" class="form-control is-invalid" id="customer_name" placeholder="John Doe">
                        //     <label for="customer_name">Customer Name is required</label>`;
                        //     custField.innerHTML = htmlString;
                        // ) 
                        // :
                        // (
                        //     $('#staticBackdrop').modal('hide');
                        //     $('#successModal').modal('show');
                        //     $('#success_message').text(response.message)
                        //     $('#order_number').text('Order number: '+response.order_number)
                        //     console.log(response);
                        // );
                        
                            
                        
                    },
                    error: function () {
                        console.error('Failed to place order.');
                    }
                });

            }
            
        </script>
    </body>
</html>