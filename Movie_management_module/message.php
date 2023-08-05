<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'connection.php';

$getMesg = mysqli_real_escape_string($con, $_POST['text']);

$getMesgFilter = explode(" ", $getMesg);
$reply_status = 0;

for($i = 0; $i<count($getMesgFilter);$i++){
    // checking user query to database query
    $check_data = "SELECT replies FROM chatbot WHERE queries LIKE ?";
    $run = $con->prepare($check_data);
    $param = "%".$getMesgFilter[$i]."%";
    $run->bind_param("s", $param);
    $run->execute();
    $result = $run->get_result();

    // id user query matched to database query we'll show the reply otherwise it go to else statement
    if($result->num_rows > 0){
        $row = $result->fetch_array();
        if($row){
            echo $row['replies'];
            $reply_status = 1;
            break;
        }
    }
    else{
        continue;
    }
}

if($reply_status != 1){
    echo "I'm sorry, I don't understand your question. Could you please rephrase or provide more context?";
}