<?php
session_start();
include('./header.php');
?>

<div class="container my-5">
  <h3 class="mb-4">Culinary Resources &raquo;</h3>

  <div class="row">
    <?php
    // Array of resources to avoid repetition
    $resources = [
        [
            "title" => "Knife Skills 101",
            "description" => "Learn the basics of knife handling, cuts, and safety tips.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/download (1).jpg"
        ],
        [
            "title" => "Baking Essentials",
            "description" => "A guide to essential baking ingredients and tools.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/bakingessentials-mixing-600x700.jpg"
        ],
        [
            "title" => "Sautéing Techniques",
            "description" => "Master the art of sautéing for perfect stir-fries and more.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/cooking_techniques-saute.webp"
        ],
        [
            "title" => "Spices & Herbs Guide",
            "description" => "A complete guide to spices and herbs for adding flavor.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/bakingessentials-mixing-600x700.jpg"
        ],
        [
            "title" => "Culinary Plating Tips",
            "description" => "Enhance your dish presentation with these simple plating tips.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/image/tea-salad-close-up-header.jpg"
        ],
        [
            "title" => "Food Safety Tips",
            "description" => "Learn essential tips for handling food safely to prevent risks.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/cooking_techniques-saute.webp"
        ],
        [
            "title" => "Meal Prepping Guide",
            "description" => "A comprehensive guide on how to meal prep effectively.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/download (1).jpg"
        ],
        [
            "title" => "Health Methods",
            "description" => "Explore various cooking methods.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/download (1).jpg"
        ],
        [
            "title" => "Seasonal Ingredients",
            "description" => "Discover the best seasonal ingredients for freshness.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/e1.jpg"
        ],
        [
            "title" => "Seasonal Ingredients",
            "description" => "Discover the best seasonal ingredients for freshness.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/e8.png"
        ],
        [
            "title" => "Seasonal Ingredients",
            "description" => "Discover the best seasonal ingredients for freshness.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./upload/download (1).jpg"
        ],
        [
            "title" => "Essential Kitchen Tools",
            "description" => "A guide to must-have kitchen tools for aspiring chefs.Sift in the flour and baking powder with a tsp of salt and fold into the cake batter. Pour in the milk and beat to loosen the mixture. Scatter over the sprinkles and ripple through the cake batter before dividing between each cake tin. Bake for 25-30 mins until golden and the sponge springs back when you press it lightly. Swap the tins around in the oven after 15 mins to ensure they cook evenly. Cool on wire racks completely before icing.",
            "image" => "./images/slide/jay-wennington-N_Y88TWmGwA-unsplash.jpg"
        ]
    ];

    // Loop to generate resource cards dynamically
    foreach ($resources as $index => $resource) {
        echo '
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
          <div class="card h-100 resource-card" data-index="' . $index . '" data-title="' . htmlspecialchars($resource['title']) . '" data-description="' . htmlspecialchars($resource['description']) . '" data-image="' . htmlspecialchars($resource['image']) . '">
            <img src="' . $resource['image'] . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($resource['title']) . '">
            <div class="card-body">
              <h5 class="card-title">' . htmlspecialchars($resource['title']) . '</h5>
              <p class="card-text text-truncate ">' . htmlspecialchars($resource['description']) . '</p>
            </div>
          </div>
        </div>';
    }
    ?>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="culinaryResourceModal" tabindex="-1" aria-labelledby="culinaryResourceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="culinaryResourceModalTitle">Resource Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="culinaryResourceModalImage" src="" class="img-fluid mb-3" alt="Resource Image">
        <p id="culinaryResourceModalDescription">Resource description goes here.</p>
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
    document.getElementById('culinaryResourceModalTitle').textContent = title;
    document.getElementById('culinaryResourceModalDescription').textContent = description;
    document.getElementById('culinaryResourceModalImage').src = image;

    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('culinaryResourceModal'));
    modal.show();
  });
});
</script>

