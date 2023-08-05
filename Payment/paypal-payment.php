<?php 
session_start();                   
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/paypal.css" rel="stylesheet" type="text/css"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <div class="container"> 
            <h2>Pay by Paypal<hr><div class="checkout">Checkout</div><br></h2>            
            <div class="flex1">   
                <form action="invoice.php" method="POST" id="payment-form">                                   
                    <table class="table1">                           
                        <tr>
                            <td class="name">Name</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="name" name="name" class="basicInfo" placeholder="Enter Name" required></td>
                        </tr>
                        <tr>
                            <td><div id="name-error"></div></td>
                        </tr>
                        <tr>
                            <td class="contact-no"><br><br>Contact Number</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="contactNo" name="contactNo" class="basicInfo" placeholder="60123456789" maxlength="12" required></td>
                        </tr>
                        <tr>
                            <td><div id="contactNo-error"></div></td>
                        </tr>
                        <tr>
                            <td>
                                <script src="https://www.paypal.com/sdk/js?client-id=AfB6EwcVCjunhlsWwD8V9wc0LEIHsT8SxaX00EA0edi_ORJ9W_8ARaTU2-zAu-cuAWi7vpFK5TZp16w-&disable-funding=card"></script>
                                
                                <!-- Set up a container element for the button -->
                                <div id="paypal-button-container"></div>
                            </td>
                        </tr>
                    </table>     
                </form>
            </div>
                     
            <?php 
            $movie_id = $_SESSION['movie_id'];
            $user_id = $_SESSION['user_id'];
            $booking_date = $_SESSION['booking_date'];
            $booking_time = $_SESSION['booking_time'];
            $childTicket_Qty = $_SESSION['childQty'];
            $adultTicket_Qty = $_SESSION['adultQty'];
            $booking_status = 'PENDING';
            
            require_once '../config/db.php';
            
            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            $sql = "SELECT * FROM TICKET T, BOOKING B, MOVIE M, HALL H, CINEMA C                    
                    WHERE T.BOOKING_ID = B.BOOKING_ID
                    AND T.MOVIE_ID = M.ID
                    AND T.HALL_ID = H.HALL_ID
                    AND H.CINEMA_ID = C.CINEMA_ID
                    AND USER_ID = ? 
                    AND MOVIE_ID = ? 
                    AND BOOKING_DATE = ? 
                    AND BOOKING_TIME = ?
                    AND UPPER(BOOKING_STATUS) = ?";
            
            $stmt = $con->prepare($sql);
            $stmt->bind_param('iisss', $user_id, $movie_id, $booking_date, $booking_time, $booking_status);
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            while($rows = mysqli_fetch_assoc($result)){
                $row[] = array(
                    'booking_id'       =>    $rows['booking_id'],
                    'movie_name'       =>    $rows['mv_name'],
                    'cinema_location'  =>    $rows['cinema_address']                     
                );
            }
            
            $_SESSION['booking_id'] = $row[0]['booking_id'];
            
            // Select Payment ID            
            $sql = "SELECT * FROM PAYMENT";
            $result = mysqli_query($con, $sql);
            
            $i = 0;
            
            while($record = mysqli_fetch_assoc($result)){
                $i = 1;
                $payment_id = $record['payment_id'];
            }
            
            if(!$i){
                $payment_id = 0;
            }
            
            ?>
                     
            <div class='flex2'>
                <table class="table2">
                    <tr>
                        <th colspan="2">$ Payment Details</th>
                    </tr>
                    <tr class="space">
                        <td>Booking ID</td> 
                        <td><?php echo str_pad($row[0]['booking_id'], 12, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr class="space">
                        <td>Payment ID</td>
                        <td><?php echo str_pad($payment_id + 1, 12, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr class="space">
                        <td>Payment Description</td>
                        <td>
                            <?php 
                            if($adultTicket_Qty > 0 && $childTicket_Qty > 0){
                                $_SESSION['description'] = $row[0]['movie_name'].' | '.$row[0]['cinema_location'].' | Child: '.$childTicket_Qty.' | Adult: '.$adultTicket_Qty.' | '.$booking_date.' | '.$booking_time;
                                echo $_SESSION['description'];
                            }
                            
                            else if($adultTicket_Qty > 0){
                                $_SESSION['description'] = $row[0]['movie_name'].' | '.$row[0]['cinema_location'].' | Adult: '.$adultTicket_Qty.' | '.$booking_date.' | '.$booking_time; 
                                echo $_SESSION['description'];
                            }
                            
                            else {
                                $_SESSION['description'] = $row[0]['movie_name'].' | '.$row[0]['cinema_location'].' | Child: '.$childTicket_Qty.' | '.$booking_date.' | '.$booking_time; 
                                echo $_SESSION['description'];
                            }
                            ?>
                        </td>
                    </tr>                   
                    <tr class="space">
                        <td>Total</td> 
                        <td>MYR <?php echo number_format($_SESSION['total_price'], 2); ?></td>
                    </tr>
                </table>               
            </div>
        </div>               

    <script> 
        $(document).ready(function(){               
                $('#name').on('input', function(){
                    if($('#name').val() !== ''){
                        var name = $(this).val();
                        $.ajax({
                            url: "format-validation.php", 
                            method: "POST",
                            data:{name:name},
                            dataType: "JSON",
                            success:function(data){
                                $('#name').css('box-shadow', 'none');
                                $('#name').addClass('invalid');
                                $('#name-error').text(data);
                            }
                        });
                    }
                    
                    $('#name').css('box-shadow', '0 2px 4px 0 #cfd7df');
                    $('#name').removeClass('invalid');
                    $('#name-error').text('');
                });

                $('#name').on('blur', function(){
                    var name = $(this).val();
                    if($('#name').val() !== ''){
                        $.ajax({
                            url: "format-validation.php", 
                            method: "POST",
                            data:{name:name},
                            dataType: "JSON",
                            success:function(data){
                                $('#name').css('box-shadow', 'none');
                                $('#name').addClass('invalid');
                                $('#name-error').text(data);
                            }
                        });
                    }
                });      
                
                $('#contactNo').on('input', function(){
                    if($('#contactNo').val().length === 12){
                        var contactNo = $(this).val();
                        $.ajax({
                            url: "format-validation.php", 
                            method: "POST",
                            data:{contactNo:contactNo},
                            dataType: "JSON",
                            success:function(data){
                                $('#contactNo').css('box-shadow', 'none');
                                $('#contactNo').addClass('invalid');
                                $('#contactNo-error').text(data);
                            }
                        });
                    }
                    
                    $('#contactNo').css('box-shadow', '0 2px 4px 0 #cfd7df');
                    $('#contactNo').removeClass('invalid');
                    $('#contactNo-error').text('');
                });

                $('#contactNo').on('blur', function(){
                    var contactNo = $(this).val();
                    if($('#contactNo').val() !== ''){
                        $.ajax({
                            url: "format-validation.php", 
                            method: "POST",
                            data:{contactNo:contactNo},
                            dataType: "JSON",
                            success:function(data){
                                $('#contactNo').css('box-shadow', 'none');
                                $('#contactNo').addClass('invalid');
                                $('#contactNo-error').text(data);
                            }
                        });
                    }
                });               
            });
        
        paypal.Buttons({
            style: {
                // Change the text on the button
                label: 'paypal', 
                layout: 'horizontal',
                color: 'gold',
                shape: 'pill'
            },
            
            // onInit is called when the button first renders
            onInit: function(data, actions) {

                // Disable the buttons
                actions.disable();

                // Listen for input
                var nameInput = document.querySelector('#name');
                var contactNoInput = document.querySelector('#contactNo');
                nameInput.addEventListener('input', checkInputs);
                contactNoInput.addEventListener('input', checkInputs);

                function checkInputs() {
                    // If both inputs are non-empty, enable the button
                    if (nameInput.value.trim() !== '' && contactNoInput.value.trim() !== '') {
                        actions.enable();
                    } else {
                        actions.disable();
                        actions.reject({reason: 'incomplete_fields', message: 'Please fill in all required information'});
                    }
                }
            },
            
            // onClick is called when the button is clicked
            onClick: function(data, actions) {
                var nameInput = document.querySelector('#name');
                var contactNoInput = document.querySelector('#contactNo');
                var name = nameInput.value.trim();
                var contactNo = contactNoInput.value.trim();
    
                if (name === '' || contactNo === '') {
                    alert('Please fill in all required information.');
                    return false; // Prevent the order from being submitted
                }
                
                if(!/^[a-zA-Z][a-zA-Z\s]*$/.test(name) && !/^601[012346789]{1}\d{7,8}$/.test(contactNo) && !/^60[3456789]\d{7,8}$/.test(contactNo)){
                    alert('Please enter a valid name and contact number.');
                    return false; // Prevent the order from being submitted
                }
                
                if(!/^[a-zA-Z][a-zA-Z\s]*$/.test(name)){
                    alert('Please enter a valid name.');
                    return false; // Prevent the order from being submitted
                }
                
                if(!/^601[012346789]{1}\d{7,8}$/.test(contactNo) && !/^60[3456789]\d{7,8}$/.test(contactNo)){
                    alert('Please enter a valid phone number.');
                    return false; // Prevent the order from being submitted
                }
            },
          
            // Order is created on the server and the order id is returned
            createOrder: function(data, actions){
                return actions.order.create({
                    // Capture payment from buyer
                    intent: 'CAPTURE',
                    purchase_units: [{                                                       
                        amount: {  
                            currency_code: 'USD',
                            value: '<?php echo $_SESSION['total_price']; ?>'
                        }
                    }]
                });
            },
        
            // Finalize the transaction on the server after payer approval
            onApprove: function(data, actions){
                // Payment approved
                return actions.order.capture().then(function(orderData){   
                    var payment_method = "Paypal";
                    var cust_name = $('#name').val();
                    var cust_phone = $('#contactNo').val();
                    $.ajax({
                        url: 'success.php',
                        method: 'POST',
                        data:{payment_method:payment_method, cust_name:cust_name, cust_phone:cust_phone},
                        success:function(data){
                            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                            const transaction = orderData.purchase_units[0].payments.captures[0];
                            window.location.href = 'invoice.php';
                        }
                    });                   
                });
            },
            
            onCancel: function(data){
                // Payment cancel
                alert("Payment cancelled");
            },
            
            onError: function(err){
                alert("Something wrong with your address information that prevents checkout");
            }
        }).render('#paypal-button-container');
    </script>
  </body>
</html>
