<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
require_once 'connection.php';

    if(!isset($_POST['email'])){
        header("Location: resend_verification_email.php");
        exit(0);
    }
    
    if($_POST['email'] != ""){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $email_pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        
        if (preg_match($email_pattern, $email)){
            $check_email = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $run = $check_email->get_result();
        
            if($run && $run->num_rows > 0){
                $row = $run->fetch_array();
                $get_email = $row['email'];
                $verify_token = $row['verify_token'];
            
                if($row['verify_status'] == "0"){
                    $response['resendmail_token'] = $verify_token;
                    $response['resendmail_email'] = $get_email;
                    $response['url'] = "sign_in.php";
                }
                else{
                    $response['resendmail'] = "The email has already been activated. Please Login!";
                }
            }
            else{
                $response['resendmail'] = "No email found. Please enter the valid email.";
            }
        }
        else{
            $response['resendmail'] = "The email address pattern is incorrect.";
        }
    }
    else{
        $response['resendmail'] = "Please enter your email address.";
    }
        
echo json_encode($response);