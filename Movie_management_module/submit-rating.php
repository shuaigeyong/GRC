<?php 
session_start();
require_once 'connection.php';
?>

<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

$user_id = $_SESSION['user_id'];
 
if(isset($_POST["rating_data"])){
    $data = array(
            'movie_id'       =>   $_POST["movie_id"],
            'rating'         =>   $_POST["rating_data"],
            'review'         =>   $_POST["user_review"],
            'rating_date'    =>   date('Y-m-d'),
            'rating_time'    =>   date('H:i:s')
    );    
    
    $sql = "SELECT * FROM RATING WHERE USER_ID = ? AND MOVIE_ID = ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $user_id, $data['movie_id']);
    $stmt->execute();
    $result = $stmt->get_result();
                        
    if($result->num_rows > 0){ 
        $sql = "UPDATE RATING SET RATING = ?, REVIEW = ?, RATING_DATE = ?, RATING_TIME = ? WHERE USER_ID = ? AND MOVIE_ID = ?";
    
        $stmt = $con->prepare($sql);
        $stmt->bind_param('isssii', $data['rating'], $data['review'], $data['rating_date'], $data['rating_time'], $user_id, $data['movie_id']);
        $stmt->execute(); 
    }else{   
        $sql = "INSERT INTO RATING (USER_ID, MOVIE_ID, RATING, REVIEW, RATING_DATE, RATING_TIME) VALUES (?, ?, ?, ?, ?, ?)";
    
        $stmt = $con->prepare($sql);
        $stmt->bind_param('iiisss', $user_id, $data['movie_id'], $data['rating'], $data['review'], $data['rating_date'], $data['rating_time']);
        $stmt->execute();         
    }  
}

if(isset($_POST["del_userId"])){
    $delete = array(
        'user_id'    =>   $_POST["del_userId"],
        'movie_id'   =>   $_POST["del_movieId"]
    );
    
    $sql = "DELETE FROM RATING WHERE USER_ID = ? AND MOVIE_ID = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $delete['user_id'], $delete['movie_id']);
    $stmt->execute();
}

if(isset($_POST["action"])){
    $avg_rating = 0;
    $rating_times = 0;
    $total_rating = 0;
    $five_star = 0;
    $four_star = 0;
    $three_star = 0;
    $two_star = 0;
    $one_star = 0;
    $review = array();
    
    $movie_id = $_POST['movie_id'];
    
    $query = "SELECT *
            FROM RATING R, USERS U, MOVIE M
            WHERE R.USER_ID = U.USER_ID
            AND R.MOVIE_ID = M.ID
            AND R.MOVIE_ID = $movie_id
            ORDER BY CASE WHEN 
            R.USER_ID = $user_id THEN 0 ELSE 1 END, RATING_ID DESC";
    
    $result = $con->query($query);
    
    while($row = mysqli_fetch_assoc($result)){
        $review[] = array(
            'user_id'     => $row['user_id'],
            'user_name'   => $row['user_name'],
            'movie_id'    => $row['movie_id'],
            'movie_name'  => $row['mv_name'],
            'rating'      => $row['rating'],
            'review'      => $row['review'],
            'rating_date' => date('F j, Y', strtotime($row['rating_date']))                   
        );
       
        
        if($row["rating"] == '5'){
            $five_star++;
        }
        
        if($row["rating"] == '4'){
            $four_star++;
        }
       
        if($row["rating"] == '3'){
            $three_star++;
        }
                
        if($row["rating"] == '2'){
            $two_star++;
        }
       
        if($row["rating"] == '1'){
            $one_star++;
        }
       
        $rating_times++;
       
        $total_rating += $row["rating"]; 
                               
    }
    
    if($rating_times != 0){
        $avg_rating = $total_rating / $rating_times;
    }
    
    $return = array(
        'avg_rating'   =>  number_format($avg_rating, 1),
        'rating_times' =>  $rating_times,
        'five_star'    =>  $five_star,
        'four_star'    =>  $four_star,
        'three_star'   =>  $three_star,
        'two_star'     =>  $two_star,
        'one_star'     =>  $one_star,
        'review'       =>  $review
    );
            
    echo json_encode($return);
}
 
?>