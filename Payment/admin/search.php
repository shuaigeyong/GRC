<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

if(isset($_POST['paymentId'])){
    require_once '../../config/db.php';

    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $paymentId = $_POST['paymentId'];
    
    $sql = "SELECT * FROM PAYMENT WHERE PAYMENT_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $paymentId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    while($rows = mysqli_fetch_assoc($result)){
        $row = array(
            'booking_id'        =>    $rows['booking_id'],
            'payment_method'    =>    $rows['payment_method'],
            'payment_amount'    =>    $rows['payment_amount'],
            'payment_date'      =>    $rows['payment_date'],
            'payment_time'      =>    $rows['payment_time']
        );
    }
    
    $con->close();
    
    if ($result->num_rows > 0) {
            printf("
                <table id='table'>
                    <tr class='title'>
                        <td>Payment ID</td>
                        <td>Booking ID</td>
                        <td>Payment Method</td>
                        <td>Payment Amount (RM)</td>
                        <td>Payment Date</td>
                        <td>Payment Time</td>
                        <td colspan='3'>Actions</td>
                    </tr>
                    <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td class='view'><a href='viewTransaction.php?id=%s'>View</a></td>
                        <td class='edit'><a href='editTransaction.php?id=%s'>Edit</a></td>
                        <td class='delete' data-id='%s'>Delete</td>
                    </tr>
                </table>
            ", $paymentId, $row['booking_id'], $row['payment_method'], number_format($row['payment_amount'], 2), $row['payment_date'], $row['payment_time'], $paymentId, $paymentId, $paymentId);
    }
    
    else {
        echo "<tr><td colspan='9'>No record found.</td></tr>";
    }
}
