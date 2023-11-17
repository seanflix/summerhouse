<?php

include('connection.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//CREATE

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];
    
    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if ($conn->query($sql) ===TRUE) {
         $success = "Category added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

//UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $updatedID = $_POST['updated_id'];
    $category_name = $POST['category_name'];

    $sql = "UPDATE categories
            SET category_name = '$category_name' WHERE id = $updatedID";

    if ($conn->query($sql) === TRUE) {
        echo "Category updated successfully!";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

//DELETE
if(isset($_GET['delete_id'])) {
    $deletedId = $_GET['deleted_id'];

    $deleteSql = "DELETE FROM categories WHERE id = $deletedId";
    if ($conn->query($deletedSql) === TRUE) {
        echo "Category deleted successfully!";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

//EDIT
if(isset($_GET['edit_id'])) {
    $editedId = $GET['edit_id'];

    $editSql = "SELECT categories.*, WHERE id = $editedId";

    $editResult = $conn->query($editSql);
        if ($editResult->num_rows > 0) {
            $editRow = $editResult->fetch_assoc();
            ?>
                <div class="card rounded-4 overflow-hidden border-0 shadow mb-3">
                        <div class="p-4">
                            <h4 class="mb-0">Edit Category</h4>
                        </div>
                        <?php
                            if(isset($success)) {
                                echo '<p class="mb-0 text-success text-center mb-3">'.$success.'</p>';
                            }
                        ?>
                        <div class="px-4 pb-4">
                            <form action="categories2.php" method="post" enctype="multipart/form-data">
                                <!-- <div class="form-floating mb-3">
                                    <select class="form-select" id="category_id" name="category_id" aria-label="Enter category">
                                        <option value="<?php //echo $editRow['category_id']; ?>" selected><?php //echo $editRow['category_name']; ?></option>
                                    </select>
                                    <label for="floatingSelect">Category</label>
                                </div> -->
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter category name" value="<?php echo $editRow['category_name']; ?>">
                                    <label for="category_name">Category Name</label>
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
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" /> -->
    <script src="js/5.3.2_dist_js_bootstrap.bundle.min.js"></script>
    <script src="js/fontawesome.js"></script>
</head>
<body>
    <!-- NavBar Section -->
    <nav class="sticky-top p-3" style="background-color: #141436;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none me-lg-2">
                    <img class="rounded-pill" style="height: 40px;" src="product-images/logo.png" alt="">
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="index.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="history.php" class="nav-link px-2 text-white">Orders</a></li>
                    <li><a href="products.php" class="nav-link px-2 text-white">Products</a></li>
                    <li><a href="categories.php" class="nav-link px-2 text-warning fw-semibold">Categories</a></li>
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
                        <!-- <button>test</button> -->
                    </div>
                </div>
                <table id="myTable" class="table table-borderless table-striped table-hover align-middle border rounded-4 overflow-hidden shadow">
                    <thead class="bg-dark">
                        <tr>
                            <th class="col-2">Category ID</th>
                            <th class="col-2">Category Name</th>
                            <th class="col-2 position-relative">Category Actions</th>
                        </tr>
                    </thead>
                    <tbody id="category_table">
                        <!-- category details will dynamically populate here -->
                       
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <div class="sticky-top card rounded-4 overflow-hidden border-0 shadow" style="top: 100px;">
                    <div class="p-4">
                        <h4 class="mb-0">Add Category</h4>
                    </div>
                    <?php
                        // confirmation once the category has been added
                        if(isset($success)) {
                            echo '<p class="mb-0 text-success text-center mb-3">'.$success.'</p>';
                        }
                    ?>
                    <div class="px-4 pb-4">
                        <form action="categories2.php" method="post" enctype="multipart/form-data">
                            <!-- <div class="form-floating mb-3">
                                <select name="form-select" id="ca"></select>
                            </div> -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="product_name" name="category_name" placeholder="Enter Category Name">
                                <label for="category_name">Category Name</label>
                            </div>
                            <!-- <div class="form-floating mb-3">
                                <input type="text">
                            </div> -->
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Add Category
                            </button>
                        </form>

                    </div>
                </div>
                        
            </div>
        </div>
    </div>

    

<!-- JS Imports -->
<script src="js/jquery-3.7.1.js"></script>
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script> -->

<!-- JS Codes  -->
<script>
    $(document).ready(function () {
       // JavaScript code to fetch data from the PHP script
    fetch('api/get_categories.php')
        .then(response => response.json())
        .then(data => {
            // Get a reference to the table body
            const tableBody = document.getElementById('category_table');

            // Loop through the data and create table rows with combined edit and delete buttons
            console.log(data);

            let htmlString = ``;
            data.forEach(category => {
                htmlString += `
                    <tr>
                        <td>
                        ${category.id}
                        </td>
                        <td>
                        ${category.category_name}
                        </td>
                        <td>
                            <button type="button" class="btn btn-secondary">Edit</button>
                            <button type="button" class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                `
            });
            console.log(htmlString);
            tableBody.innerHTML = htmlString;
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });

    function editCategory(categoryId) {
        $('#editModal')
    }

    

</script>
</body>
</html>