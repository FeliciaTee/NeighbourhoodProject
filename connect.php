<?php 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "workshop project"; 

$conn = mysqli_connect(hostname:$servername,
                        username:$username,
                        password:$password,
                        database:$dbname); 

if (!$conn) { 
  die("Connection failed: " . mysqli_connect_error()); 
} 
?>
