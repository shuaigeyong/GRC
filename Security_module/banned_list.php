<?php
include 'connection.php';
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="../css/member_list.css" rel="stylesheet" type="text/css"/>
        <script src="../js/admin_dashboard.js" type="text/javascript"></script>
    </head>
    <body>
      <!-- Sidebar -->
      <?php
      include'sidebar_detail.php'
      ?>

      <div id="main">
        <div class="head"> 
          <div class="col-div-6"></div>
            <span onclick="dashboard()" class="nav" style="font-size: 30px;cursor: pointer; color: white;">&#9776; Dashboard</span>
            <span onclick="dashboard2()" class="nav2" style="font-size: 30px;cursor: pointer; color: white;">&#9776; Dashboard</span>

          <div class="col-div-6"></div>
          <div class="profile">
              <img src="img/baby.jpg" class="pro-img"  alt="">
            <p >Baby Boss <span>President</span></p>
          </div>

          <div class="col-div-6"></div>
          
          <div class="col-div-8">
            <div class="box-8">
                <div class="content-box">
                    <div class="container">
                        <!-- Banned List -->
                        <div class="heading">
                            <p class="add-title">Banned List</p><br>
                        </div>
                        
                        <table>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Verify Status</th>
                                <th scope="col">Join Date</th>
                                <th scope="col">Operations</th>
                            </tr>
        <?php
        $i = 1;
        $sql = $con->prepare("SELECT * FROM users WHERE user_type = 'user' AND account_available = 0"); 
        $sql->execute();
        $result = $sql->get_result();
        if($result && $result->num_rows > 0){
            while($row = $result->fetch_array()){
                $id = $row['user_id'];
                $name = $row['user_name'];
                $email = $row['email'];
                $verify_status = $row['verify_status'];
                $join_date = $row['join_date'];
              
                printf("<tr>
                            <th scope='row'>%d</th>
                            <td>%d</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td style='position: relative; left: 50px;'>%s</td>
                            <td>%s</td>
                            <td>
                                <button class='btn btn-outline-primary' data-id='{$id}' > <a class='text-light'>Restore</a></button>
                            </td>
                        </tr>", $i++, $id, $name, $email, $verify_status, $join_date, $id);
            }
        }
        else{
            printf("<tr><td colspan='7' style='text-align: center;'>No account has been banned!</td></tr>");
        }
      ?>
                </table>
              </div>
            </div>
          </div>
              
          <div class="clearfix"></div>
          
        </div>
      </div>
      </div>
    </body>
    
    <!-- Restore Button -->
    <script>
        // The confirmation message for the restore account
        const deleteButtons = document.querySelectorAll('.btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id; // 如果一个元素有一个名为"data-id"的自定义属性，那么可以使用元素的dataset.id属性来访问该属性的值
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false
                });
                
                swalWithBootstrapButtons.fire({
                  title: 'Are you sure?',
                  text: "This account will be restored!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes, restore it!',
                  cancelButtonText: 'No, cancel!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire(
                      'Restore!',
                      'This account has been restored :)',
                      'success'
                    );

                    setTimeout(function(){
                        window.location.href = "deletemember.php?restoreid=" + id;
                    }, 2000); // 延迟才2秒执行restore
                  } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    swalWithBootstrapButtons.fire(
                      'Cancelled',
                      'This account is still be banned.',
                      'error'
                    );
                  }
                });
            });
        }); 
    </script>
</html>