<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
require_once 'connection.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $query = $con->prepare("SELECT verify_token, verify_status FROM users WHERE verify_token = ? LIMIT 1");
    $query->bind_param("s", $token);
    $query->execute();
    $run = $query->get_result();
    
    if($run && $run->num_rows > 0){
        $row = $run->fetch_array();
        if($row['verify_status'] == "0"){
            $clicked_token = $row['verify_token'];
            $sql = $con->prepare("UPDATE users SET verify_status='1' WHERE verify_token = ? LIMIT 1");
            $sql->bind_param("s", $clicked_token);
            $sql->execute();
            
            if($sql){
                $_SESSION['status'] = "Your Account has been verified Successfully!";
                header("Location: sign_in.php");
                exit(0);
            }
            else{
                $_SESSION['status'] = "Verification Failed!";
                header("Location: sign_in.php");
                exit(0);
            }
        }
        else{
            $_SESSION['status'] = "This account already been activated. Please Login!";
            header("Location: sign_in.php");
            exit(0);
        }
    }
}