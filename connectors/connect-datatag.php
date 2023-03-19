<?php
    require("connection.php");
    $database = "datatag";
    $connect_datatag = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_datatag){
        die("Connection failed: db datatag");
    }
?>