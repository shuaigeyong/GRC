<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
$org_username = $_SESSION['username'];
$org_password = $_SESSION['password'];
$username = mysqli_real_escape_string($con, $_POST['update_name']);
$update_password = $_POST['update_pass'];
$new_password = $_POST['new_pass'];
$confirm_password = $_POST['confirm_pass'];
$hashed_password = "";
$password_pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,.<>§~\|\/?]).{8,}$/';
$new_img = $_FILES['update_image']['name'];
$target = "C:/xampp/htdocs/GRCC2/profile/". basename($_FILES['update_image']['name']);
$old_img = $_SESSION['old_img'];

function checkEditUserName($username, $con, $org_username){
    if($username == NULL){
        return "Please enter the username.";
    }
    else if(checkEditDuplicateName($username, $con, $org_username)){
        return "The username is already taken.";
    }
    else{
        return " ";
    }
}

function checkEditDuplicateName($username, $con, $org_username){
    $exist = false;
    $sql = $con->prepare("SELECT * FROM users WHERE user_name = ? LIMIT 1");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();
    if($result && $result->num_rows > 0){
        // check if the username that user has entered is same as his/her current username
        // if same, $exists = false
        // else, $exists = true
        if($username != $org_username){
            $exist = true;
        }
    }
    return $exist;
}

function checkFileUploaded($img){
    // Check if there is any upload error?
    $file = $img;
    if($file['error'] > 0){
        // This validation is done by PHP
        // WITH ERROR, handle to display error msg
        switch($file['error']){
            case UPLOAD_ERR_NO_FILE:
                return "No file was selected.";
                break;
            
            case UPLOAD_ERR_FROM_SIZE:
                return "File uploaded is too large. Maximum 1MB is allowed.";
                break;
            
            default: // other error
                return "There was an error when uploading the file.";
                break;
        }  
    }
    else if($file['size'] > 2097152){
        // Validate specifically, file size
        // 1MB = 1024 x 1024
        return "File uploaded is too large. Max 1MB is allowed.";
    }
    else{
        // Extract file extension, eg: png,jpg, gif
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check file extension
        if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif'){
            return "Only JPG, JPEG, GIF and PNG images are allowed.";
        }
        else{
            return " ";
        }
    }
}

function checkPasswordMatching($update_password, $con, $org_username, $hashed_password){
    $query = $con->prepare("SELECT password FROM users WHERE user_name = ? LIMIT 1");
    $query->bind_param("s", $org_username);
    $query->execute();
    $query->bind_result($hashed_password);
    $query->fetch();
    $query->close();
            
    // verify the password
    $password_verify = password_verify($update_password, $hashed_password);
            
    if(!$password_verify){
        return "This password is not the same as the registered password.";
    }
    else{
        return " ";
    }
}

function checkNewPassword($new_password, $password_pattern){
    if ($new_password == NULL) {
        return "Please enter the new password.";
    }
    else{
        if (preg_match($password_pattern, $new_password) == 0) {
            return "The password must contain at least 1 uppercase letter, 1 lowercase letter, 1 digit, 1 special character, and have a minimum length of 8 characters.";
        }
        else{
            return " ";
        }
    }
}

function checkConfirmPassword($confirm_password, $new_password){
    if ($confirm_password == NULL) {
        return "Please enter the confirm password.";
    }
    else{
        if ($confirm_password !== $new_password) {
            return "The password does not match.";
        }
        else{
            return " ";
        }
    }
}

$error['username'] = checkEditUserName($username, $con, $org_username);
if($new_img != ""){
    $error['img'] = checkFileUploaded($_FILES['update_image']);
}
else{
    $error['img'] = " ";
}

// If the $update_password (old password) is not NULL, the $new_password and $confirm_password also cannot be NULL
if($update_password != NULL){
    $error['update_password'] = checkPasswordMatching($update_password, $con, $org_username, $hashed_password);
    $error['new_password'] = checkNewPassword($new_password, $password_pattern);
    $error['confirm_password'] = checkConfirmPassword($confirm_password, $new_password);
}
// If the $update_password (old password) is NULL but the $new_password OR $confirm_password is/are not NULL
// ask the user to enter the $update_password (old password)
else if($update_password == NULL && ($new_password != NULL || $confirm_password != NULL)){
    $error['update_password'] = "Please enter the old password.";
    $error['new_password'] = checkNewPassword($new_password, $password_pattern);
    $error['confirm_password'] = checkConfirmPassword($confirm_password, $new_password);
}
else{
    $error['update_password'] = " ";
    $error['new_password'] = " ";
    $error['confirm_password'] = " ";
}

// Store the error message in an empty array
$response = array();

if (!empty($error['username'])) {
    $response["username"] = $error['username'];
}

if (!empty($error['img'])) {
    $response["img"] = $error['img'];
}

if (!empty($error['update_password'])) {
    $response["update_password"] = $error['update_password'];
}

if (!empty($error['new_password'])) {
    $response["new_password"] = $error['new_password'];
}

if (!empty($error['confirm_password'])) {
    $response["confirm_password"] = $error['confirm_password'];
}

if($error['username'] == " " && $error['img'] == " " && $error['update_password'] == " " && $error['new_password'] == " " && $error['confirm_password'] == " "){
    // If the profile image has changed, update the new profile image
    // Else use the old profile image
    if($new_img != ""){
        $update_filename = $new_img;
    }
    else{
        $update_filename = $old_img;
    }
    
    // If the password has changed, hash the new password
    // Else use the old password (already hashed)
    if($new_password != ""){
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT); 
    }
    else{
        $password_hash = $org_password;
    }
    
    $sql = $con->prepare("UPDATE users SET user_name = ?, password = ?, profile_img = ? WHERE user_id = ?");
    $sql->bind_param("sssi", $username, $password_hash, $update_filename, $user_id);
    $sql->execute();
    if($sql){
        if($new_img != ""){
            move_uploaded_file($_FILES['update_image']['tmp_name'], $target);                    
        }
        $_SESSION['update_profile_status'] = 1;
        
        // If the profile has updated, delete the cookie
        setcookie('username', $username, time() - 10);
        setcookie('password', $password_hash, time() - 10);
        
        // If the password has changed, redirect the user to the sign_in.php to login again
        // Else back to homepage
        if($new_password != ""){
            unset($_SESSION['user_id']);
            $response["url"] = "sign_in.php";
        }
        else{
            $response["url"] = "../Movie_management_module/homepage.php";
        }
    }
}
 
// return the array in json form
echo json_encode($response);