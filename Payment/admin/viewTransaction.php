<?php
require_once '../../config/db.php';

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(isset($_GET['id'])){
    $payment_id = $_GET['id'];
    
    
    $sql = "SELECT * FROM TICKET T, BOOKING B, PAYMENT P, MOVIE M, HALL H, CINEMA C, INVOICE I, USERS U, CUSTOMER CT 
        WHERE T.BOOKING_ID = B.BOOKING_ID
        AND B.BOOKING_ID = P.BOOKING_ID
        AND T.BOOKING_ID = P.BOOKING_ID
        AND T.MOVIE_ID = M.ID
        AND T.HALL_ID = H.HALL_ID
        AND H.CINEMA_ID = C.CINEMA_ID
        AND I.PAYMENT_ID = P.PAYMENT_ID
        AND CT.USER_ID = U.USER_ID
        AND P.CUST_ID = CT.CUST_ID
        AND P.PAYMENT_ID = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $payment_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while($rows = mysqli_fetch_assoc($result)){
        $row[] = array(
            'cinema_name'        =>   $rows['cinema_name'],
            'payment_id'         =>   $rows['payment_id'],
            'invoice_id'         =>   $rows['invoice_id'],
            'booking_id'         =>   $rows['booking_id'],       
            'movie_name'         =>   $rows['mv_name'],
            'cinema_location'    =>   $rows['cinema_address'],
            'hall_id'            =>   $rows['hall_id'],
            'movie_date'         =>   $rows['movie_date'],
            'movie_time'         =>   $rows['movie_time'],
            'seats'              =>   $rows['seat_id'],
            'booking_date'       =>   $rows['booking_date'],            
            'booking_time'       =>   $rows['booking_time'],
            'processing_fee'     =>   $rows['processing_fee'], 
            'payment_method'     =>   $rows['payment_method'],
            'payment_amount'     =>   $rows['payment_amount'],
            'payment_date'       =>   $rows['payment_date'],            
            'payment_time'       =>   $rows['payment_time'],
            'adultTicket_qty'    =>   $rows['adultTicket_qty'],
            'childTicket_qty'    =>   $rows['childTicket_qty'],
            'adultTicket_Price'  =>   $rows['adultTicket_Price'],
            'childTicket_Price'  =>   $rows['childTicket_Price'],
            'subTotal'           =>   $rows['total_price'],
            'total'              =>   $rows['payment_amount'],
            'transaction_date'   =>   $rows['payment_date'],
            'transaction_time'   =>   $rows['payment_time'],
            'user_name'          =>   $rows['user_name'],
            'email'              =>   $rows['email'],
            'cust_name'          =>   $rows['cust_name'],
            'cust_phone'         =>   $rows['cust_phone']
        
        );
    }   
}

$con->close();
?>


<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link href="../css/viewTransaction.css" rel="stylesheet" type="text/css"/>
        <title>GRC Cinema</title>
    </head>
    <body>
        <div class="transaction-container">
            <div class="table-container">
                <h1>Transaction Details</h1>
                <table>
                    <tr>
                        <th>Payment ID</th>
                        <td><?php echo str_pad($payment_id, 12, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr>
                        <th>Invoice ID</th>
                        <td><?php echo str_pad($row[0]['invoice_id'], 12, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr>
                        <th>Booking ID</th>
                        <td><?php echo str_pad($row[0]['booking_id'], 12, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td><?php echo $row[0]['payment_method']; ?></td>
                    </tr>
                    <tr>
                        <th>Payment Amount</th>
                        <td>RM <?php echo number_format($row[0]['payment_amount'], 2) ?></td>
                    </tr>
                    <tr>
                        <th>Payment Date and Time</th>
                        <td>
                            <?php 
                            echo date("d M Y ", strtotime($row[0]['payment_date'])); 
                            echo date(" h:i A", strtotime($row[0]['payment_time'])); 
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="details-container">
                <div class="order-details">
                    <h2>Order Details</h2>
                    <p>Movie Title: <?php echo $row[0]['movie_name']; ?></p>
                    
                    <?php 
                    if($row[0]['adultTicket_qty'] > 0){
                        echo "<p>Adult:&nbsp; x " . $row[0]['adultTicket_qty']. "&nbsp;&nbsp;&nbsp;(RM " . number_format($row[0]['adultTicket_Price'], 2) .")</p>";
                    }
    
                    if($row[0]['childTicket_qty'] > 0){
                        echo "<p>Child:&nbsp; x " . $row[0]['childTicket_qty']. "&nbsp;&nbsp;&nbsp;(RM " . number_format($row[0]['childTicket_Price'], 2) .")</p>";
                    }
                    ?>
                    
                    <p>Cinema Location: <?php echo $row[0]['cinema_location']; ?></p>
                    <p>Hall: <?php echo $row[0]['hall_id']; ?></p>
                    <p>Seat No: <?php echo implode(', ', array_column($row, 'seats')); ?></p>
                    <p>Movie Date: <?php echo date("d M Y", strtotime($row[0]['movie_date'])); ?></p>
                    <p>Movie Time: <?php echo date("h:i A", strtotime($row[0]['movie_time'])); ?></p>
                    <p>Booking Date: <?php echo date("d M Y", strtotime($row[0]['booking_date'])); ?></p>
                    <p>Booking Time: <?php echo date("h:i A", strtotime($row[0]['booking_time'])); ?></p>
                    <p>Sub Total: RM <?php echo number_format($row[0]['subTotal'], 2) ?></p>
                    <p>Processing Fee: RM <?php echo number_format($row[0]['processing_fee'], 2) ?></p>
                </div>
                
                <div class="customer-details">
                    <h2>Customer Details</h2>
                    <p>Username: <?php echo $row[0]['user_name']; ?></p>
                    <p>CardHolder Name: <?php echo $row[0]['cust_name']; ?></p>
                    <p>Contact No: <?php echo $row[0]['cust_phone']; ?></p>
                    <p>Email: <?php echo $row[0]['email']; ?></p>
                </div>
            </div>
        </div>
    </body>
</html>
