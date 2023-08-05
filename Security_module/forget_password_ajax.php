<?php
session_start();

    require_once 'connection.php';
    include 'functions.php';
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
    
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $email_pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        $otp = random_otp(6);
        
        if($email != ""){
            if (preg_match($email_pattern, $email)){
                $check_email = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
                $check_email_run = mysqli_query($con, $check_email);
        
                if(mysqli_num_rows($check_email_run) > 0){
                    $row = mysqli_fetch_array($check_email_run);
                    $get_email = $row['email'];
            
                    $update_otp = "UPDATE users SET OTP = '$otp' WHERE email = '$get_email' LIMIT 1";
                    $update_otp_run = mysqli_query($con, $update_otp);
            
                    if($update_otp_run){
                        send_password_reset($get_email, $otp);
                        $_SESSION['email'] = $email;
                        $_SESSION['status'] = "We e-mailed you a password reset link";
                        $_SESSION['otp_time'] = time();
                        echo "OTP.php";
                    }
                    else{
                        echo "No email found. Please enter the valid email.";
                    }
                }
                else{
                    echo "No email found. Please enter the valid email.";
                }
            }
            else{
                echo "The email address pattern is incorrect.";
            }
        }
        else{
            echo "Please enter your email address.";
        }