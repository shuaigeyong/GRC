<?php
include 'con_db.php';

?>
<!DOCTYPE html>
    
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>GRC Cinema</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <link href="../css/admin_dashboard.css" rel="stylesheet" type="text/css"/>
  <script src="../js/admin_dashboard.js" type="text/javascript"></script>
</head>
    <body>
      <?php
      include '../Admin_Account_Module/sidebar_detail.php'
        ?>

      <div id="main">
        <div class="head"> 
          <div class="col-div-6"></div>
            <span class="dashboard nav" onclick="dashboard()" >&#9776; Dashboard</span>
            <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Dashboard</span>
            

          <div class="col-div-6"></div>
          
          <div class="profile">
              
              <img src="../img/baby.jpg" class="pro-img"  alt="">
            <p >Baby Boss <span>President</span></p>
          </div>

          <div class="clearfix"></div>
<?php
$sql = "SELECT COUNT(*) AS totalmovie FROM movie";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $total_movie = $row["totalmovie"];
}

$seatsql = "SELECT COUNT(*) AS totalseat FROM seat WHERE status='AVAILABLE'";
$seatresult = mysqli_query($con, $seatsql);
if (mysqli_num_rows($seatresult) > 0) {
  $row = mysqli_fetch_assoc($seatresult);
  $total_seat = $row["totalseat"];
}

$revenuesql = "SELECT SUM(payment.payment_amount + booking.processing_fee) AS total_revenue FROM booking JOIN payment ON booking.booking_id = payment.booking_id JOIN ticket ON booking.booking_id = ticket.booking_id JOIN movie ON ticket.movie_id = movie.id";
$revenueresult = mysqli_query($con, $revenuesql);
if (mysqli_num_rows($revenueresult) > 0) {
  $row = mysqli_fetch_assoc($revenueresult);
  $total_revenue = $row["total_revenue"];
}

$ordersql = "SELECT SUM(b.adultTicket_qty + b.childTicket_qty) AS total_order FROM booking b JOIN ticket t ON b.booking_id = t.booking_id AND b.booking_status = 'COMPLETED';";
$orderresult = mysqli_query($con, $ordersql);
if (mysqli_num_rows($orderresult) > 0) {
  $row = mysqli_fetch_assoc($orderresult);
  $total_order = $row["total_order"];
}


?>
          <div class="col-div-3">
            <a href="../Movie_management_module/movielist.php"><div class="box">
                <p><?php echo $total_movie ?><br/><span>Movie</span></p>
                
                <i id="g1" class="fa fa-solid fa-film icons box-icon"></i>
            </div></a>
          </div>
          <div class="col-div-3">
            <a href="../Seat_Module/staff_seat.php"><div class="box">
                <p><?php echo $total_seat ?><br/><span>Seat Available</span></p>
                <i id="g2" class="fa fa-couch box-icon"></i>
            </div></a>
          </div>
          <div class="col-div-3">
            <a href="../Payment/admin/transaction.php"><div class="box">
                <p><?php echo $total_order ?><br/><span>Order</span></p>
                <i id="g3" class="fa fa-duoton fa-ticket box-icon"></i>
            </div></a>
          </div>
          <div class="col-div-3">
            <a href="../Payment/admin/transaction.php"><div class="box">
                <p><?php echo $total_revenue ?><br/><span>Earning</span></p>
                <i id="g4" class="fa fa-solid fa-coins box-icon"></i>
            </div></a>
          </div>
          <div class="clearfix"></div>
          <br><br>
          <div style="width:65%;" id="bChart" class="col-div-8">    
            <div class="box-8">
              <div class="content-box">
                  <p>Weekly sales</p>
                 <canvas style="width: auto;" id="barChart"></canvas>
              </div>
            </div>
          </div>
          
          <div style="width:35%; margin-right: 0px;" class="col-div-8 pieChart">
            <div class="box-8">
              <div class="content-box">
                  <p>Film genre</p>
                  <br>
                  <canvas style="width: 360px; margin: auto;" id="pieChart"></canvas>
              </div>
            </div>
          </div>
          
          
          <div class="clearfix"></div>
          <br><br>
          <div class="col-div-8">
            <div class="box-8">
              <div class="content-box">
                  <p>Top Selling <a href="sale.php"><span class="viewBtn" style="cursor: pointer">View all</span></a></p>
                <br>
                <table>
                  <tr>
                    <th>Rank</th>
                    <th>Movie Title</th>
                    <th>Director</th>
                    <th>Sales</th>
                    <?php
                    $topsellsql = "SELECT movie.mv_name AS movie_title, movie.director, SUM(ticket.ticket_price) AS purchase_count FROM movie LEFT JOIN ticket ON movie.id = ticket.movie_id GROUP BY movie.id ORDER BY purchase_count DESC";
                    $topsellresult = mysqli_query($con, $topsellsql);

                    if ($topsellresult) {
                      $i = 0;
                      while ($row = mysqli_fetch_assoc($topsellresult)) {
                        $movie_title = $row["movie_title"];
                        $director = $row["director"];
                        $purchase_count = $row["purchase_count"];
                        $i++;
                        printf("<tr>
                                        <td>%d</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>$%.2f</td>
                                     </tr>", $i, $movie_title, $director, $purchase_count);
                      }
                    }
                    ?>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-div-8">
            <div class="box-8">
              <div class="content-box">
                  <p>Top Rating  <a href="rating.php"><span class="viewBtn" style="cursor: pointer">View all</span></a></p>
                <br>
                <table>
                  <tr>
                    <th>Rank</th>
                    <th>Movie Title</th>
                    <th>No of Ratings</th>
                    <th>Rating</th>
                  </tr>
                  <?php
                  $topratingsql = "SELECT movie.mv_name AS movie_title, COUNT(rating.rating_id) AS rating_count, AVG(rating.rating) AS avg_rating FROM movie LEFT JOIN rating ON movie.id = rating.movie_id GROUP BY movie.id ORDER BY avg_rating DESC;";
                  $topratingresult = mysqli_query($con, $topratingsql);

                  if ($topratingresult) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($topratingresult)) {
                      $movie_title = $row["movie_title"];
                      $rating_count = $row["rating_count"];
                      $rating_avg = $row["avg_rating"];
                      $i++;
                      printf("<tr>
                                        <td>%d</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%.2f</td>
                                     </tr>", $i, $movie_title, $rating_count, $rating_avg);
                    }
                  }
                  ?>
                </table>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>

