<?php
    require("connection.php");
    $database = "datafolder";
    $connect_datafolder = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_datafolder){
        die("Connection failed: db datafolder");
    }
?>