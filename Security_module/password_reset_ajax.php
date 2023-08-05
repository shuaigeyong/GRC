<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
require_once 'connection.php';

function checkPassword($password, $password_pattern){
    // check whether password entered is validity or not
    if ($password != NULL) {
        if (preg_match($password_pattern, $password) == 0) {
            return "Please refer to the below password policy.";
        }
        else{
            return " ";
        }
    }
    else{
        return "Please enter your password.";
    }
}

function checkConfirmPassword($password, $confirm_password){
    // check whether the confirm password is same as the password or not
    if ($confirm_password) {
        if ($confirm_password !== $password) {
            return "The password do not match.";
        }
        else{
            return " ";
        }
    }
    else{
        return "Please confirm your password.";
    }
}

if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
}
else{
    echo "<script type='text/JavaScript'>window.location.href='password_reset.php';</script>";  
}


$password = $_POST['psd'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$password_pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,.<>§~\|\/?]).{8,}$/';
$confirm_password = $_POST['confirm-psd'];

$error['password'] = checkPassword($password, $password_pattern);
$error['confirm_pass'] = checkConfirmPassword($password, $confirm_password);
  
$response = array();
  
if (!empty($error['password'])) {
    $response["password"] = $error['password']; 
}

if (!empty($error['confirm_pass'])) {
    $response["confirm_pass"] = $error['confirm_pass'];
}
 
if($error['password'] == " " && $error['confirm_pass'] == " "){
    // update the new password to database
    $query = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
    $query->bind_param("ss", $password_hash, $email);
    $query->execute();
    unset($_SESSION['email']);
    $_SESSION['reset_status'] = 1;
    setcookie('username', '', time() - 10);
    setcookie('password', '', time() - 10);
    if($query){
        $response["url"] = "sign_in.php";
    }
}

echo json_encode($response);