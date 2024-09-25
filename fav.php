<?php
 session_start();
include('./header.php')

?>
<div class="container-fluid">
        <nav class="navbar navbar-expand-lg  bg-dark ">
          <a class="navbar-brand" href="index.php">
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
                <a class="nav-link text-white " href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link  text-white " href="">Culinary Rescources</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white " href="recipe.php">Recipe Collection</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white " href="community_cookbook.php">Community Cookbook</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white " href="">Contact Us</a>
              </li>
            
          

            </ul>
            <?php
if (isset($_SESSION['user_array'])) {
    ?>
    <button type="button" class="btn" onclick="location.href='viewProfile.php?view_id=<?php echo $_SESSION['user_array']['user_id'] ?>'">
        <img src="./upload/<?php echo  $_SESSION['user_array']['image']  ?>" width="80" height="80" class="rounded-circle pe-auto" alt="profile">
    </button>
    <?php
}
?>
          </div>
        </nav>
      </div>


      <?php

if (isset($_POST['recipe_id'])) {
    $recipe_id = $_POST['recipe_id'];
    $user_id = $_SESSION['user_array']['user_id'];

    // Query to remove the recipe from the user's saved recipes
    $query = "DELETE FROM save WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
    $result = mysqli_query($conn, $query);


}
?>


      <?php
$user_id = $_SESSION['user_array']['user_id'];
$query = "SELECT * FROM save WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
?>


<div class="container-fluid mt-5 ">
    <h1 class="text-center ">My Favorite Recipes</h1>
    <div class="row m-3">
        <?php
         if (mysqli_num_rows($result) > 0) {
        while ($save = mysqli_fetch_assoc($result)) {
            $recipe_id = $save['recipe_id'];
            $query2 = "SELECT * FROM recipes WHERE recipe_id = '$recipe_id'";
            $result2 = mysqli_query($conn, $query2);
            $recipe = mysqli_fetch_assoc($result2);
            ?>
         <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="./upload/<?php echo $recipe['image']; ?>" class="card-img-top" alt="Recipe Image" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $recipe['title']; ?></h5>
                    <div style="max-height: 150px; overflow-y: auto;">
                        <p class="card-text">
                            <strong>Ingredients:</strong> <?php echo $recipe['ingredients']; ?><br>
                            <strong>Steps:</strong> <?php echo $recipe['steps']; ?><br>
                            <strong>Cuisine Type:</strong> <?php echo $recipe['cuisine_type']; ?><br>
                            <strong>Dietary Preferences:</strong> <?php echo $recipe['dietary_preferences']; ?><br>
                            <strong>Difficulty Level:</strong> <?php echo $recipe['difficulty_level']; ?>
                        </p>
                    </div>
                    <form action="fav.php" method="post">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
                <button type="submit" class="btn btn-outline-danger mt-2"><img src="./images/Remove Tag.png" alt="" class="img-fluid " style="max-width: 35px; height: auto;"> Unsave</button>
            </form>
                </div>
                
            </div>
        </div>
            <?php
        }
        } else {
         
            echo '<div class="alert alert-danger text-center" role="alert">
<p class="text-center mb-lg-5 mt-lg-5">You don\'t have any saved recipes.</p>
</div>';
            echo '<script type="text/javascript">';
            echo 'alert("Oops! You need to save post");';
            echo 'setTimeout(function(){ window.location.href = "recipe.php"; }, 1500);'; // 3-second delay before redirect
            echo '</script>';
        }
        ?>
    </div>
</div>

<?php
include('./footer.php');
?>