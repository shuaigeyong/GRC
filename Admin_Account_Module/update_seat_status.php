<?php
include 'con_db.php';
//cancel seat
$seattime = $_POST['seattime'];
$seatdate = $_POST['seatdate'];
$hall = $_POST['hall'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $seat = isset($_POST['seat_id']) ? $_POST['seat_id'] : '';
  $status = isset($_POST['status']) ? $_POST['status'] : '';

  if (!empty($seat) && !empty($status) && !empty($seattime) && !empty($seatdate) && !empty($hall)) {
    // update seat status IN DB
    $sql = "UPDATE seat SET status = '$status' WHERE seat_id = '$seat'";
    mysqli_query($con, $sql);
    $sqldel = "UPDATE ticket SET ticket_status = 'Cancelled' WHERE movie_date = '$seatdate' AND movie_time = '$seattime' AND seat_id = '$seat' AND hall_id ='$hall';";
    mysqli_query($con, $sqldel);
  }

}
?>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->