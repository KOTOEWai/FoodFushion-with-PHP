<?php
session_start();
include('./header.php');
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    // Check if 'user_array' exists in session, then get user_id; otherwise, set it to null
    $user_id = isset($_SESSION['user_array']) ? $_SESSION['user_array']['user_id'] : 0;
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO messages (name, user_id, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $user_id, $email, $subject, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<script>alert("Message inserted successfully");</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


 <!-- Contact Us Section -->
 <div class="container my-5">
    <div class="row">
      <!-- Contact Form -->
      <div class="col-lg-6">
        <h2>Contact Us</h2>
        <form method="POST" action="contact.php">
  <div class="mb-3">
    <label for="name" class="form-label">Your Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
  </div>
  <div class="mb-3">
    <label for="subject" class="form-label">Subject</label>
    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject">
  </div>
  <div class="mb-3">
    <label for="message" class="form-label">Your Message</label>
    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here" required></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

      </div>

      <!-- Map -->
      <div class="col-lg-6">
        <h2>Find Us</h2>
        <div class="mapouter">
          <div class="gmap_canvas">
            <iframe width="100%" height="350" id="gmap_canvas" src="https://maps.google.com/maps?q=New York&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>






<?php
include('./footer.php');
?>


