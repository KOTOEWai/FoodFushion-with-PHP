
<?php  

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
<link href="css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<!-- Custom styles for this template -->

<!-- responsive style -->
<link href="css/responsive.css" rel="stylesheet" />

</head>
<style>

.primary-color {
    background-color: #FF6F61;
    color: white;
}
.secondary-color {
    background-color: #28A745;
    color: white;
}
.accent-color {
    color: #FFC107;
}
.text-dark {
    color: #252c33;
}
.bg-light-gray {
    background-color: #F8F9FA;
}
.sliver{
  background-color: #FF5733;
}

  /* Custom Modal Styles */
.custom-modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
  padding-top: 60px;
}
.custom-modal-content {
  background-color: #fff;
  margin: 5% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 600px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.custom-close {
  color: #e62323;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.custom-close:hover,
.custom-close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}




/* Custom Color Palette */


</style>


<body class="">
  <?php   
   if(isset($_POST['logout'])){
    session_destroy();
    header("location: index.php");
   }
?>








