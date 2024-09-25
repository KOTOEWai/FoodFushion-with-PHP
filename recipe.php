<?php
session_start();
include('./header.php');

// Search logic
$cuisine_type = isset($_POST['cuisine_type']) ? mysqli_real_escape_string($conn, $_POST['cuisine_type']) : '';
$dietary_preferences = isset($_POST['dietary_preferences']) ? mysqli_real_escape_string($conn, $_POST['dietary_preferences']) : '';
$difficulty_level = isset($_POST['difficulty_level']) ? mysqli_real_escape_string($conn, $_POST['difficulty_level']) : '';

// Modify the query based on search inputs
$query = "SELECT re.*, u.first_name, u.last_name
          FROM recipes re
          JOIN users u ON re.user_id = u.user_id
          WHERE 1"; // Always true condition to append search filters

if (!empty($cuisine_type)) {
    $query .= " AND re.cuisine_type LIKE '%$cuisine_type%'";
}

if (!empty($dietary_preferences)) {
    $query .= " AND re.dietary_preferences LIKE '%$dietary_preferences%'";
}

if (!empty($difficulty_level)) {
    $query .= " AND re.difficulty_level = '$difficulty_level'";
}
if (!empty($title)) {
    $query .= " AND re.title = '$title'";
}


$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
?>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['recipe_id'])) {
      // Get the logged-in user's ID
      $user_id = $_SESSION['user_array']['user_id'];  // Ensure the user is logged in
      $recipe_id = mysqli_real_escape_string($conn, $_POST['recipe_id']);

      // Check if the recipe is already favorited by this user
      $query = "SELECT * FROM save WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) == 0) {
          // Insert into the favorites table
          $query = "INSERT INTO save (user_id, recipe_id) VALUES ('$user_id', '$recipe_id')";
          if (mysqli_query($conn, $query)) {
              $message = "Recipe saved as favorite!";
          } else {
              $message = "Failed to save the recipe: " . mysqli_error($conn);
          }
      } else {
          $message = "!Oh, Sorry , This recipe is already saved. ";
      }

      // Optional: Redirect back to the same page with a message
      header("Location: recipe.php?message=" . urlencode($message));
      exit();
  }
}
?>


<!-- Navbar Section -->
<div class="container-fluid sticky-top">
    <nav class="navbar navbar-expand-lg bg-dark">
        <a class="navbar-brand" href="index.php">
            <span>Feane</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Culinary Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="recipe.php">Recipe Collection</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="community_cookbook.php">Community Cookbook</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Contact Us</a>
                </li>
            </ul>

            <?php if (isset($_SESSION['user_array'])) { ?>
                <button type="button" class="btn" onclick="location.href='viewProfile.php?view_id=<?php echo $_SESSION['user_array']['user_id'] ?>'">
                    <img src="./upload/<?php echo $_SESSION['user_array']['image']; ?>" width="80" height="80" class="rounded-circle pe-auto" alt="profile">
                </button>
            <?php } ?>
        </div>
    </nav>
</div>
<?php if (isset($_GET['message'])): ?>
    <?php
    $message = $_GET['message'];
    $class = strpos($message, 'Recipe saved as favorite!') !== false ? 'alert-success' : (strpos($message, 'Failed to save the recipe:') !== false ? 'alert-danger' : 'alert-danger');
    ?>
    <div class="alert <?= $class ?> text-center text-black ">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="close " data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" >&times;</span>
        </button>
    </div>
<?php endif; ?>

