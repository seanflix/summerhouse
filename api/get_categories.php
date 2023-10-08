<?php
include('../connection.php');

// SQL query to fetch categories
$sql = "SELECT id, category_name FROM categories";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all rows into an array
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    // Output as JSON
    echo json_encode($categories);
} else {
    echo json_encode(array('message' => 'No categories found.'));
}

?>
