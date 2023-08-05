<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/AI_chatbot.css" rel="stylesheet" type="text/css"/>
        <script src="https://kit.fontawesome.com/8d6dbf3dd8.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <title>GRC Cinema</title>
    </head>
    <body>
        <div class="wrapper-1" style="display: none">
            <div class="title">GRC Chatbot</div>
            <div class="form">
                <div class="bot-inbox inbox">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="msg-header">
                        <p>Hello there, how can I help you?</p>
                    </div>
                </div>
            </div>
            <div class="typing-field">
                <div class="input-data">
                    <input id="data" type="text" placeholder="Type something here..." required />
                    <button id="send-btn">Send</button>
                </div>    
            </div>
        </div>

        <script>
            $(document).ready(function(){
                $("#data").on("keydown", function(e) {
                    // check if the user press the ENTER button
                    // If YES, the system will automate click the #send-btn button
                    if (e.keyCode === 13) {
                        if($("#data").val() !== ""){
                            $("#send-btn").trigger("click");
                        }
                    }
                    else if(e.shiftKey && e.keyCode === 13){
                        $(this).val($(this).val()+"\n");
                    }
                });
                
                // If the #send-btn is click
                $("#send-btn").on("click", function(){
                    $value = $("#data").val();
                    $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>' + $value + '</p></div></div>';
                    $(".form").append($msg);
                    $("#data").val('');
                    
                    // start ajax code
                    $.ajax({
                        url: 'message.php',
                        type: 'POST',
                        data: 'text=' + $value,
                        success: function(result){
                            $reply = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>' + result + '</p></div></div>';
                            var delay = result.length * 20;
                            // display the message that user send
                            setTimeout(function(){$(".form").append($reply);}, delay);
                            // when chat goes down the scroll bar automatically comes to the bottom
                            $(".form").scrollTop($(".form")[0].scrollHeight);
                        }
                    });
                });
            });
        </script>
    </body>
</html>