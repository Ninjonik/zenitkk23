<?php 

    require_once('includes.php');


    if(!$_SESSION["loggedIn"]){
        header("Location: admin.php?action=notLoggedIn");
    }


    $id = $_GET['id'];
    $query = mysqli_execute_query($conn, "DELETE FROM location WHERE id=$id");

    if($query){
        header("Location: admin.php?action=locationRemoved");
    }
    