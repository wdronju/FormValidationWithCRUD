<?php
$hostname ="localhost";
$user ="root";
$password = "";
$dbname = "db31";
$conn = mysqli_connect($hostname,$user,$password,$dbname);
if(!$conn){
    echo "Connection failed";
}

?>