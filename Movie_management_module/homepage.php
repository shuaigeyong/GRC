<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/homepage.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
         <!-- Link Swiper's CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Alkatra:wght@500;700&family=Ma+Shan+Zheng&family=Oswald:wght@700&family=Poppins:wght@100;200;300;400;500;600;700;900&family=Roboto:wght@500;900&display=swap');
        </style>
        <title>GRC Cinema</title>
    </head>
    <body>
        <?php 
            include 'cust_header.php';
            include 'AI_chatbot.php';
            
            if(isset($_SESSION['update_profile_status'])){
                echo "<script>alert('Profile Successfully Updated...');</script>";
                unset($_SESSION['update_profile_status']);
            }
        ?>
        <div class="msg-icon"><i class="fa-sharp fa-solid fa-headset"></i></div>
        <!-- Home -->
        <section class="home swiper" id="home">
            <div class="swiper-wrapper">
                <!-- Box 1 -->
                <div class="swiper-slide container" style="display: flex; align-items: center;">
                    <img src="../images/Demon_Slayer-slideshow.png" alt="" />
                    <div class="home-text">
                        <span>Demon Slayer:<br>Swordsmith Village</span><br><br>
                        <!-- If the user doesn't sign in, the user is not allowed to buy the ticket -->
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <a href="../Seat_Module/seatsel.php?id=6" class="btn">Book Now</a>
                        <?php }else{ ?>
                            <a class="btn" onclick="alert('Please sign in first!')" style="cursor: pointer">Book Now</a>
                        <?php } ?>
                        <a href="movieinfo.php?id=6" class="btn" id="btn-info">More Info</a>
                        <div class="video-container">
                            <a href="https://www.youtube.com/embed/a9tq0aS5Zu8" class="play" id="video-1">
                                <i class='bx bx-play-circle'></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Box 2 -->
                <div class="swiper-slide container" style="display: flex; align-items: center;">
                    <img src="../images/Your_Name-slideshow.png" alt="" />
                    <div class="home-text">
                        <span>Your Name</span><br><br>
                        <!-- If the user doesn't sign in, the user is not allowed to buy the ticket -->
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <a href="../Seat_Module/seatsel.php?id=2" class="btn">Book Now</a>
                        <?php }else{ ?>
                            <a class="btn" onclick="alert('Please sign in first!')" style="cursor: pointer">Book Now</a>
                        <?php } ?>
                        <a href="movieinfo.php?id=2" class="btn" id="btn-info">More Info</a>
                        <div class="video-container">
                            <a href="https://www.youtube.com/embed/k4xGqY5IDBE" class="play" id="video-2">
                                <i class='bx bx-play-circle'></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Box 3 -->
                <div class="swiper-slide container" style="display: flex; align-items: center;">
                    <img src="../images/Venom-slideshow.png" alt="" />
                    <div class="home-text">
                        <span>Venom: Let There <br/> Be Carnage</span><br><br>
                        <!-- If the user doesn't sign in, the user is not allowed to buy the ticket -->
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <a href="../Seat_Module/seatsel.php?id=7" class="btn">Book Now</a>
                        <?php }else{ ?>
                            <a class="btn" onclick="alert('Please sign in first!')" style="cursor: pointer">Book Now</a>
                        <?php } ?>
                        <a href="movieinfo.php?id=7" class="btn" id="btn-info">More Info</a>
                        <div class="video-container">
                            <a href="https://www.youtube.com/embed/-ezfi6FQ8Ds" class="play" id="video-3">
                                <i class='bx bx-play-circle'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </section>
        
        <!-- Movies -->
        <section class="movies" id="movies">
            <h2 class="heading">Opening This Week</h2>
            <!-- Movies Container -->
            <div class="movies-container">
            <?php
            $currentDate = date('Y-m-d');
            $query = $con->prepare("SELECT * FROM movie m, genre g WHERE g.genre_id = m.genre_id AND release_date <= ? ORDER BY id");
            $query->bind_param("s", $currentDate);
            $query->execute();
            $run = $query->get_result();
        
            if($run){
                while($row = $run->fetch_array()){
            ?>
                <div class="box">
                    <div class="box-img">
                        <?php echo "<img src='../thumb/".$row['img']."' />" ?>
                    </div>
                    <h3 class="movie-title"><?php echo $row['mv_name']; ?></h3>
                    <span><?php echo $row['duration']; ?> | <?php echo $row['genre_name']; ?></span>
                    <br/>
                    <!-- If the user doesn't sign in, the user is not allowed to buy the ticket -->
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <a href="../Seat_Module/seatsel.php?id=<?php echo $row['id']; ?>" id="btn-1">Book Now</a><br/>
                    <?php }else{ ?>
                        <a id="btn-1" onclick="alert('Please sign in first!')" style="cursor: pointer">Book Now</a><br/>
                    <?php } ?>
                    <a href="movieinfo.php?id=<?php echo $row['id']; ?>" id="btn-2">More Info</a>                  
                </div>
            <?php
                }
            }
            ?>
            </div>
        </section>
        
        <!-- Coming Soon -->
        <section class="movies" id="coming">
            <h2 class="heading">Coming Soon</h2>
            <!-- Coming Soon Container -->
            <div class="coming-container swiper">
                <div class="swiper-wrapper">
                    <?php
                    $query = $con->prepare("SELECT * FROM movie m, genre g WHERE g.genre_id = m.genre_id AND release_date > ?");
                    $query->bind_param("s", $currentDate);
                    $query->execute();
                    $run = $query->get_result();
        
                    if($run){
                        while($row = $run->fetch_array()){
                    ?>
                        <div class="swiper-slide box">
                            <div class="box-img">
                                <?php echo "<img src='../thumb/".$row['img']."' />" ?>
                            </div>
                            <h3 class="movie-title"><?php echo $row['mv_name']; ?></h3>
                            <span><?php echo $row['duration']; ?> | <?php echo $row['genre_name']; ?></span>
                            <br/>
                            <a href="movieinfo.php?id=<?php echo $row['id']; ?>" id="btn-1">More Info</a>                  
                        </div>
                    <?php       
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
        
        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
        
        <!-- Link To Custom JS -->
        <script src="../js/homepage.js" type="text/javascript"></script>
        
        <!-- The Most Popular -->
        <section class="movies" id="popular">
            <h2 class="heading" style='text-align: left;'>The Most Popular</h2>
            <a href="movieinfo.php?id=2" alt="">
                <div class="popular-img">
                    <div class="wrapper">
                        <img src="../images/The_Most_Popular_1.png" class="image-1" />
                        <img src="../images/The_Most_Popular_2.png" class="image-2" />
                    </div>
                </div>
            </a>
        </section>
        
        <div style="height: 450px;"></div>
        
        <?php include 'cust_footer.php' ?>
        
        <!-- Video Pop-ups -->
        <script>
            $(document).ready(function(){
                var playBtn = $('.play');
                var modal = null;
                
                playBtn.click(function(e) {
                    var videoUrl = $(this).attr('href');
                    
                    e.preventDefault();
  
                    if(modal === null){
                        modal = $('<div>').addClass('modal');
                        var iframe =  $('<iframe>').attr({src: videoUrl, allowfullscreen: '', frameborder: 0});
  
                        modal.append(iframe);
                        $('body').append(modal);
                    
                        var closeButton = $('<button>').addClass('close-button');
                        closeButton.text('X');
                        modal.append(closeButton);
                    
                        closeButton.click(function(){
                            modal.hide(); // Hide video pop-ups
                            iframe.remove(); // Close the video
                            modal = null;
                        });
                    }
                    else{
                        modal.toggle();
                    }
                });
            });
        </script>
        
        <!-- AI chatbot -->
        <script>
            var chatbot = document.getElementsByClassName("wrapper-1")[0];
            var robot = document.getElementsByClassName("msg-icon")[0];
            
            robot.addEventListener("click", function(){
                if (chatbot.style.display === "none") {
                    chatbot.style.display = "block";
                } 
                else {
                    chatbot.style.display = "none";
                }
            });
        </script>
        
        <!-- Animation -->
        <script>
            $(document).ready(function(){
                $(window).on('scroll', function() {
                    $('.movies-container .box').each(function() {
                        if ($(this).offset().top - $(window).scrollTop() < $(window).height() * 0.8) {
                            $(this).addClass('visible');
                        }
                    });
                    $('.movies .heading').each(function(){
                        if ($(this).offset().top - $(window).scrollTop() < $(window).height() * 0.9) {
                            $(this).addClass('visible');
                        }
                    });
                    $('.coming-container .box').each(function() {
                        if ($(this).offset().top - $(window).scrollTop() < $(window).height() * 0.8) {
                            $(this).addClass('visible');
                        }
                    });
                    /*$('.popular-img .image-2').each(function() {
                        if ($(this).offset().top - $(window).scrollTop() < $(window).height() * 0.8) {
                            $(this).addClass('visible');
                        }
                    });
                    $('.popular-img .image-1').each(function() {
                        if ($(this).offset().top - $(window).scrollTop() < $(window).height() * 0.8) {
                            $(this).addClass('visible');
                        }
                    });*/
                });
            });
        </script>
    </body>
</html>