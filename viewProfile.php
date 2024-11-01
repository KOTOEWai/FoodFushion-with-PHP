<?php
session_start();
include('./db.php');

// Image compression function (optional)
function compressImage($source, $destination, $quality) {
  // Get image info
  $imgInfo = getimagesize($source);
  $mime = $imgInfo['mime'];

  // Create a new image from the file based on its type
  switch ($mime) {
      case 'image/jpeg':
          $image = imagecreatefromjpeg($source);
          break;
      case 'image/png':
          $image = imagecreatefrompng($source);
          break;
      case 'image/webp':
          $image = imagecreatefromwebp($source);
          break;
      default:
          return false; // Unsupported image type
  }

  // Save the compressed image to the destination path
  imagejpeg($image, $destination, $quality);

  // Free up memory
  imagedestroy($image);

  return true;
}

// Insert recipe
if(isset($_POST['create'])) {
    $image = $_FILES['image'];
    $title = trim($_POST['title']);
    $ingredients = trim($_POST['ingredients']);
    $steps = trim($_POST['steps']);
    $cuisine = trim($_POST['cuisine']);
    $dietary = trim($_POST['dietary']);
    $level = $_POST['level'];
    $id = $_SESSION['user_array']['user_id'];

    $imgName = $image['name'];
    $imgTmp = $image['tmp_name'];
    $imgSize = $image['size'];
    $imgError = $image['error'];
    $allowedExt = ['jpg', 'png', 'jpeg'];

    $fileEx = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    if(in_array($fileEx, $allowedExt)) {
        if($imgError === 0 && $imgSize <= 1000000) {
            $newfilename = uniqid('', true) . '.' . $fileEx;
            $filePath = "upload/" . basename($newfilename);

            // Compress and upload the image
            compressImage($imgTmp, $filePath, 75); // Compress image to 75% quality

            // Use prepared statements for database insertion
            $stmt = $conn->prepare("INSERT INTO recipes (image, title, ingredients, steps, cuisine_type, dietary_preferences, difficulty_level, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $newfilename, $title, $ingredients, $steps, $cuisine, $dietary, $level, $id);

            if($stmt->execute()) {
                echo '<script>alert("Successfully Created");</script>';
    echo "<script type='text/javascript'> document.location = 'recipe.php'; </script>";
            } else {
                die('Error: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            echo 'File is too large or there was an error uploading';
        }
    } else {
        echo 'Invalid file type. Only JPG, PNG, and JPEG are allowed.';
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

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("UPDATE recipes SET title=?, ingredients=?, steps=?, cuisine_type=?, dietary_preferences=?, difficulty_level=? WHERE recipe_id=?");
    $stmt->bind_param("ssssssi", $title, $ingredients, $steps, $cuisine, $dietary, $level, $recipe_id);

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

                // Compress and upload the image
                compressImage($imgTmp, $filename, 75);

                $stmt = $conn->prepare("UPDATE recipes SET image=? WHERE recipe_id=?");
                $stmt->bind_param("si", $newfilename, $recipe_id);
                $stmt->execute();
            } else {
                echo 'File is too large or there was an error uploading';
                exit;
            }
        } else {
            echo 'Invalid file type';
            exit;
        }
    }

    if ($stmt->execute()) {
        echo '<script>alert("Successfully Updated");</script>';
        header('Location: viewProfile.php');
    } else {
        die('Error: ' . $stmt->error);
    }
    $stmt->close();
}

?>
<?php
if (isset($_GET['delete_id'])) {
    $recipe_id = $_GET['delete_id'];

    // Use prepared statement to fetch image path
    $stmt = $conn->prepare("SELECT image FROM recipes WHERE recipe_id = ?");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = 'upload/' . $row['image'];

        // Use prepared statement to delete the recipe
        $stmt = $conn->prepare("DELETE FROM recipes WHERE recipe_id = ?");
        $stmt->bind_param("i", $recipe_id);

        if ($stmt->execute()) {
            // Remove the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            echo '<script>alert("Recipe deleted successfully");</script>';
        } else {
            echo '<script>alert("Error: ' . $stmt->error . '");</script>';
        }
    }
    header('Location: viewProfile.php'); // Redirect to avoid re-submission
    $stmt->close();
}

