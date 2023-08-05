<?php 
require_once '../../config/db.php';

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$sql = "SELECT * FROM PAYMENT ORDER BY PAYMENT_ID DESC";
$stmt = mysqli_query($con, $sql);

$i = 0;

while($rows = mysqli_fetch_assoc($stmt)){
    $row[] = array(
        'payment_id'      =>   $rows['payment_id'],
        'booking_id'      =>   $rows['booking_id'],
        'payment_method'  =>   $rows['payment_method'],
        'payment_amount'  =>   $rows['payment_amount'],       
        'payment_date'    =>   $rows['payment_date'],       
        'payment_time'    =>   $rows['payment_time']    
    );
    $i++;
}

$con -> close();
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="../css/transaction.css" rel="stylesheet" type="text/css"/>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>        
        <script src="../../js/admin_dashboard.js" type="text/javascript"></script>
        <title>GRC Cinema</title>
    </head>
    <body>
        <?php
        include'sidebar_detail.php';
        ?>
        
        <div id="main">
            <div class="head"> 
                <div class="col-div-6"></div>
                <span class="dashboard nav" onclick="dashboard()" >&#9776; Transaction</span>
                <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Transaction</span>
                
                <div class="col-div-6"></div>
          
                <div class="profile">
                    <img src="img/baby.jpg" class="pro-img"  alt="">
                    <p>Baby Boss <span>President</span></p>
                </div>
          
                <div class="col-div-8">
                    <div class="box-8">
                        <div class="content-box">
                            <div class="heading">
                                <h2>Transaction List</h2>
                                <div class="search-container">
                                    <input id="payment-id" type="text" placeholder="Payment ID">
                                    <button id="search-btn" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                                <div class="filter-container">
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="filter-header">
                                                    <i class="fa fa-filter"></i>
                                                    <span>Filter</span>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr> 
                                    </table>
                                    <div class="filter-row">
                                        <table>
                                        <tr>
                                            <td>From</td>
                                            <td><input type="date" id="start-date" name="start-date" /></td>                                          
                                        </tr>
                                        <tr>
                                            <td>To</td>
                                            <td><input type="date" id="end-date" name="end-date" /></td>
                                        </tr>                                        
                                        <tr>
                                            <td colspan="2">
                                                <div class="search-container2">
                                                    <button type="submit" id="filter" class="search-btn"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                                                </div>
                                            </td>
                                        </tr>                                    
                                    </table>  
                                    </div>
                                </div>   
                            <div class="transaction-container">
                                <table id="table">
                                    <tr class="title">
                                        <td>Payment ID</td>
                                        <td>Booking ID</td>
                                        <td>Payment Method</td>
                                        <td>Payment Amount (RM)</td>
                                        <td>Payment Date</td>
                                        <td>Payment Time</td>
                                        <td colspan="3">Actions</td>
                                    </tr>
                                    <?php 
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
                                        ", $row[$j]['payment_id'], $row[$j]['booking_id'], $row[$j]['payment_method'], $row[$j]['payment_amount'], $row[$j]['payment_date'], $row[$j]['payment_time'], $row[$j]['payment_id'], $row[$j]['payment_id'], $row[$j]['payment_id']);
                                    }
                                    ?>                                    
                               </table>
                            </div>
                        </div>                                                           
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            $(document).ready(function() {
                $(".filter-header").click(function() {
                    $(".filter-row").slideToggle();                  
                });                                
                
                
                $('#search-btn').on('click', function() {
                    // Get the search payment id
                    var paymentId = $('#payment-id').val(); 
                    
                    $.ajax({
                        url: 'search.php',
                        type: 'POST',
                        data: {paymentId: paymentId},
                        dataType: 'html',
                        success: function(response){
                            // Show the result
                            $('#table tbody').html(response);
                            deleteButton();
                        }
                    });
                }); 
                
                                    $('#filter').click(function(){                      
                        dateFilter();
                    });
            });
            
            const startDateInput = document.getElementById("start-date");
            const endDateInput = document.getElementById("end-date");
            startDateInput.addEventListener("change", handleDateFilter);
            endDateInput.addEventListener("change", handleDateFilter);
            
            function dateFilter() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                // Send Ajax Permission
                $.ajax({
                    url: 'filter.php',
                    type: 'POST',
                    data: { startDate: startDate, endDate: endDate },
                    dataType: 'html',
                    success: function(response){
                        // SHow Filter Results
                        $('#table tbody').html(response);
                        deleteButton();
                    }
                });
            }


            
        deleteButton(); 
         
        function deleteButton(){
            // The confirmation message for the delete movie
            const deleteButtons = document.querySelectorAll('.delete');
        
            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id; 
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                
                    swalWithBootstrapButtons.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        
                        if (result.isConfirmed) {
                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'The transaction has been deleted.',
                                'success'
                            );
                            setTimeout(function(){
                                window.location.href = "delTransaction.php?id=" + id;
                            }, 2000); // 延迟才2秒执行delete
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        )   {
                            swalWithBootstrapButtons.fire(
                                'Cancelled',
                                'The transaction is safe :)',
                                'error'
                            );
                            }
                    });
                });
            });
        }
        
	</script>
    </body>
</html>
