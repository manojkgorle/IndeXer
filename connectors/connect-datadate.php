<?php
    require("connection.php");
    $database = "datadate";
    $connect_datadate = mysqli_connect($servername,$username,$password,$database);
    if(!$connect_datadate){
        die("Connection failed: db datadate");
    }
?>