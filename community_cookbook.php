<?php


session_start(); 
include('./db.php');
ob_start();
if (!isset($_SESSION['user_array'])) {
  echo '<script type="text/javascript">';
  echo 'alert(" You need to log in to access this page. Redirecting to the homepage...");';
  echo 'setTimeout(function(){ window.location.href = "index.php"; }, 1500);'; // 3-second delay before redirect
  echo '</script>';
    exit();

    
}



include('./header.php'); 
// Include this after session checks and redirections

?>



<style>
    .overlay-text {
        z-index: 1; /* Ensure the text appears above the video */
    }
    video {
        object-fit: contain;
    }
    
 
    .recipe-card img {
        height: 100px;
        object-fit: cover;
    }

</style>

<section class="community-section text-center ">
    <div class="container">
        <h1 class="display-4">Community Cookbook</h1>
        <p class="lead">Share your favorite recipes and explore new ones from the community!</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Recipe</button>
            <a type="btn" class="btn btn-danger btn-lg"  href="viewProfile.php">Upload Rescources</a>
        </div>
    </div>
</section>



<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Recipe</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="viewProfile.php" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="recipeTitle" class="form-label">Upload Your Recipe Image</label>
            <input type="file" class="form-control" id="recipeimage" name="image" placeholder="Enter Recipe image" required>
          </div>
          <!-- Title Input -->
          <div class="mb-3">
            <label for="recipeTitle" class="form-label">Recipe Title</label>
            <input type="text" class="form-control" id="recipeTitle" name="title" placeholder="Enter Recipe Title" required>
          </div>

          <!-- Ingredients Input -->
          <div class="mb-3">
            <label for="recipeIngredients" class="form-label">Ingredients</label>
            <textarea class="form-control" id="recipeIngredients" name="ingredients" rows="3" placeholder="Enter Ingredients" required></textarea>
          </div>
          <!-- Steps Input -->
          <div class="mb-3">
            <label for="recipeSteps" class="form-label">Steps</label>
            <textarea class="form-control" id="recipeSteps" name="steps" rows="3" placeholder="Enter Cooking Steps" required></textarea>
          </div>

          <!-- Cuisine Type Selection -->
          <div class="mb-3">
            <label for="cuisineType" class="form-label">Cuisine Type</label>
            <input type="text" class="form-control" id="cuisine" name="cuisine" placeholder="Enter Cuisine" required>
          </div>

          <!-- Dietary Preferences Selection -->
          <div class="mb-3">
            <label for="dietaryPreferences" class="form-label">Dietary Preferences</label>
            <input type="text" class="form-control" id="dietary" name="dietary" placeholder="Enter Dietary Preferences" required>
          </div>

          <!-- Difficulty Level Selection -->
          <div class="mb-3">
            <label for="difficultyLevel" class="form-label">Difficulty Level</label>
            <select class="form-select" id="Level" name="level" required>
              <option selected disabled value="">Choose Difficulty Level...</option>
              <option value="Easy">Easy</option>
              <option value="Medium">Medium</option>
              <option value="Hard">Hard</option>
            </select>
          </div>
          <!-- Submit Button -->
          <button type="submit" name="create" class="btn btn-primary">Create Recipe Category</button>
        </form>
      </div>
      </div>
    
    </div>
  </div>



<?php
$query = "
    SELECT r.recipe_id, r.title, r.dietary_preferences, r.image, u.first_name, u.last_name , u.image As user_image
    FROM shares s
    JOIN recipes r ON s.recipe_id = r.recipe_id
    JOIN users u ON s.user_id = u.user_id
    ORDER BY s.share_date DESC
";

