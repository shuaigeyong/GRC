<!DOCTYPE html>
<?php 
require_once '../config/db.php';

// Connnect database
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$oldBooking_status = 'PENDING';
$newBooking_status = 'Cancelled';

// SQL 1: Update the booking status to cancelled
$sql = "UPDATE BOOKING SET BOOKING_STATUS = ? WHERE UPPER(BOOKING_STATUS) = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param('ss', $newBooking_status, $oldBooking_status);
$stmt->execute();

$oldTicket_status = 'RESERVED';
$newTicket_status = 'Cancelled';

// SQL 2: Update the ticket status to cancelled
$sql = "UPDATE TICKET SET TICKET_STATUS = ? WHERE UPPER(TICKET_STATUS) = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param('ss', $newTicket_status, $oldTicket_status);
$stmt->execute();

$con->close();
?>
    
    
<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grc";


if(isset($_POST['bookdate'])){
    $date=$_POST['bookdate'];
} else {
    $date=date("Y-m-d");
}
if(isset($_POST['time'])){
    $timeseat=$_POST['time'];
}else{
    $timeseat='10:00:00';
}
if(isset($_POST['hall'])){
    $hall_id=$_POST['hall'];
}else{
    $hall_id=1;
}
if(isset($_POST['movie_id'])){
    $movieId = $_POST['movie_id'];
}else{
    if(isset($_GET['id'])){
        $movieId = $_GET['id'];
    }
    
}

//session_start();
?>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script>
             $(document).ready(function(){
    $('.disavailable[type="checkbox"]').prop('disabled', true);
    $('.unavailable[type="checkbox"]').prop('disabled', true);

      var maxChecked = 10;
$('#cbtn').attr('disabled','disabled');
      $('input[type="checkbox"]').click(function() {
        var checked = $('input[type="checkbox"]:checked');
        if (checked.length >= maxChecked) {
          $('input[type="checkbox"]:not(:checked)').attr('disabled', 'disabled');
           
        } else {
          $('input[type="checkbox"]:not(:checked)').removeAttr('disabled');
        }
        if(checked.length>=1){
          //$('#cbtn').hide();   
          $('#cbtn').removeAttr('disabled');
         $('#cbtn').css('filter','brightness(100%)');
        }else{
            $('#cbtn').attr('disabled','disabled');
              $('#cbtn').css('filter','brightness(50%)');
        }
       
      });
      
       
    });
  </script>
       <title>GRC Cinema</title>
        <link rel="icon" type="image/x-icon" href="img/GRC.ico">
        <link href="../css/seat.css" rel="stylesheet" type="text/css"/>
        <link href="../Booking/booking.css" rel="stylesheet" type="text/css"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
<!--       <style>*{background-color: white;
            color: black}</style>-->
        <title>Movie and Seat</title>
        <style>
            a {
  color: inherit;
  text-decoration: none;
}


