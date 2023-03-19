<?php 
    /**setup login & session and ajax request validators for the params passed */
    $params = $_POST['sendparam'] + 1;
    $fetch_data_line = 25;
    $fetch = $params*$fetch_data_line;
    $limit = 0;
    require("../connectors/connect-datafolder.php");//
    require("../connectors/connect-namefolder.php");
    require("../connectors/connect-userdata.php");//
    $user_id = 11111111111;
    $total_records = mysqli_query($connect_namefolder,"SELECT COUNT(*) As total_records FROM `$user_id`");
    $total_records = mysqli_fetch_array($total_records);
    $total_records = $total_records['total_records'];
    if($total_records >= $fetch){
        $limit = $fetch;
    }else{
        $limit = $total_records;
    }
    $sql_fetch_folder_data = "SELECT foldername, folderid FROM `$user_id` LIMIT $limit";
    $result_sql_fetch_folder_data = mysqli_query($connect_namefolder,$sql_fetch_folder_data);
    if(!$result_sql_fetch_folder_data){
        echo "Error loading data";
        die(mysqli_error($connect_namefolder));
    }else{
        $print_data = "";
        while($data_fetch = mysqli_fetch_array($result_sql_fetch_folder_data)){
         $print_data .= "<tr id = '".$data_fetch['folderid']."' onclick='redirectfolder(this,this.id)'>
                            <td class='data'>".$data_fetch['foldername']."</td>
                            <td class='edit'>edit</td>       
                        </tr>";
        }
        echo($print_data);
    }

?>