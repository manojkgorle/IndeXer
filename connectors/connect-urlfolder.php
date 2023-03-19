<?php
    require("connection.php");
    $database = "urlfolder";
    $connect_urlfolder = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_urlfolder){
        die("Connection failed: db urlfolder");
    }
?>