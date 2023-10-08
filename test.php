<?php
include('connection.php');

session_start();

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
    <title>Order Form</title>
    <style>
        .order-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php

function getProductById($productId) {
    // Implement your database query to fetch product details by ID
    // Replace the following with your actual database query
    global $conn; // Assuming $connection is your database connection variable

    $productId = mysqli_real_escape_string($conn, $productId);
    $sql = "SELECT * FROM products WHERE id = '$productId'";
    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row;
    } else {
        return null; // Return null if product not found
    }
}

// Your database connection code goes here

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data and insert into the database
    // Ensure to validate and sanitize user inputs before database operations

    $user_id = 1; // Replace with the actual user ID

    // Insert into orders table
    $total_amount = 0;
    // Calculate total amount based on product prices and quantities
    foreach ($_POST['products'] as $productId => $quantity) {
        $product = getProductById($productId); // You need to implement this function to fetch product details from the database
        $total_amount += $product['price'] * $quantity;
    }

    // Insert into orders table
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES ('$user_id', '$total_amount')";
    // Execute the SQL query

    $order_id = mysqli_insert_id($conn); // Get the last inserted order ID

    // Insert into order_details table
    foreach ($_POST['products'] as $productId => $quantity) {
        if ($quantity > 0) {
            $product = getProductById($productId);
            $subtotal = $product['price'] * $quantity;

            $sql = "INSERT INTO order_details (order_id, product_id, quantity, subtotal) 
                    VALUES ('$order_id', '$productId', '$quantity', '$subtotal')";
            // Execute the SQL query
        }
    }

    echo "<h2>Your Order has been placed!</h2>";
}
?>

<h1>Place Your Order</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="order-form">
    <div id="order-items">
        <!-- Order items will be dynamically added/removed here -->
    </div>

    <button type="button" onclick="addOrderItem()">Add Item</button>
    <br><br>
    <input type="submit" value="Place Order">
</form>

<script>
    function addOrderItem() {
        var orderItemsDiv = document.getElementById('order-items');
        var newItemDiv = document.createElement('div');
        newItemDiv.classList.add('order-item');

        // Add product dropdown
        var productDropdown = document.createElement('select');
        productDropdown.name = 'products[]';

        <?php
        // Populate product options dynamically
        foreach ($products as $productId => $productName) {
            echo "var option = document.createElement('option');";
            echo "option.value = '$productId';";
            echo "option.text = '$productName';";
            echo "productDropdown.add(option);";
        }
        ?>
        
        newItemDiv.appendChild(productDropdown);

        // Add quantity input
        var quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.name = 'quantities[]';
        quantityInput.min = 0;
        quantityInput.value = 1; // Default quantity
        newItemDiv.appendChild(quantityInput);

        // Add remove button
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = 'Remove Item';
        removeButton.onclick = function () {
            orderItemsDiv.removeChild(newItemDiv);
        };
        newItemDiv.appendChild(removeButton);

        orderItemsDiv.appendChild(newItemDiv);
    }
</script>

</body>
</html>
