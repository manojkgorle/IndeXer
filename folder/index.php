<?php
    if(!isset($_GET['folder_id'])){
        header("location:../");
        die();
    }else{
        $user_id = 11111111111;
        $url_base = 0;
        //implementing user login sessions & finalise the folder page later replicate the page for remianing activities
        $folder_id = $_GET['folder_id'];
        require('../connectors/connect-namefolder.php');
        require('../connectors/connect-datafolder.php');
        require('../connectors/connect-userdata.php');
        $sql_get_folder_name = "SELECT foldername FROM `$user_id` WHERE folderid ='".$folder_id."'";
        $result_sql_get_folder_name = mysqli_query($connect_namefolder,$sql_get_folder_name);
        $result_sql_get_folder_name = mysqli_fetch_assoc($result_sql_get_folder_name);
        $folder_name = $result_sql_get_folder_name['foldername'];
        //getting all the urls & their names
        $sql_get_all_urlid = "SELECT urlid FROM $folder_id LIMIT 20";
        $result_sql_get_all_urlid = mysqli_query($connect_datafolder,$sql_get_all_urlid);
        $total_url = mysqli_num_rows($result_sql_get_all_urlid);
        while($data_all_urlid = mysqli_fetch_assoc($result_sql_get_all_urlid)){
            //can use prepared statements for this case
            // two dimensional array --> 0 name 1 url 2 urlid
            $sql_get_url_name = "SELECT name,url FROM `$user_id` WHERE urlid = '".$data_all_urlid['urlid']."'";
            $result_sql_get_url_name =mysqli_fetch_assoc(mysqli_query($connect_userdata,$sql_get_url_name));
            $array[$url_base] = [$result_sql_get_url_name['name'],$result_sql_get_url_name['url'],$data_all_urlid['urlid']];
            $url_base += 1;
        }
        mysqli_close($connect_namefolder);
        mysqli_close($connect_datafolder);
        mysqli_close($connect_userdata);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Folder Infomatics - Indexer</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/ext.css'>
</head>
<body>
    <div class="main-header">
        IndeXer/Folder
    </div>
    <div class="grid-container">
        <div class="grid-container-column">
            <div class="title">Infomatics</div>
        </div>
        <div class="grid-container-column">
            <div class="title">Content</div>
            <div class="content">
                    
                    <?php 
                        $content = "";
                        for($i=0;$i<$total_url;$i++){
                            $content .= "<div class='name'>".$array[$i][0]."</div>
                                        <div class='url'>".$array[$i][1]."</div>
                                        <div class='edit' data-urlid=".$array[$i][2].">edit</div>
                                        <div class='delete' data-urlid=".$array[$i][2].">delete</div>";
                        }
                        echo $content;
                    ?>
                    <tr>
                        <div class='name'>hgjfnvflkfjajbnvlhfbfnalkdfb vcnf dlnvn cklbanlfb nv jdlkanaklal;a</div>
                        <div class='url'>hgjfnvflkfjajbnvlhfbfnalkdfb vcnf dlnvn cklbanlfb n jdla  allsv</div>
                        <div class='edit'>edit</div>
                        <div class='delete'>delete</div>
                    </tr>
            </div>
        </div>
    </div>
</body>
</html>