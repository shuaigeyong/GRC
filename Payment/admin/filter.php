<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

if (isset($_POST["startDate"]) && isset($_POST["endDate"])) {
    require_once '../../config/db.php';

    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];

    // Query the transaction data within the specified date range
    $sql = "SELECT * FROM PAYMENT WHERE PAYMENT_DATE BETWEEN ? AND ? ORDER BY PAYMENT_ID DESC";
    
    // Prepare the statement
    $stmt = $con->prepare($sql);
    
    // Bind the value
    $stmt->bind_param('ss', $startDate, $endDate);
    
    // Execute
    $stmt->execute();
    
    $result = $stmt->get_result();
    $i = 0;
    
    while ($rows = mysqli_fetch_assoc($result)) {
        $row[] = array(           
            'payment_id' => $rows['payment_id'],
            'booking_id' => $rows['booking_id'],
            'payment_method' => $rows['payment_method'],
            'payment_amount' => $rows['payment_amount'],
            'payment_date' => $rows['payment_date'],
            'payment_time' => $rows['payment_time']
        );
        $i++;
    }

    $con->close();

    
        if ($result->num_rows > 0) {
            echo "<table id = 'table'>";
            echo "<tr class = 'title'>";
            echo "<td>Payment ID</td>";
            echo "<td>Booking ID</td>";
            echo "<td>Payment Method</td>";
            echo "<td>Payment Amount (RM)</td>";
            echo "<td>Payment Date</td>";
            echo "<td>Payment Time</td>";
            echo "<td colspan = '3'>Actions</td>";
            echo "</tr>";
        for($j = 0; $j < $i; $j++){
                printf("                
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
            ", $row[$j]['payment_id'], $row[$j]['booking_id'], $row[$j]['payment_method'], number_format($row[$j]['payment_amount'], 2), $row[$j]['payment_date'], $row[$j]['payment_time'], $row[$j]['payment_id'], $row[$j]['payment_id'], $row[$j]['payment_id']);
            }
        
        } else {
            echo "<tr><td colspan='9'>No record found.</td></tr>";
        }
}
