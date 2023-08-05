<?php
session_start();

    if(isset($_SESSION['activate_email'])){
        echo "<script>alert('Registration Successful. We have sent you an email. PLease ACTIVATE it!');</script>";
        unset($_SESSION['activate_email']);
    }
    else if(isset($_SESSION['reset_status'])){
        echo "<script>alert('The password has already been changed! You can login now.');</script>";
        unset($_SESSION['reset_status']);
    }
    else if(isset($_SESSION['resend_email'])){
        echo "<script>alert('Verification Email Link has been sent to your email address!');</script>";
        unset($_SESSION['resend_email']);
    }
    else if(isset($_SESSION['update_profile_status'])){
        echo "<script>alert('Profile Successfully Updated...');</script>";
        unset($_SESSION['update_profile_status']);
    }
    
    if(isset($_SESSION['user_id'])){
        header("Location: ../Movie_management_module/homepage.php");
        exit(0);
    }
    
    if(isset($_COOKIE['username'])&& isset($_COOKIE['password'])){
        $uname = $_COOKIE['username'];
        $psd = $_COOKIE['password'];
    }
    else{
        $uname = "";
        $psd = "";
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
        <link href="../css/3D_Carousel_Slider.css" rel="stylesheet" type="text/css"/>
        <link href="../css/sign_in.css" rel="stylesheet" type="text/css"/>
        <script src="https://kit.fontawesome.com/8d6dbf3dd8.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="../js/sign_in.js" type="text/javascript"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;900&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;900&family=Tilt+Neon&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700;900&display=swap');
        </style>
        <title>GRC Cinema</title>
    </head>
    <body>
        <?php
            // If the user doesn't activate their account via email
            // Ask the user if they want to resend the activate account email
            // If YES, redirect them to the resend_verification_email.php
            // Else, redirect them to the sign_in.php
            if(isset($_SESSION['status']) && isset($_SESSION['resendmail'])){
                echo "<script>";
                echo "if(confirm('" . $_SESSION['status'] . "\\n" . $_SESSION['resendmail'] . "')){
                        window.location.href = 'resend_verification_email.php';
                      }
                      else{
                        window.location.href = 'sign_in.php';
                      }";
                echo "</script>";
                unset($_SESSION['status']);
                unset($_SESSION['resendmail']);
            }
        ?>
        <div id="container">          
            <div class="box">
                <!-- 3D Carousel Slider  -->
                <section class="slideshow" id="slider">
                    <input type="radio" name="slider" id="s1">
                    <input type="radio" name="slider" id="s2">
                    <input type="radio" name="slider" id="s3" checked>
                    <input type="radio" name="slider" id="s4">
                    <input type="radio" name="slider" id="s5">                    
       
                    <label for="s1" id="slide1">
                        <img src="../images/Spirited_Away.png" alt="" width="100%" height="100%" />
                    </label>
                                
                    <label for="s2" id="slide2">
                        <img src="../images/Anthem_of_the_Heart.png" alt="" width="100%" height="100%" />
                    </label>
                                
                    <label for="s3" id="slide3">
                        <img src="../images/Suzume.png" alt="" width="100%" height="100%" />
                    </label>
                                
                    <label for="s4" id="slide4">
                        <img src="../images/Summer_August.png" alt="" width="100%" height="100%" />
                    </label>
                                
                    <label for="s5" id="slide5">
                        <img src="../images/After_the_Rain.png" alt="" width="100%" height="100%" />
                    </label>                                       
                </section>  
                
                <!-- Sign in form -->
                <div class="form">
                    <form action="" method="post" id="signin_form">
                        <h1>Welcome back!</h1>
                        <div class="info" id="child1">
                            <input type="text" id="user-name" name="user-name" value="<?php echo $uname; ?>" required />
                            <label for="user-name">User Name<span class="star">*</span></label>
                            <div id="error-message-1"></div>
                        </div>               
                    
                        <div class="info" id="child2">
                            <input type="password" id="psd" name="psd" value="<?php echo $psd; ?>" required />
                            <label for="psd">Password<span class="star">*</span></label>
                            <i id="eye-1" class="fa-sharp fa-regular fa-eye-slash"></i>
                            <i id="eye-2" style="display: none" class="fa-regular fa-eye"></i>
                            <div id="error-message-2"></div>
                        </div>
                        
                        <div class="checkbox">
                            <input id="remember" type="checkbox" name="remember" />
                            <label for="remember">Remember Me</label>
                            <a href="forget_password.php">Forgot Password?</a>
                        </div>                             
                        
                        <div class="form-submit">
                            <input type="button" name="submit" value="SIGN IN" onclick="ajaxFunction()" />
                        </div>
                        
                        <div class="signup-btn">
                            <div>New to <b>GRC?</b></div>
                            <a href="sign_up.php">JOIN NOW</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    
    <script type="text/javascript">
        // verify the info entered by the user and display the error message in real time 
        // before they click the submit button
        document.getElementById("user-name").addEventListener("blur", checkUserName);
        document.getElementById("psd").addEventListener("blur", checkPsd);
    </script>
    
    <script>
        // ajax -> verify the info entered by the user after they click the submit button
        // If there is no error message, redirect them to the sign up page
        // Else display the error message
        function ajaxFunction(){
            var xmlhttp;
            var formdata = new FormData(document.getElementById("signin_form"));
        
            if(window.XMLHttpRequest)
                xmlhttp = new XMLHttpRequest();
            else if(ActiveXObjext("Microsoft.XMLHTTP"))
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            else{
                alert("Problem with your browser!");
                return false;
            }
        
            // Create a function that will receive data from the server
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState === 4){
                    console.log(xmlhttp.responseText);
                    var response = JSON.parse(xmlhttp.responseText);
                
                    if (response.username) {
                        $("#error-message-1").text(response.username).show();
                    }
                    if (response.password) {
                        $("#error-message-2").text(response.password).show();
                    }
                    if (response.url) {
                        window.location.href = response.url;
                    }
                }    
            };
        
            xmlhttp.open("POST", "sign_in_ajax.php", true); // send the request to the sign_in_ajax.php in POST method
            xmlhttp.send(formdata);
        }
    </script>
    
    <script>
        // show and hide the password by clicking the eye icon
        $("#eye-1").on("click", function(){
            $("#psd").attr("type", "text");
            $("#eye-1").css("display", "none");
            $("#eye-2").css("display", "block");
        });
            
        $("#eye-2").on("click", function(){
            $("#psd").attr("type", "password");
            $("#eye-1").css("display", "block");
            $("#eye-2").css("display", "none");
        });
    </script>
</html>