a:active{
    
}
a.cancel {
  color: transparent;
}

        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,200,1,200" />
    </head>
    
    <body>
        
        <?php 
        date_default_timezone_set('Asia/Kuala_Lumpur'); 
        include '../Time_table/timetable.php';
        
        ?>
        
        
        <a href="../Movie_management_module/movieinfo.php?id=<?php echo $movieId; ?>">
                <span class="material-symbols-rounded" style="top:0px;left: 95%;font-size:45px;position:fixed;float:right;margin-right:50px;margin-top:30px;color:white">close</span>
            </a>
        
        
            <form name="date" action="seatsel.php" method="POST"  >
                <label id="ld"for="bookdate">Date: </label>
                <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">

                <input type="date" id="" name="bookdate" value="<?php echo $date ?>" onchange="this.form.submit()">

                <label id="lh" for="hall">Hall: </label>
                <select id="hall_select" name="hall" onchange="this.form.submit()">
                   
                    <option value="1"<?php if(isset($_POST['hall']) && $_POST['hall'] == "1") echo " selected"; ?>>1</option>
                    <option value="2"<?php if(isset($_POST['hall']) && $_POST['hall'] == "2") echo " selected"; ?>>2</option>
                    <option value="3"<?php if(isset($_POST['hall']) && $_POST['hall'] == "3") echo " selected"; ?>>3</option>
                    <option value="4"<?php if(isset($_POST['hall']) && $_POST['hall'] == "4") echo " selected"; ?>>4</option>
                    <option value="5"<?php if(isset($_POST['hall']) && $_POST['hall'] == "5") echo " selected"; ?>>5</option>
                </select>
                <select class="select3" name="time" onchange="this.form.submit()">
                    <option value="10:00:00"<?php if(isset($_POST['time']) && $_POST['time'] == "10:00:00") echo " selected"; ?>>10.00 AM</option>
                    <option value="13:00:00"<?php if(isset($_POST['time']) && $_POST['time'] == "13:00:00") echo " selected"; ?>>13.00 PM</option>
                    <option value="16:00:00"<?php if(isset($_POST['time']) && $_POST['time'] == "16:00:00") echo " selected"; ?>>16.00 PM</option>
                    <option value="19:00:00"<?php if(isset($_POST['time']) && $_POST['time'] == "19:00:00") echo " selected"; ?>>19.00 PM</option>
                    <option value="22:00:00"<?php if(isset($_POST['time']) && $_POST['time'] == "22:00:00") echo " selected"; ?>>22.00 PM</option>
                </select>
            </form>
        
        <table class="seatbar">
            <tr>
                <td>
                    <figure>
                        <span class="material-symbols-rounded guide available" style="cursor:default;filter: brightness(100%);position:relative;top:5px;">weekend</span>
                        <figcaption style="text-align: center; color:yellow;" >Available</figcaption>
                    </figure>
                </td>
                <td>
                    <figure >
                        <span class="material-symbols-rounded selected" style="cursor:default;position:relative;top:5px;">weekend</span>
                        <figcaption style="color:#38FFFC;text-align: center;" >Selected</figcaption>
                    </figure>
                </td>
                <td>
                    <figure>
                        <span class="material-symbols-rounded disavailable" style="cursor:default;position:relative;top:5px;" >weekend</span>
                        <figcaption style="text-align: center;color:red;" >Sold</figcaption>
                    </figure>
                </td>
                <td>
                    <figure>
                        <span class="material-symbols-rounded unavailable" style="cursor:default;position:relative;top:5px;" >weekend</span>
                        <figcaption style="text-align: center;color:gray;" >Unavailable</figcaption>
                    </figure>
                </td>
            </tr>
        </table>
               
        <!-- The Modal -->
        <div id="modalBox" class="modal">

            <!-- Modal content -->
            <span class="close">&times;</span>
            <div class="flex-container">                
                <div class="classic" onclick="toggleArrow(); dropdownActive()">
                    Classic <span class="arrow"></span>
                </div>       
 
                <form id="booking" name="booking" action="../Payment/payment-method.php" method="POST"> 
                    <div class="dropdown">                        
                        <div class="content">                                                
                            <div class="flex1 pos1">                    
                                Adult<br/><span id="adultPrice" data-value="19.00" style="color: red; font-size: 15px;">RM 19.00</span>
                            </div>

                            <div class="flex2 pos2">
                                <div class="ticketQty">
                                    <div class="flex3"><input type="button" class="button1" value="-" onclick="adultDecrementQty()" /></div>
                                    <div class="flex4"><input type="text" id="adultQty" name="adultQty" value="0" size="1" disabled /></div>
                                    <div class="flex5"><input type="button" class="button2" value="+" onclick="adultIncrementQty()" /></div>
                                </div>
                            </div>                                     
                        </div> 

                        <div class="content">
                            <div class="flex1 pos1">   
                                Child<br/><span id="childPrice" data-value="13.00" style="color: red; font-size: 15px;">RM 13.00</span>  
                            </div>

                            <div class="flex2 pos2">
                                <div class="ticketQty">
                                    <div class="flex3"><input type="button" class="button1" value="-" onclick="childDecrementQty()" /></div>
                                    <div class="flex4"><input type="text" id="childQty" name="childQty" value="0" size="1" disabled /></div>
                                    <div class="flex5"><input type="button" class="button2" value="+" onclick="childIncrementQty()" /></div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="foot-container">
                        <div class="totalPrice">Total <b>RM</b><input type="text" id="total" name="total" value="0.00" size="2" disabled onchange="calculateTotal()" /></div>
                        <div><input type="submit" value="CONTINUE" id="continue" name="continue" /></div>
                    </div>
                </form>                     
            </div>        
        </div>
        
<?php

if(!empty($movieId)){

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("connection fail: " . $conn->connect_error);
}


//$sql = "SELECT * FROM seat";

//$timeseat='time01';

//$movieId = $_SESSION['movieId'];







