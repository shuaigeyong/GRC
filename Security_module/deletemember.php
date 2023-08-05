<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'connection.php';

if(isset($_GET['deleteid'])){
    $id = trim($_GET['deleteid']);
    
    $sql = $con->prepare("UPDATE users SET account_available = 0 WHERE user_id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    
    if($sql){
        header("Location: member_list.php");
        exit(0);
    }
    else{
        echo "<script>alert('Something Went Wrong!!');window.location.href='member_list.php';</script>";
    }
}
else if(isset($_GET['restoreid'])){
    $id = trim($_GET['restoreid']);
    
    $sql = $con->prepare("UPDATE users SET account_available = 1 WHERE user_id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    
    if($sql){
        header("Location: banned_list.php");
        exit(0);
    }
    else{
        echo "<script>alert('Something Went Wrong!!');window.location.href='banned_list.php';</script>";
    }
}
else{
    header("Location: member_list.php");
    exit(0);
}