<!-- Responsive YouTube Video Section -->
<div class="container my-5">
  <h4 class="mb-4">Cooking Tips & Techniques Videos</h4>
  <div class="row gy-4">
    <div class="col-12 col-md-6 col-lg-3">
      <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/aopS3q6f1GY" 
          title="Cooking Tips For Kitchen Beginners" allowfullscreen></iframe>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/BHcyuzXRqLs" 
          title="50 Cooking Tips With Gordon Ramsay" allowfullscreen></iframe>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/wHRXUeVsAQQ" 
          title="10 Incredibly Useful Cooking Tips" allowfullscreen></iframe>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/bx0BlXeNet0" 
          title="30 Must Know Tips from a Professional Chef" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</div>

<?php
$sql = "SELECT resources.resource_id, resources.resource_type, resources.title, resources.file_path, 
resources.image_path, users.first_name, users.last_name, users.email
FROM resources
JOIN users ON resources.user_id = users.user_id
WHERE resources.resource_type = 'culinary'";

$result = $conn->query($sql);
?>

<div class="container mt-5 mb-2">
  <h1 class="mb-4">Culinary Resources Files &raquo;</h1>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
            $imagePath = htmlspecialchars($row['image_path']) ?: 'default_image.jpg';
            echo '
            <div class="col">
              <div class="cardRes position-relative h-100">
                <img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($row['title']) . '">
               
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
$resources = [
    [
        "title" => "Knife Skills 101",
        "description" => "Learn the basics of knife handling, cuts, and safety tips.",
        "image" => "./upload/download (1).jpg",
        "video" => "./video/3191888-uhd_3840_2160_25fps.mp4"
    ],
    [
        "title" => "Baking Essentials",
        "description" => "A guide to essential baking ingredients and tools.",
        "image" => "./upload/bakingessentials-mixing-600x700.jpg",
        "video" => "./video/3192081-uhd_3840_2160_25fps.mp4"
    ],
    [
        "title" => "Sautéing Techniques",
        "description" => "Master the art of sautéing for perfect stir-fries and more.",
        "image" => "./upload/cooking_techniques-saute.webp",
        "video" => "./video/3196566-uhd_3840_2160_25fps.mp4"
    ],
    [
      "title" => "Knife Skills 101",
      "description" => "Learn the basics of knife handling, cuts, and safety tips.",
      "image" => "./upload/download (1).jpg",
      "video" => "./video/3191888-uhd_3840_2160_25fps.mp4"
  ],
  [
      "title" => "Baking Essentials",
      "description" => "A guide to essential baking ingredients and tools.",
      "image" => "./upload/bakingessentials-mixing-600x700.jpg",
      "video" => "./video/3192081-uhd_3840_2160_25fps.mp4"
  ],
  [
    "title" => "Baking Essentials",
    "description" => "A guide to essential baking ingredients and tools.",
    "image" => "./upload/bakingessentials-mixing-600x700.jpg",
    "video" => "./video/3192081-uhd_3840_2160_25fps.mp4"
]


];
?>

<div class="container my-5">
<h1 class="mb-4">Culinary Resources Videos &raquo;</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($resources as $index => $resource) : ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?= htmlspecialchars($resource['image']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($resource['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($resource['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($resource['description']) ?></p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#videoModal" 
                                data-video="<?= htmlspecialchars($resource['video']) ?>">
                            Watch Video
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Recipe Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <video id="modalVideo" class="w-100" controls>
                    <source src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

<script>
    // Select the modal and video element
    const videoModal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    let videoSource = modalVideo.querySelector('source');

    // When the modal is opened, change the video source based on the clicked button
    videoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const videoPath = button.getAttribute('data-video');
        videoSource.src = videoPath;
        modalVideo.load(); // Load the new video
    });

    // Pause the video and reset when the modal is closed
    videoModal.addEventListener('hidden.bs.modal', function () {
        modalVideo.pause();
        modalVideo.currentTime = 0;
    });
</script>

<?php
include('./footer.php');
?>

<!-- Custom Styles -->
<style>
  .card-img-top {
    aspect-ratio: 4 / 3; /* Maintain aspect ratio for images */
    object-fit: cover; /* Ensure images fit nicely inside cards */
  }
  .card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .ratio {
    border-radius: 10px;
    overflow: hidden;
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
  
  .card-footer {
    background-color: transparent;
    border-top: none;
  }
  .badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: #28a745; /* Green color */
      font-size: 0.8rem;
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
