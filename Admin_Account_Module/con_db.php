<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        define('DB_HOST', "localhost");
        define('DB_USER', "root");
        define('DB_PASS', "");
        define('DB_NAME', "grc");
        
        $con= new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        
        if(!$con){
            die(mysqli_error($con));
        }
        
                
                
                
        ?>
    </body>
</html>
