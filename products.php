<?php
include('connection.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $target_dir = "product-images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO products (product_name, price, category_id, image) VALUES ('$product_name', '$price', '$category_id', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        $success = "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

// UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $updateId = $_POST['update_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $sql = "UPDATE products 
            SET product_name = '$product_name', price = '$price', category_id = '$category_id' 
            WHERE id = $updateId";

    if ($conn->query($sql) === TRUE) {
        $success = "Product updated successfully!";
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

// DELETE
if(isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    $imageSql = "SELECT image FROM products WHERE id = $deleteId";
    $imageResult = $conn->query($imageSql);

    if ($imageResult->num_rows > 0) {
        $imageRow = $imageResult->fetch_assoc();
        $imagePath = $imageRow['image'];

        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $deleteSql = "DELETE FROM products WHERE id = $deleteId";

    if ($conn->query($deleteSql) === TRUE) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

// EDIT
if(isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    
    $editSql = "SELECT products.*, categories.category_name FROM products INNER JOIN categories ON products.category_id = categories.id WHERE products.id = $editId";

    $editResult = $conn->query($editSql);
if ($editResult->num_rows > 0) {
    $editRow = $editResult->fetch_assoc();
    ?>
    <div class="card rounded-4 overflow-hidden border-0 shadow mb-3">
        <div class="p-4">
            <h4 class="mb-0">Edit Product</h4>
        </div>
        <?php
            if(isset($success)) {
                echo '<p class="mb-0 text-success text-center mb-3">'.$success.'</p>';
            }
        ?>
        <div class="px-4 pb-4">
            <form action="products.php" method="post" enctype="multipart/form-data">
                <div class="form-floating mb-3">
                    <select class="form-select" id="category_id" name="category_id" aria-label="Enter category">
                        <!--  -->
                        <option value="<?php echo $editRow['category_id']; ?>" selected><?php echo $editRow['category_name']; ?></option>
                    </select>
                    <label for="floatingSelect">Category</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" value="<?php echo $editRow['product_name']; ?>">
                    <label for="product_name">Product Name</label>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Php</span>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="price" name="price" placeholder="Username" value="<?php echo $editRow['price']; ?>">
                        <label for="price">Price</label>
                    </div>
                </div>
                <div class="mb-3">
                    <input class="form-control" id="image" name="image" type="file" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Save Changes
                </button>
                <a href="products.php" class="btn btn-secondary w-100 mt-2">
                    Cancel
                </a>
            </form>
        </div>
    </div>
    <?php
    }  
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
    <link rel="stylesheet" href="css/jquery.dataTables.css" />
    <script src="js/5.3.2_dist_js_bootstrap.bundle.min.js"></script>
    <script src="js/fontawesome.js"></script>
    <style>

    </style>
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
                    <li><a href="history.php" class="nav-link px-2 text-white">Orders</a></li>
                    <li><a href="products.php" class="nav-link px-2 text-warning fw-semibold">Products</a></li>
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
                    <!-- <form class="d-flex" role="search">
                        <input id="searchInput" class="form-control" type="search" placeholder="Search" aria-label="Search">
                    </form> -->
                </div>
                <table id="myTable" class="table table-borderless table-striped table-hover align-middle border rounded-4 overflow-hidden shadow">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="products_table">
                        <!--  -->
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <div class="sticky-top card rounded-4 overflow-hidden border-0 shadow" style="top: 100px;">
                    <div class="p-4">
                        <h4 class="mb-0">Add Products</h4>
                    </div>
                    <?php
                        if(isset($success)) {
                            echo '<p class="mb-0 text-success text-center mb-3">'.$success.'</p>';
                        }
                    ?>
                    <div class="px-4 pb-4">
                        <form action="products.php" method="post" enctype="multipart/form-data">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="category_id" name="category_id" aria-label="Enter category">
                                    <!--  -->
                                </select>
                                <label for="floatingSelect">Category</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name">
                                <label for="product_name">Product Name</label>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Php</span>
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Username">
                                    <label for="price">Price</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input class="form-control" id="image" name="image" type="file" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Add Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="#editProductForm">
                    <div class="modal-body">
                        <div class="px-4 pb-4">
                            <div class="ratio ratio-4x3 mb-4">
                                <img id="edit_product_image" class="rounded-4 object-fit-cover" src="product-images/affogato.jpg" alt="">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" id="edit_image" type="file" accept="image/*">
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="edit_category_id" aria-label="Enter category">
                                    <!--  -->
                                </select>
                                <label for="floatingSelect">Category</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="edit_product_name" placeholder="Enter product name">
                                <label for="product_name">Product Name</label>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Php</span>
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="edit_price" placeholder="Username">
                                    <label for="price">Price</label>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button onClick="closeEditModal()" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button onClick="updateProduct()" type="button" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="js/jquery-3.7.1.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script>

    let products = [];
    let displayedProducts = [];
    let fetchedCategories = [];
    let productEditId = null;


    $(document).ready( function () {
        fetchCategories();
        fetchProducts('','All Categories');

        $('#categoryDropdown').on('click', 'a', function () {
            var categoryId = $(this).data('category-id');
            var categoryName = $(this).data('category-name');
            fetchProductsByCategory(categoryId, categoryName);
        });

    } );

    // Initialize datatables
    function initDataTables() {
        $('#myTable').DataTable( {
            'columnDefs': [ {
                'targets': [0,4], /* column index */
                'orderable': false, /* true or false */
            }]
        });
        $('#myTable_length').addClass('mb-3');
        $('#myTable_paginate').addClass('mt-3');
        $('.dataTables_info').addClass('mt-3');
    }

    // Destroy datatables
    function destroyDataTables() {
        $('#myTable').DataTable().clear().destroy();
    }

    // Get all categories
    function fetchCategories() {
        $.ajax({
            url: 'api/get_categories.php',
            method: 'GET',
            dataType: 'json',
            success: function (categories) {
                fetchedCategories = categories;
                const categoryDropdown = document.getElementById('categoryDropdown');
                const categoryFormDropdown = document.getElementById('category_id');
                let htmlString = `
                        <li>
                            <a class="dropdown-item" href="#" data-category-id="" data-category-name="All Categories">All Categories</a>
                        </li>
                `;
                let htmlString2 = ``;
                categories.forEach(function (category) {
                    htmlString += `
                        <li>
                            <a class="dropdown-item" href="#" data-category-id="${category.id}" data-category-name="${category.category_name}">${category.category_name}</a>
                        </li>
                    `;
                    htmlString2 += `
                        <option value="${category.id}">${category.category_name}</option>
                    `;
                });
                categoryDropdown.innerHTML = htmlString;
                categoryFormDropdown.innerHTML = htmlString2;
                
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
        destroyDataTables();
        let filteredProductsfilteredProducts;

        if (categoryId !== "") {
            filteredProducts = products.filter(product => parseInt(product.category_id) === categoryId);
            displayedProducts = filteredProducts;
        } else {
            filteredProducts = products;
            displayedProducts = filteredProducts;
        }

        $('#categoryButton').text(categoryName);
        const productContainer = document.getElementById('products_table');
        let htmlString = '';
        filteredProducts.forEach(function (product) {
            htmlString += `
                <tr>
                    <td>
                        <div class="ratio ratio-4x3">
                            <img class="rounded-3" style="object-fit: cover;" src="${product.image}" alt="Product Image">
                        </div>
                    </td>
                    <td class="fw-bold">${product.product_name}</td>
                    <td class="fw-medium text-primary">Php ${product.price}</td>
                    <td class="text-secondary">${product.category_name}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <button onClick="editProduct(${product.id})" class="btn btn-sm btn-secondary me-1" style="width:70px;">Edit</button>
                            <a class="btn btn-sm btn-danger" style="width:70px;" href="?delete_id=${product.id}" onclick='return confirm("Delete ${product.product_name} from list?")'>Delete</a>
                        </div>
                    </td>
                </tr>
            `;
        });
        productContainer.innerHTML = htmlString;
        initDataTables();
    }

    function getProductById(id) {
        // Find the product with the specified id
        let foundProduct = products.find(product => parseInt(product.id) === id);

        // Return the found product or null if not found
        return foundProduct || null;
    }

    function setCategoriesToEditModal() {
        let editCategoryDropdown = document.getElementById('edit_category_id');
        let htmlString = ``;
        fetchedCategories.forEach(function (category) {
            htmlString += `
            <option value="${parseInt(category.id)}">${category.category_name}</option>
            `;
        });
        editCategoryDropdown.innerHTML = htmlString;
    }

    function closeEditModal() {
        productEditId = null;
    }

    // Open edit modal
    function editProduct(productId) {
        $('#editModal').modal('show');
        let editProductImage = document.getElementById('edit_product_image');
        let editImage = document.getElementById('edit_image');
        let editCategory = document.getElementById('edit_category_id');
        let editProductName = document.getElementById('edit_product_name');
        let editPrice = document.getElementById('edit_price');
        productEditId = productId;
        const productToEdit = getProductById(productId);
        
        setCategoriesToEditModal();

        console.log(productToEdit);

        editProductImage.src = productToEdit.image
        editCategory.value = productToEdit.category_id
        editProductName.value = productToEdit.product_name
        editPrice.value = productToEdit.price

        console.log({
            category_id: editCategory.value,
            product_name: editProductName.value,
            price: editPrice.value
        });
    }

    function updateProduct() {
        let editProductImage = document.getElementById('edit_product_image');
        let editImage = document.getElementById('edit_image');
        let editCategory = document.getElementById('edit_category_id');
        let editProductName = document.getElementById('edit_product_name');
        let editPrice = document.getElementById('edit_price');

        let editData = new FormData();
        editData.append('update_id', productEditId);
        editData.append('image', editImage.files[0]);
        editData.append('category_id', editCategory.value);
        editData.append('product_name', editProductName.value);
        editData.append('price', editPrice.value);

        $.ajax({
            url: 'update_product.php',
            method: 'POST',
            data: editData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == "success") {
                    alert(response.message);
                    fetchProducts('','All Categories');
                    window.location.reload();
                    
                } else {
                    alert(response.message);
                }
            },
            error: function(error) {
                console.error('Error updating product:', error);
            }
        });
    }


</script>
</body>
</html>