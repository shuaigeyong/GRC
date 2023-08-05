<?php
require_once 'connection.php';
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="../css/movielist.css" rel="stylesheet" type="text/css"/>
        <script src="../js/admin_dashboard.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
        include 'sidebar_detail.php';
        ?>
        
        <div id="main">
        <div class="head"> 
            <div class="col-div-6"></div>
            <span class="dashboard nav" onclick="dashboard()" >&#9776; Dashboard</span>
            <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Dashboard</span>
            

            <div class="col-div-6"></div>
          
            <div class="profile">
                <img src="img/baby.jpg" class="pro-img"  alt="">
                <p>Baby Boss <span>President</span></p>
            </div>
          
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box">
                        <div class="container">
                            <div class="heading">
                                <p>Movies List</p><br>
                                <div><a class="btn btn-warning text-light" href="addmovie.php">Add a Movie</a></div>
                                <form class="form-inline" action="movielist.php" method="post">
                                    <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search">
                                    <button class="btn btn-success" name="submit" type="submit">Search</button>
                                </form>
                            </div>
                            <div class="row" id="movielist">
                                <?php
                                $query = "SELECT * FROM movie";
                                $run = mysqli_query($con, $query);
                            
                                if($run){
                                    while($row = mysqli_fetch_assoc($run)){
                                ?>
                                <div class="card-container">
                                    <div class="card" style="width: 300px; text-align: center;">
                                        <?php echo "<img src='../thumb/".$row['img']."' height='380px'/>" ?>
                                        <div class="card-body">
                                            <h4 class="card-title"><?php echo $row['mv_name']; ?></h4>
                                            <br/>
                                            <a href="viewmovie.php?id=<?php echo $row['id']; ?>" id="btn-1">View Details</a>
                                            <br/><br/>
                                            <input type="button" class="btn btn-danger btn-delete" data-id="<?php echo $row['id']; ?>" id="btn-2" value="Delete" />
                                            <a href="editmovie.php?id=<?php echo $row['id']; ?>" class="btn btn-info" id="btn-3">Edit</a>
                                        </div>
                                    </div>
                                </div>
                                <?php        
                                    }
                                }
                                ?>
                            </div>
                                
                            <!-- Searching Movie -->
                            <div class="row" id="searchmovie">
                                <?php
                                if(isset($_POST['submit'])){
                                    echo "<style>
                                            #movielist{display: none;}
                                            #searchmovie{display: grid;}
                                          </style>";
                                    $search = $_POST['search'];
                                    $searchpreg = preg_replace("/^[0-9a-z]$/i", "", $search);
                                    $querySearch = "SELECT * FROM movie m, genre g WHERE g.genre_id = m.genre_id AND (mv_name LIKE '%$search%'
                                              OR release_date LIKE '%$search%' OR lang LIKE '%$search%' OR director LIKE '%$search%' OR writter LIKE '%$search%'
                                              OR starring LIKE '%$search%' OR music LIKE '%$search%' OR country LIKE '%$search%' OR genre_name LIKE '%$search%')";
                                    $sql = mysqli_query($con, $querySearch);
                                    if($sql){ 
                                        if($sql->num_rows == 0){
                                            echo "<h1>No Movie Found!</h1>";
                                        }
                                        else{
                                            while($result = $sql->fetch_array()){
                                                ?>
                                                <div class="card-container">
                                                    <div class="card" style="width: 300px; text-align: center;">
                                                        <?php echo "<img src='../thumb/".$result['img']."' height='380px'/>" ?>
                                                        <div class="card-body">
                                                            <h4 class="card-title"><?php echo $result['mv_name']; ?></h4>
                                                            <br/>
                                                            <a href="viewmovie.php?id=<?php echo $result['id']; ?>" id="btn-1">View Details</a>
                                                            <br/><br/>
                                                            <input type="button" class="btn btn-danger btn-delete" data-id="<?php echo $result['id']; ?>" id="btn-2" value="Delete" />
                                                            <a href="editmovie.php?id=<?php echo $result['id']; ?>" class="btn btn-info" id="btn-3">Edit</a>
                                                        </div>
                                                    </div>
                            
                                                </div>
                                            <?php
                                            }
                                        }
                                    }
                                }
                                else{
                                    echo "<style>
                                            #movielist{display: grid;}
                                            #searchmovie{display: none;}
                                          </style>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </body>    
    
    <!-- Delete Button -->
    <script>
        // The confirmation message for the delete movie
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
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
                      'The movie has been deleted.',
                      'success'
                    );
                    setTimeout(function(){
                        window.location.href = "deletemovie.php?id=" + id;
                    }, 2000); // 延迟才2秒执行delete
                  } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    swalWithBootstrapButtons.fire(
                      'Cancelled',
                      'The movie is safe :)',
                      'error'
                    );
                  }
                });
            });
        }); 
    </script>
</html>