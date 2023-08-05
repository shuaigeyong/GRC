<?php
    require_once 'connection.php';
            
    if(isset($_GET['id'])){
        $id = trim($_GET['id']);
        
        $query = $con->prepare("DELETE FROM movie WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        
        if($query){
            header('Location:movielist.php');
            exit(0);
        }
        else{
            echo "<script>alert('Something Went Wrong!!');window.location.href='movielist.php';</script>";
        }
    }
    else{
        header('Location: movielist.php');
        exit(0);
    }        