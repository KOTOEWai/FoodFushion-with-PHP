<?php
session_start();
include('./db.php');

?>
<?php
if(isset($_POST['create'])){
  $image =$_FILES['image'];
  $title=trim($_POST['title']);
  $ingredients=trim($_POST['ingredients']);
  $steps= trim($_POST['steps']);
  $cuisine =trim($_POST['cuisine']);
  $dietary=trim($_POST['dietary']);
  $level=$_POST['level'];
  $id = $_SESSION['user_array']['user_id'];

  $imgName=$image['name'];
  $imgTmp=$image['tmp_name'];
  $imgSize=$image['size'];
  $imgError=$image['error'];
   $allow =['jpg','png','jpeg'];

   $fileEx=strtolower(pathinfo($imgName,PATHINFO_EXTENSION));
   if(in_array($fileEx,$allow)){
       if($imgError===0){
           if($imgSize<=1000000){
               $newfilename = uniqid('',true).'.'.$fileEx;
               $filename="upload/".basename($newfilename);
               if(move_uploaded_file($imgTmp,$filename)){
                $insertQuery="INSERT INTO recipes(image,title,ingredients,steps,cuisine_type,dietary_preferences,difficulty_level,user_id) 
                VALUES ('$newfilename','$title','$ingredients','$steps','$cuisine','$dietary','$level','$id')";
                $res= mysqli_query($conn,$insertQuery);
         if($res==true){
            echo '<script type="text/javascript">';
            echo 'alert("Succcessfully Created");';
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

?>
<?php

if (isset($_POST['update'])) {
    $recipe_id = $_POST['recipe_id'];
    $title = trim($_POST['title']);
    $ingredients = trim($_POST['ingredients']);
    $steps = trim($_POST['steps']);
    $cuisine = trim($_POST['cuisine']);
    $dietary = trim($_POST['dietary']);
    $level = $_POST['level'];
    $image = $_FILES['image'];
    $imgName = $image['name'];

    // Check if the image is uploaded
    if (!empty($imgName)) {
        $imgTmp = $image['tmp_name'];
        $imgSize = $image['size'];
        $imgError = $image['error'];
        $allow = ['jpg', 'png', 'jpeg'];
        $fileEx = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

        if (in_array($fileEx, $allow)) {
            if ($imgError === 0 && $imgSize <= 1000000) {
                $newfilename = uniqid('', true) . '.' . $fileEx;
                $filename = "upload/" . basename($newfilename);

                if (move_uploaded_file($imgTmp, $filename)) {
                    $updateQuery = "UPDATE recipes SET image='$newfilename', title='$title', ingredients='$ingredients', steps='$steps', cuisine_type='$cuisine', dietary_preferences='$dietary', difficulty_level='$level' WHERE recipe_id='$recipe_id'";
                } else {
                    echo 'There was an error moving the uploaded file';
                    exit;
                }
            } else {
                echo 'File is too large or there was an error uploading';
                exit;
            }
        } else {
            echo 'Invalid file type';
            exit;
        }
    } else {
        $updateQuery = "UPDATE recipes SET title='$title', ingredients='$ingredients', steps='$steps', cuisine_type='$cuisine', dietary_preferences='$dietary', difficulty_level='$level' WHERE recipe_id='$recipe_id'";
    }

    $res = mysqli_query($conn, $updateQuery);

    if ($res) {
      echo '<script type="text/javascript">';
      echo 'alert("Succcessfully Updated");';
      echo '</script>';
        header('Location: viewProfile.php');
    } else {
        die('Error: ' . mysqli_error($conn));
    }
}
?>
<?php
if (isset($_GET['delete_id'])) {
    $recipe_id = $_GET['delete_id'];
    
    // Fetch the recipe to get the image path
    $query = "SELECT image FROM recipes WHERE recipe_id = '$recipe_id'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $imagePath = 'upload/' . $row['image'];
        
        // Delete the recipe from the database
        $deleteQuery = "DELETE FROM recipes WHERE recipe_id = '$recipe_id'";
        if (mysqli_query($conn, $deleteQuery)) {
            // Remove the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            echo '<script>alert("Recipe deleted successfully");</script>';
        } else {
            echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
        }
    }
    header('Location: viewProfile.php'); // Redirect to avoid re-submission
}
?>


<script>

function confirmDelete(recipeId) {
    if (confirm('Are you sure you want to delete this recipe?')) {
        window.location.href = 'viewProfile.php?delete_id=' + recipeId;
    }
}
</script>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
       
        .half-height-img {
            height: 70vh; /* Half viewport height */
            width: 100%; /* Full width */
            object-fit: cover; /* Ensure image covers without distortion */
        }
        .profile-img {
            width: 200px; /* Adjust profile image size */
            height: 200px; /* Adjust profile image size */
            object-fit: cover; /* Make sure the profile image is not distorted */
            border-radius: 50%; /* Make the profile image round */
            bottom: -95px; /* Adjust position */
           
            left: 0px; 
            /* Adjust position */
        }
        .proname{
            font-size:30px;
        }
        .profile-sidebar {
      background-color: #f8f9fa;
      padding: 20px;
    }
    .profileBody{
      
      margin-top: 30px;
    }
   
    
    
    .text-truncate {
      max-width: 150px; /* Set a max width for table cell */
      white-space: nowrap; /* Prevent text from wrapping to next line */
      overflow: hidden; /* Hide any text that overflows */
      text-overflow: ellipsis; /* Display ellipsis (...) for overflowing text */
    }
  </style>
</head>
<body>

<?php

if (isset($_SESSION['user_array'])) {
    
?>
<div class="container-fluid p-0 position-relative ">
  <img src="./upload/image/food-4k-1pf6px6ryqfjtnyr.jpg" class=" half-height-img img-fluid" alt="Cover Photo">
  
  <div class="d-flex justify-content-center align-items-center position-absolute profile-section" style="top: 105%; left: 50%; transform: translate(-50%, -50%);">
    <img src="./upload/<?php echo  $_SESSION['user_array']['image']  ?>" class="profile-img img-fluid" alt="Profile Picture">
    <p class="ms-3 mt-5 pt-5 proname"><?php echo $_SESSION['user_array']['first_name']; echo $_SESSION['user_array']['last_name'];  ?></p>
  </div>
</div>




<div class="container mb-4 pt-5 mt-5 ">
    <div class="row profileBody">
      
      <!-- Sidebar for Intro Section -->
      <div class="col-lg-12 col-md-4  ">
       <div  class="d-flex justify-content-center">
        <div class="text-center mb-3 col-lg-5  profile-sidebar bg-dark-subtle rounded-3">
        <p class="text-dark">Lives in <?php echo $_SESSION['user_array']['address'] ?></p>
        <p class="text-dark">From <?php echo $_SESSION['user_array']['address'] ?></p>
        <p class="text-dark">Work at Foodfusion</p>
          <a class="btn  bg-light btn-block mb-3">Edit Bio</a>
        <a href="editProfile.php?update_id=<?php echo $_SESSION['user_array']['user_id'] ?>"   class=" bg-light btn-block  mb-3 pt-2 pb-2">Edit Profile</a>
          <button class="btn  bg-light btn-block" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Recipe</button>
          <a class="btn bg-light btn-block" href="fav.php"> Your favourite recipes 
    <?php
    $user_id = $_SESSION['user_array']['user_id'];
    $query = "SELECT * FROM save WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('Error: ' . mysqli_error($conn));
    }
    $count = mysqli_num_rows($result);
    ?>
    <span class="badge bg-danger"><?php echo $count; ?></span>
</a>
          </div>
        </div>

      

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
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
</div>


<div class="col-lg-12 col-md-12 col-sm-12 mt-4  ">
    
 <p class="text-center bg-dark-subtle shawdow p-3 ">Manage Recipe Details</p>

 <div class="table-responsive">
 <table class="table col-sm-6">
  <thead>
    <tr>
    <th scope="col">No</th>
      <th scope="col">Image</th>
      <th scope="col">Title</th>
      <th scope="col">Ingredients</th>
      <th scope="col">Steps</th>
      <th scope="col">cuisine_type</th>
      <th scope="col">dietary_preferences</th>
      <th scope="col">difficulty_level</th>
      <th scope="col">Action</th>
     
    </tr>
  </thead>
  <?php   
$user_id = $_SESSION['user_array']['user_id'];
$query = "SELECT * FROM recipes WHERE user_id = '$user_id'";


$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}

      foreach($result as $index => $recipe){
        ?>
  <tbody>
    <tr>
     <td><?php echo $recipe['recipe_id']   ?></td>
       <td><img src="./upload/<?php echo $recipe['image']   ?> " class="w-75 img-fluid"  alt=""></td>
      <td><?php echo $recipe['title']   ?></td>
      <td class="text-truncate"><?php echo $recipe['ingredients']   ?></td>
      <td class="text-truncate"><?php echo $recipe['steps']   ?></td>
      <td><?php echo $recipe['cuisine_type']   ?></td>
      <td><?php echo $recipe['dietary_preferences']   ?></td>
      <td><?php echo $recipe['difficulty_level']   ?></td>
      <td>
  <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $recipe['recipe_id']; ?>">Update</button>
  </td>
  <td>
  <button class="btn btn-outline-danger " onclick="confirmDelete(<?php echo $recipe['recipe_id']; ?>)">Delete</button>
  </td>
<!-- Modal for editing -->
<div class="modal fade" id="editModal<?php echo $recipe['recipe_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Recipe</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="viewProfile.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
          
          <!-- Recipe Image -->
          <div class="mb-3">
            <label for="recipeImage" class="form-label">Update Image</label>
            <input type="file" class="form-control" name="image">
          </div>

          <!-- Recipe Title -->
          <div class="mb-3">
            <label for="recipeTitle" class="form-label">Recipe Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $recipe['title']; ?>" required>
          </div>

          <!-- Ingredients -->
          <div class="mb-3">
            <label for="recipeIngredients" class="form-label">Ingredients</label>
            <textarea class="form-control" name="ingredients" required><?php echo $recipe['ingredients']; ?></textarea>
          </div>

          <!-- Steps -->
          <div class="mb-3">
            <label for="recipeSteps" class="form-label">Steps</label>
            <textarea class="form-control" name="steps" required><?php echo $recipe['steps']; ?></textarea>
          </div>

          <!-- Cuisine -->
          <div class="mb-3">
            <label for="cuisineType" class="form-label">Cuisine Type</label>
            <input type="text" class="form-control" name="cuisine" value="<?php echo $recipe['cuisine_type']; ?>" required>
          </div>

          <!-- Dietary Preferences -->
          <div class="mb-3">
            <label for="dietaryPreferences" class="form-label">Dietary Preferences</label>
            <input type="text" class="form-control" name="dietary" value="<?php echo $recipe['dietary_preferences']; ?>" required>
          </div>

          <!-- Difficulty Level -->
          <div class="mb-3">
            <label for="difficultyLevel" class="form-label">Difficulty Level</label>
            <select class="form-select" name="level" required>
              <option value="Easy" <?php if ($recipe['difficulty_level'] == 'Easy') echo 'selected'; ?>>Easy</option>
              <option value="Medium" <?php if ($recipe['difficulty_level'] == 'Medium') echo 'selected'; ?>>Medium</option>
              <option value="Hard" <?php if ($recipe['difficulty_level'] == 'Hard') echo 'selected'; ?>>Hard</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary" name="update">Update Recipe</button>
        </form>
      </div>
    </div>
  </div>
</div>

     
    </tr>
    <?php }?>
    
  </tbody>
</table>

</div>
<?php
$user_id = $_SESSION['user_array']['user_id'];
$query = "SELECT * FROM recipes WHERE user_id = '$user_id'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
?>
 <p class="text-center bg-dark-subtle shawdow p-3 mt-5 ">You created Recipes</p>
<div class="container mt-5">
    <div class="row">
        <?php while ($recipe = mysqli_fetch_assoc($result)) { ?>
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
                </div>
              
            </div>
        </div>
        <?php } ?>
    </div>
</div>


      </div>
       

    </div>
  </div>
</body>

  <?php

}
    
?>


  
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
