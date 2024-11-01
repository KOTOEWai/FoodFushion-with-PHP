<?php
include('./header.php')
?>


<?php
// Include the database connection
if(isset($_GET['update_id'])){
    $update=$_GET['update_id'];
    $query="SELECT * FROM users WHERE user_id=$update";
    $result=mysqli_query($conn,$query);
    if($result){
        $user=mysqli_fetch_assoc($result);
    }else{
        die('Error:'. mysqli_error($conn));
    }
 }

// Get user_id from the URL

// Initialize error messages and success message
$error = $success = "";
if (isset($_POST['update'])) {
    // Get the form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $input_password = $_POST['password'];

    // You can add validation here for each field as needed

    // Update query
    

 $update_query="SELECT * FROM users WHERE user_id=$update";
 $result= mysqli_query($conn,$update_query);

     if($result && $user = mysqli_fetch_assoc($result)) {

        $old_password= $user['password'];

        $newpassword = !empty($input_password) ? md5($input_password) : $user['password'];

      $update_query = "UPDATE users SET 
        first_name='$first_name', 
        last_name='$last_name', 
        email='$email', 
        phone='$phone', 
        address='$address',
        password='$newpassword'
         WHERE user_id='$update'";
         $result = mysqli_query($conn,$update_query);
         if ($result) {
            // Update the session data
            $_SESSION['user_array']['first_name'] = $first_name;
            $_SESSION['user_array']['last_name'] = $last_name;
            $_SESSION['user_array']['email'] = $email;
            $_SESSION['user_array']['phone'] = $phone;
            $_SESSION['user_array']['address'] = $address;
            $success = "Profile updated successfully.";
         }
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>



    <div class="container-fluid col-lg-5 col-md-5  col-sm-5  mb-5 mt-5">
        <h2>Edit Profile</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>">
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea name="address" id="address" class="form-control"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" name="password" id="password" class="form-control" placeholder="Enter Your new password">
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
        </form>
    </div>





<?php
include('./footer.php')
?>