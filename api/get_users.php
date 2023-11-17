<?php
include('../connection.php');

// SQL query to fetch categories
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all rows into an array
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    // Output as JSON
    echo json_encode($users);
} else {
    echo json_encode(array('message' => 'No users found.'));
}

?>