// Execute the query
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<div class="container mt-5">
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
          
            <div class="col">

                <div class="card shadow-lg border-0 ">
                    <!-- User info -->
                    <div class="card-header bg-info-subtle d-flex align-items-center justify-content-start py-3">
                        <!-- User image -->
                        <img src="upload/<?php echo $row['user_image']; ?>" class="rounded-circle me-3" alt="User Image" style="width: 40px; height: 40px; object-fit: cover;">
                        <!-- User name -->
                        <p class="text-muted mb-0">
                            Shared by: <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
                        </p>
                    </div>

                    <!-- Recipe image -->
                    <a href="Eachrecipe.php?recipe_id=<?php echo $row['recipe_id']; ?>">
                    <img src="upload/<?php echo $row['image']; ?>" class="card-img-top img-fluid" alt="Recipe Image" style="height: 380px; object-fit: cover;">
        </a>
                    <!-- Recipe details -->
                    <div class="card-body ">
                        <!-- Recipe title -->
                        <h5 class="card-title mb-3 text-primary fw-bold"><?php echo htmlspecialchars($row['title']); ?></h5>  
                    </div>
                    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['like_recipe_id'])) {
        $user_id = $_SESSION['user_array']['user_id'];
        $recipe_id = mysqli_real_escape_string($conn, $_POST['like_recipe_id']);

        // Check if the user has already liked the recipe
        $query = "SELECT * FROM likes WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            // Insert a like into the 'likes' table
            $query = "INSERT INTO likes (user_id, recipe_id) VALUES ('$user_id', '$recipe_id')";
            if (mysqli_query($conn, $query)) {
                echo '<script type="text/javascript">';
                echo 'alert("You liked the recipe!");';
                echo 'setTimeout(function() { window.location.href = "community_cookbook.php"; }, 1000);'; // Delay before redirect
                echo '</script>';
            } else {
                echo "Failed to like: " . mysqli_error($conn);
            }
        } else {
            // Remove the like (toggle functionality)
            $query = "DELETE FROM likes WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
            if (mysqli_query($conn, $query)) {
                echo '<script type="text/javascript">';
                echo 'alert("You unliked the recipe!");';
                echo ' window.location.href = "community_cookbook.php"; ';
                echo '</script>';
            } else {
                echo "Failed to unlike: " . mysqli_error($conn);
            }
        }
    }
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['comment_recipe_id']) && isset($_POST['comment_text'])) {
      $user_id = $_SESSION['user_array']['user_id'];
      $recipe_id = mysqli_real_escape_string($conn, $_POST['comment_recipe_id']);
      $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

      // Insert the comment into the 'comments' table
      $query = "INSERT INTO comments (user_id, recipe_id, comment_text) VALUES ('$user_id', '$recipe_id', '$comment_text')";
      mysqli_query($conn, $query);

      // Redirect to avoid resubmission
      header("Location: community_cookbook.php");
      exit;
  }
}
?>              <!-- Like, Comment, Share buttons -->
<div class="card-footer bg-info-subtle d-flex justify-content-around">
    <!-- Like button -->
    <form method="POST" action="community_cookbook.php">
        <input type="hidden" name="like_recipe_id" value="<?php echo $row['recipe_id']; ?>">
        <button type="submit" class="btn btn-outline-danger ">❤️ 
            <?php
                // Query to count the number of likes
                $query = "SELECT COUNT(*) as total_likes FROM likes WHERE recipe_id = '" . $row['recipe_id'] . "'";
                $like_result = mysqli_query($conn, $query);
                $likes = mysqli_fetch_assoc($like_result)['total_likes'];
                echo $likes . " Likes";
            ?>
        </button>
    </form>
    <!-- Comment button -->
    <button class="btn btn-outline-primary ms-2 me-2" data-bs-toggle="collapse" data-bs-target="#commentSection<?php echo $row['recipe_id']; ?>">
        <i class="fas fa-comment"></i> 
        <?php
                // Query to count the number of likes
                $query = "SELECT COUNT(*) as total_comments FROM comments WHERE recipe_id = '" . $row['recipe_id'] . "'";
                $like_result = mysqli_query($conn, $query);
                $likes = mysqli_fetch_assoc($like_result)['total_comments'];
                echo $likes . " comments";
            ?>
    </button>
    <!-- Share button -->
    <button class="btn btn-outline-success">
        <i class="fas fa-share"></i>
        <?php
                // Query to count the number of likes
                $query = "SELECT COUNT(*) as total_shares FROM shares WHERE recipe_id = '" . $row['recipe_id'] . "'";
                $like_result = mysqli_query($conn, $query);
                $likes = mysqli_fetch_assoc($like_result)['total_shares'];
                echo $likes . " shares";
            ?>
    </button>
</div>
<div class="collapse bg-dark-subtle" id="commentSection<?php echo $row['recipe_id']; ?>">
    <form method="POST" action="community_cookbook.php" class="mt-2 mb-2">
        <input type="hidden" name="comment_recipe_id" value="<?php echo $row['recipe_id']; ?>">
        <textarea name="comment_text" class="form-control" rows="2" placeholder="Add a comment..."></textarea>
        <button type="submit" class="btn btn-outline-success mt-2" id="submitCommentButton" onclick="this.disabled=true; this.form.submit();">Post Comment</button>
    </form>

    <!-- Display existing comments -->
    <div class="mt-3 " style="max-height: 150px; overflow-y: auto;">
        <?php
            $comment_query = "SELECT u.first_name, u.last_name, c.comment_text, c.commented_at 
                              FROM comments c 
                              JOIN users u ON c.user_id = u.user_id 
                              WHERE c.recipe_id = '" . $row['recipe_id'] . "' ORDER BY c.commented_at DESC";
            $comment_result = mysqli_query($conn, $comment_query);
            while ($comment_row = mysqli_fetch_assoc($comment_result)) {
        ?>
            <div class="border-bottom pb-2 mb-2 ms-3 " >
                <strong><?php echo htmlspecialchars($comment_row['first_name'] . ' ' . $comment_row['last_name']); ?></strong>
                <p class="mb-0"><?php echo htmlspecialchars($comment_row['comment_text']); ?></p>
                <small class="text-muted"><?php echo htmlspecialchars($comment_row['commented_at']); ?></small>
            </div>
        <?php } ?>
    </div>

</div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



<?php
include('./footer.php');
ob_end_flush();
?>
