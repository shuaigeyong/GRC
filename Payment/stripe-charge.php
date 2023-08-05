<?php 
session_start();
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'vendor/autoload.php';
        require_once './Payment Data/customer.php';
        
        \Stripe\Stripe::setApiKey('sk_test_51MkK28LVFpC4e2xLKyOXhFYXLSuNsq09s3Xe9jlND5s962q9Pbz7e7bzRgolCdwykFg6eoi9YcgpVOoYLp5biAl900D4JVXM3J');
        
        $total = $_SESSION['total_price'];
        $user_id = $_SESSION['user_id'];
        
        $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
        
        $cardHolderName = $POST['cardHolderName'];
        $contactNo = $POST['contactNo'];
        $token = $POST['stripeToken'];
        $stripeTotal = (int)round($total) * 100;
        
        // Customer Data
        $customerData = [
            'user_id' => $user_id,
            'cust_name' => $cardHolderName,
            'cust_phone' => $contactNo
        ];
        
        // Instantiate Customer
        $cust = new Customer;
        
        // Add Customer To DB
        $cust->addCustomer($customerData);        
        
        // Succesful Payment
        $payment_method = 'Debit / Credit Card';
        require_once './success.php';
        
        
        // Create Customer In Stripe
        $customer = \Stripe\Customer::create(array(
            "email" => $email,
            "source" => $token
        ));
           
        $charge = \Stripe\Charge::create(array(
            "amount" => $stripeTotal,
            "currency" => "myr",
            "description" => $_SESSION['description'],
            "customer" => $customer->id
        ));                
               
        // Redirect to success
        header('Location: invoice.php?trans_id='.$charge->id.'&product='.$charge->description);
        ?>
    </body>
</html>
