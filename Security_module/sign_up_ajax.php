<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'connection.php';
session_start();

// something was posted
$user_name = $_POST['user-name'];
$email = $_POST['email'];
$email_pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
$password = $_POST['psd'];
$password_hash = password_hash($password, PASSWORD_DEFAULT); // hash the password
$password_pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,.<>§~\|\/?]).{8,}$/';
$confirm_password = $_POST['confirm-psd'];
$verify_token = md5(rand());
    
// Perform various verifications
function checkUserName($user_name, $con){
    // check if username is already taken 
    if ($user_name == NULL) {
        return "Please enter your user name.";
    }
    else if(checkDuplicateName($con, $user_name)){
        return "The username is already taken.";
    }
    else{
        return " ";
    }
}

function checkDuplicateName($con, $user_name){
    $exist = false;
    $uname = $con->prepare("SELECT * FROM users WHERE user_name = ? LIMIT 1");
    $uname->bind_param("s", $user_name);
    $uname->execute();
    $run = $uname->get_result();
    if ($run && $run->num_rows > 0) {
        $exist = true;
    } 
    return $exist;
}
    
function checkEmail($email, $email_pattern, $con){
    // check whether email entered is validity or not
    if ($email != NULL) {
        if (preg_match($email_pattern, $email)) {
            // check if mobile phone is already taken
            $mail = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $mail->bind_param("s", $email);
            $mail->execute();
            $result = $mail->get_result();
            if ($result && $result->num_rows > 0) {
                return "The email address is already taken.";
            }
            else{
                return " ";
            }
        }
        else {
            return "The email address pattern is incorrect.";
        }
    }
    else{
        return "Please enter your email address.";
    }
}
    
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

// Call the function
$error['username'] = checkUserName($user_name, $con);
$error['email'] = checkEmail($email, $email_pattern, $con);
$error['password'] = checkPassword($password, $password_pattern);
$error['confirm_pass'] = checkConfirmPassword($password, $confirm_password);

// Store the error message in an empty array
$response = array();

if (!empty($error['username'])) {
    $response["username"] = $error['username'];
}

if (!empty($error['email'])) {
    $response["email"] = $error['email'];
}

if (!empty($error['password'])) {
    $response["password"] = $error['password'];
}

if (!empty($error['confirm_pass'])) {
    $response["confirm_pass"] = $error['confirm_pass'];
}

// If there is no error message, insert the user information into the users table
if($error['username'] == " " && $error['email'] == " " && $error['password'] == " " && $error['confirm_pass'] == " "){
    // save user to database
    $current_date = date('Y-m-d');
   
    $query = $con->prepare("INSERT INTO users (user_name,email,password,verify_token,join_date) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssss", $user_name, $email, $password_hash, $verify_token, $current_date);
    $query->execute();
    if($query){
        $response["url"] = "sign_in.php";
        $response["token"] = $verify_token;
        $response["send_email"] = $email;
    }
}

// return the array in json form
echo json_encode($response);