$sqlbook="SELECT seat_id FROM booking";
$sql = "SELECT seatRow, seatCol, seat_id, status FROM seat where hall_id =" . (string)$hall_id;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // 输出数据 
  echo '<form action="" method="POST" onsubmit="return validateForm()" >';
  echo '<div id="seat_area">';
  echo '<div id="screen"></div>';
 
  for($i = 1; $i <= 12; $i++) {
    for($j = 1; $j <= 18; $j++) {
      $status = "unavailable";
      $checkbox= "disabled";
      while($row = $result->fetch_assoc()) {
        if (($row['seatRow'] == $i && $row['seatCol'] == $j) && $row['status'] == "AVAILABLE") {
          $status = "available";
          $checkbox="";
          // 
            $sid=$row['seat_id'];
            //echo $sid;
            $sqlb="SELECT * FROM ticket WHERE (seat_id ='$sid' AND movie_date='$date' AND hall_id=$hall_id AND movie_time='$timeseat') AND (UPPER(ticket_status)='SOLD' OR UPPER(ticket_status)='RESERVED')";
            if($bk=$conn->query($sqlb)){
                if($bk->num_rows>0){
                    $status="disavailable";
                }
            }                                                  
          /*if($row['seat_id']=='1B11'){
            $status="disavailable";
          }*/
          //
          break;
        }
        
      }
      if($j == 5 || $j == 15){
          $leftmargin=20;
      }
      else{
           $leftmargin=0;
      } 
      $symbol = "weekend";
      $letter = chr(65 + $i - 1);
      $colnum = sprintf('%02s', $j); 
      $seatValue=(string)$hall_id . $letter . (string)$colnum;
      echo '<label>';
      echo "<input type=\"checkbox\" class=\"seat $status checkbox\" name=\"seat[]\" value=\"$seatValue\" $checkbox>";
      echo "<span class=\"material-symbols-rounded seat $status checkbox\" style=\"margin-left: ". $leftmargin . "px\">$symbol</span>";
      echo '</label>';
      //echo "<span class=\"material-symbols-rounded seat $status\" style=\"margin-left: ". $leftmargin . "px\" onclick=\"changeColor(this)\">$symbol</span>";
      $result->data_seek(0); 
    }
    echo '<br>';
  }
  echo '<br>';
  echo '</div>';
  echo '<input type="hidden" name="date" value="' . $date . '"/>';
  echo '<input type="hidden" name="hall" value="' . $hall_id . '"/>';
  echo '<input type="hidden" name="time" value="' . $timeseat . '"/>';
  echo '<input type="hidden" name="movie_id" value="' . $movieId . '"/>';
  echo '<input type="hidden" name="movie_name" value="' . $movie_name . '"/>';
  echo '<input type="hidden" id="seatCount" name="seatCount" value="0">';
  //<!-- Trigger/Open The Modal -->
  
  echo "<center><input id='cbtn' onclick='jumpout()' class='btnContinue' type='button' disabled value='CONTINUE' /></center>";

  echo '</form>';
} else {
  echo "0";
}


$conn->close();

}else{
    echo '<div style="height:100%;width:100%;z-index:9999;background-color:black;position:fixed;">';
    echo '<center><img src="../img/GRC_logo.png" alt=""/></center>';
    printf("<p style='font-size:40px;color:white;text-align:center;'>OOPS!</p>");
    printf("<p style='font-size:40px;color:white;text-align:center;'>We could not find this page.</p>");

    printf("<a href='homepage.php'><p style='font-size:40px;color:white;text-align:center;text-decoration: underline;'>[Please Click Here Back To Home Page.]</p></a>");
    echo '</div>';
}

