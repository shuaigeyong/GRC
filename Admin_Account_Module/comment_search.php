<?php
include'con_db.php';
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
  <link href="../css/comment.css" rel="stylesheet" type="text/css"/>
  <script src="../js/admin_dashboard.js" type="text/javascript"></script>
</head>
<body>
<?php

if(isset($_POST['input']) && isset($_POST['id'])){
    $search = trim($_POST['input']);
    $movieid = $_POST['id'];
    $sql = "SELECT r.rating_id, u.user_name, r.user_id, r.rating, r.review, r.rating_date FROM rating r JOIN users u ON r.user_id = u.user_id WHERE (r.user_id LIKE '%$search%' OR u.user_name LIKE '%$search%' OR r.rating_id LIKE '%$search%' OR r.rating LIKE '%$search%' OR r.review LIKE '%$search%' OR r.rating_date LIKE '%$search%') AND r.movie_id = $movieid; ";
    $result = mysqli_query($con,$sql);
    
    if(mysqli_num_rows($result) > 0){
        printf("
            <br>
            <table>
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Date</th>
                    <th>Operations</th>
                  </tr>
            ");
        while($row = mysqli_fetch_assoc($result)){
            $uid = $row['user_id'];
            $rating = $row['rating'];
            $review = $row['review'];
            $date = $row['rating_date'];
            $rating_id =$row['rating_id'];
            $uname=$row['user_name'];
            
            printf("
                  <tr>
                            <td>%d</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%.2f</td>
                            <td>%s</td>
                            <td>
                            <button class='btn btn-outline-danger btndel text-light' data-id='{$rating_id}' data-id2='{$uid}'>Delete</button>
                            </td>
                            </tr>", $uid, $uname, wordwrap($review, 100, "<br>"), $rating, $date);
       
        }
    
        
    }else{
        printf("
            <br>
            <table>
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Date</th>
                    <th>Operations</th>
                  </tr>
                  <tr>
                  <td class='text-danger' colspan='7' style='text-align: center;'>No Comments Found !</td>
                  </tr>
            "); 
    }
    
    echo"</table>";
}

?>
</body>
<script>
$(document).ready(function() {
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
          setTimeout(function() {
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
});
</script>
