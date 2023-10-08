<?php
include('../connection.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id']; 

if (isset($_GET['orders']) && isset($_GET['customer']) && isset($_GET['payment'])) {
    $products = $_GET['orders'];
    $customer = $_GET['customer'];
    $payment = $_GET['payment'];
    $notes = $_GET['notes'];

    if($customer == '') {
        echo json_encode(array('error' => 'Customer name is required.'));
    } else {
        try {
            $orderTotal = 0;
            $sqlOrder = "INSERT INTO orders (user_id, total_amount, customer_name, payment_type, notes) VALUES ($userID, $orderTotal, '$customer', '$payment', '$notes')";
        
            if ($conn->query($sqlOrder)) {
                $orderID = $conn->insert_id;
        
                foreach ($products as $product) {
                    $productID = $product['productId'];
                    $quantity = $product['quantity'];
        
                    $sqlOrderDetails = "INSERT INTO order_details (order_id, product_id, quantity, subtotal) VALUES ($orderID, $productID, $quantity, (SELECT price FROM products WHERE id = $productID) * $quantity)";
                    $conn->query($sqlOrderDetails);
        
                    if ($conn->affected_rows > 0) {
                        $sqlProductPrice = "SELECT price FROM products WHERE id = $productID";
                        $priceResult = $conn->query($sqlProductPrice);
    
                        if ($priceResult->num_rows > 0) {
                            $priceRow = $priceResult->fetch_assoc();
                            $price = $priceRow['price'];
                        } else {
                            $price = 0;
                        }
    
                        $orderTotal += $price * $quantity;
                    } else {
                        $orderTotal += 0;
                    }
                }
        
                $conn->query("UPDATE orders SET total_amount = $orderTotal WHERE id = $orderID");
                $conn->commit();

                echo json_encode(array('message' => 'Order Success!', 'order_number' => $orderID));

            } else {
                throw new Exception("Error creating order: " . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }

} else {
    echo json_encode(array('message' => 'Invalid input.'));
}

?>
