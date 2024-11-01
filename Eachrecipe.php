<?php
ob_start(); // Start output buffering
include('./header.php');
?>

 <style>
   .star-rating {
      display: flex;
      direction: row-reverse;
      justify-content: center;
    }

    .star-rating input {
      display: none;
    }

    .star-rating label {
      font-size: 2.5rem;
      color: lightgray;
      cursor: pointer;
    }

    .star-rating input:checked ~ label {
      color: #ff8800;
    }

    .star-rating input:hover ~ label {
      color: #ff8800;
    }

    .rating-text {
      margin-top: 10px;
      font-weight: bold;
      text-align: center;
      color: #333;
    }
    .rating-summary {
      text-align: center;
    }

    .star-rating {
      font-size: 1rem;
      color: #ffcc00;
    }

    .progress {
      height: 10px;
      margin-top: 5px;
    }

    .progress-bar {
      background-color: #ffcc00;
    }

    .rating-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 5px;
    }

    .rating-text {
      font-weight: bold;
      margin-left: 10px;
    }
    .user-image {
    width: 80px;  /* Adjust this value to make the image smaller */
    height: 80px; /* Keep it proportional or set a fixed size */
}

  </style>

<?php
function displayStarRating($rating) {
  $fullStars = floor($rating);
  $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
  $emptyStars = 5 - $fullStars - $halfStar;

  $stars = str_repeat('<i class="fas fa-star"></i>', $fullStars); // Full stars
  if ($halfStar) {
      $stars .= '<i class="fas fa-star-half-alt"></i>'; // Half star
  }
  $stars .= str_repeat('<i class="far fa-star"></i>', $emptyStars); // Empty stars

  return $stars;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $recipe_id = $_POST['recipe_id'];
  $user_id = $_POST['user_id'];
  $rating = isset($_POST['rating']) && $_POST['rating'] !== "" ? mysqli_real_escape_string($conn, $_POST['rating']) : NULL;
  $comment = isset($_POST['comment']) && $_POST['comment'] !== "" ? mysqli_real_escape_string($conn, $_POST['comment']) : NULL;

  // Check if the user has already rated the recipe
  $checkQuery = "SELECT * FROM recipe_ratings WHERE recipe_id = '$recipe_id' AND user_id = '$user_id'";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
      // User has already rated, so update the existing record
      $updateQuery = "UPDATE recipe_ratings SET ";
      if ($rating !== NULL) {
          $updateQuery .= "rating = '$rating', ";
      } 
      $updateQuery .= "comment = '$comment' WHERE recipe_id = '$recipe_id' AND user_id = '$user_id'";
       
      if (mysqli_query($conn, $updateQuery)) {
          echo '<script type="text/javascript">';
          echo 'alert("Successfully updated rating");';
          echo '</script>';
          header("Location: Eachrecipe.php?recipe_id=$recipe_id");
          exit;
      } else {
          echo "Error: " . mysqli_error($conn);
      }
  } else {
      // User hasn't rated yet, so insert a new record
      $insertQuery = "INSERT INTO recipe_ratings (recipe_id, user_id, rating, comment) VALUES ('$recipe_id', '$user_id', " . ($rating !== NULL ? "'$rating'" : "NULL") . ", '$comment')";
      
      if (mysqli_query($conn, $insertQuery)) {
          header("Location: Eachrecipe.php?recipe_id=$recipe_id"); // Redirect back with success
          exit;
      } else {
          echo "Error: " . mysqli_error($conn);
      }
  }
}


?>

<?php
if (isset($_GET['recipe_id'])) 
  $recipe_id = $_GET['recipe_id'];
       $query = "SELECT recipes.*, users.first_name, users.last_name, users.image as user_image 
          FROM recipes 
          JOIN users ON recipes.user_id = users.user_id 
          WHERE recipe_id = '$recipe_id'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
          $recipe = mysqli_fetch_assoc($result);
        
          // New query to calculate average rating and distribution
          $ratingQuery = "SELECT AVG(rating) AS avg_rating,
                              COUNT(rating) AS total_ratings,
                              SUM(rating = 5) AS five_star,
                              SUM(rating = 4) AS four_star,
                              SUM(rating = 3) AS three_star,
                              SUM(rating = 2) AS two_star,
                              SUM(rating = 1) AS one_star
                             

                          FROM recipe_ratings 
                          WHERE recipe_id = '$recipe_id'";

          $ratingResult = mysqli_query($conn, $ratingQuery);


          $ratingData = mysqli_fetch_assoc($ratingResult);
  
          // Calculate average rating and number of ratings
          $avgRating = round($ratingData['avg_rating'], 1);

          $totalRatings = $ratingData['total_ratings'];
          $fiveStarCount = $ratingData['five_star'];
          $fourStarCount = $ratingData['four_star'];
          $threeStarCount = $ratingData['three_star'];
          $twoStarCount = $ratingData['two_star'];
          $oneStarCount = $ratingData['one_star'];
      
          // Calculate percentages for progress bars
          $fiveStarPercentage = $totalRatings > 0 ? ($fiveStarCount / $totalRatings) * 100 : 0;
          $fourStarPercentage = $totalRatings > 0 ? ($fourStarCount / $totalRatings) * 100 : 0;
          $threeStarPercentage = $totalRatings > 0 ? ($threeStarCount / $totalRatings) * 100 : 0;
          $twoStarPercentage = $totalRatings > 0 ? ($twoStarCount / $totalRatings) * 100 : 0;
          $oneStarPercentage = $totalRatings > 0 ? ($oneStarCount / $totalRatings) * 100 : 0;
        
        
    ?>
