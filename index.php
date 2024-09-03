<?php 

include('./header.php')

?>
<?php 

include 'db.php';
$errorEmail = "";
$errorPassword = "";
$errorFirst="";
$errorLastName="";

if(isset($_POST['register'])){
    $firstName=$_POST['firstName'];
    $lastName=$_POST['lastName'];
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    $enpassword = md5(trim($password)); 

    $isValid = true;
    if (empty($email)) {
        $errorEmail = "Email is required";
        $isValid = false;

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format";
        $isValid = false;
    }
    if (empty($password)) {
        $errorPassword = "Password is required";
        $isValid=false;
    }
    if (empty($firstName)) {
        $errorFirst = "FirstName is required";
        $isValid=false;
    }
    if (empty($lastName)) {
        $errorLastName = "LastName is required";
        $isValid=false;
    }
    if($isValid){ 
    $insertQuery="INSERT INTO users(first_name,last_name,email,password) VALUES ('$firstName','$lastName','$email','$enpassword')";
     $res= mysqli_query($conn,$insertQuery);
     if($res==true){
            header("Location: login.php");
        }else{
            die('Error:'.mysqli_error($conn));
        }
     }
    }
?>

<section class="intro-section text-center mb-5  bg-dark">
    <div class="container">
        <h1 class="display-4 text-white">Welcome to FoodFusion</h1>
        <p class="lead text-white">Promoting home cooking and culinary creativity among food enthusiasts. Join us in exploring a world of flavors!</p>
       <?php
if (!isset($_SESSION['user_array'])) {
    
?>
        <button class="btn btn-primary ml-2" id="signUpBtn" data-toggle="modal" data-target="#signUpModal">Join us</button>
    </div>
</section>

<div id="signUpModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">


    <h5 class="modal-title" id="signUpModalLabel">Join Us</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="header.php" method="post">
                    <div class="form-group">
                        <label for="registerFirstName">First Name:</label>
                        <input type="text" class="form-control" id="registerFirstName" name="firstName" placeholder="Enter your first name" required>
                        <small class="text-danger"><?php echo $errorFirst; ?></small>
                    </div>
                    <div class="form-group">
                        <label for="registerLastName">Last Name:</label>
                        <input type="text" class="form-control" id="registerLastName" name="lastName" placeholder="Enter your last name" required>
                        <small class="text-danger"><?php echo $errorLastName; ?></small>
                    </div>
                    <div class="form-group">
                        <label for="registerEmail">Email:</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Enter your email" required>
                        <small class="text-danger"><?php echo $errorEmail; ?></small>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Password:</label>
                        <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Create a password" required>
                        <small class="text-danger"><?php echo $errorPassword; ?></small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="register">Join Now</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php 
} 
?>


<video class="w-100" autoplay loop muted>
  <source src="./upload/Burmese Beef Khaowsuey (KhaowSay) Recipe  By Food Fusion Bakra Eid Special.mp4"type="video/mp4" />
</video>

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
        <h2 class="text-center mb-4">Featured Recipes & Trends</h2>
        <div class="row">
<div class="col-md-4 mb-4">
    <div class="card shadow-sm">
        <img src="./upload/image/noodle.webp" class="card-img-top" alt="Recipe 1">
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





<script>
    // Get the modal
// Get the modals
var veganModal = document.getElementById("veganModal");

// Get the button that opens the pasta modal
var openVegan = document.getElementById("openVegan");

// Get the <span> element that closes the pasta modal
var closeVegan = document.getElementById("closeVegan");

// When the user clicks the button, open the pasta modal
openVegan.onclick = function() {
    veganModal.style.display = "block";
}

// When the user clicks on <span> (x), close the pasta modal
closeVegan.onclick = function() {
    veganModal.style.display = "none";
}

// When the user clicks anywhere outside of the pasta modal, close it
window.onclick = function(event) {
    if (event.target == veganModal) {
        veganModal.style.display = "none";
    }
}


// Get the modals
var pastaModal = document.getElementById("pastaModal");

// Get the button that opens the pasta modal
var openPastaModal = document.getElementById("openPastaModal");

// Get the <span> element that closes the pasta modal
var closePastaModal = document.getElementById("closePastaModal");

// When the user clicks the button, open the pasta modal
openPastaModal.onclick = function() {
    pastaModal.style.display = "block";
}

// When the user clicks on <span> (x), close the pasta modal
closePastaModal.onclick = function() {
    pastaModal.style.display = "none";
}

// When the user clicks anywhere outside of the pasta modal, close it
window.onclick = function(event) {
    if (event.target == pastaModal) {
        pastaModal.style.display = "none";
    }
}

// Get the modal
var chocolateModal = document.getElementById("chocolateModal");

// Get the button that opens the modal
var openChocolateModal = document.getElementById("openChocolateModal");

// Get the <span> element that closes the modal
var closeChocolateModal = document.getElementById("closeChocolateModal");

// When the user clicks the button, open the modal
openChocolateModal.onclick = function() {
    chocolateModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeChocolateModal.onclick = function() {
    chocolateModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == chocolateModal) {
        chocolateModal.style.display = "none";
    }
}

</script>
    
    



<style>
.slideimg{
    width: 800px;
    height: 750px;
}
</style>
<?php 
include('./footer.php')

?>