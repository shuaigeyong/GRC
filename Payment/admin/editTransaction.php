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
            'cust_id'            =>   $rows['cust_id'],
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>GRC Cinema</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="../css/editTransaction.css" rel="stylesheet" type="text/css"/>
        <script src="../../js/admin_dashboard3.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
        include'sidebar_detail.php';
        ?>       
               
        <div id="main">
        <div class="head"> 
          <div class="col-div-6"></div>
            <span class="dashboard nav" onclick="dashboard()" >&#9776; Dashboard</span>
            <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Dashboard</span>
            

          <div class="col-div-6"></div>
          
          <div class="profile">
              <img src="img/baby.jpg" class="pro-img"  alt="">
              <p >Baby Boss <span>President</span></p>
          </div>
          
          <div class="col-div-8">
            <div class="box-8">
                <div class="content-box">
                    <div class="heading">
                        <p class="add-title">Edit Transaction</p><br>
                    </div>
                
                    <div class="container" style='min-width: 1350px'>
            <div class="jumbotron" >
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <h5>Transaction Details</h5>
                    </div>
                    <div class="form-group">
                        <input type="text" value="Payment ID: <?php echo str_pad($payment_id, 12, '0', STR_PAD_LEFT); ?>" name="payment_id" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                         <input type="text" value="Invoice ID: <?php echo str_pad($row[0]['invoice_id'], 12, '0', STR_PAD_LEFT); ?>" name="invoice_id" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" value="Booking ID: <?php echo str_pad($row[0]['booking_id'], 12, '0', STR_PAD_LEFT); ?>" name="booking_id" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <p>Payment Method</p>
                    </div>
                    <div class="form-group">
                        <input type="text" value="<?php echo $row[0]['payment_method']; ?>" id="payment_method" name="payment_method" class="form-control" maxlength="19" />
                    </div>
                    
                    <div id="paymentMethod-error"></div>
                    
                    <br>
                    <div class="form-group">
                        <input type="text" value="RM <?php echo number_format($row[0]['payment_amount'], 2) ?>" name="payment_amount" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" value="<?php echo date("d M Y ", strtotime($row[0]['payment_date'])); ?>" name="payment_date" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" value="<?php echo date("h:i A", strtotime($row[0]['payment_time'])); ?>" name="payment_time" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <h5>Customer Details</h5>
                    </div>
                    <div class="form-group">
                        <input type="text" value="<?php echo $row[0]['user_name']; ?>" name="user_name" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <p>CardHolder Name</p>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" value="<?php echo $row[0]['cust_name']; ?>" id="cust_name" name="cust_name" class="form-control" maxlength="255" />
                    </div>                                                             
                    
                    <div id="name-error"></div> 
                    
                    <br>
                    <div class="form-group">
                        <p>Contact No</p>
                    </div>                                        
                    <div class="form-group">
                        <input type="text" value="<?php echo $row[0]['cust_phone']; ?>" id="cust_phone" name="cust_phone" class="form-control" maxlength="12" />
                    </div>
                    
                    <div id="contactNo-error"></div>
                    
                    <br>
                    <div class="form-group">
                        <input type="text" value="<?php echo $row[0]['email']; ?>" name="email" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <h5>Order Details</h5>
                    </div>
                    <div class="form-group">
                        <input type="text" value="<?php echo $row[0]['movie_name']; ?>" name="movie_name" class="form-control" disabled />
                    </div>
                    <?php 
                    if($row[0]['adultTicket_qty'] > 0){
                        echo "<div class='form-group'>";
                        echo "<input type='text' value='Adult TIcket Quantity: " . $row[0]['adultTicket_qty']. "' name='adultTicket_qty' class='form-control' disabled />";
                        echo "</div>";
                    }
    
                    if($row[0]['childTicket_qty'] > 0){
                        echo "<div class='form-group'>";
                        echo "<input type='text' value='Child TIcket Quantity: " . $row[0]['childTicket_qty']. "' name='childTicket_qty' class='form-control' disabled />";
                        echo "</div>";
                    }
                    ?> 

                    <div class="form-group">
                        <input type="text" value="<?php echo implode(', ', array_column($row, 'seats')); ?>" name="seats" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" value="Movie Date: <?php echo date("d M Y", strtotime($row[0]['movie_date'])); ?>" name="movie_date" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" value="Movie Time: <?php echo date("h:i A", strtotime($row[0]['movie_time'])); ?>" name="movie_time" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" value="Processing Fee: RM <?php echo number_format($row[0]['processing_fee'], 2) ?>" name="processing_fee" class="form-control" disabled />
                    </div>
                    <button id="submit" type="submit" name="submit" class="btn btn-info btn-lg">Submit</button>
                </form>
            </div>
        </div>
                </div>
            </div>
        </div>
        </div>
        
        <script>
            $(document).ready(function(){               
                $('#cust_name').on('input', function(){
                    if($('#cust_name').val() !== ''){
                        var cust_name = $(this).val();
                        $.ajax({
                            url: "../format-validation.php", 
                            method: "POST",
                            data:{nameEdit:cust_name},
                            dataType: "JSON",
                            success:function(data){
                                if(data === ''){
                                    $('#name-error').text('');
                                    $('#submit').prop('disabled', false);
                                } else {
                                    $('#name-error').text(data);
                                    $('#submit').prop('disabled', true);
                                }
                            }
                        });                 
                    }
                    
                    $('#name-error').text('');
                });

                $('#cust_name').on('blur', function(){
                    var cust_name = $(this).val();
                    if($('#cust_name').val() !== ''){
                        $.ajax({
                            url: "../format-validation.php", 
                            method: "POST",
                            data:{nameEdit:cust_name},
                            dataType: "JSON",
                            success:function(data){
                                if(data === ''){
                                    $('#name-error').text('');
                                    $('#submit').prop('disabled', false);
                                } else {
                                    $('#name-error').text(data);
                                    $('#submit').prop('disabled', true);
                                }
                            }
                        });
                    }
                });      
                
                $('#cust_phone').on('input', function(){
                    if($('#cust_phone').val().length === 12){
                        var contactNo = $(this).val();
                        $.ajax({
                            url: "../format-validation.php", 
                            method: "POST",
                            data:{contactNoEdit:contactNo},
                            dataType: "JSON",
                            success:function(data){
                                if(data === ''){
                                    $('#contactNo-error').text('');
                                    $('#submit').prop('disabled', false);
                                } else {
                                    $('#contactNo-error').text(data);
                                    $('#submit').prop('disabled', true);
                                }
                            }
                        });
                    }
                    
                    $('#cust_phone').removeClass('invalid');
                    $('#contactNo-error').text('');
                });

                $('#cust_phone').on('blur', function(){
                    var contactNo = $(this).val();
                    if($('#cust_phone').val() !== ''){
                        $.ajax({
                            url: "../format-validation.php", 
                            method: "POST",
                            data:{contactNoEdit:contactNo},
                            dataType: "JSON",
                            success:function(data){
                                if(data === ''){
                                    $('#contactNo-error').text('');
                                    $('#submit').prop('disabled', false);
                                } else {
                                    $('#contactNo-error').text(data);
                                    $('#submit').prop('disabled', true);
                                }
                            }
                        });
                    }
                });     
                
                $('#payment_method').on('input', function(){
                    if($('#payment_method').val().length === 19){
                        var payment_method = $(this).val();
                        $.ajax({
                            url: "../format-validation.php", 
                            method: "POST",
                            data:{payment_methodEdit:payment_method},
                            dataType: "JSON",
                            success:function(data){
                                if(data === ''){
                                    $('#paymentMethod-error').text('');
                                    $('#submit').prop('disabled', false);
                                } else {
                                    $('#paymentMethod-error').text(data);
                                    $('#submit').prop('disabled', true);
                                }
                            }
                        });
                    }
                    
                    $('#paymentMethod-error').text('');
                });

                $('#payment_method').on('blur', function(){
                    var payment_method = $(this).val();
                    if($('#payment_method').val() !== ''){
                        $.ajax({
                            url: "../format-validation.php", 
                            method: "POST",
                            data:{payment_methodEdit:payment_method},
                            dataType: "JSON",
                            success:function(data){
                                if(data === ''){
                                    $('#paymentMethod-error').text('');
                                    $('#submit').prop('disabled', false);
                                } else {
                                    $('#paymentMethod-error').text(data);
                                    $('#submit').prop('disabled', true);
                                }
                            }
                        });
                    }
                });                                                
            });                    
        </script>
            
        <?php
        if(isset($_POST['submit'])){
            $payment_method = $_POST['payment_method'];
            $cust_name = $_POST['cust_name'];
            $cust_phone = $_POST['cust_phone'];                             

            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            $sql = "UPDATE PAYMENT SET PAYMENT_METHOD = ?
                    WHERE PAYMENT_ID = ?";
        
            $stmt = $con->prepare($sql);
            $stmt->bind_param('si', $payment_method, $payment_id);
            $stmt->execute();
            
            $sql = "UPDATE CUSTOMER SET CUST_NAME = ?, CUST_PHONE = ?
                    WHERE CUST_ID = ?";
        
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssi', $cust_name, $cust_phone, $row[0]['cust_id']);
            $stmt->execute();
            
            $con->close();
            
            if($stmt){
                echo "<script>alert('Transaction Successfully Updated...');window.location.href='transaction.php';</script>";
            }
            
            else {
                header('Location: transaction.php');
            }           
        }        
        ?>

    </body>
</html>

