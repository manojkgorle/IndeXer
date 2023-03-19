<?php
    require("connection.php");
    $database = "userdata";
    $connect_userdata = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_userdata){
        die("Connection failed: For db: userdata");
    }
?>