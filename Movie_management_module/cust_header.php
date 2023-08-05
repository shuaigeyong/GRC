<?php
require_once 'connection.php';
session_start();

    if(isset($_SESSION['user_id'])){
        $isLogin = true;
        $user_id = $_SESSION['user_id'];
        $sql = $con->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
        $sql->bind_param("s", $user_id);
        $sql->execute();
        $result = $sql->get_result();
        if($result && $result->num_rows > 0){
            $row = $result->fetch_array();
        }
    }
    else{
        $isLogin = false;
    }
    
    if(isset($_GET['button'])){
        unset($_SESSION['user_id']);
        header("Location: ../Security_module/sign_in.php");
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <link href="../css/cust_header.css" rel="stylesheet" type="text/css"/>
        <title></title>
    </head>
    <body>
        <header>
            <a href="homepage.php" class="logo" style="color: #fff;">
                <i class="bx bxs-movie"></i> GRC Cinema
            </a>
            
            <div class="bx bx-menu" id="menu-icon"></div>
            
            <!-- Menu -->
            <ul class="navbar">
                <li><a href="homepage.php" class="home-active">Home</a></li>
                <li><a href="homepage.php#movies">Movies</a></li>
                <li><a href="homepage.php#coming">Coming</a></li>
                <li><a href="homepage.php#popular">Popular</a></li>
            </ul>
            <div class="search-bar">
                <input type="text" id="search_input">
                <div class="search" id="movie-list">
                    <?php
                    $searchValue = (isset($_POST["searchValue"])) ? "%" . $_POST["searchValue"] . "%" : "";
                    $query = $con->prepare("SELECT id, img, mv_name, genre_name, duration, ROUND(IFNULL(AVG(rating), 0.00), 1) AS AVERAGE_RATING
                                            FROM movie m LEFT JOIN rating r ON m.id = r.movie_id JOIN genre g ON m.genre_id = g.genre_id 
                                            WHERE mv_name LIKE ? OR genre_name LIKE ? OR duration LIKE ? GROUP BY id, img, mv_name, genre_name, duration ORDER BY id");
                    $query->bind_param("sss", $searchValue, $searchValue, $searchValue);
                    $query->execute();
                    $run = $query->get_result();
                    if($run && $run->num_rows > 0){
                        while($ro = $run->fetch_array()){
                    ?>
                    <div class="card">
                        <a href="movieinfo.php?id=<?php echo $ro['id']; ?>" class="card">
                            <img src="../thumb/<?php echo $ro['img']; ?>" alt="" />
                            <div class="cont">
                                <h3><?php echo $ro['mv_name']; ?></h3>
                                <p><?php echo $ro['genre_name']; ?> | <?php echo $ro['duration']; ?> | <span><i class='bx bxs-star'></i> <?php echo $ro['AVERAGE_RATING'] ?></span></p>
                            </div>
                        </a>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <a href="../Security_module/sign_in.php" class="button" <?php if ($isLogin == true){ echo 'style="display: none"';} ?>>Sign In</a>
            <?php if ($isLogin == true){ ?>
                <?php if ($row['profile_img'] == ""){ ?>
                        <div class="headshot" id="profile-image"><?php echo substr($row['user_name'], 0, 1); ?></div>
                <?php } else { ?>
                    <img src="<?php echo '../profile/' . $row['profile_img']; ?>" class="profile-image" id="profile-image">
                <?php } ?>
            <?php } ?>
        </header>
        
        <div class="container-image" style="display: none;">
            <div class="profile">
            <?php
            if(isset($_SESSION['user_id'])){
                $sql = $con->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
                $sql->bind_param("s", $user_id);
                $sql->execute();
                $result = $sql->get_result();
                if($result && $result->num_rows > 0){
                    $row = $result->fetch_array();
                }
                if($row['profile_img'] == ""){
                    echo '<div class="headshot-big">' . substr($row['user_name'], 0, 1) . '</div>';
                }
                else{
                    echo "<img src='../profile/". $row['profile_img'] . "'>";
                }
            ?>
                <h3><?php echo $row['user_name']; ?></h3>
                <p><?php echo $row['email']; ?></p><br />
                <div>
                <a href="../Security_module/update_profile.php?profileID=<?php echo $user_id; ?>" class="update-profile-btn">Update Profile</a>
                <a href="homepage.php?button=logout" class="logout">Log out</a>
                </div>
            <?php 
            }
            ?>
            </div>
        </div>
        
        <script>
            var header = document.querySelector('header');
            var menu = document.querySelector('#menu-icon');
            var navbar = document.querySelector('.navbar');
            
            window.addEventListener("scroll", function(){
               header.classList.toggle('shadow', window.scrollY > 0); 
            });
            
            menu.onclick = () => {
                menu.classList.toggle("bx-x");
                navbar.classList.toggle("active");
            };
            
            $(document).ready(function(){
                $("#profile-image").on("click", function(){
                    $(".container-image").toggle();
                });
                
                // 监听搜索框输入
                $("#search_input").on("keyup", function(){
                    var searchValue = $(this).val();
                    // 向后端发送请求
                    $.ajax({
                        url: "cust_header.php",
                        type: "POST",
                        data: { searchValue: searchValue },
                        success: function(data){
                            // 更新电影列表
                            $("#movie-list").html(data);
                            // $("#movie-list").html(data.replace(new RegExp(searchValue, "gi"), "<span class='highlight'>$&</span>"));
                        }
                    });
                });
                
                $("#search_input").on("blur", function() {
                    $(this).val(''); // Clear the content of the search box
                    setTimeout(function() {
                        $("#movie-list").html(''); // Clear movie list content
                    }, 500); 
                });
                
                if($("#search_input").val() === ""){
                    $("#search_input").attr("placeholder", "Search...");
                }
            });
        </script>
    </body>
</html>
