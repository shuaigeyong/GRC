<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

if(isset($_POST['name'])){
    $name = $_POST['name'];
    
    if(!preg_match('/^[a-zA-Z][a-zA-Z\s]*$/', $name)){
        $error = "Your name is invalid (Only Contain Alphabet and Space, First Letter must be Alphabet).";              
    } 

    // Return errors in JSON format
    echo json_encode($error);
}

if(isset($_POST['contactNo'])){
    $contactNo = $_POST['contactNo'];
    
    if(strlen($contactNo) < 10){
        $error = "Your contact number is incomplete.";
    }
    else if(!preg_match('/^601[012346789]{1}\d{7,8}$/', $contactNo) && !preg_match('/^60[3456789]\d{7,8}$/', $contactNo)){
        $error = "Your contact number is invalid.";      
    }      
        
    // Return errors in JSON format
    echo json_encode($error);
}


if(isset($_POST['nameEdit'])){
    $name = $_POST['nameEdit'];
    
    if(!preg_match('/^[a-zA-Z][a-zA-Z\s]*$/', $name)){
        $error = "Your name is invalid (Only Contain Alphabet and Space, First Letter must be Alphabet).";              
    } 
    
    else{
        $error = "";
    }

    // Return errors in JSON format
    echo json_encode($error);
}

if(isset($_POST['contactNoEdit'])){
    $contactNo = $_POST['contactNoEdit'];
    
    if(strlen($contactNo) < 10){
        $error = "Your contact number is incomplete.";
    }
    else if(!preg_match('/^601[012346789]{1}\d{7,8}$/', $contactNo) && !preg_match('/^60[3456789]\d{7,8}$/', $contactNo)){
        $error = "Your contact number is invalid.";      
    }      
    else{
        $error = "";
    }
        
    // Return errors in JSON format
    echo json_encode($error);
}

if(isset($_POST['payment_methodEdit'])){
    $payment_method = $_POST['payment_methodEdit'];
    
    if(!preg_match('/^Debit \/ Credit Card$/', $payment_method) && !preg_match('/^Paypal$/', $payment_method)){
        $error = "Your payment method is invalid (Only Contain Debit / Credit Card and Paypal).";              
    }  
    else{
        $error = "";
    }
    
    // Return errors in JSON format
    echo json_encode($error);
}