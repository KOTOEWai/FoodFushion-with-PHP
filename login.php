<?php
session_start();
include('./db.php');

$error = "";
$errorEmail = "";
$errorPassword = "";

// Initialize failed attempts session if not already set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}
$max_attempts = 3;
$lockout_duration = 300; // in seconds (e.g., 300 = 5 minutes)
if (time() < $_SESSION['lockout_time']) {
    $remaining_time = $_SESSION['lockout_time'] - time();
    $error = "Too many failed attempts. Please try again in $remaining_time seconds.";
} else {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        if (empty($email)) {
            $errorEmail = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorEmail = "Invalid email format";
        }
        if (empty($password)) {
            $errorPassword = "Password is required";
        }
        if (empty($errorEmail) && empty($errorPassword)) {
            $password = md5(trim($password)); 
            $user = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
            $user_count = mysqli_num_rows($user);
            if ($user_count === 1) {
                // Successful login: Reset failed attempts and login
                if (!isset($_COOKIE['cookie_accepted'])) {
                    // Show the cookie consent banner
                    setcookie('email',$email, time() + (30 * 24 * 60 * 60), "/");
                    setcookie('password',$password, time() + (30 * 24 * 60 * 60), "/"); // 30 days expiry
                }
                $userFetch = mysqli_fetch_assoc($user);
                $_SESSION['user_array'] = $userFetch;
                $_SESSION['failed_attempts'] = 0; // Reset failed attempts
                if ($userFetch['role'] == 'user') {
                    echo '<script type="text/javascript">';
                    echo 'alert("Login successfully");';
                    echo 'setTimeout(function() { window.location.href = "index.php"; }, 1000);'; // Delay before redirect
                    echo '</script>';
                } else {
                    echo "Login error";
                }
            } else {
                $_SESSION['failed_attempts']++;
                if ($_SESSION['failed_attempts'] >= $max_attempts) {
                    $_SESSION['lockout_time'] = time() + $lockout_duration;
                    $error = "Too many failed attempts. Please try again in $lockout_duration seconds.";
                } else {
                    $error = "Invalid email or password. You have " . ($max_attempts - $_SESSION['failed_attempts']) . " attempts remaining.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
 

<div class="container login-container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-center">Login</h2>
            <form action="login.php" method="post">

                <i class="text-danger"><?php echo $error; ?></i>
                 
                <div class="form-group">
                    <label for="useremail">Email:</label>
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                    <div class="text-danger"><?php echo $errorEmail; ?></div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="text-danger"><?php echo $errorPassword; ?></div>
                </div>
      
                <button type="submit" value="Login" name="login" class="btn btn-primary btn-block mt-3">Login</button>
            </form>

            <p class="mt-3 text-center">Don't have an account? <a href="index.php">Register</a></p>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }
    .login-container {
        max-width: 500px;
        margin: auto;
        padding-top: 50px;
    }
    .card {
        border-radius: 1rem;
    }
</style>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
