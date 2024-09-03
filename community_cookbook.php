<?php
include('./header.php')




?>
<?php
if(!isset($_SESSION['user_array'])){
  header('location:login.php');
}
if($_SESSION['user_array']['role']!='user'){
  header('location: ./login.php');
}
?>

<?php
include('./footer.php')
?>