<?php
    require("connection.php");
    $database = "nametag";
    $connect_nametag = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_nametag){
        die("Connection failed: ");
    }
?>