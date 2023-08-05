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
        <link href="css/payment-method.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="jquery.backDetect.js"></script>
        <title>GRC Cinema</title>
    </head>
    <body>     
        <?php 
        session_start();
        
        if(isset($_POST['total'])){
            $_SESSION['adultQty'] = $_POST['adultQty'];
            $_SESSION['childQty'] = $_POST['childQty'];
            $_SESSION['total'] = $_POST['total'];
        }
        
        ?>
        <h1>Purchase Method</h1>
        <div class="flex-container">
            <div class="buyDetails" onclick="toggleArrow(); dropdownActive()">
                Purchase Details <span class="arrow"></span>
            </div>   
            <div class="dropdown">
                <table class="table"> 
                    <tr>
                        <td colspan="3">TICKETS</td>
                    </tr>
                    <?php 
                    $adult_total = $_SESSION['adultTicket_Price'] * $_SESSION['adultQty']; 
                    $child_total = $_SESSION['childTicket_Price'] * $_SESSION['childQty']; 
                    
                    if($_SESSION['adultQty'] > 0 && $_SESSION['childQty'] > 0){
                        echo "<tr class='row-2'>";
                        echo "<td>Adult</td>";                        
                        echo "<td>(RM". number_format($_SESSION['adultTicket_Price'], 2) . " x " . $_SESSION['adultQty'] . ")</td>";
                        echo "<td>RM" . number_format($adult_total, 2) . "</td>";
                        echo "</tr>";
                        
                        echo "<tr class='row-3'>";
                        echo "<td>Child</td>";                        
                        echo "<td>(RM". number_format($_SESSION['childTicket_Price'], 2) . " x " . $_SESSION['childQty'] . ")</td>";
                        echo "<td>RM" . number_format($child_total, 2) . "</td>";
                        echo "</tr>";
                    }
                    
                    else if ($_SESSION['adultQty'] > 0){
                        echo "<tr class='row-2'>";
                        echo "<td>Adult</td>";                        
                        echo "<td>(RM". number_format($_SESSION['adultTicket_Price'], 2) . " x " . $_SESSION['adultQty'] . ")</td>";
                        echo "<td>RM" . number_format($adult_total, 2) . "</td>";
                        echo "</tr>";
                    }
                    
                    else{
                        echo "<tr class='row-2'>";
                        echo "<td>Child</td>";                        
                        echo "<td>(RM". number_format($_SESSION['childTicket_Price'], 2) . " x " . $_SESSION['childQty'] . ")</td>";
                        echo "<td>RM" . number_format($child_total, 2) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>           
                    <tr class="row-5">
                        <td colspan="2">Sub Total</td>
                        <td>RM <?php echo $_SESSION['total']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>
                    <tr class="row-7">
                        <td colspan="2">Processing Fee</td>
                        <td>RM <?php echo number_format($_SESSION['processing_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>
                            RM 
                            <?php 
                            $_SESSION['total_price'] = $_SESSION['total'] + $_SESSION['processing_fee'];
                            echo number_format($_SESSION['total_price'], 2); 
                            ?>
                        </td>
                    </tr>
                </table> 
            </div>
               
            <div class="button-container">
                <button type="button" value="Paypal" name="paypal" class="paypal" onclick="location='paypal-payment.php'">
                    <img src="images/paypal-logo.png" alt="paypal" width="120px" height="43px" />
                </button>
                
                <button type="submit" value="Debit or Credit Card" name="card" class="card" onclick="location='stripe-payment.php'">
                    <img src="images/credit2.png" alt="credit" class="credit" />
                    <img src="images/debit-logo.png" alt="debit" class="debit" />
                    <span class="text">Debit or Credit Card</span>                   
                </button>              
            </div>
            </div>
<!--            <div>
                <button type="submit" value="Debit or Credit Card" name="card" class="card">
                    <img src="images/card-logo.png" alt="card"/>
                    <span>Debit or Credit Card</span>
                </button>
            </div>-->
            
        </div>
            
        <script>            
            function toggleArrow(){
                var arrow = document.querySelector(".arrow");
                arrow.classList.toggle("active"); 
            }
        
            function dropdownActive(){
                var dropdown = document.querySelector(".dropdown");
                dropdown.classList.toggle("dropdown-active");          
            }  
            
//            $(window).load(function(){     
//                $('body').backDetect(function(){ 	  // Callback function       
//                    alert("Look forward to the future, not the past!");     
//                });   
//            });

            // Press the current state into the browser's history when the page is loaded
            window.history.pushState({fromHistory: false}, '');

            // When the user clicks the Continue button, the current state is pressed into the browser's history
            document.querySelector('button').addEventListener('click', function() {
		window.history.pushState({fromHistory: false}, '');
            });
        </script>
    </body>
</html>
