<?php



$errFile="";
$errMove="";
$errType="";
$errUpload="";

?>
<?php 

include 'db.php';


if(isset($_POST['submit'])){
    $title=trim($_POST['title']);
    $price=trim($_POST['price']);
    $des=trim($_POST['des']);

    $image=$_FILES['image'];

    $imgName=$image['name'];
    $imgTmp=$image['tmp_name'];
    $imgSize=$image['size'];
    $imgError=$image['error'];

     

     $allow = ['jpg','png','jpeg'];
     $fileEx=strtolower(pathinfo($imgName,PATHINFO_EXTENSION));

     if(in_array($fileEx,$allow)){
        if($imgError===0){
            if($imgSize<=1000000){
                $newfilename = uniqid('',true).'.'.$fileEx;
                $filename="uploads/".basename($newfilename);
                if(move_uploaded_file($_FILES['image']['tmp_name'],$filename)){
                $insertQuery="INSERT INTO product(name,description,price,image) VALUES ('$title','$des','$price','$image')";
                $res= mysqli_query($conn,$insertQuery);
                    if($res==true){
                        header("Location: register.php");
                    }else{
                        die('Error:'.mysqli_error($conn));
                    }
            }
            else{
                $errMove="there was a problem moving the uploaded";
            }
        }
        else{
          $errFile="File is too large";
        }
     }else{
     $errUpload="There was error for uploading";
     }
 }else{
    $errType= "Cannot upload files of this type. Only provide  jpg,png,jpeg";
 }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link rel="stylesheet" href="./admindashboard/create.css">

</head>
<body>

<div class="container contact-form">
            <div class="contact-image">
                <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
            </div>
            <form method="post" enctype="multipart/form-data">
                <h3>Create Product</h3>
               <i class="text-danger"><?php echo $errMove  ?></i>
               <i class="text-danger"><?php echo $errFile  ?></i>
               <i class="text-danger"><?php echo $errUpload ?></i>
               <i class="text-danger"><?php echo $errType  ?></i>
               <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <p>Title</p>

                            <input type="text" name="title" class="form-control" value=""required />
                    
                          
                        </div>
                        <div class="form-group">
                            <p>Image</p>
                         <!------    <input type="text" name="image" class="form-control" value="" />---->

                        <input type="file" class="form-control" name="image" id="inputGroupFile01" required> 
                        </div>
                        <div class="form-group">
                            <p>Price</p>
                            <input type="number" name="price" class="form-control"  value="price" required/>
                        </div>
                       
                        <div class="form-group">
                          
                            <input type="submit" name="submit" class="btnContact" value="Create" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <p>Description</p>
                            <textarea name="des" class="form-control" placeholder="Your Message *" style="width: 100%; height: 150px;" required></textarea>
                        </div>
                    </div>
                </div>
            </form>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
<?php


?>