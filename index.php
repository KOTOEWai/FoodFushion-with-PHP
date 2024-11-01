<?php 
include('./header.php');
include 'db.php'; // Ensure db.php contains mysqli connection

$errors = []; // Initialize an array to store validation errors
$errMove = '';
$errFile = '';
$errUpload = '';
$errType = '';
if (isset($_POST['register'])) {
    $image = $_FILES['image'];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required.";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    }
    if (empty($firstName) || !preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
        $errors['firstName'] = "First name should only contain letters.";
    }
    if (empty($lastName) || !preg_match("/^[a-zA-Z'-]+$/", $lastName)) {
        $errors['lastName'] = "Last name should only contain letters.";
    }
    if (empty($phone) || !preg_match("/^[0-9]{11}$/", $phone)) {
        $errors['phone'] = "Invalid phone number format. Must be 11 digits.";
    }
    if (empty($address)) {
        $errors['address'] = "Address is required.";
    }
    if (empty($image['name'])) {
        $errors['image'] = "Image is required.";
    }
    if (empty($errors)) {
        $allow = ['jpg', 'png', 'jpeg'];
        $fileEx = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (in_array($fileEx, $allow) && $image['error'] === 0 && $image['size'] <= 1000000) {
            $newfilename = uniqid('', true) . '.' . $fileEx;
            $filename = "upload/" . basename($newfilename);
            if (move_uploaded_file($image['tmp_name'], $filename)) {
                // Use prepared statements
                $stmt = $conn->prepare("INSERT INTO users (image, first_name, last_name, phone, address, email, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bind_param("sssssss", $newfilename, $firstName, $lastName, $phone, $address, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    echo '<script>alert("Successfully Registered");</script>';
                } else {
                    die('Error: ' . $stmt->error);
                }
            } else {
                $errMove = "There was a problem moving the uploaded file.";
            }
        } else {
            $errType = "Cannot upload files of this type. Only jpg, png, jpeg are allowed.";
        }
    }
}
?>

<!-- Display error messages -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Modals and other HTML content remain unchanged -->

