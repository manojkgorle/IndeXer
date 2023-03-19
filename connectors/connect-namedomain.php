<?php
    require("connection.php");
    $database = "namedomain";
    $connect_namedomain = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_namedomain){
        die("Connection failed: db namedomain");
    }
?>