<!-- Navbar Section -->



<style>
  .star-rating {
    display: flex;
    direction: row-reverse;
    justify-content: center;
  }
  .star-rating input {
    display: none;
  }
  .star-rating label {
    font-size: 2rem;
    color: lightgray;
    cursor: pointer;
  }
  .star-rating input:checked ~ label {
    color: #ff8800;
  }
  .star-rating input:hover ~ label {
    color: #ff8800;
  }
  .rating-text, .rating-summary {
    text-align: center;
  }
  .progress {
    height: 10px;
    margin-top: 5px;
  }
  .rating-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
  }
  .user-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
  }

  @media (max-width: 767px) {
    .user-image {
      width: 50px;
      height: 50px;
    }
    .star-rating label {
      font-size: 1.5rem;
    }
  }
</style>

<div class="container mt-4">
  <div class="row">
    <!-- Recipe Image and Details -->
    <div class="col-lg-8 col-md-7 col-sm-12 mb-3">
      <div class="card">
        <img src="./upload/<?php echo $recipe['image']; ?>" class="card-img-top img-fluid" alt="Recipe Image" style="max-height: 430px; object-fit: cover;">
        <div class="card-body">
          <p>
            <strong>Cuisine Type:</strong> <?php echo $recipe['cuisine_type']; ?><br><br>
            <strong>Dietary Preferences:</strong> <?php echo $recipe['dietary_preferences']; ?><br><br>
            <strong>Difficulty Level:</strong> <?php echo $recipe['difficulty_level']; ?><br><br>
            <strong>Ingredients:</strong>
            <ul>
              <?php
              $ingredients = explode("\n", $recipe['ingredients']);
              foreach ($ingredients as $ingredient) {
                echo "<li>" . htmlspecialchars($ingredient) . "</li>";
              }
              ?>
            </ul>
            <strong>Steps:</strong>
            <ol>
              <?php
              $steps = explode("\n", $recipe['steps']);
              foreach ($steps as $step) {
                echo "<li>" . htmlspecialchars($step) . "</li>";
              }
              ?>
            </ol>
          </p>
        </div>
      </div>
    </div>

    <!-- User Info and Rating Form -->
    <div class="col-lg-4 col-md-5 col-sm-12">
      <div class="p-4 bg-light rounded">
        <strong>Created By</strong>
        <div class="d-flex align-items-center">
          <img src="./upload/<?php echo $recipe['user_image']; ?>" class="rounded-circle me-3 user-image" alt="User Image">
          <div>
            <h6 class="mb-0"><?php echo $recipe['first_name']; ?></h6>
            <small class="text-muted">Community member</small>.ogd
            <a href="personal_pf.php?user_id=<?php echo $recipe['user_id']; ?>" class="d-block text-danger" target="_blank">www.profile.com/foodfushion/</a>
          </div>
        </div>

        <p class="mt-2">
          "Sweeten your day with a coffee! Treat yourself to a sweet delight with this recipe. Let us know how you liked it!"
        </p>
      </div>
      <?php

if (isset($_SESSION['user_array'])) {
    ?>    

      <?php

      if ($recipe['user_id'] !== $_SESSION['user_array']['user_id'] ): ?>
      <div class="border mt-4 p-4 rounded">
        <h3 class="text-center"><?php echo $recipe['title']; ?></h3>
        <form action="Eachrecipe.php" method="POST">
          <div class="mb-4">
            <label for="rating" class="form-label"><strong>Rate Us</strong></label>
            <div class="star-rating flex-row-reverse">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
                <label for="star<?php echo $i; ?>">★</label>
              <?php endfor; ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="review" class="form-label"><strong>My Review (Optional)</strong></label>
            <textarea class="form-control" id="review" name="comment" rows="3" placeholder="Share your thoughts or leave blank."></textarea>
          </div>

          <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
          <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_array']['user_id']; ?>">

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="location.href='recipe.php'">Cancel</button>
            <button type="submit" class="btn btn-primary">Rate</button>
          </div>
        </form>
      </div>
      <?php endif; ?>
        <?php
}?>
    </div>
  </div>
