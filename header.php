
<?php  
session_start();

?>



<?php

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
    <title>FoodFusion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link href="./css/style.css" rel="stylesheet">

</head>

<body class="bg-dark">
  <?php   
   if(isset($_POST['logout'])){
    session_destroy();
    header("location:login.php");
   }
?>

<nav class="navbar navbar-expand-lg  navbar-dark bg-dark ">
        <a class="navbar-brand" href="index.php">
            <img src="./upload/image/food.png" width="70" height="70" class="d-inline-block align-top rounded-5" alt="FoodFusion">
           <p class="d-inline-block mt-3 font-weight-bold">FoodFusion</p> 
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto pt-5 pb-5">
                <li class="nav-item">
                    <a class="nav-link text-white " href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="about.php">Culinary Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="recipe.php">Recipe Collection</a>
                </li>
                <li class="nav-item">
               <a class="nav-link text-white" href="community_cookbook.php">Community Cookbook</a>
                    <!-- Button trigger modal -->

                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="resources.php">Contact Us</a>
                </li>
              
            </ul>

        
          <form method='post'>
            <ul class="navbar-nav">
                <!-- Login Button -->
               
          

            <?php
if (!isset($_SESSION['user_array'])) {
    ?>
    <li class="nav-item">
        <button class="btn btn-outline-primary">
            
            <a class="btn " id="signUpBtn" data-toggle="modal" data-target="#signUpModal">Login</a>
        </button>
    </li>
    <?php 
} 
?>

<div id="signUpModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">


    <h5 class="modal-title" id="signUpModalLabel">login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php
if (isset($_SESSION['user_array'])) {
    ?>
   <li class="nav-item">
                <button type="submit" value="Logout" name="logout"  class="btn btn-outline-primary" onclick="return confirm('Are you sure to logout?');"">LogOut</button>
    </li>
    <?php 
} 
?>
               
                <!-- Sign Up Button -->
            </ul>
            </form>
        </div>
    </nav>