<?php
if (!isset($_SESSION['user_array'])) {
?>
<section class="intro-section text-center  ">
 <div class="container ">
         <h1 class="display-4 ">Welcome to FoodFusion</h1>
        <small class="text-danger"> <i class="text-danger"><?php echo $errMove  ?></i>
               <i class="text-danger"><?php echo $errFile  ?></i>
               <i class="text-danger"><?php echo $errUpload ?></i>
               <i class="text-danger"><?php echo $errType  ?></i></small>
        <div>
        <button class="btn btn-primary" id="joinusBtn" data-toggle="modal" data-target="#joinusModal">Join us</button>
         <button class="btn btn-primary " id="signUpBtn" data-toggle="modal" data-target="#signUpModal">Login</button>
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
      
                <button type="submit" value="Login" name="login" class="btn btn-primary btn-block mt-3">Login</button>
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
                    <button type="submit" class="btn btn-primary btn-block mt-2" name="register">Join Now</button>
         </form>
            </div>
        </div>
    </div>
</div>
   <main>
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 m-auto">
                <div class="heroText">
                    <h1 class="text-white mb-lg-5 mb-3">Delicious Recipes</h1>
                    <div class="c-reviews my-3 d-flex flex-wrap align-items-center">
                        <div class="d-flex flex-wrap align-items-center">
                            <h4 class="text-white mb-0 me-3">4.4/5</h4>
                            <div class="reviews-stars">
                                <i class="fas fa-star reviews-icon"></i>
                                <i class="fas fa-star reviews-icon"></i>
                                <i class="fas fa-star reviews-icon"></i>
                                <i class="fas fa-star reviews-icon"></i>
                                <i class="fas fa-star-half-alt reviews-icon"></i>
                            </div>
                        </div>
                        <p class="text-white w-100">From <strong>1,206+</strong> Reviews</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <div id="carouselExampleCaptions" class="carousel carousel-fade hero-carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="carousel-image-wrap">
                                <img src="images/slide/jay-wennington-N_Y88TWmGwA-unsplash.jpg" class="img-fluid carousel-image" alt="">
                            </div>                            
                            <div class="carousel-caption">
                                <span class="text-white">
                                    <i class=" me-2"></i>
                                   RECIPE
                                </span>
                                <h4 class="hero-text">Food Fushion</h4>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-image-wrap">
                                <img src="images/slide/jason-leung-O67LZfeyYBk-unsplash.jpg" class="img-fluid carousel-image" alt="">
                            </div>                           
                            <div class="carousel-caption">
                                <div class="d-flex align-items-center">
                                    <h4 class="hero-text">Steak</h4>                                 
                                </div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="reviews-text mb-0 me-3">3.8/5</h5>
                                    <div class="reviews-stars">
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-image-wrap">
                                <img src="images/slide/ivan-torres-MQUqbmszGGM-unsplash.jpg" class="img-fluid carousel-image" alt="">
                            </div>                         
                            <div class="carousel-caption">
                                <div class="d-flex align-items-center">
                                    <h4 class="hero-text">Sausage Pasta</h4>           
                                </div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="reviews-text mb-0 me-3">4.2/5</h5>
                                    <div class="reviews-stars">
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                        <i class="fas fa-star reviews-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H3.707l10.147 10.146a.5.5 0 0 1-.708.708L3 3.707V8.5a.5.5 0 0 1-1 0z"/>
</svg>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M14 2.5a.5.5 0 0 0-.5-.5h-6a.5.5 0 0 0 0 1h4.793L2.146 13.146a.5.5 0 0 0 .708.708L13 3.707V8.5a.5.5 0 0 0 1 0z"/>
</svg>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="video-wrap">
        <video autoplay="" loop="" muted="" class="custom-video" poster="">
            <source src="video/production_ID_3769033.mp4" type="video/mp4">
              Your browser does not support the video tag.
        </video>
    </div>
    <div class="overlay"></div>
</section>
</main>
<h3 class="section-title text-center text-success mt-5">Best Rated Recipe</h3>
<div class="container   d-sm-flex d-lg-flex  mt-4 ">
<?php
$query = "SELECT recipes.*, users.first_name, users.last_name, users.image AS user_image, 
AVG(recipe_ratings.rating) AS avg_rating, COUNT(recipe_ratings.rating) AS total_ratings 
FROM recipes 
JOIN users ON recipes.user_id = users.user_id
LEFT JOIN recipe_ratings ON recipes.recipe_id = recipe_ratings.recipe_id
GROUP BY recipes.recipe_id
ORDER BY avg_rating DESC
LIMIT 3";  // Only get top 3 recipes based on rating
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
if (mysqli_num_rows($result) > 0) {
    while ($recipe = mysqli_fetch_assoc($result)) {
        // Fetch average rating for each recipe
        $recipe_id = $recipe['recipe_id'];
        $ratingQuery = "SELECT AVG(rating) AS avg_rating, COUNT(rating) AS total_ratings 
                        FROM recipe_ratings 
                        WHERE recipe_id = '$recipe_id'
                        ";
        $ratingResult = mysqli_query($conn, $ratingQuery);

        if ($ratingResult) {
            $ratingData = mysqli_fetch_assoc($ratingResult);
            $avg_rating = round($ratingData['avg_rating'], 1);
            $total_ratings = $ratingData['total_ratings'];
        } else {
            $avg_rating = 0;
            $total_ratings = 0;
        }
?>
<div class="card me-2 mt-3 shadow-lg" style="width: 28rem;">
    <img src="./upload/<?php echo $recipe['image']; ?>" class="card-img-top img-fluid " style="height: 180px; object-fit: cover;" alt="Recipe Image">
    <div class="card-body">
        <h5 class="card-title"><?php echo $recipe['title']; ?></h5>
        <p class="card-text">Created By: <?php echo $recipe['first_name'] . ' ' . $recipe['last_name']; ?></p>
        <p class="card-text">Cuisine: <?php echo $recipe['cuisine_type']; ?></p>
        <p class="card-text">Difficulty: <?php echo $recipe['difficulty_level']; ?></p>
        <p class="card-text">Average Rating: <strong><?php echo $avg_rating; ?></strong> (<?php echo $total_ratings; ?> ratings)</p> 
        <a href="Eachrecipe.php?recipe_id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-primary">View Recipe</a>
    </div>
</div>
<?php
   }
} else {
    echo "<p>No recipes found.</p>";
}
?>
</div>


<div class="container my-5 bg-white">
  <div class="row align-items-center">
    <!-- Text Content Section -->
    <div class="col-md-6">
      <h1>Charred Green Beans With Brown Butter Vinaigrette</h1>
      <p><strong>By Samantha Lande</strong></p>
      <p>These tea-focused Advent calendars are the perfect gift for self-care.</p>
    </div>

    <!-- Image Section -->
    <div class="card border-0  col-md-6">
      <img src="./upload/i1.webp" class="img-fluid" alt="Tea Advent Calendar">
    </div>
  </div>

  <div class="row align-items-center">
  <div class="col-md-6 card border-0 ">
      <img src="./upload/i2.webp" class="img-fluid" alt="Tea Advent Calendar">
    </div>
    <!-- Text Content Section -->
    <div class="col-md-6">
      <h1>How to Cook a Turkey for Thanksgiving </h1>
      <p><strong>By Samantha Lande</strong></p>
      <p>These tea-focused Advent calendars are the perfect gift for self-care in a cup.</p>
    </div>

    <div class="row align-items-center">
    <!-- Text Content Section -->
    <div class="col-md-6">
      <h1>How to Clean and Prep Mussels</h1>
      <p><strong>By Samantha Lande</strong></p>
      <p>These tea-focused Advent calendars are the perfect gift for self-care.</p>
    </div>

    <!-- Image Section -->
    <div class="col-md-6 card border-0 ">
      <img src="./upload/i3.webp" class="img-fluid" alt="Tea Advent Calendar">
    </div>
  </div>
    <!-- Image Section -->
   
  </div>
</div>


<div class="container my-5">
    <!-- Section Title -->
    <h2 class="mb-4">What to cook today</h2>

    <!-- Diet Section -->
    <h4 class="mb-3">Diet</h4>
    <div class="row mb-4">
      <div class="col-md-4 ">
        <div class="recipe-card ">
          <img src="./upload/image/vagan.jpg" class="card-img-left" alt="I eat everything">
          <div class="">
            <p class="ps-3">I eat everything</p>
          </div>
        </div>

      </div>
      <div class="col-md-4">
        <div class=" recipe-card">
          <img src="./images/slide/jason-leung-O67LZfeyYBk-unsplash.jpg" class="card-img-left" alt="Vegetarian">
          <div class="">
            <p class="ps-3">Vegetarian</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="recipe-card">
          <img src="./images/f1.png" class="card-img-left" alt="Vegan">
          <div class="">
            <p class="ps-3">Vegan</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Meal Section -->
    <h4 class="mb-3">Meal</h4>
    <div class="row mb-4">
      <div class="col-md-4">
        <div class=" recipe-card">
          <img src="./images/f9.png" class="card-img-left" alt="Dinner">
          <div class="">
            <p class="ps-3">Dinner</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class=" recipe-card">
          <img src="./images/f4.png" class="card-img-left" alt="Lunch">
          <div class="">
            <p class="ps-3">Lunch</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="recipe-card">
          <img src="./images/slide/jay-wennington-N_Y88TWmGwA-unsplash.jpg" class="card-img-left" alt="Breakfast">
          <div class="">
            <p class="ps-3">Breakfast</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Type Section -->
    <h4 class="mb-3">Type</h4>
    <div class="row mb-4">
      <div class="col-md-4">
        <div class=" recipe-card">
          <img src="./images/f8.png" class="card-img-left" alt="Under 30 mins">
          <div class="card-body">
            <p class="card-text ps-2">Under 30 mins</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class=" recipe-card">
          <img src="./upload/image/tea-salad-close-up-header.jpg" class="card-img-left" alt="Healthy">
          <div class="card-body">
            <p class="card-text ps-3">Healthy</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class=" recipe-card">
          <img src="./upload/image/noodle.webp" class="card-img-left" alt="For Children">
          <div class="card-body">
            <p class="card-text ps-3">For Children</p>
          </div>
        </div>
      </div>
    </div>
  </div>


<section class="news-feed mt-5">
    <div class="container">
        <h2 class="text-center mb-4 text-dark">Featured Recipes & Trends</h2>
        <div class="row">
<div class="col-md-4 mb-4">
    <div class="card shadow-sm">
        <img src="./upload/BA 6270355672Jul 08 2024.webp" class="card-img-top" alt="Recipe 1">
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

    .recipe-card {
  display: flex;
  align-items: center;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.recipe-card img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 15px 0 0 15px;
}

.recipe-card .card-body {
  padding-left: 15px;
}

.card-text {
  font-size: 16px;
  font-weight: 500;
}


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

/* Initial state of the image */
.card img {
    transition: transform 0.3s ease-in-out; /* Smooth transition */
}

/* On hover, apply a scaling effect */
.card:hover img {
    transform: scale(1.1); /* Slightly zoom in */
}
/* Rotate and add shadow on hover */
.card:hover img {
    transform: scale(1.1) rotate(5deg); /* Zoom and rotate slightly */
    box-shadow: 0 4px 8px rgba(0,0,0,0.3); /* Add a shadow */
}
</style>

<?php 
include('./footer.php')

?>