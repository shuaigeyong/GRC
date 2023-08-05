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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <link href="../css/comment.css" rel="stylesheet" type="text/css" />
  <script src="../js/admin_dashboard.js" type="text/javascript"></script>
</head>

<body>
  <?php
  include '../Admin_Account_Module/sidebar_detail.php'
    ?>

  <div id="main">
    <div class="head">
      <div class="col-div-6"></div>
      <span class="dashboard nav" onclick="dashboard()">&#9776; Dashboard</span>
      <span class="dashboard nav2" onclick="dashboard2()">&#9776; Dashboard</span>


      <div class="col-div-6"></div>

      <div class="profile">

        <img src="../img/baby.jpg" class="pro-img" alt="">
        <p>Baby Boss <span>President</span></p>
      </div>

      <div class="clearfix"></div>

      <div id="search-results"></div>
      <div class="col-div-8">
        <div class="box-8">
          <div class="content-box">
            <p>Comment<a href="rating.php"><span class="backBtn" style="cursor: pointer">Back</span></a>
              <input style="margin-left: 40%;" type="text" placeholder="Search..." name="searchcomment" id="searchCom"
                autocomplete="off" />
            </p>

            <div id="searchresult">

            </div>
            <br>
            <table id="result">
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Date</th>
                <th>Operations</th>
              </tr>
              <?php
              if (isset($_GET['movie_id'])) {
                $id = $_GET['movie_id'];
                $sql = "SELECT users.user_id,users.user_name, rating.rating_id, rating.rating, rating.review, rating.rating_date FROM rating JOIN users ON rating.user_id = users.user_id WHERE rating.movie_id =$id";
                $result = mysqli_query($con, $sql);
                if ($result && $result->num_rows > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['user_id'];
                    $name = $row['user_name'];
                    $rating = $row['rating'];
                    $rating_id = $row['rating_id'];
                    $review = $row['review'];
                    $date = $row['rating_date'];
                    printf("<tr>
                            <td>%d</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%.2f</td>
                            <td>%s</td>
                            <td>
                            <button  class='btn btn-outline-danger btndel' data-id='{$rating_id}' data-id2 ='{$id}'> <a class='text-light'>Delete</a></button>
                            </td>
                            </tr>", $id, $name, wordwrap($review, 100, "<br>"), $rating, $date);
                  }
                } else {
                  printf("<tr><td class='text-danger' colspan='7' style='text-align: center;'>No Comments Found !</td></tr>");
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
  $(document).ready(function () {
    const deleteButtons = document.querySelectorAll('.btndel');

    deleteButtons.forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        const id2 = button.dataset.id2;
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
          title: 'Are you sure?',
          text: "This message will be permanently deleted.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delele it!',
          cancelButtonText: 'No, keep it!',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            swalWithBootstrapButtons.fire(
              'Deleted!',
              'This comment has been permanently deleted!',
              'success'
            );
            setTimeout(function () {
              window.location.href = "comment_delete.php?ratingid=" + id + "&&movieid=" + id2;
            }, 2000);
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              'Cancelled',
              'This comment is safe.',
              'error'
            );
          }
        });
      });
    });

    $("#searchCom").keyup(function () {
      var input = $(this).val();
      var movieId = "<?php echo $_GET['movie_id']; ?>";

      if (input !== "") {
        $.ajax({
          url: "comment_search.php",
          method: "POST",
          data: { input: input, id: movieId },
          success: function (data) {
            $("#searchresult").html(data);
            $("#searchresult").css("display", "block");
            $("#result").css("display", "none");
          }
        });
      } else {
        $("#searchresult").css("display", "none");
        $("#result").css("display", "table");
      }
    });
  });
</script>

<? phpmysqli_close($con);
?>

</html>