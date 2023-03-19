<?php
    require("connection.php");
    $database = "namedate";
    $connect_namedate = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_datafolder){
        die("Connection failed: db namedate");
    }
?>