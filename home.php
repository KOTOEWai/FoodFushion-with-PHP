<?php 

include('./header.php')

?>

<style>



.hero {
  background-color: #f8f9fa;
}

.hero img {
  margin-bottom: 1.5rem;
}

.latest-proposals img {
  margin: 0.5rem;
  border-radius: 10px;
}



</style>



<section class="hero text-center py-5">
  <div class="container">
    <h1 class="display-4">Pizza "Special Order"</h1>
    <p class="lead">Delicious pizza made from the freshest ingredients. Order your special pizza today!</p>
    <div class="row justify-content-center">
      <div class="col-md-3">
        <img src="./upload/image/download.jpg" alt="Pizza 1" class="img-fluid rounded-circle">
      </div>
      <div class="col-md-3">
        <img src="./upload/image/download.jpg" alt="Pizza 2" class="img-fluid rounded-circle">
      </div>
      <div class="col-md-3">
        <img src="./upload/image/download.jpg" alt="Pizza 3" class="img-fluid rounded-circle">
      </div>
    </div>
  </div>
</section>






<section class="latest-proposals bg-dark text-white py-5">
  <div class="container text-center">
    <h2>Latest Proposals</h2>
    <div class="row">
      <div class="col-md-2">
        <img src="./upload/image/download.jpg" class="img-fluid" alt="Proposal 1">
      </div>
      <div class="col-md-2">
        <img src="./upload/image/download.jpg" class="img-fluid" alt="Proposal 2">
      </div>
      <div class="col-md-2">
        <img src="./upload/image/download.jpg" class="img-fluid" alt="Proposal 3">
      </div>
      <div class="col-md-2">
        <img src="./upload/image/download.jpg" class="img-fluid" alt="Proposal 4">
      </div>
      <div class="col-md-2">
        <img src="./upload/image/download.jpg" class="img-fluid" alt="Proposal 5">
      </div>
    </div>
  </div>
</section>


<section class="welcome py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <h3>Welcome!</h3>
        <p>Enjoy our exclusive pizza offers and experience the best flavors around town. Made with love, every slice is a taste of perfection.</p>
        <a href="#" class="btn btn-primary">See Details</a>
      </div>
      <div class="col-md-4">
        <img src="./upload/image/download.jpg" alt="Pizza Ingredients" class="img-fluid">
      </div>
    </div>
  </div>
</section>

















<?php 

include('./footer.php')

?>