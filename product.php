<?php 

include('./head&footer/header.php');
include 'db.php';
?>


<div class="d-flex flex-wrap m-5">
<?php  
$productsql = "SELECT * FROM product";
$result= mysqli_query($conn,$productsql);
  foreach($result as $product){ 
     ?>
<div class="card ms-3"  style="width: 18rem;">
  <img class="card-img-top h-100" src="./upload/<?php  echo $product['image']     ?>" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?php echo $product['name']  ?></h5>
    <p class="card-text"><?php  echo $product['description']  ?></p>
    <a href="#" class="btn btn-primary">Sarch T Shirts</a>
  </div>
</div>

 
  


   <?php }
?>
  </div>



<?php 
include('./head&footer/footer.php')

?>
