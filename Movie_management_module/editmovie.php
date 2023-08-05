<?php
session_start();
require_once 'connection.php';
include 'validpost.php';

if($_SESSION['new_img'] == NULL){
    $_SESSION['new_img'] = "";
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    $query = $con->prepare("SELECT * FROM movie WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    
    if($result && $result->num_rows > 0){
        while($row = $result->fetch_array()){
            $org_mv_name = $row['mv_name'];
            $org_mv_date = $row['release_date'];
            $_SESSION['old_img'] = $row['img']; 
            
            if(isset($_POST['submit'])){
                $mv_name = $_POST['mv_name'];
                $genre_id = $_POST['genre_id'];
                $mv_link1 = $_POST['mv_link1'];
                $mv_link2 = $_POST['mv_link2'];
                $mv_lang = $_POST['mv_lang'];
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
                    $_SESSION['new_img'] = $_FILES['img']['name']; 
                }
                $new_img = $_SESSION['new_img']; 
                $target = "C:/xampp/htdocs/GRCC2/thumb/". basename($_FILES['img']['name']);
                $old_img = $_SESSION['old_img'];
                
                $error['name'] = checkEditMovieName($mv_name, $con, $org_mv_name);
                $error['genre_id'] = checkGenreID($genre_id, $con);
                $error['link'] = checkTrailorLink($mv_link1, $mv_link2);
                $error['date'] = checkEditReleaseDate($mv_date, $org_mv_date);
                $error['duration'] = checkDuration($mv_duration);
                $error['language'] = checkLanguage($mv_lang);
                $error['director'] = checkDirector($mv_director);
                $error['writter'] = checkWritter($mv_writter);
                $error['starring'] = checkStarring($mv_starring);
                $error['music'] = checkMusic($mv_music);
                $error['country'] = checkCountry($country);
                if($_FILES['img']['name'] != ""){
                    $error['img'] = checkFileUploaded($_FILES['img']);
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
        <div id="sidebar" class="sidebar">
            <p class="logo"><span>G</span>RC</p>
            <a href="admin_dashboard.html" class="icon-a"><i class="fa fa-dashboard icons"></i>&nbsp;&nbsp;Dashboard</a>
            <a href="#" class="icon-a"><i class="fa fa-users icons"></i>&nbsp;&nbsp;Customers</a>
            <a href="#" class="icon-a"><i class=" fa fa-light fa-ticket icons"></i>&nbsp;&nbsp;Orders</a>
            <a href="movielist.php" class="icon-a"><i class="fa fa-solid fa-film icons"></i>&nbsp;&nbsp;Movie</a>
            <a href="#" class="icon-a"><i class="fa fa-thin fa-couch icons"></i>&nbsp;&nbsp;Seat </a>
            <a href="staff_detail.php" class="icon-a"><i class="fa fa-user icons"></i>&nbsp;&nbsp;Accounts</a>
            <a href="#" class="icon-a"><i class="fa fa-list-alt icons"></i>&nbsp;&nbsp;Tasks</a>
            <a href="#" class="icon-a"><i class="fa-solid fa-bell fa-shake fa-lg icons"></i>&nbsp;&nbsp;Notice</a>
            <a href="#" class="icon-a"><i class="fa-solid fa-right-from-bracket icons"></i>&nbsp;&nbsp;Log Out</a>
        </div>
        
        <div id="main">
            <div class="head"> 
                <div class="col-div-6"></div>
                <span class="dashboard nav" onclick="dashboard()" >&#9776; Dashboard</span>
                <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Dashboard</span>
            
                <div class="col-div-6"></div>
          
                <div class="profile">
                    <img src="img/baby.jpg" class="pro-img"  alt="">
                    <p>Baby Boss <span>President</span></p>
                </div>
          
                <div class="col-div-8">
                    <div class="box-8">
                        <div class="content-box">
                            <div class="container">
                                <div class="heading">
                                    <p class="add-title">Edit Movie</p><br>
                                </div>
                                <div class="jumbotron" >
                                    <?php
                                    // Display the error message
                                    if(isset($_POST['submit'])){
                                        echo "<ul class='error'>";
                                        foreach($error as $value){
                                            echo "<li>$value</li>";
                                        }
                                        echo "</ul>";
                                    }
                                    ?>
                                    <form action="#" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_name : $row['mv_name'] ?>" name="mv_name" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $genre_id : $row['genre_id'] ?>" name="genre_id" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_link1 : $row['link1'] ?>" name="mv_link1" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_link2 : $row['link2'] ?>" name="mv_link2" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="date" value="<?php echo (isset($_POST['submit'])) ? $mv_date : $row['release_date'] ?>" name="mv_date" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_duration : $row['duration'] ?>" name="mv_duration" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_lang : $row['lang'] ?>" name="mv_lang" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_director : $row['director'] ?>" name="mv_director" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_writter : $row['writter'] ?>" name="mv_writter" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_starring : $row['starring'] ?>" name="mv_starring" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $mv_music : $row['music'] ?>" name="mv_music" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $country : $row['country'] ?>" name="country" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $childTicket_Price : number_format($row['childTicket_Price'], 2, '.', ''); ?>" name="childTicket_Price" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?php echo (isset($_POST['submit'])) ? $adultTicket_Price : number_format($row['adultTicket_Price'], 2, '.', ''); ?>" name="adultTicket_Price" class="form-control" />
                                        </div>
                                        <div class="custom-file mb-3">
                                            <input type="file" name="img" class="custom-file-input" id="customFile" />
                                            <input type='hidden' name='MAX_FILE_SIZE' value='2097152'>
                                            <label class="custom-file-label" for="customFile"></label>
                                        </div>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <div class="form-group">
                                            <textarea type="text" name="mv_desc" class="form-control" rows="4"><?php echo (isset($_POST['submit'])) ? $mv_desc : $row['meta_description'] ?></textarea>
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
        // Update the new info to the database
        if(isset($_POST['submit']) && empty($error)){
            if($new_img != ""){
                $update_filename = $new_img;
            }
            else{
                $update_filename = $old_img;
            }
            
            $query1 = $con->prepare("UPDATE `movie` SET `genre_id`= ?, `mv_name`= ?,`link1`= ?,`link2`= ?,
                      `img`= ?,`release_date`= ?,`duration`= ?,`lang`= ?,
                      `director`= ?,`writter`= ?,`starring`= ?,`music`= ?,
                      `country`= ?,`meta_description`= ?, `childTicket_Price`= ?,
                      `adultTicket_Price`= ? WHERE id = ?");
            $query1->bind_param("isssssssssssssddi", $genre_id, $mv_name, $mv_link1, $mv_link2, $update_filename, $mv_date, $mv_duration, 
                                $mv_lang, $mv_director, $mv_writter, $mv_starring, $mv_music, $country, $mv_desc, $childTicket_Price, $adultTicket_Price, $id);
            $query1->execute();
        
            if($query1){
                if($new_img != ""){
                    move_uploaded_file($_FILES['img']['tmp_name'], $target);                    
                }
                unset($_SESSION['old_img']);
                unset($_SESSION['new_img']);
                echo "<script>alert('Movie Successfully Updated...');window.location.href='movielist.php';</script>";
            }
        } 
        if(!isset($_POST['submit'])){
            // Display the old image name in the box
            setDefaultFileName($row['img']);
        }
        else{
            // Display the new image name in the box
            if($new_img == ""){
                setDefaultFileName($old_img);
            }
            else{
                setDefaultFileName($new_img);
            }
        }
        }
    }
}
else{
    header('Location: movielist.php');
    exit(0);
}
?>
    </body>
    
    <script>
        // 当文件选择器的值发生变化时
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