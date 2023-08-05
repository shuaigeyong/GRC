<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
*/

require_once 'connection.php';
include 'validpost.php';
session_start();

if($_SESSION['imgName'] == NULL || $_SESSION['img'] == NULL){
    $_SESSION['imgName'] = "";
    $_SESSION['img'] = "";
}

if(isset($_POST['submit'])){
    $mv_name = $_POST['mv_name'];
    $genre_id = $_POST['genre_id'];
    $mv_link1 = $_POST['mv_link1'];
    $mv_link2 = $_POST['mv_link2'];
    $mv_lang = ucwords(strtolower($_POST['mv_lang']));
    $mv_duration = $_POST['mv_duration'];
    $mv_director = ucwords(strtolower($_POST['mv_director']));
    $mv_writter = ucwords(strtolower($_POST['mv_writter']));
    $mv_starring = ucwords(strtolower($_POST['mv_starring']));
    $mv_music = ucwords(strtolower($_POST['mv_music']));
    $country = ucwords(strtolower($_POST['country']));
    $childTicket_Price = $_POST['childTicket_Price'];
    $adultTicket_Price = $_POST['adultTicket_Price'];
    $mv_desc = $_POST['mv_desc'];
    $mv_date = date('Y-m-d', strtotime($_POST['mv_date']));
    if($_FILES['img']['name'] != ""){
        $_SESSION['imgName'] = $_FILES['img']['name'];
        $_SESSION['img'] = $_FILES['img'];
    }
    $img = $_SESSION['imgName'];
    $target = "C:/xampp/htdocs/GRCC2/thumb/". basename($img);
                
    // call the function
    $error['name'] = checkMovieName($mv_name, $con);
    $error['genre_id'] = checkGenreID($genre_id, $con);
    $error['link'] = checkTrailorLink($mv_link1, $mv_link2);
    $error['date'] = checkReleaseDate($mv_date);
    $error['duration'] = checkDuration($mv_duration);
    $error['language'] = checkLanguage($mv_lang);
    $error['director'] = checkDirector($mv_director);
    $error['writter'] = checkWritter($mv_writter);
    $error['starring'] = checkStarring($mv_starring);
    $error['music'] = checkMusic($mv_music);
    $error['country'] = checkCountry($country);
    if($img != ""){
        $error['img'] = checkFileUploaded($_SESSION['img']);
    }
    else if($img == ""){
        $error['img'] = "<b>No file</b> was selected!";
    }
    else{
        $error['img'] = "";
    }
    $error['description'] = checkDescription($mv_desc);
    $error['childTicket_Price'] = checkChildTicketPrice($childTicket_Price);
    $error['adultTicket_Price'] = checkAdultTicketPrice($adultTicket_Price);
                
    $error = array_filter($error);  
    
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>GRC Cinema</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="../css/addmovie.css" rel="stylesheet" type="text/css"/>
        <script src="../js/admin_dashboard.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
        include 'sidebar_detail.php';
        ?>
        
        <div id="main">
        <div class="head">
          <div class="col-div-6"></div>
            <span class="dashboard nav" onclick="dashboard()" >&#9776; Dashboard</span>
            <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Dashboard</span>
            

          <div class="col-div-6"></div>
          
          <div class="profile">
              <img src="img/baby.jpg" class="pro-img" alt="">
              <p >Baby Boss <span>President</span></p>
          </div>
          
          <div class="col-div-8">
            <div class="box-8">
                <div class="content-box">
                    <div class="container">
                        <div class="heading">
                            <p class="add-title">Add Movie</p><br>
                        </div>
                        <div class="jumbotron">
                            <?php
                            if(isset($_POST['submit'])){
                                // If there is no error, insert the data into database
                                if(empty($error)){
                                    $query = "INSERT INTO `movie`(`genre_id`, `mv_name`, `link1`, `link2`, `img`, `release_date`, `duration`, `lang`, `director`, `writter`, `starring`, `music`, `country`,`meta_description`, `childTicket_Price`, `adultTicket_Price`)
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
                                    $stmt = $con->prepare($query);
                                    $stmt->bind_param("isssssssssssssdd", $genre_id, $mv_name, $mv_link1, $mv_link2, $img, $mv_date, $mv_duration, $mv_lang, $mv_director, $mv_writter, $mv_starring, $mv_music, $country, $mv_desc, $childTicket_Price, $adultTicket_Pirce);
                                    $stmt->execute();
                                    move_uploaded_file($_FILES['img']['tmp_name'], $target);
        
                                    if($stmt->affected_rows > 0){
                                        unset($_SESSION['img']);
                                        unset($_SESSION['img_name']);
                                        echo "<script>alert('Movie Successfully Added...');window.location.href='movielist.php';</script>";
                                    }
                                    else{
                                        echo "<script>alert('Something Went Wrong!!');window.location.href='addmovie.php';</script>";
                                    }
                                }
                                else{
                                    // If there is error, display the error
                                    echo "<ul class='error'>";
                                    foreach($error as $value){
                                        echo "<li>$value</li>";
                                    }
                                    echo "</ul>";
                                }
                            }
                            ?>
                            
                            <form action="addmovie.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="text" name="mv_name" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_name : ''; ?>" placeholder="Enter Movie Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="genre_id" class="form-control" value="<?php echo isset($_POST['submit']) ? $genre_id : ''; ?>" placeholder="Enter Genre ID">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_link1" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_link1 : ''; ?>" placeholder="Enter Link 1">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_link2" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_link2 : ''; ?>" placeholder="Enter Link 2">
                                </div>
                                <div class="form-group">
                                    <input type="date" name="mv_date" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_date : 'dd/mm/yyyy'; ?>" placeholder="Enter Release Date">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_duration" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_duration : ''; ?>" placeholder="Enter Movie Duration (Example Format: 120 minutes)">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_lang" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_lang : ''; ?>" placeholder="Enter Movie Language">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_director" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_director : ''; ?>" placeholder="Enter Movie Director">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_writter" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_writter : ''; ?>" placeholder="Enter Movie Writter">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_starring" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_starring : ''; ?>" placeholder="Enter Movie Starring">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mv_music" class="form-control" value="<?php echo isset($_POST['submit']) ? $mv_music : ''; ?>" placeholder="Enter Movie Music Producer">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="country" class="form-control" value="<?php echo isset($_POST['submit']) ? $country : ''; ?>" placeholder="Enter Movie's Country">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="childTicket_Price" class="form-control" value="<?php echo isset($_POST['submit']) ? $childTicket_Price : ''; ?>" placeholder="Enter Child Ticket Price (Example Format: 13.00)">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="adultTicket_Price" class="form-control" value="<?php echo isset($_POST['submit']) ? $adultTicket_Price : ''; ?>" placeholder="Enter Adult Ticket Price (Example Format: 19.00)">
                                </div>
                                <div class="custom-file mb-3">
                                    <input type="file" name="img" class="custom-file-input" id="customFile">
                                    <input type='hidden' name='MAX_FILE_SIZE' value='2097152'>
                                    <label class="custom-file-label" for="customFile"></label>
                                </div>
                                <br/>
                                <br/>
                                <br/>
                                <div class="form-group">
                                    <textarea type="text" name="mv_desc" class="form-control" placeholder="Enter Movie Description" rows="4"><?php echo isset($_POST['submit']) ? $mv_desc : ''; ?></textarea>
                                </div>
                                
                                <button type="submit" name="submit" class="btn btn-info btn-lg">Submit</button>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php
        // display the file name in the box
        if(isset($_POST['submit'])){
            setDefaultFileName($img);
        }
        else{
            setDefaultFileName("No File Chosen");
        }
        ?>
    </body>
    <script>
        // When the value of the file selector changes
        // display the file name that admin chose
        $('#customFile').on('change', function() {
        // Get the file name
        var fileName = $(this).val().split('\\').pop(); 
        // val() -> value of the selected file (absolute pathname), split('\\') -> divide the string based on '\', pop() -> select the last group of the string
        // Display the file name in the box
        $(this).siblings('.custom-file-label').html(fileName); 
        // $(this).siblings('.custom-file-label') -> select the siblings of the selected thing(#customFile) which class name is "custom-file-label"
        });
    </script>
</html>