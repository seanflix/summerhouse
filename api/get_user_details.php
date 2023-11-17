<?php
include('../connection.php');

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $sql = "SELECT users.*, roles.type FROM users INNER JOIN roles ON users.role_id = roles.id WHERE users.id = $userId";

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
    
} else {
    echo json_encode(array('message' => 'Category ID not provided.'));
}

?>
