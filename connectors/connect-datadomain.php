<?php
    require("connection.php");
    $database = "datadomain";
    $connect_datadomain = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_datadomain){
        die("Connection failed: db datadomain");
    }
?>