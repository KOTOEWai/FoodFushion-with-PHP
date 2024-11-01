<?php
session_start();
include('./header.php');
?>
<div class="container my-4">
  <h3 class="mb-3">Educational Resources &raquo;</h3>
  <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
    <!-- Static Educational Cards -->
    <?php
    $staticResources = [
        ["e1.jpg", "Understanding Nutrition", "Learn about essential nutrients and how they benefit health.
        Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e2.png", "Cooking Techniques", "Master various cooking techniques for better meal preparation.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e3.jpg", "Menu Planning", "Tips and tricks for creating a balanced and appealing menu. Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e4.jpg", "History of Food", "Explore the fascinating history behind various cuisines and ingredients.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e5.jpg", "Culinary Terms Glossary", "A handy glossary of culinary terms for aspiring chefs.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e6.jpg", "Knife Skills Basics", "Learn the essential knife skills for every chef.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e7.png", "Food Safety Guidelines", "Essential practices to keep your food safe and healthy.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e8.png", "Understanding Flavor Profiles", "Explore how to combine flavors for delicious dishes.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e9.jpg", "Baking Basics", "A guide to essential baking techniques and tips.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
["i2.webp", "Baking Basics", "A guide to essential baking techniques and tips.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
["i1.webp", "Baking Basics", "A guide to essential baking techniques and tips.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."],
        ["e10.jpg", "Cooking for Beginners", "A complete guide for those starting their culinary journey.  Taste-testing the dish as you cook is one of the most critical steps to remember. It’s also one of the most fun cooking tips! If you don’t try it as you go, how will you know if it tastes good? 
You don’t want to wait until the dish is finished to do the first test — try to do this throughout the cooking process. That way, you can season and adjust ingredients accordingly. Maybe it could use more salt, pepper, onion powder, garlic or sugar. The only way to know for sure is to taste away! Just make sure whatever you’re tasting doesn’t include raw ingredients like eggs or meats."]
    ];
    foreach ($staticResources as $index => $resource) {
        echo '
        <div class="col">
          <div class="card h-100 resource-card" data-index="' . $index . '" data-title="' . htmlspecialchars($resource[1]) . '" data-description="' . htmlspecialchars($resource[2]) . '" data-image="./upload/' . htmlspecialchars($resource[0]) . '">
            <img src="./upload/' . htmlspecialchars($resource[0]) . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($resource[1]) . '">
            <div class="card-body">
              <h5 class="card-title">' . htmlspecialchars($resource[1]) . '</h5>
              <p class="card-text text-truncate ">' . htmlspecialchars($resource[2]) . '</p>
            </div>
          </div>
        </div>';
    }
    ?>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="resourceModal" tabindex="-1" aria-labelledby="resourceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="resourceModalTitle">Resource Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="resourceModalImage" src="" class="img-fluid mb-3" alt="Resource Image">
        <p id="resourceModalDescription">Resource description goes here.</p>
      </div>
    </div>
  </div>
</div>

<script>
// Attach click event to each card to open the modal with the respective content
document.querySelectorAll('.resource-card').forEach(function(card) {
  card.addEventListener('click', function() {
    var title = card.getAttribute('data-title');
    var description = card.getAttribute('data-description');
    var image = card.getAttribute('data-image');
    
    // Populate the modal with the resource info
    document.getElementById('resourceModalTitle').textContent = title;
    document.getElementById('resourceModalDescription').textContent = description;
    document.getElementById('resourceModalImage').src = image;

    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('resourceModal'));
    modal.show();
  });
});
</script>

<?php
$sql = "SELECT resources.resource_id, resources.resource_type, resources.title, resources.file_path, 
resources.image_path, users.first_name, users.last_name, users.email
FROM resources
JOIN users ON resources.user_id = users.user_id
WHERE resources.resource_type = 'Educational'";

$result = $conn->query($sql);
?>


<div class="container mt-5 mb-2">
  <h1 class="mb-4">Educational Resources Files &raquo;</h1>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
            $imagePath = htmlspecialchars($row['image_path']) ?: 'default_image.jpg';
            echo '
            <div class="col">
              <div class="cardRes position-relative h-100">
                <img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($row['title']) . '">
                <div class="age-badge">Every Person</div>
                <div class="card-body p-2">
                  <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                  <p><strong>Type:</strong> ' . htmlspecialchars($row['resource_type']) . '</p>
                  <p><strong>Uploaded by:</strong> ' . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . '</p>
                  <p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center p-2">
                  <button class="btn btn-outline-dark btn-sm">WORD</button>
                  <a href="' . htmlspecialchars($row['file_path']) . '" class="btn btn-outline-dark btn-sm" download>Download</a>
                </div>
              </div>
            </div>';
        }
    } else {
        echo "<p>No resources found.</p>";
    }
    ?>
  </div>
</div>

<?php
include('./footer.php');
?>

<!-- CSS Styles -->
<style>
  .card-img-top {
    width: 100%;
    height: auto;
    aspect-ratio: 9 / 6;
    object-fit: cover; /* Ensures proper image scaling */
  }
  .card {
    margin-top: 1.5rem;
  }
  .cardRes {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
  .cardRes img {
    height: 150px;
    object-fit: cover;
  }
  .age-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #3742fa;
    color: white;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 20px;
  }
  .card-footer {
    background-color: transparent;
    border-top: none;
  }

  /* Responsive Adjustments */
  @media (max-width: 576px) {
    .card-body {
      padding: 10px;
    }
    .card-title {
      font-size: 1.2rem;
    }
    .card-footer {
      flex-direction: column;
    }
    .btn {
      width: 100%; /* Full width buttons on mobile */
      margin-top: 5px;
    }
  }
</style>