?>

<?php
if (isset($_POST['update_photo'])) {
    $user_id = $_SESSION['user_array']['user_id'];
    $profile_image = $_FILES['profile_image'];

    // Handle image upload
    $imgName = $profile_image['name'];
    $imgTmp = $profile_image['tmp_name'];
    $imgSize = $profile_image['size'];
    $imgError = $profile_image['error'];

    // Allowed file extensions
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $file_ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

    // Check file type
    if (in_array($file_ext, $allowed_ext)) {
        if ($imgError === 0 && $imgSize <= 1000000) { // Ensure file is less than 1MB
            $new_filename = uniqid('', true) . '.' . $file_ext;
            $file_destination = "upload/" . $new_filename;

            // Compress and move uploaded file
            if (compressImage($imgTmp, $file_destination, 75)) {
                // Use prepared statement to update database
                $stmt = $conn->prepare("UPDATE users SET image = ? WHERE user_id = ?");
                $stmt->bind_param("si", $new_filename, $user_id);

                if ($stmt->execute()) {
                    $_SESSION['user_array']['image'] = $new_filename;
                    echo '<script>alert("Profile photo updated successfully!");</script>';
                    header("Location: viewProfile.php");
                    exit();
                } else {
                    echo 'Error: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                echo 'Error compressing the image.';
            }
        } else {
            echo 'File size too large or error uploading the file.';
        }
    } else {
        echo 'Invalid file type. Only JPG, JPEG, PNG, and WebP are allowed.';
    }
}

?>

<?php
$current_user_id = $_SESSION['user_array']['user_id'];
$profile_user_id =$_SESSION['user_array']['user_id'];  // Replace this with the user_id of the profile you're viewing

// Check if current user is following this profile


// Count followers
$followersQuery = "SELECT COUNT(*) AS follower_count FROM followers WHERE following_id = '$profile_user_id'";
$followersResult = mysqli_query($conn, $followersQuery);
$followersCount = mysqli_fetch_assoc($followersResult)['follower_count'];

// Count following
$followingQuery = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_id = '$profile_user_id'";
$followingResult = mysqli_query($conn, $followingQuery);
$followingCount = mysqli_fetch_assoc($followingResult)['following_count'];
?>



<script>

function confirmDelete(recipeId) {
    if (confirm('Are you sure you want to delete this recipe?')) {
        window.location.href = 'viewProfile.php?delete_id=' + recipeId;
    }
}
</script>





<?php
include('./header.php');


?>
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
           margin-top: 1rem; /* Adjust position */
           
            left: 0px; 
            /* Adjust position */
        }
        .proname{
            font-size:30px;
        }
        .profile-sidebar {
      background-color: #f8f9fa;
      padding: 10px;
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
<div class="container-fluid p-0  ">
  <div class="d-flex justify-content-center align-items-center  profile-section" >
    <img src="./upload/<?php echo $_SESSION['user_array']['image']; ?>" class="profile-img img-fluid" alt="Profile Picture" id="profileImage" data-toggle="modal" data-target="#uploadModal">
    <p class="ms-3 mt-5 pt-5 proname">
      <?php echo $_SESSION['user_array']['first_name'] . ' ' . $_SESSION['user_array']['last_name']; ?>
    </p>
  </div>
</div>

<!-- Modal for uploading profile photo -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Upload Profile Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="viewProfile.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="profile_image">Select a file:</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control" required>
          </div>
          <button type="submit" name="update_photo" class="btn btn-primary">Update Photo</button>
        </form>
      </div>
    </div>
  </div>
</div>



</div>
<div class="container-fluid mb-4  ">
<div class="d-flex justify-content-center mt-5 pe-5 mb-3 ">
<?php   
$user_id = $_SESSION['user_array']['user_id'];
$query = "SELECT * FROM recipes WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
if (!$result) {
   die('Error: ' . mysqli_error($conn));
}
$countrecipe = mysqli_num_rows($result);
       ?>
<p class="fs-5 ">Recipes  <span class="badge bg-danger"><?php echo $countrecipe; ?></p>
<?php
     }?>
