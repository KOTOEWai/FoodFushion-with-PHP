

<?php 

include 'db.php';

$errorEmail = "";
$errorPassword = "";

if(isset($_POST['register'])){
    $firstName=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $enpassword=md5($password);

    if (empty($email)) {
        $errorEmail = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format";
    }

    if (empty($password)) {
        $errorPassword = "Password is required";
    }
   

   
     $insertQuery="INSERT INTO user(name,email,password) VALUES ('$firstName','$email','$enpassword')";
     $res= mysqli_query($conn,$insertQuery);
     if($res==true){
            header("Location: login.php");
        }else{
            die('Error:'.mysqli_error($conn));
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
<P>toewai</P>
<div class="container register-container">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-center">Register</h2>
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="name" name="name">
                       <i><?php echo $errorEmail; ?></i>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    <i><?php echo $errorPassword ?></i>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                     
                    </div>
                    <button type="submit" name="register" class="btn btn-primary btn-block mt-2">Register</button>
                </form>
                <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: auto;
            padding-top: 50px;
        }
        .card {
            border-radius: 1rem;
        }
    </style>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>