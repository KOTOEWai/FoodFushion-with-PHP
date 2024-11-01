<?php
session_start();
include('./header.php');
include('./db.php'); // Ensure you include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_array'])) {
    echo "<script>alert('You need to Login first!');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$current_user_id = $_SESSION['user_array']['user_id']; // Current logged-in user ID

// Handling follow action
if (isset($_POST['follow'])) {
    $follower_id = $current_user_id;
    $following_id = $_POST['following_id'];

    // Check if already following using a prepared statement
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
            die('Error: ' . $stmt->error);
        }
    }
}

// Handling unfollow action
if (isset($_POST['unfollow'])) {
    $follower_id = $current_user_id;
    $following_id = $_POST['following_id'];

    // Remove the follow relationship using a prepared statement
    $stmt = $conn->prepare("DELETE FROM followers WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $follower_id, $following_id);
    if ($stmt->execute()) {
        echo '<script>alert("Successfully Unfollowed!");</script>';
    } else {
        die('Error: ' . $stmt->error);
    }
}

// Query to get the list of all users except the logged-in user
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id != ?");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="user-list-section">
    <div class="container">
        <h2 class="text-center">User List</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $profile_user_id = $row['user_id'];

                    // Check if current user is following this profile
                    $checkFollowStmt = $conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND following_id = ?");
                    $checkFollowStmt->bind_param("ii", $current_user_id, $profile_user_id);
                    $checkFollowStmt->execute();
                    $isFollowing = $checkFollowStmt->get_result()->num_rows > 0;
                    ?>
                    <div class="col-md-6">
                        <!-- Link to user profile -->
                        <a href="personal_pf.php?user_id=<?php echo $profile_user_id; ?>" class="card-link" style="text-decoration: none; color: inherit;">
                            <div class="card mb-3 bg-white-subtle shadow-lg mt-2" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-12 col-md-4">
                                        <!-- Profile Image -->
                                        <img src="./upload/<?php echo $row['image']; ?>" alt="Profile Image" class="img-fluid rounded-start" style="object-fit: cover; width: 100%; height: 100%;">
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></h5>
                                            <p class="card-text">
                                                <strong>Email:</strong> <?php echo $row['email']; ?><br>
                                                <strong>Phone:</strong> <?php echo $row['phone']; ?><br>
                                                <strong>Address:</strong> <?php echo $row['address']; ?>
                                            </p>
                                            <!-- Follow/Unfollow Button -->
                                            <form method="POST" action="friend.php">
                                                <input type="hidden" name="following_id" value="<?php echo $profile_user_id; ?>">
                                                <?php if ($isFollowing): ?>
                                                    <button type="submit" name="unfollow" class="btn btn-outline-danger">Unfollow</button>
                                                <?php else: ?>
                                                    <button type="submit" name="follow" class="btn btn-primary">Follow</button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No users found.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php
include('./footer.php');
?>
