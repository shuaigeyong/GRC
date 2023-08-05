<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="../css/forget_password.css" rel="stylesheet" type="text/css"/>
        <script src="../js/sign_up.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;900&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;900&family=Tilt+Neon&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;700;900&display=swap');
        </style>
        <title>GRC Cinema</title>
    </head>
    <body>
        <div class="container">
            <form action="" method="post" id="form-1">
                <div class="heading">
                    <button class="back-btn"><a href="sign_in.php">&larr;</a></button>
                    <h1>Resend Email Verification</h1>
                </div>
                <p>Please enter your registered email address and we will resend you a verification email</p>
                <div class="psd-reset">
                    <input type="text" id="email" name="email" required />
                    <label for="email">Email Address<span class="star">*</span></label>
                    <div id="error_message_2" style="top: 245px;"></div>
                </div>
                
                <div class="submit-btn">
                    <a href="sign_in.php">BACK</a>
                    <input type="button" name="submit" value="RESEND EMAIL" onclick="ajaxFunction()">
                </div>
            </form>
        </div>
    </body>
    
    <script>
        document.getElementById("email").addEventListener("blur", checkEmail);
        document.getElementById("email").addEventListener("input", checkEmail);
        
        function ajaxFunction(){
            var xmlhttp;
            var formdata = new FormData(document.getElementById("form-1"));
            
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
                    var response = JSON.parse(xmlhttp.responseText);
                    
                    if (response.resendmail) {
                        $("#error_message_2").text(response.resendmail).show();
                    }
                    if (response.url) {
                        var token = response.resendmail_token;
                        var send_email = response.resendmail_email;
                        var xhttp = new XMLHttpRequest();
                        
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                console.log("Email sent successfully.");
                            }
                        };
                        xhttp.open("GET", "resend_email.php?email=" + send_email + "&token=" + token, true);
                        xhttp.send();
                        window.location.href = response.url;
                    }
                }    
            };
            
            xmlhttp.open("POST", "resend_verification_email_ajax.php", true);
            xmlhttp.send(formdata);
        }
    </script>
</html>
