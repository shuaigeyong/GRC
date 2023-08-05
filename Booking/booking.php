<?php 

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
require_once '../config/db.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(isset($_POST['seats'])){   
    $data = array(
        'adultQty'        =>    $_POST['adultQty'],
        'childQty'        =>    $_POST['childQty'],
        'movie_id'        =>    $_POST['movie_id'],
        'user_id'         =>    $_POST['user_id'],
        'seats'           =>    $_POST['seats'],
        'hall_id'         =>    $_POST['hall_id'],
        'movie_date'      =>    $_POST['movie_date'],
        'movie_time'      =>    $_POST['movie_time'],
        'booking_date'    =>    date('Y-m-d'),
        'booking_time'    =>    date('H:i:s')
    );

    $_SESSION['movie_id'] = $data['movie_id'];
    $_SESSION['booking_date'] = $data['booking_date'];
    $_SESSION['booking_time'] = $data['booking_time'];
    
    $adultQty = $data['adultQty'];
    $childQty = $data['childQty'];
    $seatQty = $data['adultQty'] + $data['childQty'];
    
    // SQL 1: SELECT CHILD & ADULT TICKET PRICE
    $sql = "SELECT childTicket_Price, adultTicket_Price FROM MOVIE WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $data['movie_id']);
    $stmt->execute();
    
    $result = $stmt->get_result();
    while($rows = mysqli_fetch_assoc($result)){
        $row = array(
            'childTicket_Price'   =>   $rows['childTicket_Price'],
            'adultTicket_Price'   =>   $rows['adultTicket_Price']       
        );
    }
    
    $_SESSION['childTicket_Price'] = $row['childTicket_Price'];
    $_SESSION['adultTicket_Price'] = $row['adultTicket_Price'];
    $total_price = ($childQty * $row['childTicket_Price']) + ($adultQty * $row['adultTicket_Price']); 
    $processing_fee = 0.50;
    $_SESSION['processing_fee'] = $processing_fee;
    
    for($i = 0; $i < $adultQty; $i++){
        // SQL 2: INSERT INTO TICKET TABLE (ADULT)
        $sql = "INSERT INTO TICKET (MOVIE_ID, SEAT_ID, HALL_ID, TICKET_TYPE, TICKET_PRICE, MOVIE_DATE, MOVIE_TIME, TICKET_STATUS)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $seat_id = $data['seats'][$i];
        $ticket_type = 'adult';        
        $ticket_status = 'Reserved';
                
        $stmt = $con->prepare($sql);
        $stmt->bind_param('isisdsss', $data['movie_id'], $seat_id, $data['hall_id'], $ticket_type, $row['adultTicket_Price'], $data['movie_date'], $data['movie_time'], $ticket_status);
        
        $stmt->execute();
    }   
    
    // Insert child ticket
    for($j = $i; $j < $seatQty; $j++){
        // SQL 3: INSERT INTO TICKET TABLE (CHILD)
        $sql = "INSERT INTO TICKET (MOVIE_ID, SEAT_ID, HALL_ID, TICKET_TYPE, TICKET_PRICE, MOVIE_DATE, MOVIE_TIME, TICKET_STATUS)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $seat_id = $data['seats'][$j];
        $ticket_type = 'child';        
        $ticket_status = 'Reserved';
                
        $stmt = $con->prepare($sql);
        $stmt->bind_param('isisdsss', $data['movie_id'], $seat_id, $data['hall_id'], $ticket_type, $row['childTicket_Price'], $data['movie_date'], $data['movie_time'], $ticket_status);       
        $stmt->execute();
    }      
    
    $booking_status = 'Pending';
    
    // SQL 4: INSERT INTO BOOKING TABLE
    $sql = "INSERT INTO BOOKING (USER_ID, ADULTTICKET_QTY, CHILDTICKET_QTY, TOTAL_PRICE, PROCESSING_FEE, BOOKING_DATE, BOOKING_TIME, BOOKING_STATUS)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('iiiddsss', $data['user_id'], $adultQty, $childQty, $total_price, $processing_fee, $data['booking_date'], $data['booking_time'], $booking_status);
    $stmt->execute();
    
    // SQL 5: SELECT BOOKING_ID
    $sql = "SELECT * FROM BOOKING 
          WHERE BOOKING_DATE = ? 
          AND BOOKING_TIME = ?
          AND USER_ID = ?";
   
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssi', $data['booking_date'], $data['booking_time'], $data['user_id']);
    $stmt->execute();

    $result = $stmt->get_result();

    // fetch data
    while ($row = mysqli_fetch_assoc($result)) {
        $booking_id = $row['booking_id'];
    }
    
    // SQL 6: UPDATE BOOKING_ID IN TICKET TABLE
    $update = "UPDATE TICKET SET BOOKING_ID = ?            
               WHERE BOOKING_ID IS NULL"; 
    
    $stmt = $con->prepare($update);
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
}
?>

