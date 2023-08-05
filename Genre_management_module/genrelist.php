<?php
require_once 'connection.php';

if(isset($_POST['add'])){
    $genre_name = ucwords(strtolower($_POST['genre_name']));
    
    /* Add genre */
    function checkGenreName($genre_name, $con){
        if($genre_name == NULL){
            return "Please enter the <b>genre name.</b>";
        }
        else if(checkDuplicateName($genre_name, $con)){
            return "Same <b>genre name</b> detected";
        }
    }
    
    function checkDuplicateName($genre_name, $con){
        $exist = false;
        $sql = $con->prepare("SELECT * FROM genre WHERE genre_name = ? LIMIT 1");
        $sql->bind_param("s", $genre_name);
        $sql->execute();
        $result = $sql->get_result();
        if($result && $result->num_rows > 0){
            $exist = true;
        }
        return $exist;
    }
    
    $error['genreName'] = checkGenreName($genre_name, $con);
    $error = array_filter($error);  
}

if(isset($_GET['id'])){
    $genre_id = strtoupper(trim($_GET['id']));
    
    $query = "SELECT * FROM genre WHERE genre_id = $genre_id";
    $run = mysqli_query($con, $query);
    if($run){
        while($row = mysqli_fetch_assoc($run)){      
            if(isset($_POST['edit'])){
                $genre_name = $_POST['genreName'];
                $org_genre_name = $row['genre_name'];
                
                /* Edit genre */
                function checkEditGenreName($genre_name, $con, $org_genre_name){
                    if($genre_name == NULL){
                        return "Please enter the <b>genre name.</b>";
                    }
                    else if(checkEditDuplicateName($genre_name, $con, $org_genre_name)){
                        return "Same <b>genre name</b> detected";
                    }
                }
            
                function checkEditDuplicateName($genre_name, $con, $org_genre_name){
                    $exist = false;
                    $sql = $con->prepare("SELECT * FROM genre WHERE genre_name = ? LIMIT 1");
                    $sql->bind_param("s", $genre_name);
                    $sql->execute();
                    $result = $sql->get_result();
                    if($result && $result->num_rows > 0){
                        if($genre_name != $org_genre_name){
                            $exist = true;
                        }
                    }
                    return $exist;
                }
                $editerror['editname'] = checkEditGenreName($genre_name, $con, $org_genre_name);
                $editerror = array_filter($editerror);  
            }
?>

<!-- Edit Genre -->
<div class="jumbotron" id="edit-genreform" style="display: none;">
    <?php
        // Display the error message
        if(isset($_POST['edit'])){
            echo "<ul class='error'>";
            foreach($editerror as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
            echo "<script>
                    var editGenreform = document.getElementById('edit-genreform');
                    var editGenre = document.getElementById('edit-genre'');
        
                    editGenreform.style.display = 'block';
                    editGenreform.style.zIndex = '10000'';
            
                    // Let the background become darker
                    var overlay = document.createElement('div');
                    overlay.setAttribute('style', 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999;');
                    document.body.appendChild(overlay);
        
                    var windowElement = window;
                    windowElement.addEventListener('click', function(e){
                        if(e.target.parentNode !== editGenre && !editGenreform.contains(e.target)){
                            editGenreform.style.display = 'none';
                            overlay.setAttribute('style', 'z-index：-9999;');
                        }
                    });
            </script>";
        }
    ?>
    <form action="#" method="post">
        <div class="form-row">
            <div class="col-12">
                <input type="text" name="genreName" class="form-control" value="<?php echo (isset($_POST['edit'])) ? $genre_name : $row['genre_name'] ?>">
            </div>
        </div>
        <br/><br/>
        <input class="btn btn-primary btn-xs" name="edit" type="submit" value="Update Genre" />
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var editGenreform = document.getElementById("edit-genreform");
        var editGenre = document.getElementById("edit-genre");
        
        editGenreform.style.display = "block";
        editGenreform.style.zIndex = "10000";
            
        // Let the background become darker
        var overlay = document.createElement('div');
        overlay.setAttribute('style', 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999;');
        document.body.appendChild(overlay);
        
        var windowElement = window;
        windowElement.addEventListener("click", function(e){
            if(e.target.parentNode !== editGenre && !editGenreform.contains(e.target)){
                editGenreform.style.display = "none";
                overlay.setAttribute('style', 'z-index：-9999;');
            }
        });
    });
</script>

<?php 
        }
    } 
    if(isset($_POST['edit']) && empty($editerror)){
        $name = ucwords(strtolower($_POST['genreName']));
        
        $stmt = $con->prepare("UPDATE genre SET genre_name = ? WHERE genre_id = ?");
        $stmt->bind_param("si", $name, $genre_id);
        $stmt->execute();
        
        if($stmt){
            // Record update
            echo "<script>alert('Genre Successfully Updated...');window.location.href='genrelist.php';</script>";
        }
        else{
            // Unable to update
            echo "<ul class='error'>";
            foreach($editerror as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>GRC Cinema</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link href="../css/movielist.css" rel="stylesheet" type="text/css"/>
        <script src="../js/admin_dashboard.js" type="text/javascript"></script>
        <style>
            .error{
                border: 2px solid black;
                background-color: pink;
                list-style-position: inside;
                padding-left: 25px;
                padding-top: 15px;
                padding-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div id="sidebar" class="sidebar">
            <p class="logo"><span>G</span>RC</p>
            <a href="../Admin_Account_Module/admin_dashboard.php" class="icon-a"><i class="fa fa-dashboard icons"></i>&nbsp;&nbsp;Dashboard</a>
            <a href="../Admin_Account_Module/staff_detail.php" class="icon-a"><i class="fa fa-user icons"></i>&nbsp;&nbsp;Admin</a>
            <a href="../Security_module/member_list.php" class="icon-a"><i class="fa fa-users icons"></i>&nbsp;&nbsp;Member</a>
            <a href="../Security_module/banned_list.php" class="icon-a"><i class="fa fa-solid fa-ban icons"></i>&nbsp;&nbsp;Banned List</a>
            <a href="../Movie_management_module/movielist.php" class="icon-a"><i class="fa fa-solid fa-film icons"></i>&nbsp;&nbsp;Movie</a>
            <a href="genrelist.php" class="icon-a"><i class=" fa fa-light fa-ticket icons"></i>&nbsp;&nbsp;Genre</a>
            <a href="../Seat_Module/staff_seat.php" class="icon-a"><i class="fa fa-thin fa-chair icons"></i>&nbsp;&nbsp;Seat </a>
            <a href="../Time_table/timetable_admin.php" class="icon-a"><i class="fa fa-list-alt icons"></i>&nbsp;&nbsp;Time Table</a>
            <a href="../payment/admin/transaction.php" class="icon-a"><i class="fa fa-regular fa-credit-card icons"></i>&nbsp;&nbsp;Transaction</a>
            <a href="../faq_module/admin_faq.php" class="icon-a"><i class="fa-solid fa-bell fa-shake fa-lg icons"></i>&nbsp;&nbsp;Notice</a>
            <a href="../Security_module/sign_in.php" class="icon-a"><i class="fa-solid fa-right-from-bracket icons"></i>&nbsp;&nbsp;Log Out</a>
        </div>
        
        <div id="main">
        <div class="head"> 
          <div class="col-div-6"></div>
            <span class="dashboard nav" onclick="dashboard()" >&#9776; Dashboard</span>
            <span class="dashboard nav2" onclick="dashboard2()" >&#9776; Dashboard</span>

          <div class="col-div-6"></div>
          
          <div class="profile">
              <img src="img/baby.jpg" class="pro-img"  alt="">
              <p >Baby Boss <span>President</span></p>
          </div>
          
        <div class="col-div-8">
            <div class="box-8">
                <div class="content-box">
                    <div class="heading">
                        <p class="add-title">Genre List</p><br>
                    </div>
                    <div class="container">
                        <div class="head">
                            <table class="table table-striped" style="background: #fff; border-radius: 1.5rem;">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Genre ID</th>
                                        <th scope="col">Genre Name</th>
                                        <th scope="col">No. of Post</th>
                                        <th scope="col">Operations</th>
                                    </tr>
                                </thead>
                                <?php
                                $i = 1;
                                $stmt = $con->prepare("SELECT * FROM genre");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    while($row = $result->fetch_array()){
                                ?>
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php printf("%d", $i++) ?></th>
                                        <td><?php echo $row['genre_id']; ?></td>
                                        <td><?php echo $row['genre_name']; ?></td>
                                        <?php 
                                        $id = $row['genre_id'];
                                        
                                        $query = $con->prepare("SELECT count(*) AS TOTAL FROM movie m, genre g WHERE m.genre_id = g.genre_id AND g.genre_id = ?;");
                                        $query->bind_param("i", $id);
                                        $query->execute();
                                        $res = $query->get_result();
                                        if($res){
                                            while($ro = $res->fetch_array()){
             
                                        ?>
                                        <td><?php echo $ro['TOTAL']; ?></td>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <td><input type="button" class="btn btn-danger btn-delete" data-id="<?php echo $row['genre_id']; ?>" value="Delete" /> <a href="genrelist.php?id=<?php echo $row['genre_id'] ?>" id="edit-genre" class="btn btn-outline-secondary">Edit</a></td>
                                    </tr>
                                </tbody>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="container">
                        <div class="head">
                            <div class="jumbotron" id="add-genreform">
                                <?php
                                if(isset($_POST['add'])){
                                    if(empty($error)){
                                        $query = $con->prepare("INSERT INTO genre (genre_name) VALUES (?)");
                                        $query->bind_param("s", $genre_name);
                                        $query->execute();
                                        if($query){
                                            echo "<script>alert('Genre Successfully Added...');window.location.href='genrelist.php';</script>";
                                        }
                                        else{
                                            echo "<script>alert('Something Went Wrong!!');window.location.href='genrelist.php';</script>";
                                        }
                                    }
                                    else{
                                        echo "<ul class='error'>";
                                        foreach($error as $value){
                                            echo "<li>$value</li>";
                                        }
                                        echo "</ul>";
                                        
                                        echo "<script>
                                            var addGenreform = document.getElementById('add-genreform');
                                            var addGenre = document.getElementById('add-genre');

                                            addGenreform.style.display = 'block';
                                            addGenreform.style.zIndex = '10000';

                                            // Let the background become darker
                                            var overlay = document.createElement('div');
                                            overlay.setAttribute('style', 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999;');
                                            document.body.appendChild(overlay);

                                            var windowElement = window;
                                            windowElement.addEventListener('click', function(e){
                                                if(e.target.parentNode !== addGenre && !addGenreform.contains(e.target)){
                                                    addGenreform.style.display = 'none';
                                                    overlay.setAttribute('style', 'z-index：-9999;');
                                                }
                                            });
                                        </script>";
                                    }
                                }
                                ?>
                                <form action="" method="post">
                                    <div class="form-row">
                                        <div class="col-12">
                                          <input type="text" name="genre_name" class="form-control" value="<?php echo isset($_POST['add']) ? $genre_name : ''; ?>" placeholder="Genre Name">
                                        </div>
                                    </div>
                                    <br/><br/>
                                    <input class="btn btn-primary btn-xs" name="add" type="submit" value="Add Genre">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="add-class">
                        <div class="empty-box"></div>
                        <button id="add-genre"><i class='bx bx-plus'></i></button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Delete Button -->
    <script>
        const deleteButtons = document.querySelectorAll('.btn-delete');
    
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id; // Get the ID of the genre to be deleted
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
            
                // Send an Ajax request to the server to check if the genre can be deleted
                // Only genre without movie post can be deleted
                $.ajax({
                    url: 'checkgenre.php',
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        var response = JSON.parse(response);
                        if (response.total > 0) {
                            // If the genre cannot be deleted, a warning message is displayed
                            swalWithBootstrapButtons.fire({
                                title: 'Unable to delete the genre',
                                text: 'Please make sure there are no movie posts in this genre!',
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                cancelButtonText: '',
                                reverseButtons: true
                            });
                        } else {
                            // If the genre can be deleted, a confirmation message box is displayed
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
                                        'The genre has been deleted.',
                                        'success'
                                    );
                                    setTimeout(function(){
                                        window.location.href = "deletegenre.php?deleteid=" + id;
                                    }, 2000); // 延迟2秒后执行删除操作
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    swalWithBootstrapButtons.fire(
                                        'Cancelled',
                                        'The genre is safe :)',
                                        'error'
                                    );
                                }
                            });
                        }
                    }
                });
            });
        }); 
    </script>
    
    <!-- Add Genre -->
    <script>
        var addGenreform = document.getElementById("add-genreform");
        var addGenre = document.getElementById("add-genre");
        
        addGenre.addEventListener("click", function(){
            addGenreform.style.display = "block";
            addGenreform.style.zIndex = "10000";
            
            // Let the background become darker
            var overlay = document.createElement('div'); // 创建一个 div
            overlay.setAttribute('style', 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999;'); // 设置黑色幕布
            document.body.appendChild(overlay); // 将幕布添加到 body
            
            var windowElement = window;
            windowElement.addEventListener("click", function(e){
                if(e.target.parentNode !== addGenre && !addGenreform.contains(e.target)){
                    addGenreform.style.display = "none";
                    overlay.setAttribute('style', 'z-index：-9999;');
                }
            });
        });
    </script>
</html>