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
            <strong>Cuisine Type:</strong> <?php echo $recipe['cuisine_type']; ?><br>
            <strong>Dietary Preferences:</strong> <?php echo $recipe['dietary_preferences']; ?><br>
            <strong>Difficulty Level:</strong> <?php echo $recipe['difficulty_level']; ?><br>
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
            <small class="text-muted">Community member</small>
          </div>
        </div>

        <p class="mt-2">
          "Sweeten your day with a coffee! Treat yourself to a sweet delight with this recipe. Let us know how you liked it!"
        </p>
      </div>

      <?php if ($recipe['user_id'] !== $_SESSION['user_array']['user_id']): ?>
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
    </div>
  </div>
</div>
<?php
include('./footer.php');
?>
<?php
ob_end_flush(); // Send the output buffer and stop buffering
?>
