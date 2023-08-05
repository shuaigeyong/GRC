<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'connection.php';

if(isset($_POST['id'])){
    $genre_id = trim($_POST['id']);
    
    $sql = $con->prepare("SELECT COUNT(*) AS TOTAL FROM genre g, movie m WHERE g.genre_id = m.genre_id AND g.genre_id = ?");
    $sql->bind_param("i", $genre_id);
    $sql->execute();
    $result = $sql->get_result();
    if($result){
        $row = $result->fetch_array();
        if($row["TOTAL"] == 0){
            $response = array('total' => 0);
        }
        else{
            $response = array('total' => $row["TOTAL"]);
        }
    }
    $result->free_result();
    $con->close();
}
else{
    header("Location: genrelist.php");
    exit(0);
}

echo json_encode($response);