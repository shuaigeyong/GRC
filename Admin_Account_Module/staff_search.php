<?php
include 'con_db.php';
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>

<head>
  <meta charset="UTF-8">
  <title>GRC Cinema</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link href="../css/staff_search.css" rel="stylesheet" type="text/css" />
  <script src="../js/admin_dashboard.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <?php
  include 'sidebar_detail.php'
    ?>

  <div id="main">
    <div class="head">
      <div class="col-div-6"></div>
      <span onclick="dashboard()" class="nav" style="font-size: 30px;cursor: pointer; color: white;">&#9776;
        Accounts</span>
      <span onclick="dashboard2()" class="nav2" style="font-size: 30px;cursor: pointer; color: white;">&#9776;
        Accounts</span>

      <div class="col-div-6"></div>
      <div class="profile">
        <img src="../img/baby.jpg" class="pro-img" alt="">
        <p>Baby Boss <span>President</span></p>
      </div>

      <div class="clearfix"></div>


      <div class="col-div-8">
        <div class="box-8">
          <div class="content-box">

            <form action="staff_search.php" method="POST">
              <p>Staff Accounts
                <span style="cursor: pointer"><button class="btn btn-outline-info "><a href="staff_add.php"
                      class="text-light">Add User</a></button></span>
                <span><button class="btn btn-outline-light btnsearch" name="submit">Search</button></span>
                <span><input type="text" placeholder="Search..." name="search" class="iptsearch" autocomplete="off"
                    value=<?php echo $_POST['search']; ?>></span>
              </p>
            </form>
            <br>
            <table>


              <?php
              //check if the submit button is clicked
              if (isset($_POST['submit'])) {

                //get the user input
                $search = $_POST['search'];

                $sql = "SELECT * FROM users WHERE (user_name LIKE '%$search%' OR user_id LIKE'%$search%' OR email LIKE'%$search%' OR phone LIKE'%$search%' OR user_type LIKE'%$search%' OR join_date LIKE '%$search%') AND user_type != 'user'";

                //execute the query
                $result = mysqli_query($con, $sql);

                // Check if the query was successful
                if ($result) {
                  if (mysqli_num_rows($result) > 0) {
                    echo '
                                    <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">User Type</th>
                                    <th scope="col">Join Date</th>
                                    <th scope="col">Operations</th>
                                    </tr>';

                    //display data from db
                    while ($row = mysqli_fetch_assoc($result)) {

                      $id = $row['user_id'];
                      $name = $row['user_name'];
                      $email = $row['email'];
                      $phone = $row['phone'];
                      $joinDate = $row['join_date'];
                      $usertype = $row['user_type'];
                      printf("<tr>
                                        <th scope='row'>%d</th>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>
                                        <button class=' btn btn-outline-primary'> <a class='text-light' href='staff_update.php?updateid=%d'>Update</a></button>
                                        <button class='btn btn-outline-danger btndel'> <a class='text-light' data-id='{$id}'>Delete</a></button>
                                        </td>
                                        </tr>", $id, $name, $email, $phone, $usertype, $joinDate, $id);
                    }

                  } else {
                    echo "<p class='text-danger' >Data Not Found!</p>";
                  }

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
  const deleteButtons = document.querySelectorAll('.btndel');

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
        text: "This account will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delele it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          swalWithBootstrapButtons.fire(
            'Deleted!',
            'This account has been deleted :)',
            'success'
          );
          setTimeout(function () {
            window.location.href = "staff_delete.php?deleteid=" + id;
          }, 2000); 
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelled',
            'This account is safe.',
            'error'
          );
        }
      });
    });
  }); 
</script>

</html>