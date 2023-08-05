<?php
include 'con_db.php';
//Check if the request method is POST or not
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $hall = isset($_POST['hall']) ? $_POST['hall'] : '1';
  $checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
  $seattime = isset($_POST['time']) ? $_POST['time'] : '10:00:00';
  $seatdate = isset($_POST['seatdate']) ? $_POST['seatdate'] : date('Y-m-d');


  //check all the seat in array   z
  foreach ($checkboxes as $seat) {

    //check seat status in db
    $sql2 = "SELECT status FROM seat WHERE seat_id = '$seat'";
    $sql2result = mysqli_query($con, $sql2);


    if (mysqli_num_rows($sql2result) > 0) {
      $row = mysqli_fetch_assoc($sql2result);
      $status = $row["status"];
    }
    // If status of the seat is AVAILABLE, update status to UNAVAILABLE
    if ($status == "AVAILABLE") {
      $sqlins = "UPDATE seat SET status = 'UNAVAILABLE' WHERE seat_id = '$seat'";
      $insresult = mysqli_query($con, $sqlins);
    }

  }



}
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>

<head>
  <meta charset="UTF-8">
  <title>Seat Management</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="../css/staff_seat.css" rel="stylesheet" type="text/css" />
  <script src="../js/admin_seat.js"></script>
</head>

