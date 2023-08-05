<?php
    require_once 'connection.php';
    
    if(!isset($_GET['id'])){
        header("Location: homepage.php");
        exit(0);
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/movieinfo.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;600;700;900&family=Roboto:wght@500&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Alkatra:wght@700&family=Oswald:wght@700&family=Poppins:wght@100;600;700;900&family=Roboto:wght@500;900&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700;900&display=swap');
        </style>
        <title>GRC Cinema</title>
    </head>
    <body>
        <?php
            if(isset($_GET['id'])){
                $id = $_GET['id'];
    
                $query = "SELECT * FROM movie WHERE id = $id";
                $run = mysqli_query($con, $query);
                if($run){
                    while($row = mysqli_fetch_assoc($run)){
            ?>
            <div class="container">
                <div class="flow">
                    <div class="back-btn"><a href="homepage.php">HOME</a></div>
                    <?php
                    $currentDate = date('Y-m-d');
                    // Check if the movie release date is earlier than the current date
                    // If YES, display the NOW SHOWING
                    // If NO, display the COMING SOON
                    if($row['release_date'] <= $currentDate){
                        echo "<div class='back-btn'>&gt;<a style='margin-left: 17px;' href='homepage.php#movies'>NOW SHOWING</a></div>";
                    }
                    else{
                        echo "<div class='back-btn'>&gt;<a style='margin-left: 17px;' href='homepage.php#coming'>COMING SOON</a></div>";
                    }
                    ?>
                    <div class="back-btn" style="color: #38FFFC;">&gt;<a style="color: #38FFFC; margin-left: 17px;" href="movieinfo.php?id=<?php echo $id; ?>"><?php echo strtoupper($row['mv_name']); ?></a></div>
                </div>
                <div class="container-box">
                    <div class="container-image">
                        <?php echo "<img src='../thumb/".$row['img']."' height='470px' width='300px' />" ?>
                    </div>
                    <div class="container-body">
                        <h4 class="body-title"><?php echo $row['mv_name']; ?></h4>
                        <div class="body-detail">
                            <pre>RELEASE DATE<span class="value"><?php echo $row['release_date']; ?></span></pre>
                            <pre>RUNNING TIME<span class="value"><?php echo $row['duration']; ?></span></pre>
                            <pre>DIRECTED BY<span class="value"><?php echo $row['director']; ?></span></pre>
                            <pre>WRITTEN BY<span class="value"><?php echo $row['writter']; ?></span></pre>
                            <pre>STARRING<span class="value"><?php echo $row['starring']; ?></span></pre>
                            <pre>MUSIC BY<span class="value"><?php echo $row['music']; ?></span></pre>
                            <pre>LANGUAGE<span class="value"><?php echo $row['lang']; ?></span></pre>
                            <div class="synopsis">
                                <p class="label">SYNOPSIS</p>
                                <p class="value"><?php echo $row['meta_description']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="content-1">
            <!-- display the trailor of the movie -->
            <div class="video">
                <?php 
                    $url = $row['link1'];
                    parse_str(parse_url($url, PHP_URL_QUERY), $query_params);       
                    $video_id = $query_params['v'];
                ?>
                <iframe width="650" height="350" src="https://www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
            <?php
                include 'rate-review.php';
            
                $currentDate = date('Y-m-d');
                $releaseDate = $row['release_date'];
                
                // If the movie's release date is earlier than current date, display the BUY NOW button
                // Else hide the BUY NOW button
                if($releaseDate <= $currentDate){
            ?>
            <div class="button">
                <!-- If the user doesn't sign in, the user is not allowed to buy the ticket -->
                <?php if(isset($_SESSION['user_id'])){ ?>
                    <a href="../Seat_Module/seatsel.php?id=<?php echo $row['id']; ?>" id="btn-1">BUY NOW</a>
                <?php }else{ ?>
                    <a id="btn-1" onclick="alert('Please sign in first!')">BUY NOW</a>
                <?php } ?>
            </div>
            <?php 
                }
            ?>
            <?php        
                    }
                }
            }
            ?>
            <div style="height: 100px;"></div>
            
            <?php
            include 'cust_footer.php';
            ?>
   
        <script>
            $(document).ready(function(){
                $(window).scroll(function() {
                    // 计算元素距离页面顶部的距离
                    var scrollTop = $(window).scrollTop(); // 滚动条的位置
                    var elementOffset1 = $('.content-1').offset().top; // 元素距离文档顶部的像素数
                    var distance1 = (elementOffset1 - scrollTop);

                    // 当元素进入可视区域时添加 visible 类
                    if (distance1 < $(window).height() * 0.6) {
                        $('.content-1').addClass('visible');
                    }
                });
            });
        </script>
    </body>
</html>