</body>
<script>
    // 获取数据
<?php
$sqlsale = "SELECT DAYNAME(booking_date) AS day_of_week, booking_date, SUM(total_price) AS total_price FROM booking WHERE booking_date BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE() GROUP BY booking_date ORDER BY booking_date;";
$resultsale = mysqli_query($con, $sqlsale);
$weeklysale = array();
$date = array();
$week = array();

$i = 0;

while ($row = mysqli_fetch_assoc($resultsale)) {
  $weeklysale[$i] = $row["total_price"];
  $date[$i] = $row["booking_date"];
  $week[$i] = $row["day_of_week"];
  $i++;
}
?>

    var data = {
      labels: <?php echo json_encode($week); ?>,
      datasets: [{
        label: 'Sales',
        data: <?php echo json_encode($weeklysale); ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.3)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1.8
      }]
    };


    var options = {
      responsive: true,
      animation: {
        duration: 2500,
        easing: 'easeOutQuart'
      },
      scales: {
       x: {
      grid: {
        color: 'rgba(213, 217, 224, 0.2)' 
      },
      ticks: {
        color: 'rgba(213, 217, 224, 0.9)' 
      }
    },
    
        y: {
          beginAtZero: true,
          grid: {
        color: 'rgba(213, 217, 224, 0.2)' 
      },
         ticks: {
        color: 'rgba(213, 217, 224, 0.9)' 
      }
        }  
      },
      plugins: {
            legend: {
                labels: {
                    color: 'rgba(213, 217, 224, 0.9)', 
                }
            }
      }
    };


    var ctx = document.getElementById('barChart').getContext('2d');

    var chart = new Chart(ctx, {
      type: 'bar',
      data: data,
      options: options
    });
    
    </script>
  

        <?php
        $sqlgenre = "SELECT genre.genre_name, SUM(ticket.ticket_price) AS total_sales FROM ticket JOIN movie ON ticket.movie_id = movie.id JOIN genre ON movie.genre_id = genre.genre_id GROUP BY genre.genre_name ORDER BY total_sales DESC LIMIT 5;";
        $resultgenre = mysqli_query($con, $sqlgenre);
        while ($row = mysqli_fetch_assoc($resultgenre)) {
          $data[] = array(
            "genre" => $row['genre_name'],
            "genresale" => $row['total_sales']
          );
        }
        ?>

<script>
    
        // Get the PHP data from your database
var data = <?php echo json_encode($data, JSON_NUMERIC_CHECK); ?>;

// Extract the genre and genresale values from the PHP data
var labels = data.map(function(item) {
    return item.genre;
});
var values = data.map(function(item) {
    return item.genresale;
});

// Create the pie chart
var ctx = document.getElementById('pieChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: values,
            backgroundColor: [
                'rgba(56, 226, 177, 0.3)',
                'rgba(242, 155, 13, 0.3)',
                'rgba( 32, 152, 223, 0.3)',
                'rgba(255, 235, 0, 0.3)',
                'rgba(191, 0, 255, 0.3)'
            ],
            borderColor: [
                'rgba(56, 226, 177, 1)',
                'rgba(242, 155, 13, 1)',
                'rgba( 32, 152, 223, 1)',
                'rgba(255, 235, 0, 1)',
                'rgba(191, 0, 255, 1)'
            ],
            borderWidth: 1.5,
            hoverOffset: 5,
            
        }]
    },
    options: {
        responsive: false, 
        maintainAspectRatio: false,
        
        legend: {
            display: true,
            position: 'bottom',
            
        },
        plugins: {
            legend: {
                labels: {
                    color: 'rgba(213, 217, 224, 0.9)',
                }
            },
             tooltip: {
                callbacks: {
                    label: function(context) {
                        var label = context.label || '';
                        var yValue = context.raw || 0;
                        return label + ' '+'$'+ yValue.toFixed(2) ;
                    }
                }
            }
            },
        animation: {
            animateRotate: true, 
            animateScale: true, 
            duration: 1500,
            easing: 'easeOutQuad',
        }
        
    }
    
});

    </script>
 <?php mysqli_close($con);
 ?>
</html>