<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

$movie_id = $_SESSION['movie_id'];
$user_id = $_SESSION['user_id'];
$booking_date = $_SESSION['booking_date'];
$booking_time = $_SESSION['booking_time'];
$childTicket_Qty = $_SESSION['childQty'];
$adultTicket_Qty = $_SESSION['adultQty'];
$total = $_SESSION['total_price'];
$booking_status = 'PENDING';


// Check the payment is from paypal or debit/credit card
if(isset($_POST['payment_method'])){
    $payment_method = $_POST['payment_method'];
    $cust_name = $_POST['cust_name'];
    $cust_phone = $_POST['cust_phone'];
    
    require_once 'Payment Data/customer.php';
    
    // Customer Data
    $customerData = [
        'user_id' => $user_id,
        'cust_name' => $cust_name,
        'cust_phone' => $cust_phone
    ];
        
    // Instantiate Customer
    $cust = new Customer;
        
    // Add Customer To DB
    $cust->addCustomer($customerData);
}
            
require_once '../config/db.php';
            
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);            

// Update Ticket Status to Sold
$sql = "UPDATE TICKET SET TICKET_STATUS = 'Sold' 
        WHERE BOOKING_ID = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $_SESSION['booking_id']);
$stmt->execute();

// Update Booking Status to Completed
$sql = "UPDATE BOOKING SET BOOKING_STATUS = 'Completed' 
        WHERE BOOKING_ID = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $_SESSION['booking_id']);
$stmt->execute();


$payment_amount = $_SESSION['total_price'];
$payment_date = date('Y-m-d');
$payment_time = date('H:i:s');


// Retrieve Cust_id in order to Insert into Payment Table
$sql = "SELECT CUST_ID FROM CUSTOMER ORDER BY CUST_ID DESC";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$cust_id = $row['CUST_ID'];


// Insert Payment Record in Payment Table
$sql = "INSERT INTO PAYMENT (BOOKING_ID, CUST_ID, PAYMENT_METHOD, PAYMENT_AMOUNT, PAYMENT_DATE, PAYMENT_TIME) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->bind_param('iisdss', $_SESSION['booking_id'], $cust_id, $payment_method, $payment_amount, $payment_date, $payment_time);
$stmt->execute();


// Retrieve the Latest Payment ID
$sql = "SELECT * 
        FROM PAYMENT 
        WHERE BOOKING_ID = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $_SESSION['booking_id']);
$stmt->execute();

$result = $stmt->get_result();

// fetch data
while ($row = mysqli_fetch_assoc($result)) {
    $payment_id = $row['payment_id'];
}

// Store Payment ID in the SESSION
$_SESSION['payment_id'] = $payment_id;

$invoice_date = date('Y-m-d');
$invoice_time = date('H:i:s');

// Insert Invoice Record in the Invoice Table
$invoice = "INSERT INTO INVOICE (PAYMENT_ID, TOTAL_COST, AMOUNT_PAID, INVOICE_DATE, INVOICE_TIME) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($invoice);
$stmt->bind_param('iddss', $payment_id, $total, $payment_amount, $invoice_date, $invoice_time);
$stmt->execute();

// Retrieve Customer Email in order to Insert into Stripe Account
$sql = "SELECT * FROM USERS WHERE USER_ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();
$record = $result->fetch_assoc();
$email = $record['email'];

$con->close();