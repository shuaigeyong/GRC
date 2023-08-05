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
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">

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
                  <p>Ratings List <a href="admin_dashboard.php"><span class="backBtn" style="cursor: pointer">Back</span></a></p>
                <br>
                <table>
                  <tr>
                    <th>Rank</th>
                    <th>Movie Title</th>
                    <th>No of Ratings</th>
                    <th>Rating</th>
                    <th>Operations</th>
                  </tr>
                  <?php
                    $topratingsql = "SELECT movie.mv_name AS movie_title, COUNT(rating.rating_id) AS rating_count, AVG(rating.rating) AS avg_rating, movie.id AS movie_id FROM movie LEFT JOIN rating ON movie.id = rating.movie_id GROUP BY movie.id ORDER BY avg_rating DESC;";
                    $topratingresult = mysqli_query($con, $topratingsql);
                    
                    if ($topratingresult) {
                         $i=0;
                        while ($row = mysqli_fetch_assoc($topratingresult)) {
                            $movie_title = $row["movie_title"];
                            $rating_count = $row["rating_count"];
                            $rating_avg = $row["avg_rating"];
                            $movie_id = $row["movie_id"];
                            $i++;
                            printf("<tr>
                                        <td>%d</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%.2f</td>
                                        <td>
                                        <button class=' btn btn-outline-primary'> <a  class='text-light' href='rating_comment.php?movie_id=%d'>View Comment</a></button>
                                        </td>
                                     </tr>", $i, $movie_title, $rating_count, $rating_avg, $movie_id);
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
 <?phpmysqli_close($con);
?>
</body>

</html>