<p class="fs-5 ps-5 ">Followers:   <span class="badge bg-danger"> <?php echo $followersCount; ?></span> </p>
    <p class="fs-5 ps-5">Following:   <span class="badge bg-danger"> <?php echo $followingCount; ?> </span></p>
    </div>
      <!-- Sidebar for Intro Section -->
 <div class="col-lg-12 col-md-12  ">

       <div  class="d-md-flex ">
        <div class="text-center mb-0 col-lg-2  profile-sidebar bg-dark-subtle rounded-3">
        <p class="text-dark">Lives in <?php echo $_SESSION['user_array']['address'] ?></p>
        <p class="text-dark">From <?php echo $_SESSION['user_array']['address'] ?></p>
        <p class="text-dark">Work at Foodfusion</p>
        <a href="editProfile.php?update_id=<?php echo $_SESSION['user_array']['user_id'] ?>"   class=" bg-light btn-block text-decoration-none text-black  pt-2 pb-2 rounded">Edit Profile</a>
        <button class="btn  bg-light btn-block mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Recipe</button>
          <!-- Button to Open the Modal -->
<button type="button" class="btn bg-light btn-block" data-bs-toggle="modal" data-bs-target="#uploadResourceModal">
  Upload New Resource
</button>
 <a href="friend.php"   class=" bg-light btn-block  pt-2 pb-2 rounded text-black  text-decoration-none">View Friends</a>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $resource_type = $_POST['resource_type'];
  $title = $_POST['title'];
  $text = $_POST['text'];
  $user_id = $_SESSION['user_array']['user_id'];

  // File upload handling (Resource file)
  $target_dir = "rescourcesfile/";
  $file_name = basename($_FILES["file"]["name"]);
  $target_file = $target_dir . $file_name;

  // Image upload handling
  $image = $_FILES['image'];
  $img_name = basename($image['name']);
  $target_image = $target_dir . $img_name;

  if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["image"]["tmp_name"], $target_image)) {
    
    // Insert data into the database
    $insertQuery = "INSERT INTO resources (user_id, resource_type, title, text, file_path, image_path) 
                    VALUES ('$user_id', '$resource_type', '$title', '$text', '$target_file', '$target_image')";
    
    $res = mysqli_query($conn, $insertQuery);

    if ($res == true) {
      echo '<script>alert("Resources Uploaded successfully!");</script>';

     
    } else {
      echo "Error: " . mysqli_error($conn); // Show detailed error
    }

  } else {
    echo "File upload failed!";
  }
}

?>

<!-- Modal with Upload Form -->
<div class="modal fade" id="uploadResourceModal" tabindex="-1" aria-labelledby="uploadResourceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadResourceModalLabel">Upload New Resource</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="viewProfile.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="resource_type">Resource Type</label>
            <input type="text" name="resource_type" id="resource_type" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="text">Description</label>
            <textarea name="text" id="text" class="form-control" required></textarea>
          </div>
          
          <div class="form-group">
            <label for="file">Upload File</label>
            <input type="file" name="file" id="file" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
          </div>

          <button type="submit" class="btn btn-primary">Upload Resource</button>
        </form>
      </div>
    </div>
  </div>
</div>


   <a class="btn bg-light btn-block mt-2" href="fav.php">Favourite recipes 
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


 <div class="col-lg-10  mt-5">
 <p class="text-center bg-dark-subtle shawdow p-3 ">Manage Recipe Details</p>

<div class="table-responsive ">
<table class="table col-sm-6 ">
 <thead >
   <tr >
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
$countrecipe = mysqli_num_rows($result);

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
 <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $recipe['recipe_id']; ?>">Edit</button>
 
 <button class="btn btn-outline-danger mt-1 " onclick="confirmDelete(<?php echo $recipe['recipe_id']; ?>)">Delete</button>
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

</div>
        </div>
<!-- Modal -->



  </div>

</div>



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
$user_id = $_SESSION['user_array']['user_id'];
$query = "SELECT * FROM recipes WHERE user_id = '$user_id'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
?>
   
   
      
    

 <p class="text-center bg-dark-subtle shawdow p-3 mt-5  container">You created Recipes</p>
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
</body>

  


  
    <!-- Bootstrap JS (Optional) -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
