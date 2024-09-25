<?php 

include('./header.php')

?>
<?php 

include 'db.php';


$errFile="";
$errMove="";
$errType="";
$errUpload="";
$errImage = "";

if(isset($_POST['register'])){
    $image =$_FILES['image'];
    $firstName=$_POST['firstName'];
    $lastName=$_POST['lastName'];
    $phone= $_POST['phone'];
    $address =$_POST['address'];
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    $enpassword = md5(trim($password));
    $imgName=$image['name'];
    $imgTmp=$image['tmp_name'];
    $imgSize=$image['size'];
    $imgError=$image['error'];
    $allow =['jpg','png','jpeg'];
    $fileEx=strtolower(pathinfo($imgName,PATHINFO_EXTENSION));

     $errors = []; // Initialize an array to store validation errors

    // Email validation
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }


    // Password validation
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long";
    }

    // Name validation
    if (empty($firstName)) {
        $errors['firstName'] = "First name is required";
    }elseif (!preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
        $errors['firstName'] = "First name should only contain letters and valid characters";
    }

    if (empty($lastName)) {
        $errors['lastName'] = "Last name is required";
    }elseif (!preg_match("/^[a-zA-Z'-]+$/", $lastName)) {
        $errors['lastName'] = "Last name should only contain letters and valid characters";
    }

    // Phone validation
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors['phone'] = "Invalid phone number format.Must be 10 digits.";
    }

    // Address validation
    if (empty($address)) {
        $errors['address'] = "Address is required";
    }
    if(empty($image)){
        $errors['image']="image is required";
    }

       if(empty($errors)){
        if(in_array($fileEx,$allow)){
            if($imgError===0){
                if($imgSize<=1000000){
                    $newfilename = uniqid('',true).'.'.$fileEx;
                    $filename="upload/".basename($newfilename);
                    if(move_uploaded_file($imgTmp,$filename)){
                    $insertQuery="INSERT INTO users(image,first_name,last_name,phone,address,email,password) VALUES ('$newfilename','$firstName','$lastName','$phone','$address','$email','$enpassword')";
                    $res= mysqli_query($conn,$insertQuery);
             if($res==true){
                echo '<script type="text/javascript">';
                echo 'alert("Succcessfully Register");';
                echo '</script>';
             }else{
             die('Error:'.mysqli_error($conn));
             }
             } else{
            $errMove="there was a problem moving the uploaded";
             }
            } else{
             $errFile="File is too large";
            }
            }else{
               $errUpload="There was error for uploading";
             }
             }else{
            $errType= "Cannot upload files of this type. Only provide  jpg,png,jpeg";
         }
    }
   
}

?>
<style>



.hero {
  background-color: #f8f9fa;
}

.hero img {
  margin-bottom: 1.5rem;
}

.latest-proposals img {
  margin: 0.5rem;
  border-radius: 10px;
}




</style>


<?php if (!empty($errors)) { ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error) { ?>

                    <li><?php echo $error; ?></li>

                <?php } ?>
            </ul>
        </div>
    <?php } ?>


   
<section class="intro-section text-center  ">
 <div class="container-fluid ">
   

        <h1 class="display-4 ">Welcome to FoodFusion</h1>
        <small class="text-danger"> <i class="text-danger"><?php echo $errMove  ?></i>
               <i class="text-danger"><?php echo $errFile  ?></i>
               <i class="text-danger"><?php echo $errUpload ?></i>
               <i class="text-danger"><?php echo $errType  ?></i></small>

<?php
if (!isset($_SESSION['user_array'])) {
?>
       
        <div>
        <button class="btn btn-primary" id="joinusBtn" data-toggle="modal" data-target="#joinusModal">Join us</button>
         <button class="btn btn-primary " id="signUpBtn" data-toggle="modal" data-target="#signUpModal">Login</button>
         <!-- Button trigger modal -->


<!-- Modal -->

         </div>
    </div>
</section>
<?php 
} 
?>



<div id="signUpModal" class="modal fade modal1" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel" aria-hidden="true">
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
           
            </div>
        </div>
    </div>
</div>


