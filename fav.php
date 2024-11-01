<?php
 session_start();
include('./header.php')

?>
<!-- Navbar Section -->


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
          if($recipe){
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
        }}
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