?>
          
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
  
            




        </script>
        
        
        <!--------------------------------   Booking Module  -------------------------------------->
       
        
        <script>
            $('input[type="checkbox"]').on('click', function(){
                var seatSelected = document.querySelectorAll('input[type="checkbox"]:checked');
                
                // Limit seat below 10
                if(seatSelected.length > 10){
                    seatSelected = Array.prototype.slice.call(seatSelected, 0, 10);
                    alert("Maximum 10!");                   
                }
                
                document.getElementById("adultQty").value = String(seatSelected.length);                
                var adultQty = parseInt(document.getElementById("adultQty").value); 
                var childQty = parseInt(document.getElementById("childQty").value);
                
                console.log("seatSelected.length", seatSelected.length);
                console.log("adultQty", adultQty);
                
                document.getElementById("childQty").value = "0";
                seatQty = seatSelected.length - adultQty - childQty;
                seatCount = seatSelected.length;   
                
                console.log("seatCount", seatCount);
                console.log("seatQty", seatQty);
                
                calculateTotal();
            });
        </script>
        
        <script>
        var modal = document.getElementById("modalBox");
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        function jumpout() { 
            var seatSelected = document.querySelectorAll('input[type="checkbox"]:checked');           
            if(seatSelected.length > 10){
                // More than 10, limit the seat can be selected
                seatSelected = Array.prototype.slice.call(seatSelected, 0, 10);
            }
            
            // Set adult ticket quantity same as the seat selected by customer and child ticket quantity = 0            
            document.getElementById("adultQty").value = String(seatSelected.length);
            document.getElementById("childQty").value= "0";
            calculateTotal();
            
            modal.style.display = "block";           
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
        
        function toggleArrow(){
            var arrow = document.querySelector(".arrow");
            arrow.classList.toggle("active");          
        }
        
        function dropdownActive(){
            var dropdown = document.querySelector(".dropdown");
            dropdown.classList.toggle("dropdown-active");          
        }  
        
        function calculateTotal() {
            var adultPrice = parseFloat(document.getElementById("adultPrice").getAttribute("data-value"));
            var adultQty = parseInt(document.getElementById("adultQty").value);
            var childPrice = parseFloat(document.getElementById("childPrice").getAttribute("data-value"));
            var childQty = parseInt(document.getElementById("childQty").value);
            var total = (adultPrice * adultQty) + (childPrice * childQty);
            document.getElementById("total").value = total.toFixed(2);
	}
        
        function adultIncrementQty() {            
            var adultQty = parseInt(document.getElementById("adultQty").value); 
            
            if(seatCount){
                if(adultQty < seatCount && seatQty > 0){
                    document.getElementById("adultQty").value = adultQty + 1;  
                    seatQty--;
                    calculateTotal();
                }
                else {
                    var message = "You can only select " + seatCount + " seat!";
                    console.log(message);
                }            
            }           
            else {
                console.log("Can't empty!");
            }
	}

	function adultDecrementQty() {
            var adultQty = parseInt(document.getElementById("adultQty").value);          
            if (adultQty > 0) {
		document.getElementById("adultQty").value = adultQty - 1;
		calculateTotal();
                
                seatQty++;
            }
	}
        
        function childIncrementQty(){       
            var childQty = parseInt(document.getElementById("childQty").value);
            
            if(seatCount){
                if(childQty < seatCount && seatQty > 0){
                    document.getElementById("childQty").value = childQty + 1;
                    calculateTotal();
                    seatQty--;
                }             
            }           
        }
        
        function childDecrementQty(){
            var childQty = parseInt(document.getElementById("childQty").value);
            if(childQty > 0){
                document.getElementById("childQty").value = childQty - 1;
                calculateTotal();
                seatQty++;
            }
        }
        
        $(document).ready(function(){           
            $('#continue').click(function(event){
                event.preventDefault();
                
                var seatSelected = document.querySelectorAll('input[type="checkbox"]:checked');
                var adultQty = parseInt($('#adultQty').val());
                var childQty = parseInt($('#childQty').val());
                
                if(seatSelected.length > 10){
                    // More than 10, limit the seat can be selected
                    seatSelected = Array.prototype.slice.call(seatSelected, 0, 10);
                }
                   
                    
                if((adultQty + childQty) < seatSelected.length){
                    alert('Please select the correct quantity!');
                }
                
                else {
                    var movie_id = <?php echo $_SESSION['movie_id']; ?>;
                    var user_id = <?php echo $_SESSION['user_id']; ?>;                
                    var seats = $('input[name="seat[]"]:checked').map(function(){
                        return $(this).val();
                    }).get();                
                    var hall_id = <?php echo $hall_id; ?>;
                    var movie_date = '<?php echo $date; ?>';
                    var movie_time = '<?php echo $timeseat; ?>';               
                    var total = $('#total').val();
                    
                    // Let Total Input Text Disabled
                    $('#total').removeAttr('disabled');
                
                
                    $.ajax({                   
                        url: "../Booking/booking.php",
                        method: "POST",
                        data:{adultQty:adultQty, childQty:childQty, movie_id:movie_id, user_id:user_id, seats:seats, hall_id:hall_id, movie_date:movie_date, movie_time:movie_time},
                        success:function(data){
                            $.ajax({
                                url: "../Payment/payment-method.php",
                                method: "POST",
                                data:{adultQty:adultQty, childQty:childQty, total:total},
                                success:function(data){
                                    $('#continue').submit();  
                                    window.location.href = '../Payment/payment-method.php';
                                }
                            });
                        }
                    });
                }                
            });                             
        });
        
		// Press the current state into the browser's history when the page is loaded
		window.history.pushState({fromHistory: false}, '');

		// When the user clicks the Continue button, the current state is pressed into the browser's history
		document.querySelector('button').addEventListener('click', function() {
			window.history.pushState({fromHistory: false}, '');
		});
        </script>
        
       

        
<!--    <script>
  function changeColor(element) {
     
    if (element.classList.contains("available")) {
      element.classList.remove("available");
      element.classList.add("selected");
    } else {
      element.classList.remove("selected");
      element.classList.add("available");
    }
  }
  
  
</script> -->   
        
    </body>
</html>