<div id="joinusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="joinusmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">


    <h5 class="modal-title" id="joinusmodal">Join Us</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

        
          
    <form action="index.php" method="post" enctype="multipart/form-data">
    <div class="form-group text-center position-relative">
        <label for="image" class="">
            <img src="./upload/image/profile.webp" width="100" height="100" class="rounded-5" alt="profile" id="profileImage" style="cursor: pointer;">
         <!-- Plus Icon -->
        </label>
        <input type="file" class="form-control d-none" id="image" name="image">
     <p>Add Your Profile picture </p>
     <small class="text-danger"> <i class="text-danger"><?php echo $errMove  ?></i>
               <i class="text-danger"><?php echo $errFile  ?></i>
               <i class="text-danger"><?php echo $errUpload ?></i>
               <i class="text-danger"><?php echo $errType  ?></i></small>

    </div>

            

                    <div class="form-group">
                        <label for="registerFirstName">First Name:</label>
                        <input type="text" class="form-control" id="registerFirstName" name="firstName" placeholder="Enter your first name" required>
                       
                    </div>
                    <div class="form-group">
                        <label for="registerLastName">Last Name:</label>
                        <input type="text" class="form-control" id="registerLastName" name="lastName" placeholder="Enter your last name" required>
                       
                    </div>
                    <div class="form-group">
                        <label for="registerEmail">Email:</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Enter your email" required>
                       
                    </div>

                    <div class="form-group">
                        <label for="registerPhone">Phone</label>
                        <input type="phone" class="form-control" id="registerphone" name="phone" placeholder="Enter your phone" required>
                       
                    </div>
                    <div class="form-group">
                        <label for="registerAdress">Adress</label>
                        <input type="adress" class="form-control" id="registeraddress" name="address" placeholder="Enter your address" required>
                       
                    </div>

                    <div class="form-group">
                        <label for="registerPassword">Password:</label>
                        <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Create a password" required>
                       
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="register">Join Now</button>
         </form>
            </div>
            <div class="modal-footer">
             
            </div>
        </div>
    </div>
</div>


<div class="hero_area">
    <div class="bg-box">
      <img src="./upload/image/pngtree-vegetarian-recipes-overshoot-banner-image_139051.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              Feane
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item ">
                <a class="nav-link " href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="">Culinary Rescources</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="recipe.php">Recipe Collection</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="community_cookbook.php">Community Cookbook</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="book.html">Contact Us</a>
              </li>
              
             
            </ul>
            <div>
            <?php
if (isset($_SESSION['user_array'])) {
    ?>
 <li class="nav-item pt-4 ">
<form method='post' class="">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                        <li class="nav-item dropdown me-5">

                       <a  href="#"    class="pe-auto">
                       

                       <button type="button" class="btn " data-bs-toggle="modal" data-bs-target="#exampleModal">
                       <img src="./upload/<?php echo  $_SESSION['user_array']['image']  ?>" width="80" height="80" class="rounded-circle pe-auto" alt="profile">
                       </button>
                         </a>

  <!-- The profile Modal --> 
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">Profile Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <img src="./upload/<?php echo  $_SESSION['user_array']['image']  ?>" class="rounded-circle mb-3"  width="100" height="100" alt="Profile Picture">
          </div>
          <h4 class="text-center"><?php echo  $_SESSION['user_array']['first_name']  ?><?php echo  $_SESSION['user_array']['last_name']  ?></h4>
          <p class="text-center text-muted"><?php echo  $_SESSION['user_array']['role']  ?></p>
          
          <hr>
        </div>
        <div class="modal-footer">
        <a href="editProfile.php?update_id=<?php echo $_SESSION['user_array']['user_id'] ?>" type="button" class="btn btn-primary" name="update"><img src="./images/Edit.png" alt="" class="img-fluid" style="max-width: 38px; height: auto;">Edit Profile</a>
        <a href="viewProfile.php?view_id=<?php echo $_SESSION['user_array']['user_id'] ?>" type="submit" value="view" name="view"  class="btn btn-primary"> <img src="./images/view.png" alt="" class="img-fluid " style="max-width: 38px; height: auto;">View Profile</a>
        </div>
      </div>
    </div>
  </div>


                        <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            
                               <?php echo $_SESSION['user_array']['first_name'];  echo $_SESSION['user_array']['last_name'] ?>
                        </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="flex"><a class="dropdown-item" href="#"><p class="bg-warning text-center">Email</p><?php echo $_SESSION['user_array']['email'] ?></a></li>
                                <li class="flex"><a class="dropdown-item" href="#"><p class="bg-warning text-center">Role</p><?php echo $_SESSION['user_array']['role'] ?></a></li>
                                <li class="flex"><a class="dropdown-item" href="#">  <button type="submit" value="Logout" name="logout"  class="btn btn-outline-primary" onclick="return confirm('Are you sure to logout?');"">LogOut</button></a></li>
                               
                            </ul>
                        </li>
                    </ul>
                </div>
               
                </form> 
                
    </li>

  
    <?php 
} 
?>
</div>
          </div>

        </nav>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                     FoodFushion
                    </h1>
                    <p>
                    Welcome to FoodFusion, where the love for home-cooked meals and culinary creativity comes together! We believe that cooking is an art, and every meal is a masterpiece waiting to be created. Whether you're a seasoned chef or a home cook just starting out, our platform is designed to inspire and empower you.
                    </p>
                    <?php
