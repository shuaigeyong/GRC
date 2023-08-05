<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <!--<link href="../PhpProject1/seat.css" rel="stylesheet" type="text/css"/>-->
        <style>

        </style>
    </head>
    <body>
    
        <div style="height:800px;width:100px;padding-bottom: 70px;"></div>
        
        <?php
        session_start();
        if(isset($_GET['id'])){
            $_SESSION['movie_id'] = $_GET['id'];
        }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grc";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("connect fail " . $conn->connect_error);
}


$sql_time = "SELECT DISTINCT time FROM timetable";
$sql_hall = "SELECT DISTINCT hall_id FROM timetable order by hall_id asc";

$result_time = $conn->query($sql_time);
$result_hall = $conn->query($sql_hall);


$sql = "SELECT hall_id,movie_id, time, mv_name FROM timetable ORDER BY hall_id ASC, time ASC";
$result = $conn->query($sql);

$data = array();


while($row = $result->fetch_assoc()) {
    if($row['movie_id']==$_SESSION['movie_id']){
        $data[$row["hall_id"]][$row["time"]] = $row["mv_name"];
    }else{
        $data[$row["hall_id"]][$row["time"]] = " ";
    }
}


echo "<table class='timetable'>\n";
echo "<tr><td class='days'>Hall</td>"; 
while($row = $result_time->fetch_assoc()) {
    echo "<td class='days'>" . $row["time"] . "</td>";
}
echo "</tr>\n";

while($row = $result_hall->fetch_assoc()) {
    
    echo "<tr><td class='time'>" . $row["hall_id"] . "</td>"; 
    foreach($data[$row["hall_id"]] as $mv_name) {
        if($mv_name!=" "){
        echo "<td class='timeunselected'>" . $mv_name . "</td>";
        $movie_name=$mv_name;
        }
        else{
            echo "<td>" . $mv_name . "</td>";
        }
        //echo $row['movie_id'];
    }
    echo "</tr>\n";
}

echo "</table>";


?>
    </body>
</html>
