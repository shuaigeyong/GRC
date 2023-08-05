<?php
session_start();

if(!isset($_SESSION['login_status'])){
    header('Location: sign_in.php');
    exit(0);
}
else{
    unset($_SESSION['login_status']);
}

// google captcha API
if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
    // Verify the reCAPTCHA token
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '6LcLJSslAAAAAJV7iC_Wv5p2iHp2tulUCLHKSUzk';
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$recaptchaResponse;
    $response = file_get_contents($url);  // Get the verification result returned by Google reCAPTCHA server
    $responseKeys = json_decode($response,true);  // The validation result is a string in JSON format, true -> Returns an associative array instead of an object
    if($responseKeys["success"]) {
        // reCAPTCHA authentication passes, redirects to index.php
        header('Location: ../Movie_management_module/homepage.php');
        exit;
    }
}
// 如果验证失败或未提供令牌，则在当前页面上显示错误消息
$errorMsg = "reCAPTCHA authentication failed, please try again!";
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
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <title>GRC Cinema</title>
    </head>
    <body>
        <form action="captcha.php" method="POST" id="captcha-form">
            <div class="g-recaptcha" data-sitekey="6LcLJSslAAAAAMeV2Um0Juj6ggWceIlInXs7yZfa" data-callback="onSubmit"></div>
        </form>
    </body>
    
    <style>
        body{
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: -webkit-linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('../images/signup_background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            width: 100%;
            height: 730px;
            overflow: hidden;
        }
    </style>
    
    <script>
        function onSubmit() {
            document.getElementById("captcha-form").submit();
        }
    </script>
</html>
