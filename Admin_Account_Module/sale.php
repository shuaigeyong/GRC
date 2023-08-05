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
  <link href="../css/sale.css" rel="stylesheet" type="text/css"/>
  <script src="../js/admin_dashboard.js" type="text/javascript"></script>
</head>
    <body>
      <?php
      include'../Admin_Account_Module/sidebar_detail.php'
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
        
        
         

          <div class="col-div-8">
            <div class="box-8">
              <div class="content-box">
                  <p>Sales List <a href="admin_dashboard.php"><span class="backBtn" style="cursor: pointer">Back</span></a></p>
                <br>
                <table>
                  <tr>
                    <th>Rank</th>
                    <th>Movie Title</th>
                    <th>Director</th>
                    <th>Release Date</th>
                    <th>Language</th>
                    <th>Duration</th>
                    <th>Genre</th>
                    <th>Sales</th>
                    <?php
                    //$topsellsql = "SELECT movie.mv_name AS movie_title, movie.director, COUNT(ticket.booking_id) AS purchase_count FROM movie LEFT JOIN ticket ON movie.id = ticket.movie_id GROUP BY movie.id ORDER BY purchase_count DESC";
                    $topsellsql="SELECT movie.mv_name AS movie_title, movie.director, release_date, lang, duration, genre.genre_name, SUM(ticket.ticket_price) AS purchase_count FROM movie LEFT JOIN ticket ON movie.id = ticket.movie_id JOIN genre ON movie.genre_id = genre.genre_id GROUP BY movie.id ORDER BY purchase_count DESC;";
                    $topsellresult = mysqli_query($con, $topsellsql);
                    
                    if ($topsellresult) {
                         $i=0;
                        while ($row = mysqli_fetch_assoc($topsellresult)) {
                            $movie_title = $row["movie_title"];
                            $director = $row["director"];
                            $genre = $row["genre_name"];
                            $release_date = $row["release_date"];
                            $lang = $row["lang"];
                            $duration = $row["duration"];
                            $purchase_count = $row["purchase_count"];
                            $i++;
                            printf("<tr>
                                        <td>%d</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>$%.2f</td>
                                     </tr>", $i, $movie_title, $director,$release_date,$lang,$duration,$genre,$purchase_count);
                        }
                    }
                        ?>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          
          <div class="clearfix"></div>
        </div>
      </div>
 <?phpmysqli_close($con);
?>
</body>

</html>