</div>

        

 <div class=" p-4 pt-5 col-lg-8 container">
        <div class="rating-summary">
            <div class="star-rating">
                <?php echo displayStarRating($avgRating); ?>
                <span class="text-muted pe-4" style="font-size: 1rem;"><?php echo $avgRating . " out of 5"; ?></span>
            </div>
            <p class="mt-2"><?php echo $totalRatings . " Ratings"; ?></p>
        </div>

        <!-- Rating Distribution -->
        <div class="rating-bar">
            <span>5 star ★</span>
            <div class="progress flex-grow-1 mx-3">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $fiveStarPercentage; ?>%" aria-valuenow="<?php echo $fiveStarPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span><?php echo $fiveStarCount; ?></span>
        </div>

        <div class="rating-bar">
            <span>4 star ★</span>
            <div class="progress flex-grow-1 mx-3">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $fourStarPercentage; ?>%" aria-valuenow="<?php echo $fourStarPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span><?php echo $fourStarCount; ?></span>
        </div>

        <div class="rating-bar">
            <span>3 star ★</span>
            <div class="progress flex-grow-1 mx-3">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $threeStarPercentage; ?>%" aria-valuenow="<?php echo $threeStarPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span><?php echo $threeStarCount; ?></span>
        </div>

        <div class="rating-bar">
            <span>2 star ★</span>
            <div class="progress flex-grow-1 mx-3">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $twoStarPercentage; ?>%" aria-valuenow="<?php echo $twoStarPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span><?php echo $twoStarCount; ?></span>
        </div>

        <div class="rating-bar">
            <span>1 star ★</span>
            <div class="progress flex-grow-1 mx-3">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $oneStarPercentage; ?>%" aria-valuenow="<?php echo $oneStarPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span><?php echo $oneStarCount; ?></span>
        </div>
       
    </div>
    
<?php
if (isset($_GET['recipe_id'])) 
    $recipe_id = $_GET['recipe_id']; // Assume recipe_id is passed in the URL
// Query to fetch user ratings and comments for the recipe
$query = "
    SELECT rr.rating, rr.comment, rr.created_at, u.first_name, u.last_name, u.image AS user_image
    FROM recipe_ratings rr
    JOIN users u ON rr.user_id = u.user_id
    WHERE rr.recipe_id = '$recipe_id'
    ORDER BY rr.created_at DESC"; // Order by most recent

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    // Prepare an array to hold all the user reviews
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
}
?>
  <?php

if (isset($_SESSION['user_array'])) {
    ?>    
<div class="container col-lg-6 ">
    <h3>User Reviews</h3>
    <?php if (!empty($reviews)): ?>
        <ul class="list-unstyled">
            <?php foreach ($reviews as $review): ?>
                <li class="media mb-4 p-3 border rounded bg-danger-subtle">
                    <img src="./upload/<?php echo htmlspecialchars($review['user_image']); ?>" alt="User image" class="mr-3 rounded-circle" style="width: 55px; height: 54px;">
                    <div class="media-body">
                        <h5 class="mt-0 mb-1"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></h5>
                        
                        <!-- Display Star Rating -->
                        <div class="star-rating">
                            <?php
                            // Function to display star ratings
                            echo displayStarRating($review['rating']);
                            ?>
                        </div>
                        
                        <p class="text-muted small">Reviewed on <?php echo date('F j, Y', strtotime($review['created_at'])); ?></p>
                        <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    </div>
            
                    <div class="media-footer d-flex justify-content-end align-items-end">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Write your reply here..." aria-label="comment">
                            <button class="btn btn-danger ms-3 rounded-1" type="button">Reply</button>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reviews yet. Be the first to review this recipe!</p>
    <?php endif; ?>
</div>
<?php } ?>
              </div>      
    </div>
</div>

    <?php
            }
        
    ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const ratingInputs = document.querySelectorAll(".star-rating input");
  const ratingText = document.getElementById("rating-text");

  ratingInputs.forEach(input => {
    input.addEventListener("change", () => {
      const ratingValue = input.value;
      let ratingDescription = "Select a rating";
      
      switch (ratingValue) {
        case "5": ratingDescription = "Love it"; break;
        case "4": ratingDescription = "Like it"; break;
        case "3": ratingDescription = "It's ok"; break;
        case "2": ratingDescription = "Don't like it"; break;
        case "1": ratingDescription = "Could not eat it"; break;
        case "0": ratingDescription = "No rating"; break;
      }

      ratingText.innerText = ratingDescription;
    });
  });
});
</script>

<?php
include('./footer.php');
?>
<?php
ob_end_flush(); // Send the output buffer and stop buffering
?>

