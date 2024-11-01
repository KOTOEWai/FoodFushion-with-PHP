<?php
session_start();
include('./db.php');

// Ensure user is logged in
if (!isset($_SESSION['user_array']['user_id'])) {
    echo '<script type="text/javascript">';
    echo 'alert("Oops! You need to log in to access this page. Redirecting to the homepage...");';
    echo 'setTimeout(function(){ window.location.href = "index.php"; }, 1500);'; // 1.5-second delay before redirect
    echo '</script>';
    exit;
}

if (isset($_GET['user_id'])) {
    $profile = $_GET['user_id']; // Use this to fetch user recipes

    // Fetch user details using prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $profile);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();

    // Check if user exists
    if (!$user) {
        die('User not found.');
    }

    // Fetch recipes of the user
    $recipesQuery = "SELECT * FROM recipes WHERE user_id = ?";
    $stmt = $conn->prepare($recipesQuery);
    $stmt->bind_param("i", $profile);
    $stmt->execute();
    $recipesResult = $stmt->get_result();
    $countrecipe = $recipesResult->num_rows;
} else {
    die('User ID not provided.');
}

// Handle follow functionality
if (isset($_POST['follow'])) {
    $follower_id = $_SESSION['user_array']['user_id'];
    $following_id = $_POST['following_id'];

    // Check if already following
    $stmt = $conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Not following, insert the new relationship
        $stmt = $conn->prepare("INSERT INTO followers (follower_id, following_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $follower_id, $following_id);
        if ($stmt->execute()) {
            echo '<script>alert("Successfully Followed!");</script>';
        } else {
            echo 'Error: ' . $stmt->error;
        }
    }
    // Redirect back to the profile
    header('Location: personal_pf.php?user_id=' . $following_id);
    exit;
}

// Handle unfollow functionality
if (isset($_POST['unfollow'])) {
    $follower_id = $_SESSION['user_array']['user_id'];
    $following_id = $_POST['following_id'];

    // Remove the relationship
    $stmt = $conn->prepare("DELETE FROM followers WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $follower_id, $following_id);
    if ($stmt->execute()) {
        echo '<script>alert("Successfully Unfollowed!");</script>';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    // Redirect back to the profile
    header('Location: personal_pf.php?user_id=' . $following_id);
    exit;
}

// Current logged-in user ID
$current_user_id = $_SESSION['user_array']['user_id'];
$profile_user_id = $profile;

// Check if current user is following this profile
$stmt = $conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND following_id = ?");
$stmt->bind_param("ii", $current_user_id, $profile_user_id);
$stmt->execute();
$followResult = $stmt->get_result();
$isFollowing = $followResult->num_rows > 0;

// Count followers
$stmt = $conn->prepare("SELECT COUNT(*) AS follower_count FROM followers WHERE following_id = ?");
$stmt->bind_param("i", $profile_user_id);
$stmt->execute();
$followersResult = $stmt->get_result();
$followersCount = $followersResult->fetch_assoc()['follower_count'];

// Count following
$stmt = $conn->prepare("SELECT COUNT(*) AS following_count FROM followers WHERE follower_id = ?");
$stmt->bind_param("i", $profile_user_id);
$stmt->execute();
$followingResult = $stmt->get_result();
$followingCount = $followingResult->fetch_assoc()['following_count'];
?>

<?php
include('./header.php');

?>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


 <div class="container-fluid p-0 mt-3">
    <div class="d-flex justify-content-center align-items-center profile-section">
        <img src="./upload/<?php echo $user['image']; ?>" class="profile-img img-fluid" alt="Profile Picture">
        <p class="ms-3 mt-5 pt-5 proname"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
    </div>
</div>

<div class="container mt-5 mb-3">
    <div class="d-flex justify-content-center mb-3">
        <form method="post" action="personal_pf.php?user_id=<?php echo $profile_user_id; ?>">
            <input type="hidden" name="following_id" value="<?php echo $profile_user_id; ?>">
            <?php if ($isFollowing): ?>
                <button type="submit" name="unfollow" class="btn btn-outline-danger">Unfollow</button>
            <?php else: ?>
                <button type="submit" name="follow" class="btn btn-outline-success">Follow</button>
            <?php endif; ?>
        </form>
        <button type="button" class="btn btn-outline-success d-none ms-3" data-bs-toggle="modal" data-bs-target="#chatModal">Personal Chat</button>
    </div>
<div class="d-flex ">
    <p class="fs-5 ms-3">Recipes: <span class="badge bg-danger"><?php echo $countrecipe; ?></span></p>
    <p class="fs-5 ms-3">Followers: <span class="badge bg-danger"><?php echo $followersCount; ?></span></p>
    <p class="fs-5 ms-3">Following: <span class="badge bg-danger"><?php echo $followingCount; ?></span></p>
    </div>
    <div class="row profileBody">
        <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
            <div class="text-center profile-sidebar bg-primary-subtle rounded-3 p-3">
                <strong>Lives in</strong>
                <p class="text-dark"><?php echo $user['address']; ?></p>
                <strong>Email</strong>
                <p class="text-dark"><?php echo $user['email']; ?></p>
                <strong>Phone</strong>
                <p class="text-dark"><?php echo $user['phone']; ?></p>
                <strong>Works at</strong>
                <p class="text-dark">Foodfusion</p>
            </div>
        </div>

        <div class="col-lg-8 col-md-8 col-sm-12">
            <h3>Recipes by <?php echo $user['first_name']; ?>:</h3>
            <div class="row">
                <?php while ($recipe = mysqli_fetch_assoc($recipesResult)) { ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100 shadow-sm">
                            <a href="Eachrecipe.php?recipe_id=<?php echo $recipe['recipe_id']; ?>">
                                <img src="./upload/<?php echo $recipe['image']; ?>" class="card-img-top" alt="Recipe Image" style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $recipe['title']; ?></h5>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatModalLabel">Chat with <?php echo $user['first_name']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="chat-box" id="chatBox">
                    <!-- Chat messages will load here via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <form id="chatForm" class="w-100 d-flex">
                    <input type="hidden" name="user_id" value="<?php echo $profile_user_id; ?>">
                    <input type="text" id="chatMessage" name="message" class="form-control" placeholder="Type your message..." required>
                    <button type="submit" class="btn btn-primary ms-2">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<style>
    .modal-dialog {
    height: 80vh; /* 80% of the viewport height */
    max-height: 80vh;
    width: 60vh;
}

.modal-content {
    height: 100%; /* Make content fill the modal */
}

    .half-height-img {
        height: 70vh; /* Half viewport height */
        width: 100%; /* Full width */
        object-fit: cover; /* Ensure image covers without distortion */
    }
    .profile-img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
        bottom: -95px;
        left: 0px;
    }
    .proname {
        font-size: 30px;
    }
    .profile-sidebar {
        background-color: #f8f9fa;
        padding: 20px;
    }
    .profileBody {
        margin-top: 30px;
    }
</style>
