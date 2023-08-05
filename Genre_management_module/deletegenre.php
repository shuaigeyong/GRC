<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'connection.php';

if(isset($_GET['deleteid'])){
    $genre_id = trim($_GET['deleteid']);
    

        $query = $con->prepare("DELETE FROM genre WHERE genre_id = ?");
        $query->bind_param("i", $genre_id);
        $query->execute();
    
        if($query){
            header("Location: genrelist.php");
            exit(0);
        }
        else{
            echo "<script>alert('Something Went Wrong!');window.location.href='genrelist.php';</script>";
        }
        $query->free_result();
        $con->close();
    }

else{
    header("Location: genrelist.php");
    exit(0);
}