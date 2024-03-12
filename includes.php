<?php 

$host = "localhost";
$db = "zenitKK40";
$user = "zenituser40";
$pass = "zenitpass40";

$conn = mysqli_connect($host, $user, $pass, $db);

if($conn){
    echo "";
} else {
    echo "bad";
}

session_start();

error_reporting(0);

?>