<h1 class="text-center mt-2">Find Recipes</h1>
<!-- Search Form -->
<div class="container mt-4">
    <form method="POST" action="recipe.php" class="row mb-4">
        <div class="col-md-3">
            <input type="text" name="cuisine_type" class="form-control" placeholder="Search by Cuisine" value="<?php echo htmlspecialchars($cuisine_type); ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="dietary_preferences" class="form-control" placeholder="Search by Dietary Preferences" value="<?php echo htmlspecialchars($dietary_preferences); ?>">
        </div>
        <div class="col-md-3">
            <select name="difficulty_level" class="form-control">
                <option value="">Select Difficulty</option>
                <option value="Easy" <?php if ($difficulty_level == 'Easy') echo 'selected'; ?>>Easy</option>
                <option value="Medium" <?php if ($difficulty_level == 'Medium') echo 'selected'; ?>>Medium</option>
                <option value="Hard" <?php if ($difficulty_level == 'Hard') echo 'selected'; ?>>Hard</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="container mt-4">
        <div class="row">
            <?php while ($recipe = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-3 col-sm-5 mb-4">
                    <div class="card h-80 w-100 shadow-sm">
                        <div class="position-relative">
                 <form method="POST" action="recipe.php">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
                <button type="submit" class=" position-absolute top-0 end-0 border-0 bg-body ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-heart-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                    </svg>
                </button>

                            <a href="Eachrecipe.php?recipe_id=<?php echo $recipe['recipe_id']; ?>">
                                <img src="./upload/<?php echo $recipe['image']; ?>" class="card-img-top img-fluid" alt="Recipe Image" style="height: 180px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="card-body bg-light-subtle">
                            <h5 class="card-title"><?php echo $recipe['title']; ?></h5>
                        </div>
                        <div class=" text-center mb-4">
                         
                         

  
  
 
<div class="warpper">
    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#recipeModal<?php echo $recipe['recipe_id']; ?>"><i class="animation"></i>Read More<i class="animation"></i>
    </button>
</div>
                        </div>
                    </div>
                </div>

                <!-- Recipe Modal -->
                <div class="modal fade" id="recipeModal<?php echo $recipe['recipe_id']; ?>" tabindex="-1" aria-labelledby="recipeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="recipeModalLabel"><?php echo $recipe['title']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <img src="./upload/<?php echo $recipe['image']; ?>" class="img-fluid mb-3" style="height: 200px; object-fit: cover;" alt="Recipe Image">
  <hr class="my-3">
  <p class="recipe-info text-justify "> <img src="./images/cooking_15243715.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"> <strong class=" mb-3 text-uppercase fw-medium ps-3 ">   Ingredients:</strong> <br > <?php echo $recipe['ingredients']; ?></p>
<hr class="my-3"> <!-- Add this line -->
<p class="recipe-info  text-justify"><img src="./images/goal_1032095.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium ps-3  ">Steps:</strong> <br><?php echo $recipe['steps']; ?></p>
<hr class="my-3">
  <p class="recipe-info  text-justify"><img src="./images/portion_10008903.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium ps-3 ">Cuisine Type:</strong><br> <?php echo $recipe['cuisine_type']; ?></p>
  <hr class="my-3">
  <p class="recipe-info  text-justify"><img src="./images/vegan_6194123.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium ps-3">Dietary Preferences:</strong><br> <?php echo $recipe['dietary_preferences']; ?></p>
  <hr class="my-3">
  <p class="recipe-info  text-justify"><img src="./images/level-up.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium  ps-3">Difficulty Level:</strong><br> <?php echo $recipe['difficulty_level']; ?></p>
  <hr class="my-3">
  <p class="recipe-info  text-justify"><img src="./images/add.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium  ps-3">Created by:</strong><br> <?php echo $recipe['first_name']; ?></p>
  <hr class="my-3">
  <p class="recipe-info  text-justify"><img src="./images/Time.png" alt="" class="img-fluid mb-3" style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium  ps-3">Created on:</strong><br> <?php echo $recipe['created_at']; ?></p>
                            </div>

         <div class="modal-footer">
        
        
        <form method="POST" action="recipe.php">
        <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
         

 <button type="submit" class="bookmarkBtn">
  <span class="IconContainer">
    <svg viewBox="0 0 384 512" height="0.9em" class="icon">
      <path
        d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"
      ></path>
    </svg>
  </span>
  <p class="text pt-3">Save</p>
</button>


</form>
      </div>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php else: ?>
    <div class="container mt-4">
        <div class="alert alert-danger text-center">No recipes found.</div>
    </div>
<?php endif; ?>

<?php include('./footer.php'); ?>

<style>
    /* From Uiverse.io by mi-series */ 
    
.warpper .btn {
  outline: 0;
  display: inline-flex;
  align-items: center;
  justify-content: space-between;
  background: orange;
  min-width: 200px;
  border: 0;
  border-radius: 4px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
  box-sizing: border-box;
  padding: 10px 15px;
  color: #fff;
  font-size: 16px;
  font-weight: 500;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  overflow: hidden;
  cursor: pointer;
}

.warpper  .btn:hover {
  opacity: .95;
}

.warpper .btn .animation {
  border-radius: 100%;
  animation: ripple 0.6s linear infinite;
}

@keyframes ripple {
  0% {
    box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.1), 0 0 0 20px rgba(255, 255, 255, 0.1), 0 0 0 40px rgba(255, 255, 255, 0.1), 0 0 0 60px rgba(255, 255, 255, 0.1);
  }

  100% {
    box-shadow: 0 0 0 20px rgba(255, 255, 255, 0.1), 0 0 0 40px rgba(255, 255, 255, 0.1), 0 0 0 60px rgba(255, 255, 255, 0.1), 0 0 0 80px rgba(255, 255, 255, 0);
  }
}
/* From Uiverse.io by vinodjangid07 */ 
.bookmarkBtn {
  width: 100px;
  height: 40px;
  border-radius: 40px;
  border: 1px solid rgba(255, 255, 255, 0.349);
  background-color: rgb(12, 12, 12);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition-duration: 0.3s;
  overflow: hidden;
}

.IconContainer {
  width: 30px;
  height: 30px;
  background: linear-gradient(to bottom, rgb(255, 136, 255), rgb(172, 70, 255));
  border-radius: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  z-index: 2;
  transition-duration: 0.3s;
}

.icon {
  border-radius: 1px;
}

.text {
  height: 100%;
  width: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  z-index: 1;
  transition-duration: 0.3s;
  font-size: 1.04em;
}

.bookmarkBtn:hover .IconContainer {
  width: 90px;
  transition-duration: 0.3s;
}

.bookmarkBtn:hover .text {
  transform: translate(10px);
  width: 0;
  font-size: 0;
  transition-duration: 0.3s;
}

.bookmarkBtn:active {
  transform: scale(0.95);
  transition-duration: 0.3s;
}

</style>