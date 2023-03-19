<?php 

$input = "This is a string, containing special characters! ,!@#$%^&*,(){}:|:?<script>,</script>";
$clean = preg_replace("/[^a-zA-Z0-9, ]/","",$input);
$array = explode(",",$clean);
var_dump($array);
$data = "http://man.k.io";
$data2 = "manoj.io";
var_dump(preg_match("%^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\x{00a1}-\x{ffff}][a-z0-9\x{00a1}-\x{ffff}_-]{0,62})?[a-z0-9\x{00a1}-\x{ffff}]\.)+(?:[a-z\x{00a1}-\x{ffff}]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$%iuS",$data));
var_dump(preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $data2) 
&& preg_match("/^.{3,253}$/", $data2) 
&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $data2));
var_dump(password_hash("balu", PASSWORD_BCRYPT, ["cost" => 10]));
//set auto increment
echo $data.$data2;
date("Ymd");
for($x = 0; $x <10; $x++){
    echo $x;
}
//mysqli prep stat
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("connection failed" .$conn->connect_error);
}
$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $firstname, $lastname, $email);
$firstname = "John";
$lastname = "Doe";
$email = "john@example.com";
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$row[""];
?>