<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "grc";

//if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){
  //  die("failed to connect!");
//}

$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}