<?php
session_start();

    require_once 'connection.php';
    include 'functions.php';
    
    // Email sending function
    require '../phpmailer/includes/PHPMailer.php';
    require '../phpmailer/includes/SMTP.php';
    require '../phpmailer/includes/Exception.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    function send_password_reset($get_email, $otp){
        $mail = new PHPMailer(true);
    
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        
        $mail->Host = "smtp.gmail.com";
        $mail->Username   = "grccinema@gmail.com";                  //SMTP username
        $mail->Password   = "fkfofaznutdnfoux";                     //SMTP password
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
        // 配置 SSL/TLS 上下文选项
        $context = stream_context_get_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        // 设置 SMTP 选项
        $mail->SMTPOptions = [
            'ssl' => $context,
            'tls' => $context,
        ];

        //Recipients
        $mail->setFrom('grccinema@gmail.com');
        $mail->addAddress($get_email);                              //Name is optional

        $mail->isHTML(true);
        $mail->Subject = "Reset Password Notification";
        
        $email_template = "
            <h3>Hello</h3>
            <p>You are receiving this email because we received a password reset request from your account.</p>
            <br/><br/>
            <p>OTP: <b>$otp</b></p>
        ";
        
        $mail->Body = $email_template;
        $mail->send();
        $mail->smtpClose();
    }
    
    // If the email and otp_time are not set, redirect them to forget_password.php
    if(isset($_SESSION['email']) && isset($_SESSION['otp_time'])){
        $email = $_SESSION['email'];
    }
    else{
        echo "<script type='text/JavaScript'>window.location.href='forget_password.php';</script>";
    }
    
    if(isset($_POST['resend'])){
        $otp = random_otp(6);
        
        $update_otp = $con->prepare("UPDATE users SET OTP = ? WHERE email = ? LIMIT 1");
        $update_otp->bind_param("is", $otp, $email);
        $update_otp->execute();
        
        if($update_otp){
            send_password_reset($email, $otp);
            $_SESSION['otp_time'] = time();
            header("Location: OTP.php");
            exit(0);
        }
    }
    
    if(isset($_POST['submit'])){
        $otp = $_POST['otp'];
        
        $stmt = $con->prepare("SELECT OTP FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            
            // The OTP is valid for 1 minute only
            $time_diff = time() - $_SESSION['otp_time'];
            
            // check if the OTP is expired
            if($time_diff <= 60){
                // if OTP entered is wrong, redirect user to forget_password.php
                // else redirect to password_reset.php
                if($user_data['OTP'] == $otp){
                    $otp = "";
                    $run = $con->prepare("UPDATE users SET OTP = ? WHERE email = ? LIMIT 1");
                    $run->bind_param("ss", $otp, $email);
                    $run->execute();
                    echo "<script>alert('OTP verification success'); window.location.href='password_reset.php'</script>";
                }
                else{
                    $otp = "";
                    $run = $con->prepare("UPDATE users SET OTP = ? WHERE email = ? LIMIT 1");
                    $run->bind_param("ss", $otp, $email);
                    $run->execute();
                    echo "<script>alert('OTP verification failed'); window.location.href='forget_password.php'</script>";
                }  
            }
            else{
                $otp = "";
                $run = $con->prepare("UPDATE users SET OTP = ? WHERE email = ? LIMIT 1");
                $run->bind_param("ss", $otp, $email);
                $run->execute();
                $error['expired'] = "OTP is <b>expired</b>. Please <b>resend</b> it.";
                $error = array_filter($error);  
            }
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="../css/OTP.css" rel="stylesheet" type="text/css"/>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;900&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;900&family=Tilt+Neon&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700;900&display=swap');
        </style>
        <title>GRC Cinema</title>
    </head>
    <body>
        <?php    
            if(isset($_SESSION['status'])){
                echo "<script>";
                echo "alert('" . $_SESSION['status'] . "')";
                echo "</script>";
                unset($_SESSION['status']);
            }
        ?>
        <h1>OTP Verification</h1>
        <p>code has been send to the email</p>
        
        <?php
        // display the error message
        if(isset($_POST['submit'])){
            if(!empty($error)){
                echo "<ul class='error'>";
                foreach($error as $value){
                    echo "<li>$value</li>";
                    }
                echo "</ul><br />";
            }
        }
        ?>
        
        <form action="OTP.php" method="POST" id="otp-submit">
            <div class="otp-box">
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" class="space" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
            </div>
            <input type="hidden" name="otp" id="otp" />
            <div class="info">
                <div id="countdown"></div>
                <input class="submit-btn" type="submit" name="resend" value="Resend OTP" />
            </div>
            <div class="btn">
                <input style="display: none" type="submit" name="submit" id="verify-btn" value="Verify" />
            </div>
        </form>
        
        <!-- OTP key in -->
        <script type="text/javascript">
            const inputs = document.querySelectorAll(".otp-box input");
            
            inputs.forEach((input, index) => {
               input.dataset.index = index; // 为每个 input 设置自定义属性 ‘data-index’ 
               input.addEventListener("paste", handleOtppaste);
               input.addEventListener("keyup", handleOtp);
            });
            
            // handle copy and paste OTP
            function handleOtppaste(e){
                const data = e.clipboardData.getData("text"); // clipboardData - containing the currently copied or cut data | text - only text type data is obtained
                const value = data.split(""); // split the copied or cut data
                if(value.length === inputs.length){
                    inputs.forEach((input, index) => (input.value = value[index]));
                    submit();
                }
            }
            
            // handle key in OTP
            function handleOtp(e){
                const input = e.target; // e.target - target element of the event
                let value = input.value;
                input.value = ""; // clear the entered value in the box
                input.value = value ? value[0] : ""; // conditional-if
                
                let fieldIndex = input.dataset.index; // 读取索引
                if(value.length > 0 && fieldIndex < inputs.length - 1){ // determine if there is already a value entered in the input box and the current 
                    input.nextElementSibling.focus();                   // input box is not the last input box                
                }
                if(e.key === "Backspace" && fieldIndex > 0){
                    input.previousElementSibling.focus();
                }
                if(fieldIndex == inputs.length - 1){
                    submit(); // call the submit function
                }
            }
            
            function submit(){
                //let otp = "";
                var otp = document.getElementById("otp").value;
                inputs.forEach((input) => {
                    otp += input.value;
                });                
                document.getElementById("otp").value = otp; // 赋值
                document.getElementById("verify-btn").click(); // submit the form
            }
        </script>
        
        <!-- Countdown -->
        <script type="text/javascript">
            window.onload = function(){
                // Get the expiration message from local storage, if it exists
                var expirationMessage = localStorage.getItem('otpExpirationMessage');
                
                // If there is an expiration message, display it
                if (expirationMessage) {
                    document.getElementById("countdown").innerText = expirationMessage;
                }  
            };
            
            // Set the expiration time in milliseconds (1 minutes from now)
            var expirationTime = <?php echo $_SESSION['otp_time'] * 1000 + 60 * 1000; ?> ;
            
            // Update the countdown timer every second
            var countdown = setInterval(function(){
                // Get the current time
                var currentTime = Date.now();
                
                // Calculate the time difference between the expiration time and the current time
                var timeDiff = expirationTime - currentTime;
                
                // Check if the time has expired
                if(timeDiff <= 0){
                    clearInterval(countdown);
                    return;
                }
                
                // Calculate the minutes and seconds remaining
                var minutes = Math.floor(timeDiff / 1000 / 60);
                var seconds = Math.floor((timeDiff / 1000) % 60);
                
                var timeRemaining = minutes + " : " + (seconds < 10 ? "0" : "") + seconds;
                
                document.getElementById("countdown").innerText = "Your code will expire in " + timeRemaining + ".";
                
                // Check if the OTP code is expired
                if (minutes === 0 && seconds === 0) {
                // Save the expiration message in local storage
                    localStorage.setItem('otpExpirationMessage', 'Your code is expired. Please click the resend OTP.');
                } 
                else {
                // Clear the expiration message from local storage
                    localStorage.removeItem('otpExpirationMessage');
                }
                
                var expirationMessage = localStorage.getItem('otpExpirationMessage');
                
                if (expirationMessage) {
                    document.getElementById("countdown").innerText = expirationMessage;
                }  
            });
        </script>
    </body>
</html>