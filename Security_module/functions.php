<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function random_num($length){
    $text = "";
    if($length < 5){
        $length = 5;
    }

    $len = rand(4, $length);
    
    for($i = 0; $i < $len; $i++){
        $text .= rand(0, 9);
    }
    return $text;
}

function random_otp($length){
    $text = "";
    
    // make sure that the first digit if OTP is not 0 bacause the database cannot detect if the first digit is 0
    $text[0] = rand(1, 9);
    for($i = 1; $i < $length; $i++){
        $text .= rand(0, 9);
    }
    return $text;
}