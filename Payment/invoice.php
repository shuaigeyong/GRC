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
        'invoice_no'         =>   $rows['invoice_id'],
        'payment_id'         =>   $rows['payment_id'],
        'movie_name'         =>   $rows['mv_name'],
        'cinema_location'    =>   $rows['cinema_address'],
        'hall_id'            =>   $rows['hall_id'],
        'movie_date'         =>   $rows['movie_date'],
        'movie_time'         =>   $rows['movie_time'],
        'seats'              =>   $rows['seat_id'],
        'payment_method'     =>   $rows['payment_method'],
        'adultTicket_Price'  =>   $rows['adultTicket_Price'],
        'childTicket_Price'  =>   $rows['childTicket_Price'],
        'subTotal'           =>   $rows['total_price'],
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

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/invoice.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>GRC Cinema</title>
</head>
<body>
    <h1>Successful Payment !</h1>    
    <form action="invoice.php" method="POST">        
        <div class="invoice-container">
            <div id="container" class="flex">
                <table class="invoice">
                    <thead>
                        <tr class="header">
                            <td colspan="3">TAX INVOICE</td>                
                        </tr>
                        <tr>
                            <td colspan="3">
                                <hr>
                            </td>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td class="logo" colspan="3"><img src="images/GRC logo.png" alt="" width="150px" id='img' /></td>
                        </tr>
                        <tr>
                            <td class="logo" colspan="3" style="padding-bottom: 50px;"><?php echo $row[0]['cinema_name']; ?></td>
                        </tr>
                        <tr>                            
                            <td class="title">Invoice Number</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo str_pad($row[0]['invoice_no'], 12, '0', STR_PAD_LEFT); ?></td>
                        </tr>
                        <tr>
                            <td class="title">Booking ID</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo str_pad($_SESSION['booking_id'], 12, '0', STR_PAD_LEFT); ?></td>
                        </tr>
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
                            <td class="title">Tickets</td>
                            <td class="colon">:</td>
                            <?php 
                            if($adultTicket_Qty > 0 && $childTicket_Qty > 0){
                                echo "<td class='details'>Adult: $adultTicket_Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Child: $childTicket_Qty</td>";
                            }
                            
                            else if($adultTicket_Qty > 0){
                                echo "<td class='details'>Adult: $adultTicket_Qty</td>";
                            }
                            
                            else {
                                echo "<td class='details'>Child: $childTicket_Qty</td>";
                            }
                            ?>                 
                        </tr>
                        <tr>
                            <td class="title">Payment Method</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo $row[0]['payment_method']; ?></td>
                        </tr>             
                        <tr>
                            <td class="title">Transaction Date</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo date("d M Y", strtotime($row[0]['transaction_date'])); ?></td>
                        </tr>   
                        <tr class="bottom">
                            <td class="title">Transaction Time</td>
                            <td class="colon">:</td>
                            <td class="details"><?php echo date("h:i A", strtotime($row[0]['transaction_time'])); ?></td>
                        </tr> 
                    </tbody>
                    
                    <tfoot class="table">
                        <tr>
                            <td style="text-align: center;">Type</td>
                            <td>Amount(RM)</td>
                        </tr>
                            <?php 
                            if($adultTicket_Qty > 0 && $childTicket_Qty > 0){
                                echo "<tr>";
                                echo "<td>Adult Ticket Price (x$adultTicket_Qty)</td>";
                                echo "<td>".number_format($adultTicket_Qty * $row[0]['adultTicket_Price'], 2)."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td>Child Ticket Price (x$childTicket_Qty)</td>";
                                echo "<td>".number_format($childTicket_Qty * $row[0]['childTicket_Price'], 2)."</td>";
                                echo "</tr>";
                            }
                            
                            else if($adultTicket_Qty > 0){
                                echo "<tr>";
                                echo "<td style='border-bottom: 1px solid #38FFFC'>Adult Ticket Price (x$adultTicket_Qty)</td>";
                                echo "<td style='border-bottom: 1px solid #38FFFC'>".number_format($adultTicket_Qty * $row[0]['adultTicket_Price'], 2)."</td>";
                                echo "</tr>";
                            }
                            
                            else{
                                echo "<tr>";
                                echo "<td style='border-bottom: 1px solid #38FFFC'>Child Ticket Price (x$childTicket_Qty)</td>";
                                echo "<td style='border-bottom: 1px solid #38FFFC'>".number_format($childTicket_Qty * $row[0]['childTicket_Price'], 2)."</td>";
                                echo "</tr>";
                            }
                            ?>                          
                        <tr>
                            <td>Sub Total</td>
                            <td><?php echo number_format($row[0]['subTotal'], 2) ?></td>
                        </tr>    
                        <tr>
                            <td>Processing Fee</td>
                            <td><?php echo number_format($_SESSION['processing_fee'], 2) ?></td> 
                        </tr>
                        <tr>
                            <td>Total Payable</td>
                            <td><?php echo number_format($row[0]['total'], 2) ?></td>
                        </tr>                            
                    </tfoot>                                                    
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
            <button type="button" id="viewTicket" onclick="location='ticket.php'">VIEW TICKET</button>
        </div>
    </form>
    
    <?php 
        if (isset($_POST['print'])){ ?>
        <style>
            @media print {
                @page {
                    size: auto;
                    margin-top: 0; 
                    margin-bottom: 0;
                }
    
                html, body {
                    position: relative;
                    height: 100%;
                    width: 100%;                    
                }
                
                body * {
                    color: black;
                }
                
                #container, #container * {
                    visibility: visible;
                    color: black;
                    border-color: black;
                    font-size: 10px;
                    width: 100%;                    
                }
                
                table {
                    padding-bottom: 5px;
                }
                
                #img {
                    width: 15%;
                }
                
                .title, .colon, .details {
                    width: 100%;
                    top: -30px;
                }
                
                .colon {
                    position: relative;
                    left: -25%;
                }
                
                .details {
                    position: relative;
                    left: -35%;
                }
                
                #detail {
                    white-space: nowrap;
                }
                
                tfoot, tfoot * {
                    position: relative;
                    left: 5.5%;
                    top: -15px;
                }
                
                .terms1, terms2 {
                    font-size: 8px;
                    width: 100%;
                    position: relative;
                    left: -5%;
                }
                
                .terms2 li {
                    padding-left: 0;
                    position: relative;
                    left: -10%;
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
