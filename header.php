<?php  

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('./db.php');

$error = "";
$errorEmail = "";
$errorPassword = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
        $errorEmail = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format";
    }
    if (empty($password)) {
        $errorPassword = "Password is required";
    }
    
    $password = md5(trim($password)); 

    $user = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $user_count = mysqli_num_rows($user);

    if ($user_count === 1) {
        $userFetch = mysqli_fetch_assoc($user);
        $_SESSION['user_array'] = $userFetch;

        // Set the cookie before any output
        setcookie("user_email", $email, time() + (7 * 24 * 60 * 60), "/");

        // Redirect to another page to check the cookie
        if ($userFetch['role'] == 'user') {
            header('Location: index.php');
            exit;
        } else {
            echo "Login error";
        }
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion</title>

    <!-- Use only Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/tooplate-crispy-kitchen.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style>

.primary-color {
    background-color: #FF6F61;
    color: white;
}
.secondary-color {
    background-color: #28A745;
    color: white;
}
.accent-color {
    color: #FFC107;
}
.text-dark {
    color: #252c33;
}
.bg-light-gray {
    background-color: #F8F9FA;
}
.sliver{
  background-color: #FF5733;
}

  /* Custom Modal Styles */
.custom-modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
  padding-top: 60px;
}
.custom-modal-content {
  background-color: #fff;
  margin: 5% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 600px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.custom-close {
  color: #e62323;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.custom-close:hover,
.custom-close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}


.cookie-consent {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.95);
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
    padding: 20px;
    display: none; /* Initially hidden */
    z-index: 1000;
    text-align: center;
    
}


.cookie-content {
    max-width: 500px;
    margin: 0 auto;
}

.cookie-actions {
    margin: 15px 20px;
}

.btn-accept {
    background-color: gold;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-weight: bold;
}

.btn-refuse {
    margin-left: 10px;
    color: red;
    text-decoration: none;
}
.navbar-nav .nav-link.active {
            background-color: #ff6f61;
            color: white !important;
            border-radius: 5px;
        }
        .navbar-brand {
            color: #ff6f61;
            font-weight: bold;
        }


</style>


<body class="">
  <?php   
   if(isset($_POST['logout'])){
    session_destroy();
    header("location: index.php");
   }
?>


<div id="cookie-consent" class="cookie-consent">
    <div class="cookie-content">
        <h2>Do you like cookies?</h2>
        <p>Cookies for the purpose of analysis: We and our partners analyze how our website is being used in order
         to better fulfill customer requests and offer specific services.</p>
        <p>Cookies for marketing purposes: We would like to measure the range and effectiveness of our marketing measures 
            and increase their efficiency.</p>
        <div class="cookie-actions">
            <button id="accept-cookies" class="btn-accept">Accept</button>
            <a href="#" class="btn-refuse">Refuse cookies / Manage Trackers</a>
        </div>
        <p>You have the right to object at any time or to change your settings. More about this can be found in our
     <a href="#">privacy policy</a> and detailed <a href="#">cookie information</a>.</p>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">FoodFusion</a>

        <!-- Toggler for mobile view -->
        <a class="navbar-toggler border-0" type="" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </a>

        <!-- Collapsible content -->
        <div class="collapse navbar-collapse ms-3" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Active link check -->
                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                
                <!-- Home -->
                <li class="nav-item  ">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link <?php echo ($current_page == 'recipe.php') ? 'active' : ''; ?>" href="recipe.php">
                        RecipeCollection</a>
                </li>

                <!-- Community Cookbook -->
                <li class="nav-item ">
                    <a class="nav-link <?php echo ($current_page == 'community_cookbook.php') ? 'active' : ''; ?>" href="community_cookbook.php">
                        CommunityCookbook</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link <?php echo ($current_page == 'educational.php') ? 'active' : ''; ?>" href="educational.php">
                        EducationalRescources</a>
                </li>
                

                 <li class="nav-item ">
                    <a class="nav-link <?php echo ($current_page == 'culinary.php') ? 'active' : ''; ?>" href="culinary.php">
                        CulinaryRescources</a>
                </li>
                <!-- Contact Us -->
                <li class="nav-item ">
                    <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact Us</a>
                </li>
                  <!-- About Us -->
                  <li class="nav-item ">
                    <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="about.php">
                        About Us</a>
                </li>
            </ul>

            <!-- Right-aligned content -->
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_array'])): ?>
                    <!-- Profile and Logout -->
                    <li class="nav-item ">
                        <a class="nav-link  " href="viewProfile.php" id=" " role="button" > 
                            <img src="./upload/<?php echo $_SESSION['user_array']['image']; ?>" width="50" height="50" class="rounded-circle" alt="profile">
                           <span>  <?php echo $_SESSION['user_array']['first_name']; ?></sp> 
                        </a>
                    </li>
                    <li class="nav-item"><form method="post" class="nav-link">
                        <button type="submit" name="logout" class="mt-2">Logout</button>
                    </form></li>

                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<!-- JavaScript to handle navbar toggle -->






<script>
document.addEventListener("DOMContentLoaded", function() {
    const cookieConsent = document.getElementById("cookie-consent");
    const acceptButton = document.getElementById("accept-cookies");

    // Check if cookies have been accepted
    if (!localStorage.getItem("cookiesAccepted")) {
        cookieConsent.style.display = "block"; // Show the consent banner
    }

    acceptButton.addEventListener("click", function() {
        localStorage.setItem("cookiesAccepted", "true"); // Store user's consent
        cookieConsent.style.display = "none"; // Hide the consent banner
    });
});
</script>

<!-- Bootstrap JavaScript and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-QJHtvGhmr9ZO7Px5FvRyfnHU0UX7SIl3x5s9Hu5zHcq7/u6k2mbpRLZ9pYNFauB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-rbsA2VBKQW9soYP8y4vX0b9fUTR1qBpPoOt6DE2dbxUqAqk6g3g6caEl6iM91RIZ" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