if (!isset($_SESSION['user_array'])) {
?>
                    <div class="btn-box">
                      <a  class="btn1 btn-primary" id="joinusBtn" data-toggle="modal" data-target="#joinusModal">Join us
                      </a>
                    </div>
                    <?php
}
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      FoodFushion
                    </h1>
                    <p>
                    Welcome to FoodFusion, where the love for home-cooked meals and culinary creativity comes together! We believe that cooking is an art, and every meal is a masterpiece waiting to be created. Whether you're a seasoned chef or a home cook just starting out, our platform is designed to inspire and empower you
                    </p>
                    <?php
if (!isset($_SESSION['user_array'])) {
?>
                    <div class="btn-box">
                    <a  class="btn1 btn-primary" id="joinusBtn" data-toggle="modal" data-target="#joinusModal">Join us
                    </a>
                    </div>
                    <?php
}
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                     FoodFushion
                    </h1>
                    <p>
                    Discover unique recipes, share your favorite dishes, and be part of a community that celebrates the joy of cooking. With our diverse collection of recipes and culinary resources, there’s always something new to explore and experiment with. Join us in turning your kitchen into a place of endless possibilities!
                    </p>
                    <?php
if (!isset($_SESSION['user_array'])) {
?>
                    <div class="btn-box">
                    <a  class="btn1 btn-primary" id="joinusBtn" data-toggle="modal" data-target="#joinusModal">Join us
                    </a>
                    </div>

                    <?php
}
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <ol class="carousel-indicators">
            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
            <li data-target="#customCarousel1" data-slide-to="1"></li>
            <li data-target="#customCarousel1" data-slide-to="2"></li>
          </ol>
        </div>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <!-- offer section -->

  <section class="offer_section layout_padding-bottom">
    <div class="offer_container">
      <div class="container ">
        <div class="row">
          <div class="col-md-6  ">
            <div class="box ">
              <div class="img-box">
                <img src="./images/f3.png" class="" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Tasty Thursdays
                </h5>
               
                  <span>Craving pizza? You’ve come to the right place! At FoodFusion, we celebrate pizza in all its delicious forms. Whether you're a fan of classic Margherita, indulgent stuffed crusts, or adventurous gourmet toppings, our pizza recipes and guides will take your love for this iconic dish to the next level.</span> 
            
             
              </div>
            </div>
          </div>
          <div class="col-md-6  ">
            <div class="box ">
              <div class="img-box">
                <img src="images/o2.jpg" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Pizza Days
                </h5>
                
             <span>Craving pizza? You’ve come to the right place! At FoodFusion, we celebrate pizza in all its delicious forms. Whether you're a fan of classic Margherita, indulgent stuffed crusts, or adventurous gourmet toppings, our pizza recipes and guides will take your love for this iconic dish to the next level.</span> 
       
              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end offer section -->

  <!-- food section -->

  <section class="food_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
         TREND FOOD 
        </h2>
      </div>

      

      <div class="filters-content">
        <div class="row grid">
          <div class="col-sm-6 col-lg-4 all pizza">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="./upload/image/food.png" alt="" class="">
                </div>
                <div class="detail-box">
                  <h5>
                    Delicious Pizza
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $20
                    </h6>
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all burger">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f2.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Delicious Burger
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $15
                    </h6>
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all pizza">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f3.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Delicious Pizza
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $17
                    </h6>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all pasta">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f4.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Delicious Pasta
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $18
                    </h6>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all fries">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f5.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    French Fries
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $10
                    </h6>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all pizza">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f6.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Delicious Pizza
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $15
                    </h6>
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all burger">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f7.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Tasty Burger
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $12
                    </h6>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all burger">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f8.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Tasty Burger
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $14
                    </h6>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 all pasta">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="images/f9.png" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    Delicious Pasta
                  </h5>
                  <p>
                    Veniam debitis quaerat officiis quasi cupiditate quo, quisquam velit, magnam voluptatem repellendus sed eaque
                  </p>
                  <div class="options">
                    <h6>
                      $10
                    </h6>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="btn-box">
        <a href="">
          View More
        </a>
      </div>
    </div>
  </section>

  <!-- end food section -->

  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container  ">

      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="images/about-img.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                We Are Feane
              </h2>
            </div>
            <p>
              There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration
              in some form, by injected humour, or randomised words which don't look even slightly believable. If you
              are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in
              the middle of text. All
            </p>
            <a href="">
              Read More
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>




