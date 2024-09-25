<?php
session_start();
include('./db.php');


$error = "";
$errorEmail = "";
$errorPassword = "";

if (isset($_POST['login'])) {
    $email =trim( $_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
        $errorEmail = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format";
    }
    if (empty($password)) {
        $errorPassword = "Password is required";
    }
        $password = md5(trim($password)); 

        $user = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
        $user_count = mysqli_num_rows($user);

        if ($user_count === 1) {
            $userFetch= mysqli_fetch_assoc($user);
            $_SESSION['user_array']= $userFetch;
            if($userFetch['role']=='user'){
                header('Location: index.php');
               
            }else{
                 echo "Login error";
            }
        } else {
            $error = "Invalid email or password";
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

                    
                        <i class=" text-danger"><?php echo $error; ?></i>
                 
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
      
                <button type="submit" value="Login" name="login" class="btn btn-primary btn-block">Login</button>
            </form>

            <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
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
