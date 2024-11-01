<?php
session_start();
include('./header.php');
?>



<!-- About Us Section -->
<section class="about-section">
    <div class="container">
        <h2 class="about-heading">About FoodFusion</h2>
        <p class="about-content text-center">
            Welcome to FoodFusion, a culinary platform dedicated to inspiring home cooks and culinary enthusiasts around the globe. At FoodFusion, we believe that cooking is more than just a daily necessity – it's a form of art, a creative expression, and a way to bring people together. Whether you're a seasoned chef or just starting in the kitchen, FoodFusion offers something for everyone.
        </p>
        <p class="about-content text-center">
            Our mission is to make home cooking fun, accessible, and innovative. We provide a vast collection of delicious recipes, cooking tips, and a community cookbook where members can share their culinary creations. Join our growing community of passionate food lovers and start your culinary adventure with FoodFusion today!
        </p>
    </div>
</section>

<!-- Our Mission Section -->
<section class="team-section">
    <div class="container">
        <h2 class="text-center mb-4">Our Mission</h2>
        <p class="text-center">
            Our mission is to foster a love for cooking by providing simple, delicious, and innovative recipes. We aim to build a supportive community of home cooks where creativity flourishes, and culinary skills grow. At FoodFusion, everyone has a seat at the table – so grab your apron, and let’s get cooking!
        </p>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <h2 class="text-center mb-5">Meet Our Team</h2>
        <div class="row">
            <div class="col-md-4 team-member">
                <img src="./upload/ceo.jpg" alt="Founder" class="img-fluid">
                <h5>Jane Doe</h5>
                <p>Founder & Head Chef</p>
            </div>
            <div class="col-md-4 team-member">
                <img src="./upload/team1.jpg" alt="Content Creator" class="img-fluid">
                <h5>John Smith</h5>
                <p>Content Creator</p>
            </div>
            <div class="col-md-4 team-member">
                <img src="./upload/manager.jpg" alt="Community Manager" class="img-fluid">
                <h5>Sara Lee</h5>
                <p>Community Manager</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="about-section">
    <div class="container text-center">
        <h3 class="mb-4">Join Our FoodFusion Family</h3>
        <p>
            Whether you’re sharing your favorite family recipe or learning something new, we invite you to be part of our community. Create an account, start sharing your own recipes, and connect with food enthusiasts from around the world!
        </p>
        <a href="./index.php" class="btn btn-primary">Sign Up Now</a>
    </div>
</section>

<style>
        .about-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .about-heading {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .about-content {
            font-size: 1.2rem;
            line-height: 1.8;
        }
        .team-section {
            background-color: #fff;
            padding: 40px 0;
        }
        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }
        .team-member img {
            border-radius: 50%;
            max-width: 100px;
            height: auto;
        }
    </style>



<?php
include('./footer.php');
?>