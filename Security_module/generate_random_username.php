<?php
require_once 'connection.php';

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

/* Generate Random Username */
function generateRandomName($length, $con){
    $characters = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM!@#$%^&*_+=-";
    $username = "";
    do {
        for($i = 0; $i < $length; $i++){
           $username .= $characters[rand(0, strlen($characters) - 1)];
        }
    } while(checkDuplicateUsername($username, $con));
    return $username;
}
    
function checkDuplicateUsername($username, $con){
    $query = "SELECT * FROM users WHERE user_name = ? LIMIT 1";
    $sql = $con->prepare($query);
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();
    return($result && $result->num_rows > 0);
}
 
// generate random username from 3 to 12 characters
$username = generateRandomName(rand(3, 12), $con);
echo json_encode($username);