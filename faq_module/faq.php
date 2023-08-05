<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>GRC Cinema</title>
        <link href="notice.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,200,0,200" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script>
            $(document).ready( function(){
                
                $('.quest').click(function() {
                    var sibling = $(this).next('.ans');
                    var icon = $(this).find('.add-icon');
                   
                    sibling.slideToggle();
                    if (icon.text() == 'add') {
                        icon.text('remove');
                        icon.css('color', '#38FFFC');
                        $(this).css('color', '#38FFFC');
                    }
                    else if (icon.text() == 'remove') {
                        icon.text('add');
                        icon.css('color', 'white');
                        $(this).css('color', 'white');
                    }
                    
                });
                
            });
        </script>
        <style>
            body{
 
    font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    background-image: -webkit-linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('../image/signup_background.png') ;
    background-size: cover;
    background-repeat: no-repeat;
    background-position:center;
    width: 100%;
    height:1000px;
    font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    color:white;
}
h1{
    text-align: center;
    margin-bottom: 25px;
}
            .content{
                margin: auto;
                width:60%;
            }
            .quest{
                font-size:25px;
                border-bottom:1px solid  #38FFFC;
                padding-bottom:10px;
            }
            .quest:hover{
                text-shadow: 0 2px 2px  #38FFFC;
                padding-left: 10px;
                transition:0.3s;
            }
            .q{
                
            }
            .ans{
                display:none;
                background-color: rgba(255,255,255,0.2);
                border-bottom:1px solid  #38FFFC;
                padding:20px 15px;
            }
            .a{
                font-size: 18px;
            }
            .add-icon{
                float:right;
                
            }
        </style>
    </head>
    
    <body>
        <?php
        include '../Movie_management_module/cust_header.php';
        ?>
        <div style="height: 200px;" ></div>
        <h1>Frequently Asked Questions</h1>
        <div class="content">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "grc";
        $con=new mysqli($servername,$username,$password,$dbname);
        $sql="SELECT * FROM faq";
        if($result=$con->query($sql)){
            $count=1;
             while($record=$result->fetch_object()){
                printf("
                     <div class='quest'>
                        <span class='q'>%d. %s</span>
                        <span class='material-symbols-rounded add-icon' style='font-size:40px;'>add</span>
                    </div>
                    <div class='ans'>
                        <span class='a'>%s</span>
                    </div>
                         ",$count,$record->quest,$record->ans);
                 $count++;
             }
         }
         
        $result->free();
        $con->close();
        ?>
         </div>
                <div style="height: 200px;" ></div>

        <?php
        include '../Movie_management_module/cust_footer.php';
        ?>
    </body>
</html>
<a href=""></a>