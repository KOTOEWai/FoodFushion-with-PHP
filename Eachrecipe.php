<?php
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
      font-size: 4rem;
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
      font-size: 1.5rem;
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
  </style>
<!-- Navbar Section -->
<div class="container-fluid">
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

<!-- Recipe Details Section -->

<div class="container mt-5">
    <?php
    if (isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];
        $query = "SELECT * FROM recipes WHERE recipe_id = '$recipe_id'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            foreach ($result as $recipe) {
    ?>
    <h1 class="text-center mb-3"><?php echo $recipe['title']; ?> </h1>
                <div class="card mb-5">
                    <img src="./upload/<?php echo $recipe['image']; ?>" class="card-img-top img-fluid" alt="Recipe Image" style="max-height: 500px; object-fit: cover;">

                    <div class="card-body">
                       
                        <p class="card-text ">
                            <strong > <img src="./images/portion_10008903.png" alt="" class="img-fluid  me-3 " style="max-width: 44px; height: auto;">Cuisine Type:</strong> <?php echo $recipe['cuisine_type']; ?><br><br>
                            <strong ><img src="./images/vegan_6194123.png" alt="" class="img-fluid " style="max-width: 44px; height: auto;"><strong class=" pb-3 text-uppercase fw-medium ps-3">Dietary Preferences:</strong> <?php echo $recipe['dietary_preferences']; ?><br><br>
                            <strong ><img src="./images/level-up.png" alt="" class="img-fluid me-3 " style="max-width: 44px; height: auto;">Difficulty Level:</strong> <?php echo $recipe['difficulty_level']; ?><br>
                         
                        </p>
                    </div>

                    <div class="card-body">
                        <h4><img src="./images/cooking_15243715.png" alt="" class="img-fluid " style="max-width: 44px; height: auto;"> <strong class=" mb-3 text-uppercase fw-medium ps-3 "> Ingredients</h4>
                        <ul>
                            <?php
                            $ingredients = explode("\n", $recipe['ingredients']); // assuming ingredients are newline-separated
                            foreach ($ingredients as $ingredient) {
                                echo "<li>" . htmlspecialchars($ingredient) . "</li>";
                            }
                            ?>
                        </ul>

                        <h4 class="mb-3"><img src="./images/goal_1032095.png" alt="" class="img-fluid  me-3 " style="max-width: 44px; height: auto;">Steps</h4>
                        <ol >
                            <?php
                            $steps = explode("\n", $recipe['steps']); // assuming steps are newline-separated
                            foreach ($steps as $step) {
                                echo "<li>" . htmlspecialchars($step) . "</li>";
                            }
                            ?>
                        </ol>
                    </div>

                    <div class=" ">
            <div class="container">
    <div class="p-4">
      <h3 class="text-center"><?php echo $recipe['title']; ?></h3>
      <div class="mb-4">
        <label for="rating" class="form-label"><strong>Rate Us</strong></label>
        <div class="star-rating flex-row-reverse">
          <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="Love it">★</label>
          <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="Like it">★</label>
          <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="It is ok">★</label>
          <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="Don't like it">★</label>
          <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="Could not eat it">★</label>
        </div>
        <div id="rating-text" class="rating-text">Select a rating</div>
      </div>


      <div class="mb-3">
        <label for="review" class="form-label"><strong>My Review</strong></label>
        <div class="review-options">
          <button type="button" class="btn btn-outline-secondary">OK with tweaks</button>
          <button type="button" class="btn btn-outline-secondary">Needs more flavor</button>
          <button type="button" class="btn btn-outline-secondary">Might try again</button>
        </div>
        <textarea class="form-control mt-3" id="review" rows="3" placeholder="What did you think about this recipe? Did you make any changes or notes?"></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <button class="btn btn-outline-secondary">Cancel</button>
        <button class="btn btn-primary">Submit</button>
      </div>
    </div>

  </div>

  <div class="container mt-5">
    <div class="card p-4">
      <div class="rating-summary">
        <!-- Star Rating -->
        <div class="star-rating">
          ★★★★★
          <span class="text-muted" style="font-size: 1rem;">4.6 out of 5</span>
        </div>
        <p>128 Ratings</p>
      </div>

      <!-- Rating Distribution -->
      <div class="rating-bar">
        <span>5 star ★</span>
        <div class="progress flex-grow-1 mx-3">
          <div class="progress-bar" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span>91</span>
      </div>

      <div class="rating-bar">
        <span>4 star ★</span>
        <div class="progress flex-grow-1 mx-3">
          <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span>24</span>
      </div>

      <div class="rating-bar">
        <span>3 star ★</span>
        <div class="progress flex-grow-1 mx-3">
          <div class="progress-bar" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span>9</span>
      </div>

      <div class="rating-bar">
        <span>2 star ★</span>
        <div class="progress flex-grow-1 mx-3">
          <div class="progress-bar" role="progressbar" style="width: 3%" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span>3</span>
      </div>

      <div class="rating-bar">
        <span>1 star ★</span>
        <div class="progress flex-grow-1 mx-3">
          <div class="progress-bar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span>1</span>
      </div>
    </div>
  </div>
                    </div>
                </div>
    <?php
            }
        } else {
            echo "<p>No recipe found for this ID.</p>";
        }
    } else {
        echo "<p>No recipe selected.</p>";
    }
    ?>
</div>
<script>
    const starInputs = document.querySelectorAll('.star-rating input');
    const ratingText = document.getElementById('rating-text');

    starInputs.forEach(star => {
      star.addEventListener('change', function() {
        const rating = this.value;
        switch (rating) {
          case '1':
            ratingText.textContent = "Could not eat it";
            break;
          case '2':
            ratingText.textContent = "Don't like it";
            break;
          case '3':
            ratingText.textContent = "It is ok";
            break;
          case '4':
            ratingText.textContent = "Like it";
            break;
          case '5':
            ratingText.textContent = "Love it";
            break;
          default:
            ratingText.textContent = "Select a rating";
        }
      });
    });
  </script>
<?php
include('./footer.php');
?>

