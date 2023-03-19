<?php
    require("connection.php");
    $database = "urltag";
    $connect_urltag = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_urltag){
        die("Connection failed: db urltag");
    }
?>