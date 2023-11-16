<?php
include('../connection.php');

if (isset($_GET['categoryId'])) {
    $categoryId = $_GET['categoryId'];

    if($categoryId !== '') {
        $sql = "SELECT products.*, categories.category_name FROM products INNER JOIN categories ON products.category_id = categories.id WHERE category_id = $categoryId";
    } else {
        $sql = "SELECT products.*, categories.category_name FROM products INNER JOIN categories ON products.category_id = categories.id";
    }
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode(array('message' => 'No products found for the selected category.'));
    }
    
} else {
    echo json_encode(array('message' => 'Category ID not provided.'));
}

?>
