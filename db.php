<?php
$servername = "localhost";
$username = "root";


// Create connection
$conn = mysqli_connect($servername, $username,"",'foodfusiondb');
// Check connection
if ($conn==false) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
