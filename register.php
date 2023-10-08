<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if($password !== $password2) {
        $error = "Password doesn't match.";
    } else {
        if (empty($username) || empty($password) || empty($password2)) {
            $error = "All fields are required.";
        } else {
            $query = "SELECT id FROM users WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows > 0) {
                $error = "Username is already in use.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
                $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("ss", $username, $hashed_password);
    
                if ($insert_stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
    
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summerhouse Cafe</title>
    <!-- Import Bootstrap -->
    <link rel="stylesheet" href="css/5.3.2_dist_css_bootstrap.min.css">
    <script src="js/5.3.2_dist_js_bootstrap.bundle.min.js"></script>
    <script src="js/fontawesome.js"></script>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary" style="min-height: 100vh;">

    <main class="form-signin w-25 m-auto">
        <form method="post" action="register.php" class="d-flex flex-column align-items-center">
            <img class="mx-auto rounded-pill mb-2" src="https://i.pinimg.com/736x/ad/d2/bb/add2bbc8671e8158d0442b99c8153276.jpg" alt="" width="72">
            <h1 class="h3 mb-4 fw-medium">Summerhouse Cafe</h1>

            <div class="form-floating mb-3 w-100">
                <input type="username" class="form-control" id="username" name="username" placeholder="username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3 w-100">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3 w-100">
                <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password" required>
                <label for="password2">Confirm Password</label>
            </div>

            <?php
                if (isset($error)) {
                    echo '<small class="text-danger mb-3">'.$error.'</small>';
                }
            ?>

            <button class="btn btn-primary w-100 py-2 mb-3" type="submit">
                Register
            </button>
            <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="login.php">
                Login
            </a>
            <p class="mt-5 mb-3 text-body-secondary">
                © Summerhouse Cafe 2023
            </p>
        </form>
    </main>

<!-- Import JQuery -->
<script src="js/jquery-3.7.1.js"></script>
</body>
</html>