<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once '../../config/db.php';

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
if(isset($_GET['id'])){
    $payment_id = $_GET['id'];
        
    $sql = "DELETE FROM INVOICE WHERE PAYMENT_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    
    
    $sql = "DELETE FROM PAYMENT WHERE PAYMENT_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
        
    if($stmt){
        header('Location:transaction.php');
        exit(0);
    }
    else{
        echo "<script>alert('Something Went Wrong!!');window.location.href='transaction.php';</script>";
    }
}
else{
    header('Location: transaction.php');
    exit(0);
}