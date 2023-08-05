<?php 
session_start();

$movie_id = $_SESSION['movie_id'];
$user_id = $_SESSION['user_id'];
$booking_date = $_SESSION['booking_date'];
$booking_time = $_SESSION['booking_time'];
$childTicket_Qty = $_SESSION['childQty'];
$adultTicket_Qty = $_SESSION['adultQty'];
$payment_id = $_SESSION['payment_id'];
$booking_status = 'COMPLETED';
            
require_once '../config/db.php';
            
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
$sql = "SELECT * FROM TICKET T, BOOKING B, PAYMENT P, MOVIE M, HALL H, CINEMA C, INVOICE I 
        WHERE T.BOOKING_ID = B.BOOKING_ID
        AND B.BOOKING_ID = P.BOOKING_ID
        AND T.BOOKING_ID = P.BOOKING_ID
        AND T.MOVIE_ID = M.ID
        AND T.HALL_ID = H.HALL_ID
        AND H.CINEMA_ID = C.CINEMA_ID
        AND I.PAYMENT_ID = P.PAYMENT_ID
        AND B.BOOKING_ID = ?";
            
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $_SESSION['booking_id']);
$stmt->execute();

$result = $stmt->get_result();

while($rows = mysqli_fetch_assoc($result)){
    $row[] = array(
        'cinema_name'        =>   $rows['cinema_name'],
        'payment_id'         =>   $rows['payment_id'],
        'movie_name'         =>   $rows['mv_name'],
        'cinema_location'    =>   $rows['cinema_address'],
        'hall_id'            =>   $rows['hall_id'],
        'movie_date'         =>   $rows['movie_date'],
        'movie_time'         =>   $rows['movie_time'],
        'seats'              =>   $rows['seat_id'],
        'payment_method'     =>   $rows['payment_method'],
        'total'              =>   $rows['payment_amount'],
        'transaction_date'   =>   $rows['payment_date'],
        'transaction_time'   =>   $rows['payment_time']
    );
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="css/ticket.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>GRC Cinema</title>
    </head>
    <body>
        <h1>Online Payment Ticket</h1>
        <form action="" method="POST">
            <div class="ticket-container">
            <div id="container" class="flex">                  
                <table class="ticket"> 
                    <thead>
                    <tr>
                        <td class="logo"><img src="images/GRC logo.png" alt="" width="150px" id='img' /></td>            
                        <td class="cinemaName"><?php echo $row[0]['cinema_name']; ?></td>
                        <td><h2>Self Print Ticket</h2></td>
                        <td id="qrcode">                           
                            <?php
                            require_once '../config/db.php';
                            require_once 'phpqrcode/qrlib.php';
                            
                            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            
                            $path = 'images/';
                            $qrcode = $path.time().".png";
                            
                            $sql = "SELECT * FROM CUSTOMER ORDER BY CUST_ID DESC";
                            $stmt = mysqli_query($con, $sql);
                            
                            while ($rows = mysqli_fetch_assoc($stmt)) {
                                $storeQR = array(
                                    'cust_name'    =>    $rows['cust_name']                                   
                                );
                            }

                            $text = $storeQR['cust_name'];
                            QRcode :: png($text, $qrcode, 'H', 4, 4);
                            echo "<img width='80px' src='".$qrcode."'>";
                            ?>
                        </td>
                    </tr>
                    </thead>
                    <tbody>   
                        <tr>                            
                            <td class="title">Payment ID</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo str_pad($row[0]['payment_id'], 12, '0', STR_PAD_LEFT); ?></td>
                        </tr>
                        <tr>
                            <td class="title">Movie Title</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo $row[0]['movie_name']; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Cinema</td>
                            <td class="colon">:</td>
                            <td class="details" id="detail"><?php echo $row[0]['cinema_location']; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Hall</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo $row[0]['hall_id']; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Movie Date</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo date("d M Y", strtotime($row[0]['movie_date'])); ?></td>                            
                        </tr>
                        <tr>
                            <td class="title">Movie Time</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo date("h:i A", strtotime($row[0]['movie_time'])); ?></td>
                        </tr>
                        <tr>
                            <td class="title">Seat No.</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo implode(', ', array_column($row, 'seats')); ?></td>
                        </tr>
                        <tr>
                            <td class="title">Amount</td>
                            <td class="colon">:</td>
                            <td class="details">RM <?php echo number_format($row[0]['total'], 2) ?></td>
                        </tr>
                        <tr>
                            <td class="title">Payment Type</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo $row[0]['payment_method']; ?></td>
                        </tr> 
                        <tr>
                            <td class="title">Transaction Date</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo date("d M Y", strtotime($row[0]['transaction_date'])); ?></td>
                        </tr>   
                        <tr>
                            <td class="title">Transaction Time</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo date("h:i A", strtotime($row[0]['transaction_time'])); ?></td>
                        </tr> 
                    </tbody>                                                                             
                </table>
                
                <div class="terms-container">
                    <ul class="terms1">
                        <li><h4>Terms & Conditions</h4></li>
                        <li>SelfPrint ticket must be printed out and presented at the checkpoint (where applicable) to gain admission to GRC hall for the movie screening.</li>
                        <li>Alternatively, you can collect the movie tickets purchased at the selected cinema by producing the Confirmation ID of the transaction at GRC Reservation or Gold Class counter.</li>
                        <li>SelfPrint ticket will be scanned and its validity verified before you can proceed to your seats in the cinema hall.</li>
                        <li>If you opt to scan with Confirmation Bar code in the SelfPrint ticket, GRC will only admit one(1) time entry per transaction.</li>
                        <li>In the event that multiple copies of SelfPrint ticket are presented or if you fail to produce the Confirmation ID, GRC reserves the right to refuse entry to All ticket holders.</li>
                    </ul>
                    <ul class="terms2">
                        <li>For payment made via Credit Card, customers must bring along their credit card for verification purpose. GRC reserves the right to refuse entry or not to issue the ticket(s) if the authorized credit card or identification card is not presented to GRC Staff.</li>
                        <li>For identification purposes, students/senior citizens must present their student ID/identify card at checkpoint/upon collecting the tickets at the Cinema Box Office.</li>
                        <li>For the digital 3D movies, a surcharge of RM5.00 per ticket applies.</li>
                        <li>Please note that all E-Payment purchases are confirmed purchases and any request for refunds, exchange or cancellations will not be entertained.</li>
                        <li>Please refer to the full list of Terms & Conditions on </li>
                    </ul>   
                </div>      
            </div>
        </div> 
        
        <div class="button-container">
            <button type="submit" id="print" name="print"><i class="fa fa-print"></i> &nbsp; Print</button>
            <button type="button" id="homepage" onclick="location='../Movie_management_module/homepage.php'">HOME PAGE</button>
        </div>
    </form>
        
        <?php if (isset($_POST['print'])){ ?>
        
        <style>
            @media print {
                @page {
                    margin-top: 0; 
                    margin-bottom: 0;
                }  
                
                body * {
                    font-size: 12px;
                    color: black;
                    width: 100%;
                }                                
                
                .ticket {
                    border: 1px solid black;
                    width: 100%;
                }
                
                #qrcode {
                    width: 15%;
                }
                
                h1 {
                    visibility: hidden;
                }
                
                .logo img {
                    width: 30%;
                }
                
                .cinemaName {
                    position: absolute;
                    left: 20%;
                    top: 4%;
                }
                
                .ticket h2 {
                    position: absolute;
                    left: 40%;
                    top: 4%;
                }
                
                .button-container, .button-container * {
                    visibility: hidden;
                }
            }
        </style>
        <script>
            window.print();
        </script>
        <?php } ?> 
    </body>
</html>