<body>
  <?php
  include 'sidebar_detail.php'
    ?>

  <div id="main">
    <div class="head">
      <div class="col-div-6"></div>
      <span class="dashboard nav" onclick="dashboard()">&#9776; Seat Management</span>
      <span class="dashboard nav2" onclick="dashboard2()">&#9776; Seat Management</span>


      <div class="col-div-6"></div>

      <div class="clearfix"></div>

      <div class="col-div-8">
        <div class="box-8">
          <div class="content-box">

            <form action="" method="POST" id="seatform">

              <div id="seat-table">
                <?php
                $hall = isset($_POST['hall']) ? $_POST['hall'] : '1';


                //screen diagram
                echo '<div class="screen"></div>';

                //generate seat diagram
                for ($i = 1; $i <= 12; $i++) {
                  $row = chr(64 + $i); //calculate seat alphabet
                
                  echo "<label class='rowlabel text-light'>$row</label>"; //row seat label
                
                  for ($j = 1; $j <= 18; $j++) {
                    $row = chr(64 + $i);
                    $seat = "$hall" . $row . str_pad($j, 2, "0", STR_PAD_LEFT); //calculate seat number
                
                    //get seat status 
                    $sql2 = "SELECT status FROM seat WHERE seat_id = '$seat'";
                    $sql2result = mysqli_query($con, $sql2);

                    if (mysqli_num_rows($sql2result) > 0) {
                      $row = mysqli_fetch_assoc($sql2result);
                      $status = $row["status"];
                    }

                    $seattime = isset($_POST['time']) ? $_POST['time'] : '10:00:00';
                    $seatdate = isset($_POST['seatdate']) ? $_POST['seatdate'] : date('Y-m-d');
                    $sql3 = "SELECT ticket_status FROM ticket WHERE seat_id = '$seat' AND movie_time= '$seattime' AND movie_date ='$seatdate'";
                    $sql3result = mysqli_query($con, $sql3);

                    if (mysqli_num_rows($sql3result) > 0) {
                      $row = mysqli_fetch_assoc($sql3result);
                      $status2 = $row["ticket_status"];
                    } else {
                      $status2 = "";
                    }


                    //generate seat by seat status
                    if ($status2 == "Sold") {

                      $checked = "checked";
                      $color = "color:red;";
                    } elseif ($status == "UNAVAILABLE" && $status2 != "Sold") {
                      $checked = "checked";
                      $color = "";

                    } else {
                      $checked = "";
                      $color = "";
                    }
                    echo "<span  class='seats' ><label><input type='checkbox' class='checkBox red'  name='checkbox[]' value='$seat' $checked><i class='seat fa-solid fa-couch fa-2xs' style='$color;'></i></label></span>";
                  }
                  echo "<br>";
                }

                //generate col label
                echo "<div style='margin-left: 22px;'>";
                for ($collabel = 1; $collabel <= 18; $collabel++) {
                  $collabel = str_pad($collabel, 2, "0", STR_PAD_LEFT);
                  echo "<label class='text-light collabel'>$collabel</label>";

                  if ($collabel == 4 || $collabel == 14) {
                    echo "<div class='space'></div>";
                  }
                }
                echo "</div>";

                ?>

                <?php
                // Get the selected hall from the POST. if not set = 1
                $hall = isset($_POST['hall']) ? $_POST['hall'] : '1';
                $hall_options = array(
                  "1" => "Hall 1",
                  "2" => "Hall 2",
                  "3" => "Hall 3",
                  "4" => "Hall 4",
                  "5" => "Hall 5"
                );

                echo "<br><label class='text-light'style='margin-left:25px;'>Select Hall:</label>";
                echo "<select name='hall' onchange='this.form.submit()'>";
                foreach ($hall_options as $value => $text) {
                  $selected = ($value == $hall) ? "selected" : "";
                  echo "<option value='$value' $selected>$text</option>";
                }
                echo "</select>";
                $seatdate = isset($_POST['seatdate']) ? $_POST['seatdate'] : date('Y-m-d');

                echo "<label class='text-light'style='margin-left: 100px;'>Select Date: </label>";
                echo "<input type='date' name='seatdate' value='$seatdate' onchange='this.form.submit()' >";

                $seattime = isset($_POST['time']) ? $_POST['time'] : '10:00:00';
                $time_options = array(
                  "10:00:00" => "10:00 AM",
                  "13:00:00" => "01:00 PM",
                  "16:00:00" => "04:00 PM",
                  "19:00:00" => "07:00 PM",
                  "22:00:00" => "10:00 PM",
                );

                echo "<label class='text-light'style='margin-left:100px;'>Select Time:</label>";
                echo "<select name='time' onchange='this.form.submit()'>";
                foreach ($time_options as $value => $text) {
                  $selected = ($value == $seattime) ? "selected" : "";
                  echo "<option value='$value' $selected>$text</option>";
                }
                echo "</select>";

                ?>
                <br><br>
                <input style='margin-left: 772px;' type="submit" class="btn btn-success" value="Confirm">

                <br><br><br><br>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <script>

    function updateSeatTable() {
      //update value of checkbox elements by change the selected hall
      var hall = document.getElementsByName("hall")[0].value;

      for (var i = 1; i <= 12; i++) {
        for (var j = 1; j <= 18; j++) {
          var row = String.fromCharCode(64 + i);
          var seat = hall + row + ("0" + j).slice(-2);
          document.getElementsByName("checkbox[]")[18 * (i - 1) + (j - 1)].value = seat;
        }
      }
    }

    //refresh page
    function refreshPage() {
      window.location.href = window.location.href;
    }

    //update db seat status by listen the checkbox changer
    $(document).ready(function () {
      $('input[type=checkbox]').change(function () {
        var seat_id = $(this).val();

        if ($(this).is(':checked')) {
          return;
        } else {

          $.ajax({
            url: '../Admin_Account_Module/update_seat_status.php',
            type: 'POST',
            data: {
              seat_id: seat_id,
              status: 'AVAILABLE',
              hall: '<?php echo $hall; ?>',
              seatdate: '<?php echo $seatdate; ?>',
              seattime: '<?php echo $seattime; ?>'
            },
            success: function (data) {
              console.log(seat_id);
              $('input[type=checkbox][value="' + seat_id + '"]').removeAttr('disabled');
              $('input[type=checkbox][value="' + seat_id + '"]').parent().removeClass('unavailable');
            }
          });
        }
      });
    });

    $(document).ready(function () {
      $('input[type=checkbox].red').change(function () {
        var isChecked = $(this).is(':checked');
        if (isChecked) {
          $(this).next('.fa-couch').css('color', '#fffff'); 
        } else {
          $(this).next('.fa-couch').css('color', ''); 
        }
      });
    });

  </script>

</body>

</html>