<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
require_once 'connection.php';
    
// something was posted
$user_name = $_POST['user-name'];
$password = $_POST['psd'];
$current_time = time();

if($user_name != "" && $password != ""){
    $error['username'] = " ";
    $error['password'] = " ";
        
    $sql = $con->prepare("SELECT * FROM users WHERE user_name = ? LIMIT 1");
    $sql->bind_param("s", $user_name);
    $sql->execute();
    $run = $sql->get_result();
    
    if($run && $run->num_rows > 0){
        $row = $run->fetch_array();
        $last_attempt_time = $row['last_attempt_time'];
        $life = $row['life'];
        $hashed_password = $row['password'];
        
        $error['username'] = " ";
        $error['password'] = " ";
        
        // the number of attempts will be reset every 10 minutes (same as blocking time)
        if($current_time - $last_attempt_time >= 600){
            $life = 5;
        }
        
        // check if the user has tried the same account password more than 3 times
        if($life > 0){
            // verify the password
            $password_verify = password_verify($password, $hashed_password); // plaintext password
            
            // read from database
            if($hashed_password !== false && $password_verify){
                // update the number of attempts if the password entered is correct
                $sql = $con->prepare("UPDATE users SET life = 5 WHERE user_name = ?");
                $sql->bind_param("s", $user_name);
                $sql->execute();
                if($sql){
                    // Nothing to do
                }
                else{
                    $error['password'] = "Something went wrong! Please contact customer service.";
                }
                
                // If user type is user
                // 1. check the account is banned or not, if NO
                // 2. check the remember me checkbox is set or not, if YES -> set the cookie
                // 3. check the account is activated or not, if YES -> bring them to captcha.php, 
                //                                           if NO  -> ask the user if they want to resend the activate account email
                if($row['user_type'] == "user"){
                    if($row['account_available'] == 1){
                        $error['password'] = " ";
                        
                        $_SESSION['user_id'] = $row['user_id'];
                        // to set cookie
                        if(isset($_POST['remember'])){
                            setcookie('username', $user_name, time() + 7 * 24 * 60 * 60); // current time + (7 * 24hours * 60sec * 60sec)
                            setcookie('password', $password, time() + 7 * 24 * 60 * 60);  // current time + (7 * 24hours * 60sec * 60sec)
                        }
                        // to expire cookie
                        else{
                            setcookie('username', '', time() - 10);    // current time - 10 sec
                            setcookie('password', '', time() - 10);     // current time - 10 sec
                        }
                    
                        if($row['verify_status'] == "1"){
                            $_SESSION['login_status'] = 1;
                            $error['url'] = "captcha.php";
                        }
                        else{
                            $_SESSION['status'] = "Please Verify your Email Address to Login";
                            $_SESSION['resendmail'] = "Do you want to resend the Verification Email?";
                            unset($_SESSION['user_id']);
                            $error['url'] = "sign_in.php";
                        }
                    }
                    else{
                        $error['password'] = "Your account has been banned due to a violation of our terms and conditions.";
                    }
                }
                // If user type is not the user (admin), bring them to the admin dashboard and delete the cookie
                else{
                    setcookie('username', '', time() - 10);
                    setcookie('password', '', time() - 10);
                    $error['url'] = "../Admin_Account_Module/admin_dashboard.php";
                }
            }
            else{
                $life -= 1;
                
                if($life > 0){
                    $error['password'] = "The password is incorrect.";
                }
                else{
                    $error['password'] = "Your account has been blocked. Please try after 10 : 00.";
                }
                
                // update the number of attempt and last attempt time in real time
                $sql = $con->prepare("UPDATE users SET life = ?, last_attempt_time = ? WHERE user_name = ?");
                $sql->bind_param("iis", $life, $current_time, $user_name);
                $sql->execute();
            
                if($sql){
                    // Nothing to do
                }
                else{
                    $error['password'] = "Something went wrong! Please contact customer service.";
                }
            }
        }
        else{
            // show the remaining blocking time for that account
            $remaining_min = floor((600 - ($current_time - $last_attempt_time)) / 60);
            $remaining_sec = (600 - ($current_time - $last_attempt_time)) % 60;
            $time_remaining = $remaining_min . " : " . ($remaining_sec < 10 ? "0" : "") . $remaining_sec;
            $error['password'] = "Your account has been blocked. Please try after " . $time_remaining . ".";
        }
    }
    else{
        $error['username'] = "The username does not exists.";
        $error['password'] = "The password is incorrect.";
    }
}
else{
    if($user_name == ""){
        $error['username'] = "Please enter your username.";
    }
    else{
        $error['username'] = " ";
    }
    if($password == ""){
        $error['password'] = "Please enter your password.";
    }
    else{
        $error['password'] = " ";
    }
}

// Store the error message in an empty array
$response = array();

if (!empty($error['username'])) {
    $response["username"] = $error['username'];
}

if (!empty($error['password'])) {
    $response["password"] = $error['password'];
}

if($error['username'] == " " && $error['password'] == " "){
    $response["url"] = $error['url'];
}

// return the array in json form
echo json_encode($response);