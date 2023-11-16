<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_username, $db_password);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $db_username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username.";
        }

        $stmt->close();
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
    <script src="js/5.3.2_dist_js_bootstrap.bundle.min.js"></script>
    <script src="js/fontawesome.js"></script>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary" style="min-height: 100vh;">

    <main class="form-signin w-25 m-auto">
        <form method="post" action="login.php" class="d-flex flex-column align-items-center">
            <img class="mx-auto rounded-pill mb-4" src="product-images/logo.png" alt="" width="72">
            <div class="d-flex justiy-content-center">
                <h2 class="h3 mb-4 fw-medium">Summerhouse Cafe</h2>
            </div>

            <div class="form-floating mb-3 w-100">
                <input type="username" class="form-control" id="username" name="username" placeholder="username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3 w-100">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <?php
                if (isset($error)) {
                    echo '<small class="text-danger mb-3">'.$error.'</small>';
                }
            ?>

            <button class="btn btn-primary w-100 py-2 mb-3" type="submit">
                Login
            </button>
            <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="register.php">
                Register
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
