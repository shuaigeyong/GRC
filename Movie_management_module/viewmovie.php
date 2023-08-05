<?php
include 'connection.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    $query = "SELECT * FROM movie WHERE id = $id";
    $run = mysqli_query($con, $query);
    if($run){
        while($row = mysqli_fetch_assoc($run)){
            ?>
            
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link href="../css/viewmovie.css" rel="stylesheet" type="text/css"/>
        <script src="../js/admin_dashboard.js" type="text/javascript"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;600;700;900&family=Roboto:wght@500&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Alkatra:wght@700&family=Oswald:wght@700&family=Poppins:wght@100;600;700;900&family=Roboto:wght@500;900&display=swap');
        </style>
        <title>GRC Cinema</title>
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
                <img src="img/baby.jpg" class="pro-img"  alt="">
                <p>Baby Boss <span>President</span></p>
            </div>
          
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box">
                        <div class="heading">
                            <p class="p1">Movie Info</p><br>
                        </div>
                    </div>
                        
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div><?php echo "<img src='../thumb/".$row['img']."' height='400px' width='300px' />" ?></div>
                            </div>
                            <div class="col" id="col">
                                <h1><?php echo $row['mv_name']; ?></h1>
                                <br/><br/>
                                <div class="detail">
                                    <pre>RELEASE DATE<span class="value"><?php echo $row['release_date']; ?></span></pre>
                                    <pre>RUNNING TIME<span class="value"><?php echo $row['duration']; ?></span></pre>
                                    <pre>DIRECTED BY<span class="value"><?php echo $row['director']; ?></span></pre>
                                    <pre>WRITTEN BY<span class="value"><?php echo $row['writter']; ?></span></pre>
                                    <pre>STARRING<span class="value"><?php echo $row['starring']; ?></span></pre>
                                    <pre>MUSIC BY<span class="value"><?php echo $row['music']; ?></span></pre>
                                    <pre>LANGUAGE<span class="value"><?php echo $row['lang']; ?></span></pre>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>   
                            <!-- Movie Trailor -->
                            <div class="video-container">
                                <button class="btn btn-info" onclick="changeVideo(0)">Video 1</button>
                                <button class="btn btn-success" onclick="changeVideo(1)">Video 2</button><br />
                                
                                <iframe id="video" width="900" height="450" src="" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <br />
                            <div class="synopsis">
                                <p class="label">SYNOPSIS</p>
                                <p class="value"><?php echo $row['meta_description']; ?></p>
                            </div>
                            <div style="height: 50px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div> 
        
        <!-- Video Container -->
        <script>
            // store the video link in an array
            var videos = [
                "<?php echo $row['link1']; ?>",
                "<?php echo $row['link2']; ?>"
            ];
            
            var defaultID = getVideoId(videos[0]);
            var iframe = document.getElementById("video");
            
            iframe.src = "https://www.youtube.com/embed/" + defaultID;
            
            // index 0 -> Video 1, index 1 -> Video 2
            function changeVideo(index){
                var link = videos[index];
                var videoID = getVideoId(link);
                
                iframe.src = "https://www.youtube.com/embed/" + videoID;
            }
            
            // get the video ID
            function getVideoId(link){
                var params = new URLSearchParams(new URL(link).search);
                return params.get('v');
            }
        </script>
                <?php
        }
    }
}
        ?>
    </body>
</html>
