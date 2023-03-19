<?php
    require("connection.php");
    $database = "namefolder";
    $connect_namefolder = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_namefolder){
        die("Connection failed: db namefolder");
    }
?>