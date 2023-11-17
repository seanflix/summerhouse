<?php
include('../connection.php');

// SQL query to fetch roles
$sql = "SELECT `id`, `type` FROM roles";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all rows into an array
    $roles = array();
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }

    // Output as JSON
    echo json_encode($roles);
} else {
    echo json_encode(array('message' => 'No roles found.'));
}

?>
