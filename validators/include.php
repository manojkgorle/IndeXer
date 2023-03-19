<?php
/*
******when ever connection is killed alter all done sql queries ******
******convert folder name, tag name into case irrespective ******

*/
$url = $folder = $tag = $name = $domain = $comment = $error_message =  "";
$error_flag = 0;
session_start();
$user_id = 11111111111;// should be saved in session data as encrypted link in a other database which retrives the user id.
$SESSION['userid']=$user_id;
$folder_id = $tag_id =  array();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // function for converting the $folder,$tag datatype string to array containing folders,tags
    function format_data($data){
        return explode(",",preg_replace("/[^a-zA-Z0-9, ]/","",$data));

    }
     // function for safe gaurding and health cheking of given comment and name
    function clean_data($data){
        return htmlspecialchars(stripslashes($data));
    }
    $folder = format_data($_POST["include_folder"]);
    $tag = format_data($_POST["include_tag"]);
    $comment = clean_data($_POST["include_comment"]);
    $name = clean_data($_POST["include_name"]);
    //validating url --> if fail killing the script execution
    if (preg_match("%^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\x{00a1}-\x{ffff}][a-z0-9\x{00a1}-\x{ffff}_-]{0,62})?[a-z0-9\x{00a1}-\x{ffff}]\.)+(?:[a-z\x{00a1}-\x{ffff}]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$%iuS",$_POST["include_url"]) == 1){
        $url = $_POST['include_url'];
    }else{
        $error_flag += 1;
        die($error_message."Error: Invalid Url ");
    }
    //validating domain --> if fail killing the execution
    if(preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $_POST["include_domain"]) && preg_match("/^.{3,253}$/", $_POST["include_domain"]) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $_POST["include_domain"])){
        $domain = $_POST["include_domain"];
    }else{
        $error_flag += 1;
        die($error_message."Error: Invalid Domain");
    }
    //once again checking for error --> if found kill the script
    if ($error_flag != 0){
        die();
    }
    //everything goes good, then start making connections to the required databases
    require("../connectors/connect-userdata.php");
    //search if url already exists
    $sql_url_exist = "SELECT url FROM `$user_id` WHERE url = '".$url."'";
    $result_sql_url_exist = mysqli_query($connect_userdata,$sql_url_exist);
    //if exist abort script else make rest of all the connections
    if(mysqli_num_rows($result_sql_url_exist) == 1){
        die("The url already exist in the data");
    }else{
        $url_id = hash('md4',$url);
    }
    
    //connecting to databases as per requirement
    //finding lengths of folder and tag array
    $length_folder = count($folder);
    $length_tag = count($tag);
    if($length_folder != 0 && $length_folder <= 20){
        require("../connectors/connect-namefolder.php");
        require("../connectors/connect-datafolder.php");
        //if folder exist retrive its folder id, else create a folder id and upload in namefolder database userid table
        //create a table with folder id in db datafolder & entry in name folder
        /*
            *****Explore possibilities of execution of for loop into procedural manner
        */
        foreach($folder as $folder_data){
            $sql_folder_name_search = "SELECT folderid FROM `$user_id` WHERE foldername ='".$folder_data."'";
            $result_sql_folder_name_search = mysqli_query($connect_namefolder,$sql_folder_name_search);
            if(mysqli_num_rows($result_sql_folder_name_search) > 0){
                $temp_folder_id = mysqli_fetch_assoc($result_sql_folder_name_search)['folderid'];
                //array_push($folder_id,$temp_folder_id);
                $folder_id = $temp_folder_id;
            }else{
                //create a folderid -- push to $folderid array -- create a table in datafolder & entry in namefolder
                $hash_folder_id = hash('md4',$folder_data);
                $temp_folder_id = $user_id.$hash_folder_id;
                //array_push($folder_id,$temp_folder_id);
                $folder_id = $temp_folder_id;
                $sql_folder_name_insert = "INSERT INTO `$user_id` (foldername, folderid) VALUES ('".$folder_data."','".$temp_folder_id."')";
                $result_sql_folder_name_insert = mysqli_query($connect_namefolder,$sql_folder_name_insert);
                //if activity fails
                if(!$result_sql_folder_name_insert) {
                    die(mysqli_error($connect_namefolder));
                }
                $sql_folder_name_create = "CREATE TABLE $temp_folder_id (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, urlid VARCHAR(128) NOT NULL,timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
                $result_sql_folder_name_create = mysqli_query($connect_datafolder,$sql_folder_name_create);
                //if activity fails  
                if(!$result_sql_folder_name_create){
                    die(mysqli_error($connect_datafolder));
                }
            }
            //pushing url id into folderid table 
            $sql_url_id_push = "INSERT INTO $temp_folder_id (urlid) VALUES ('".$url_id."')";
            $result_sql_url_id_push = mysqli_query($connect_datafolder,$sql_url_id_push);
            if(!$result_sql_url_id_push){
                die(mysqli_error($connect_datafolder));
            }
        }
        mysqli_close($connect_namefolder);
        mysqli_close($connect_datafolder);
    }else{
        die("Error: Folder Limit out of bound, No of folders: ".$length_folder);
    }
    //--folder activity ends
    if($length_tag != 0 && $length_tag <= 30){
        require("../connectors/connect-nametag.php");
        require("../connectors/connect-datatag.php");
        //if tag exist retrive its tag id, else create a tag id and upload in nametag database userid table
        //create a table with tag id in db datatag & entry in nametag
        foreach($tag as $tag_data){
            $sql_tag_name_search = "SELECT tagid FROM `$user_id` WHERE tagname ='".$tag_data."'";
            $result_sql_tag_name_search = mysqli_query($connect_nametag,$sql_tag_name_search);
            if(mysqli_num_rows($result_sql_tag_name_search) > 0){
                $temp_tag_id = mysqli_fetch_assoc($result_sql_tag_name_search)['tagid'];
               // array_push($tag_id,$temp_tag_id);
               $tag_id[] =$temp_tag_id;
            }else{
                //create a tagid -- push to $tagid array -- create a table in datatag & entry in nametag
                $hash_tag_id = hash('md4',$tag_data);
                $temp_tag_id = $user_id.$hash_tag_id;
                //array_push($tag_id,$temp_tag_id);
                $tag_id[] =$temp_tag_id;
                $sql_tag_name_insert = "INSERT INTO `$user_id` (tagname, tagid) VALUES ('".$tag_data."','".$temp_tag_id."')";
                $result_sql_tag_name_insert = mysqli_query($connect_nametag,$sql_tag_name_insert);
                //if activity fails
                if(!$result_sql_tag_name_insert) {
                    die(mysqli_error($connect_nametag));
                }
                $sql_tag_name_create = "CREATE TABLE `$temp_tag_id` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, urlid VARCHAR(128) NOT NULL,timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
                $result_sql_tag_name_create = mysqli_query($connect_datatag,$sql_tag_name_create);
                //if activity fails  
                if(!$result_sql_tag_name_create){
                    die(mysqli_error($connect_datatag));
                }
            }
            //pushing url id into tagid table 
            $sql_url_id_push = "INSERT INTO `$temp_tag_id` (urlid) VALUES ('".$url_id."')";
            $result_sql_url_id_push = mysqli_query($connect_datatag,$sql_url_id_push);
            if(!$result_sql_url_id_push){
                die(mysqli_error($connect_datatag));
            }
        }
        mysqli_close($connect_nametag);
        mysqli_close($connect_datatag);
    }else{
        die("Error: Tag Limit out of bound, No of tags: ".$length_tag);
    }
    //--tag activity ends
    if($domain != ""){
        require("../connectors/connect-namedomain.php");
        require("../connectors/connect-datadomain.php");
        //check for domain name existance in namedomain db, userid table, if exist get domainid
        //else create domain id ,add to namedomain and create table in datadomain
        //add entry
            $sql_domain_name_search = "SELECT domainid FROM `$user_id` WHERE domain ='".$domain."'";
            $result_sql_domain_name_search = mysqli_query($connect_namedomain,$sql_domain_name_search);
            if(mysqli_num_rows($result_sql_domain_name_search) > 0){
                $temp_domain_id = mysqli_fetch_assoc($result_sql_domain_name_search)['domainid'];
            }else{
                //create $temp_domain_id -- entry into namedomain -- create a table with $temp_domain_id in datadomain db
                $hash_domain_id = hash('md4',$domain);
                $temp_domain_id = $user_id.$hash_domain_id;
                $sql_domain_name_insert = "INSERT INTO `$user_id` (domain, domainid) VALUES ('".$domain."','".$temp_domain_id."')"; 
                $result_sql_domain_name_insert = mysqli_query($connect_namedomain,$sql_domain_name_insert);
                //if activity fails
                if(!$result_sql_domain_name_insert) {
                    die(mysqli_error($connect_namedomain));
                }
                $sql_domain_name_create = "CREATE TABLE `$temp_domain_id` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, urlid VARCHAR(128) NOT NULL,timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
                $result_sql_domain_name_create = mysqli_query($connect_datadomain,$sql_domain_name_create);
                //if activity fails  
                if(!$result_sql_domain_name_create){
                    die(mysqli_error($connect_datadomain));
                }
            }
            $sql_url_id_push = "INSERT INTO `$temp_domain_id` (urlid) VALUES ('".$url_id."')";
            $result_sql_url_id_push = mysqli_query($connect_datadomain,$sql_url_id_push);
            if(!$result_sql_url_id_push){
                die(mysqli_error($connect_datadomain));
            }
            mysqli_close($connect_namedomain);
            mysqli_close($connect_datadomain);
    }else{
        die("Error: Invalid domain");
    }
    /*date format: date("Ymd") 
    dateid userid.date
    datadate -- make a query to get dummy info to find existence of table -- if table exists -- add entry -- else -- create table & add entry to table */
    require("../connectors/connect-datadate.php");//establishing database connection
    require("../connectors/connect-namedate.php");
    $date = date("Ymd");
    $temp_date_id = $user_id.$date;
    $sql_date_search = "SELECT dateid FROM `$user_id` WHERE dateid ='".$temp_date_id."'";
    $result_sql_date_search = mysqli_query($connect_namedate,$sql_date_search);
    if(mysqli_num_rows($result_sql_date_search) == 0){
        $sql_date_table_create = "CREATE TABLE `$temp_date_id` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, urlid VARCHAR(128) NOT NULL, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
        $result_sql_date_table_create = mysqli_query($connect_datadate,$sql_date_table_create);
        $sql_date_insert = "INSERT INTO `$user_id` (date, dateid) VALUES ('".$date."','".$temp_date_id."')"; 
        $result_date_insert = mysqli_query($connect_namedate,$sql_date_insert);
    }
    $sql_url_id_push = "INSERT INTO `$temp_date_id` (urlid) VALUES ('".$url_id."')";
    $result_sql_url_id_push = mysqli_query($connect_datadate,$sql_url_id_push);
    if(!$result_sql_url_id_push){
        die(mysqli_error($connect_datadate));
    }
    mysqli_close($connect_datadate);
    //--date work end
    /*upload data(name,comment,url,urlid,domain_pointer(id)) in userdata db userid tablename
        for folders & tags insert multiple with there respective connection
    */
    $sql_upload_data = "INSERT INTO `$user_id` (url,urlid,name,comment,domain) VALUES ('".$url."','".$url_id."','".$name."','".$comment."','".$temp_domain_id."')";
    $result_sql_upload_data = mysqli_query($connect_userdata,$sql_upload_data);
    if(!$result_sql_upload_data){
        die(mysqli_error($connect_userdata));
    }
    //folder integration to urlfolder -- create table with url id and next upload all folder names & folder_id
    require("../connectors/connect-urlfolder.php");
    $sql_string_multi_folder = "";
    $sql_folder_table_create = "CREATE TABLE `$url_id` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, foldername VARCHAR(64) NOT NULL, folderid VARCHAR(64) NOT NULL, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
    $result_sql_folder_table_create = mysqli_query($connect_urlfolder,$sql_folder_table_create); 
    if(!$result_sql_folder_table_create){
        die(mysqli_error($connect_urlfolder));
    }
    for($x = 0; $x < $length_folder; $x++){
        $sql_string_multi_folder .= "INSERT INTO `$url_id` (foldername,folderid) VALUES ('".$folder[$x]."','".$folder_id[$x]."');"; 
    }
    $sql_multi_folder_upload = mysqli_multi_query($connect_urlfolder,$sql_string_multi_folder);
    if(!$sql_multi_folder_upload){
        die(mysqli_error($connect_urlfolder));
    }
    //tag integration to urltag -- create table with url id and next upload all tag names & tag_id
    require("../connectors/connect-urltag.php");
    $sql_string_multi_tag = "";
    $sql_tag_table_create = "CREATE TABLE $url_id (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, tagname VARCHAR(64) NOT NULL,tagid VARCHAR(64) NOT NULL, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
    $result_sql_tag_table_create = mysqli_query($connect_urltag,$sql_tag_table_create); 
    if(!$result_sql_tag_table_create){
        die(mysqli_error($connect_urltag));
    }
    for($x = 0; $x < $length_tag; $x++){
        $sql_string_multi_tag .= "INSERT INTO $url_id (tagname,tagid) VALUES ('".$tag[$x]."','".$tag_id[$x]."');"; 
    }
    $sql_multi_tag_upload = mysqli_multi_query($connect_urltag,$sql_string_multi_tag);
    if(!$sql_multi_tag_upload){
        die(mysqli_error($connect_urltag));
    }
    //--upload data to userdata end

    //closing all established connections
    mysqli_close($connect_userdata);
    mysqli_close($connect_urltag);
    mysqli_close($connect_urlfolder);
    //all established connections closed
}
?>