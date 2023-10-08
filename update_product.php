<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $updateId = $_POST['update_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Check if a file was uploaded
    if (isset($_FILES['image'])) {
        $targetDir = "product-images/"; // Specify your target directory
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Allow only certain file formats (you can customize this if needed)
            $allowedFormats = array("jpg", "jpeg", "png", "gif");
            if (in_array($imageFileType, $allowedFormats)) {
                // Move the uploaded file to the target directory
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

                // Update the product with the new image path
                $sql = "UPDATE products 
                        SET product_name = '$product_name', price = '$price', category_id = '$category_id', image = '$targetFile'
                        WHERE id = $updateId";

                if ($conn->query($sql) === TRUE) {
                    $success = "Product updated successfully!";
                    echo json_encode(['status' => 'success', 'message' => 'Image updated successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error updating image: ' . $conn->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format. Allowed formats: jpg, jpeg, png, gif']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'File is not an image.']);
        }
    }
    
    // Update the product without changing the image
    $sql = "UPDATE products 
            SET product_name = '$product_name', price = '$price', category_id = '$category_id'
            WHERE id = $updateId";

    if ($conn->query($sql) === TRUE) {
        $success = "Product updated successfully!";
        echo json_encode(['status' => 'success', 'message' => 'Product updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating product: ' . $conn->error]);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>