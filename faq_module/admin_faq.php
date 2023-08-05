<?php

//error_reporting(0);
?>
<?php
  $servername = "localhost";
$username = "root";
$password = "";
$dbname = "grc";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("connect fail: " . $conn->connect_error);
}
if(isset($_POST['question'])){
    $question=$_POST['question'];
} else {
    $question="";
}
if(isset($_POST['answer'])){
    $answer=$_POST['answer'];
}else{
    $answer='';
}
  
  //action 
        if(isset($_POST['submit'])){
            $btnsubmit=$_POST['submit'];
        }else{
            $btnsubmit="";
        }
        if($btnsubmit=='Update'){
            
            //newid
         
            
            $sql = "UPDATE faq SET ans='$answer' where quest='$question'";
            if ($conn->query($sql) === true) {
             $text= "Record updated successfully";
            
              }
            
             else {
              $text= "Error updating record";
            }    
        }else if($btnsubmit=='Add'){
            $chk="SELECT * from faq where quest='$question'";
            $result = $conn->query($chk);
            if ($result->num_rows > 0) {
                $text= 'Already exist !';
            }else{
            
                //$insertsql = "INSERT INTO timetable (movie_id, mv_name, hall_id, time) VALUES ($mv_id, '$mv_name', $hall_id, '$time')";
                $insertsql = "insert into faq value('$question','$answer')";
                if ($conn->query($insertsql) === TRUE) {
                  $text= "New record created successfully";

                } else {
                  $text= "Error: " . $insertsql . "<br>" . $conn->error;
                } 
            }
        }else if($btnsubmit=='Delete'){
            $chk="SELECT * FROM faq WHERE quest='$question'";
            $result = $conn->query($chk);
            if ($result->num_rows == 0) {
                $text= 'Data Not exist !';
            }else{
                $sql = "delete from faq where quest='$question'";
            
               // $sql = "DELETE FROM timetable WHERE movie_id=$mv_id AND mv_name='$mv_name' AND hall_id=$hall_id AND time='$time'";

                if ($conn->query($sql) === TRUE) {
                  $text= "Record deleted successfully";
                } else {
                    $text="Error deleting record: " . $conn->error;

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
        <title>GRC Cinema</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link href="faq_admin.css" rel="stylesheet" type="text/css"/>
        <script src="../js/admin_dashboard.js" type="text/javascript"></script>
        
        <style>
            BODY{
                background-color: #6c757d;
                color:white;
                font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            }
            .timetable{
                margin: auto;
                background-color: #2d353c;
                /*border-spacing: 15px;*/
               border-collapse: collapse;
                
            }
            .timetable td{
                border:0px solid white;
                color:black;
                padding-left: 10px;
            }
            .hall{
                background-color: rgba(52, 73, 94,0.9);
                width:10%;
                    height:50%;
            }
            .timetable tr:nth-child(2n) {
  background: #eff0f1;
  /*background-color: transparent;*/
}
.timetable tr:nth-child(2n+3) {
  background: #fff;
   /*background-color: transparent;*/
}
th,td{
    border:none;
}     
            .tabs {
  width: 650px;  
  float: none;
  list-style: none;
  position: relative;
  margin: 80px 0 0 10px;
  text-align: left;
            }
            li {
    float: left;
    display: block;
  }
    input[type="radio"] {
    position: absolute;
/*    top: 0;
    left: -9999px;*/
  }
  .tab-content{
    z-index: 2;
    display: none; 
    overflow: hidden;
    width: 100%;
    font-size: 17px;
    line-height: 25px;
    padding: 25px;  
    position: absolute;
    top: 53px;
    left: 0; 
    /*background: darken($tabs-base-color, 15);*/
  }
  [id^="tab"]:checked + label { 
    top: 0;
    padding-top: 17px; 
    background-color:white;
    color:white;
    /*background-color: rgba(52, 73, 94,0.9);*/
    background-color: #2d353c;
  }
  [id^="tab"]:checked ~ [id^="tab-content"] {
    display: block;
    background-color:white;
    background-color: #2d353c;
    color:white;
    
  }
   .tab-content{
    z-index: 2;
    display: none; 
    overflow: hidden;
    width: 100%;
    font-size: 17px;
    line-height: 25px;
    padding: 25px;  
    position: absolute;
    top: 53px;
    left: 0; 
    /*background-color: #2d353c;*/
/*    background-color: #2d353c;*/
  }
 .selecttab{
    display: block;
    padding: 14px 21px;
    border-radius: 2px 2px 0 0;
    font-size: 20px;
    font-weight: normal;
    text-transform: uppercase;
    /*background-color: #2d353c;*/
     background-color: rgba(52, 73, 94,0.9);
    cursor: pointer;
    position: relative;
    top: 4px; 
  }
  .button {
  width: 140px;
  height: 45px;
  font-family: 'Roboto', sans-serif;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  font-weight: 500;
  color: #000;
  background-color: #fff;
  border: 1px solid black;
  border-radius: 45px;
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease 0s;
  cursor: pointer;
  outline: none;
  }
  input[type=radio]{
      display: none;
  }
.button:hover {
    border:none;
  /*background-color: #2EE59D;*/
  background-color:#38FFFC;
  box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
  color: #fff;
  transform: translateY(-7px);
}
input{
    font-size:18px;
  padding:10px 10px 10px 5px;
  display:block;
  width:300px;
  border:none;
  border-bottom:1px solid #757575;
  color:black;
}
input:focus 		{ outline:none; }
select{
    border:none;
    background-color: transparent;
    color:white;
}
option{
    background-color: black;
}
.movielist td,th{
    padding-left: 15px;
}
        </style>
    </head>
    <body>
   
      <?php
      include'sidebar_detail.php'
      ?>

      <div id="main">
        <div class="head"> 
          <div class="col-div-6"></div>
            <span onclick="dashboard()" class="nav" style="font-size: 30px;cursor: pointer; color: white;">&#9776; FAQ</span>
            <span onclick="dashboard2()" class="nav2" style="font-size: 30px;cursor: pointer; color: white;">&#9776; FAQ</span>

          <div class="col-div-6"></div>
          

          <div class="clearfix"></div>


          <div class="col-div-8">
            <div class="box-8" style="background-color: #6c757d;padding-bottom: 100%;">
              <?php


?>
        
    <center>      
        <div style="margin: auto;">
            <p><?php
            if(!isset($text)){
                echo '<p style="color:transparent">space</p>';
            }else{
                echo $text;
            }
            ?></p>
        </div>
    </center>
    <center>
        <ul class="tabs" style="position:relative;top:-75px;width:80%;">
            
            <li>
                <input type="radio" name="tabs" id="tab0" checked/>
                <label for="tab0" role="tab" class="selecttab" >FAQ LIST</label>
                <div id="tab-content1" class="tab-content">
                    <img src="img/GRC_icon-removebg-preview.png" style="padding:0" width="20%"><h1 style="position:absolute;z-index:5;font-family:'Segoe Print';font-size:45px;top:60px;left:160px;">Golden Reel Cinema</h1>
                    <hr/>
                    <table class="movielist" border="0">
                        <tr><th>No.</th><th>Question</th><th style="text-align:center;">Answer</th></tr>
                        <tr><td colspan='4'><hr/></td></tr>
                    <?php 
                    $sqlmovie="SELECT * FROM faq";
            
                    if($movieList=$conn->query($sqlmovie)){
                        $count=1;
                        while($record=$movieList->fetch_object()){
                            //display output
                            printf("
                                <tr>
                                <td>%d.</td>
                                <td>%s</td>
                                <td>%s</td>
                                
                                </tr>
                                  ",$count,
                                    $record->quest,
                                    $record->ans
                                    );
                            $count++;
                        }
                        printf("<tr><td colspan='4'><hr/></td></tr>
                            <tr>   
                               <td colspan='4'>
                               %d record(s) returned.
                               [<a href='faq.php'>More Details</a>]
                               </td>
                                </tr>
                        ",$movieList->num_rows);
                    }
                    ?>
                        </table>
                </div>
            </li>
            
            
            
            
            <li>
                <input type="radio" name="tabs" id="tab1" checked/>
                <label for="tab1" role="tab" class="selecttab" >ADD</label>
                <div id="tab-content1" class="tab-content">
                    <img src="img/GRC_icon-removebg-preview.png" style="padding:0" width="20%"><h1 style="position:absolute;z-index:5;font-family:'Segoe Print';font-size:45px;top:60px;left:160px;">Golden Reel Cinema</h1>
                    <form action="admin_faq.php" method="post">
                        <table width="100%">
                            
                            <tr>
                                <td>Question : </td>
                                <td>
                                    <input type="text" required name="question" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Answer : </td>
                              
                            </tr>
                            <tr>
                                <td colspan="2">
                                   <textarea name="answer" required style="height: 100px; width: 100%;" ></textarea>

                                </td>
                            </tr>
                            <hr>
                            <tr>
                                <td colspan="2"> <hr/></td>
                                
                            </tr>
                            <tr>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><input type="submit" name="submit" class="button" value="Add" /></td>
                                <td><input type="reset" class="button"  value="Reset" /></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </li>
            <li>
                <input type="radio" name="tabs" id="tab2" />
                <label for="tab2" role="tab" class="selecttab" >UPDATE</label>
                <div id="tab-content2" class="tab-content" >
                   <img src="img/GRC_icon-removebg-preview.png" style="padding:0" width="20%"><h1 style="position:absolute;z-index:5;font-family:'Segoe Print';font-size:45px;top:60px;left:160px;">Golden Reel Cinema</h1>
                    <form action="admin_faq.php" method="post">
                        <table width="100%">
                            <tr>
                                <td>Question : </td>
                                <td>
                                    <select  name="question">
                                       <?php
                                            $sql = "SELECT mv_name, id FROM faq";
                                            if($result=$conn->query($sqlmovie)){
                                                $count=1;
                                                while($record=$result->fetch_object()){
                                                   printf("<option value='%s'>%d. %s</option>",$record->quest,$count,$record->quest); 
                                                   $count++;
                                                }
                                            }
                                       ?>
                                    </select>           
                                </td>
                            </tr>
                            <tr>
                                <td>Answer : </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2">
                                   <textarea name="answer" required style="height: 100px; width: 100%;" ></textarea>

                                </td>
                            </tr>
                            <hr>
                            <tr>
                                <td colspan="2"> <hr/></td>
                                
                            </tr>
                            <tr>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><input type="submit" name="submit" class="button" value="Update" /></td>
                                <td><input type="reset" class="button" value="Reset" /></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </li>
            <li>
                <input type="radio" name="tabs" id="tab3" />
                <label for="tab3" role="tab" class="selecttab" >DELETE</label>
                <div id="tab-content3" class="tab-content" >
                    <img src="img/GRC_icon-removebg-preview.png" style="padding:0" width="20%"><h1 style="position:absolute;z-index:5;font-family:'Segoe Print';font-size:45px;top:60px;left:160px;">Golden Reel Cinema</h1>
                    <form action="admin_faq.php" method="post">
                        <table width="100%">
                            <tr>
                                <td>Question : </td>
                                <td>
                                    <select  name="question">
                                       <?php
                                            $sql = "SELECT mv_name, id FROM faq";
                                            if($result=$conn->query($sqlmovie)){
                                                $count=1;
                                                while($record=$result->fetch_object()){
                                                   printf("<option value='%s'>%d. %s</option>",$record->quest,$count,$record->quest); 
                                                   $count++;
                                                   
                                                }
                                            }
                                       ?>
                                    </select>           
                                </td>
                            </tr>
                            <hr>
                            <tr>
                                <td colspan="2"> <hr/></td>
                                
                            </tr>
                            <tr>
                                <td> </td>
                            </tr>
                            

                                <td><input type="submit" name="submit" class="button" value="Delete" onclick="setTimeout(function(){ location.reload(); }, 2000)" /></td>
                                <td><input type="reset" class="button" value="Reset" /></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </li>
        </ul>
    </center>
    <?php


    ?>
       
        
                
                
                
                
            </div>
          </div>
          
          <div class="clearfix"></div>
          
        </div>
      </div>
  
    </body>
</html>
