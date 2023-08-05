<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "grc";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("connect fail: " . $conn->connect_error);
        }
        
        
        //get id
        $mv_name=$_POST['Movie_name'];
//            $mv_id=$_POST['Movie_id'];
            $nmv_name=$_POST['nMovie_name'];
//            $nmv_id=$_POST['nMovie_id'];
            $hall_id=$_POST['hall'];
            $time=$_POST['time'];
            //get id
            $sqlm = "SELECT id FROM movie where mv_name='$mv_name'";
            $result = $conn->query($sqlm);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                 $mv_id=$row['id'];
              }    
            }
            
        
       //action 
        $btnsubmit=$_POST['submit'];
        if($btnsubmit=='Update'){
            
            //newid
            $sqlnm = "SELECT id FROM movie where mv_name='$nmv_name'";
            $sqlcheck="select * from timetable where hall_id=$hall_id and time='$time' and movie_id='$mv_id'";
            $resultcheck=$conn->query($sqlcheck);
            if($resultcheck->num_rows>0){
            $resultn = $conn->query($sqlnm);
            if ($resultn->num_rows > 0) {
              while($row = $resultn->fetch_assoc()) {
                 $nmv_id=$row['id'];
              }    
            }
            //echo $nmv_id;
            
            $sql = "UPDATE timetable SET movie_id=$nmv_id, mv_name='$nmv_name' WHERE movie_id=$mv_id AND mv_name='$mv_name' AND hall_id=$hall_id AND time='$time'";
            if ($conn->query($sql) === true) {
             $text= "Record updated successfully";
            
              }
            
            } else {
              $text= "Error updating record";
            }    
        }else if($btnsubmit=='Add'){
            $chk="SELECT movie_id,mv_name, hall_id, time FROM timetable WHERE movie_id!=0 and hall_id=$hall_id AND time='$time'";
            $result = $conn->query($chk);
            if ($result->num_rows > 0) {
                $text= 'Already exist !';
            }else{
            
                //$insertsql = "INSERT INTO timetable (movie_id, mv_name, hall_id, time) VALUES ($mv_id, '$mv_name', $hall_id, '$time')";
                $insertsql = "UPDATE timetable SET movie_id=$mv_id, mv_name='$mv_name' WHERE hall_id=$hall_id AND time='$time'";
                if ($conn->query($insertsql) === TRUE) {
                  $text= "New record created successfully";

                } else {
                  $text= "Error: " . $insertsql . "<br>" . $conn->error;
                } 
            }
        }else if($btnsubmit=='Delete'){
            $chk="SELECT movie_id,mv_name, hall_id, time FROM timetable WHERE movie_id=$mv_id AND mv_name='$mv_name' AND hall_id=$hall_id AND time='$time'";
            $result = $conn->query($chk);
            if ($result->num_rows == 0) {
                $text= 'Data Not exist !';
            }else{
                $sql = "UPDATE timetable SET movie_id=0, mv_name=' ' WHERE movie_id=$mv_id AND mv_name='$mv_name' AND hall_id=$hall_id AND time='$time'";
            
               // $sql = "DELETE FROM timetable WHERE movie_id=$mv_id AND mv_name='$mv_name' AND hall_id=$hall_id AND time='$time'";

                if ($conn->query($sql) === TRUE) {
                  $text= "Record deleted successfully";
                } else {
                    $text="Error deleting record: " . $conn->error;

                }
            }

        }
        
        
        
        
        
        
        
        
        
        
        $conn->close();
       
        ?>
    </body>
</html>
