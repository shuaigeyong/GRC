<?php
include 'con_db.php';
if(isset($_GET['ratingid'])){
    $id = $_GET['ratingid'];
    $movie_id = $_GET['movieid'];
    $sql = "DELETE FROM rating WHERE rating_id = $id";
    $result = mysqli_query($con, $sql);
    
    $location="location:rating_comment.php?movie_id=". "$movie_id";
    if($result){
        header($location);
       
    }else{
        die(mysqli_error($con));
    }
}
?>

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
        // put your code here
        ?>
    </body>
</html>