<div class="">

<div id="carouselExampleControls" class="carousel slide  " data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active ">
      <img class="d-block w-100 p-4 slideimg" src="./upload/image/culinaryeventslead.webp" alt="First slide">
  
    </div>
    <div class="carousel-item">
      <img class="d-block w-100 p-4 slideimg " src="./upload/image/corp_jeffersoncamp_cooking+11.17.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100 p-4 slideimg" src="./upload/image/options.webp" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>


<section class="news-feed mt-5">
    <div class="container">
        <h2 class="text-center mb-4 text-dark">Featured Recipes & Trends</h2>
        <div class="row">
<div class="col-md-4 mb-4">
    <div class="card shadow-sm">
        <img src="./upload/image/minced-pork-with-basil-fried-eggphoto-premium-psd_363622-737.jpg" class="card-img-top" alt="Recipe 1">
        <div class="card-body">
            <h5 class="card-title">Delicious Pasta Recipe</h5>
            <p class="card-text">Discover this amazing pasta recipe perfect for any occasion.</p>
            <button class="btn btn-primary" id="openPastaModal">Read More</button>
        </div>
    </div>
</div>

<div id="pastaModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="custom-close" id="closePastaModal">&times;</span>
        <h5 class="modal-title">Delicious Pasta Recipe</h5>
        <img src="./upload/image/noodle.webp" class="img-fluid mb-3" alt="Delicious Pasta Recipe">
        <h6>Recipe Overview</h6>
        <p>This Delicious Pasta Recipe is rich and flavorful, perfect for a hearty meal with family and friends.</p>
        
        <h6>Ingredients</h6>
        <ul>
            <li>200g pasta</li>
            <li>2 tablespoons olive oil</li>
            <li>2 cloves garlic, minced</li>
            <li>1 can diced tomatoes</li>
            <li>1 teaspoon dried basil</li>
            <li>1/2 teaspoon red pepper flakes</li>
            <li>Salt and pepper to taste</li>
            <li>Parmesan cheese, grated (optional)</li>
        </ul>

        <h6>Instructions</h6>
        <ol>
            <li>Cook pasta according to package instructions. Drain and set aside.</li>
            <li>Heat olive oil in a pan over medium heat. Add garlic and cook until fragrant.</li>
            <li>Add diced tomatoes, basil, and red pepper flakes. Simmer for 10 minutes.</li>
            <li>Add cooked pasta to the sauce and toss to coat. Season with salt and pepper.</li>
            <li>Serve with grated Parmesan cheese if desired.</li>
        </ol>

        <h6>Tips</h6>
        <p>Add some cooked vegetables or grilled chicken for extra flavor and nutrition. Adjust the spice level according to your taste.</p>
    </div>
</div>  
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <img src="./upload/image/vagan.jpg" class="card-img-top" alt="Recipe 2">
                    <div class="card-body">
                        <h5 class="card-title">Tasty Vegan Salad</h5>
                        <p class="card-text">A healthy and delicious salad recipe that’s easy to make.</p>
                        <button class="btn btn-primary" id="openVegan">Read More</button>

                    </div>
                </div>
            </div>


<div id="veganModal" class="custom-modal">
    <div class="custom-modal-content">
    <span class="custom-close" id="closeVegan">&times;</span>
        <h5 class="modal-title">Tasty Vegan Salad</h5>
        <img src="./upload/image/vagan.jpg" class="img-fluid mb-3" alt="Tasty Vegan Salad">
        <h6>Recipe Overview</h6>
        <p>This Tasty Vegan Salad is packed with fresh vegetables and a delightful vinaigrette. It's perfect for those looking to enjoy a healthy and delicious meal that's easy to prepare.</p>
        
        <h6>Ingredients</h6>
        <ul>
            <li>1 cup of mixed greens</li>
            <li>1/2 cup of cherry tomatoes, halved</li>
            <li>1/4 cup of sliced cucumbers</li>
            <li>1/4 cup of shredded carrots</li>
            <li>1/4 cup of avocado, diced</li>
            <li>1 tablespoon of olive oil</li>
            <li>1 tablespoon of balsamic vinegar</li>
            <li>Salt and pepper to taste</li>
        </ul>

        <h6>Instructions</h6>
        <ol>
            <li>In a large bowl, combine the mixed greens, cherry tomatoes, cucumbers, shredded carrots, and avocado.</li>
            <li>In a small bowl, whisk together the olive oil and balsamic vinegar.</li>
            <li>Drizzle the dressing over the salad and toss to coat.</li>
            <li>Season with salt and pepper to taste.</li>
            <li>Serve immediately and enjoy!</li>
        </ol>

        <h6>Tips</h6>
        <p>For added crunch, top the salad with some roasted nuts or seeds. You can also substitute the balsamic vinegar with lemon juice for a tangier flavor.</p>
    </div>
</div>    
<div class="col-md-4 mb-4">
    <div class="card shadow-sm">
        <img src="./upload/image/download.jpg" class="card-img-top" alt="Recipe 3">
        <div class="card-body">
            <h5 class="card-title">Chocolate Cake</h5>
            <p class="card-text">Indulge in this rich and moist chocolate cake recipe.</p>
            <button class="btn btn-primary" id="openChocolateModal">Read More</button>
        </div>
    </div>
</div>
<div id="chocolateModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="custom-close" id="closeChocolateModal">&times;</span>
        <h5 class="modal-title">Chocolate Cake</h5>
        <img src="./upload/image/download.jpg" class="img-fluid mb-3" alt="Chocolate Cake">
        <h6>Recipe Overview</h6>
        <p>This Chocolate Cake is rich, moist, and perfect for any special occasion. Follow this simple recipe to create a delicious treat.</p>
        
        <h6>Ingredients</h6>
        <ul>
            <li>1 3/4 cups all-purpose flour</li>
            <li>1 1/2 cups granulated sugar</li>
            <li>3/4 cup cocoa powder</li>
            <li>1 1/2 teaspoons baking powder</li>
            <li>1 1/2 teaspoons baking soda</li>
            <li>1 teaspoon salt</li>
            <li>2 large eggs</li>
            <li>1 cup milk</li>
            <li>1/2 cup vegetable oil</li>
            <li>2 teaspoons vanilla extract</li>
            <li>1 cup boiling water</li>
        </ul>

        <h6>Instructions</h6>
        <ol>
            <li>Preheat the oven to 350°F (175°C). Grease and flour two 9-inch round cake pans.</li>
            <li>In a large bowl, combine flour, sugar, cocoa powder, baking powder, baking soda, and salt.</li>
            <li>Add eggs, milk, oil, and vanilla extract. Beat on medium speed until smooth.</li>
            <li>Stir in boiling water (batter will be thin). Pour batter evenly into prepared pans.</li>
            <li>Bake for 30-35 minutes, or until a toothpick inserted in the center comes out clean.</li>
            <li>Cool in pans for 10 minutes, then remove from pans and cool completely on a wire rack.</li>
        </ol>

        <h6>Tips</h6>
        <p>For a richer flavor, add a layer of chocolate ganache or your favorite frosting. You can also mix in chocolate chips for extra indulgence.</p>
    </div>
</div>
 </div>
 </div>
</section>
</body>




<script>
  // General Modal Handling
function handleModal(modalId, openBtnId, closeBtnId) {
  const modal = document.getElementById(modalId);
  const openBtn = document.getElementById(openBtnId);
  const closeBtn = document.getElementById(closeBtnId);

  openBtn.onclick = function () {
    modal.style.display = "block";
  }

  closeBtn.onclick = function () {
    modal.style.display = "none";
  }

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}

// Initialize modals
handleModal("pastaModal", "openPastaModal", "closePastaModal");
handleModal("veganModal", "openVegan", "closeVegan");
handleModal("chocolateModal", "openChocolateModal", "closeChocolateModal");

</script>

    



<style>
.slideimg{
    width: 800px;
    height: 750px;
}
.carousel-item img {
    height: 500px; /* Adjust height as needed */
    object-fit: cover; /* Ensures the image covers the carousel item */
  }
  .carousel-caption {
    bottom: 20px; /* Position caption closer to the bottom */
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

.intro-section {
    background-color: #f8f9fa;
    padding: 60px 0;
}
.carousel-item img {
    height: 500px;
    object-fit: cover;
}
.news-feed .card img {
    height: 200px;
    object-fit: cover;
}

/* Custom Color Palette */


</style>

<?php 
include('./footer.php')

?>