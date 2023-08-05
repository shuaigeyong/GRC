<?php 
session_start();
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link href="../css/rate-review.css" rel="stylesheet" type="text/css"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <title>Ratings & Reviews</title>
    </head>
    <body>   
        <div class="rating-container">
        <div class="header">Ratings and Reviews<i id="view-review" class="fas fa-arrow-right arrow"></i></div>
    	<div class="flex-container">  
            <div class="first-layer">
            <div class="left">                
                <div class="left-arrange">
                    <p class="avgRate"><b><span id="average_rating">0.0</span></b></p>
                    <div>
                        <i class="fas fa-star star-light mr-1 main_star"></i>
                        <i class="fas fa-star star-light mr-1 main_star"></i>
                        <i class="fas fa-star star-light mr-1 main_star"></i>
                        <i class="fas fa-star star-light mr-1 main_star"></i>
                        <i class="fas fa-star star-light mr-1 main_star"></i>
                    </div>
                    <p class="total-review"><span id="total_review">0 </span> Ratings</p>
                </div>               
            </div>
            
            <div class="right">
                <div class="right-arrange">
                    <div class="progressBar">
                        <div class="progress-label-left"><b>5</b></div>                            
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                        </div>
                    </div>
                    <div class="progressBar">
                        <div class="progress-label-left"><b>4</b></div>                            
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                        </div>               
                    </div>
                    <div class="progressBar">
                        <div class="progress-label-left"><b>3</b></div>                         
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                        </div>               
                    </div>
                    <div class="progressBar">
                        <div class="progress-label-left"><b>2</b></div>                           
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                        </div>               
                    </div>
                    <div class="progressBar">
                        <div class="progress-label-left"><b>1</b></div>                            
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                        </div>               
                    </div>
                    <div class="add-button"> 
                        <?php 
                        require_once 'connection.php';
                        
                        if(isset($_GET['id'])){
                            $movie_id = $_GET['id'];
                        }
            
                        $user_id = $_SESSION['user_id'];                       
            
                        $sql = "SELECT * FROM RATING WHERE USER_ID = ? AND MOVIE_ID = ?";
            
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('ii', $user_id, $movie_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if($result->num_rows > 0){
                            echo "<button type='button' name='edit-review' id='edit-review' class='reviewbtn'>Edit Your Rating</button>";       
                            $rows = $result->fetch_assoc();
                            $rating = $rows['rating'];
                            $review = $rows['review'];
                        }else{     
                            echo "<button type='button' name='add-review' id='add-review' class='reviewbtn'>Write Review</button>";   
                            $rows = $result->fetch_assoc();
                            $rating = 0;
                            $review = '';
                        }
                        ?>
                    </div>
                </div>
            </div>
            </div>
            <br><br>
            <div class="review-box" id="review_content"></div>
        </div>   
        
        <div class="alert alert-warning alert-dismissible fade show display" role="alert">
            <strong>Holy guacamole!</strong> You should check in on some of those fields below.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <div id="review_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Your Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
                <h4 class="text-center mt-2 mb-4" id="rate">
                    <i class="fas fa-star star-light submit_star mr-1 org-star" id="submit_star_1" data-rating="1"></i>
                    <i class="fas fa-star star-light submit_star mr-1 org-star" id="submit_star_2" data-rating="2"></i>
                    <i class="fas fa-star star-light submit_star mr-1 org-star" id="submit_star_3" data-rating="3"></i>
                    <i class="fas fa-star star-light submit_star mr-1 org-star" id="submit_star_4" data-rating="4"></i>
                    <i class="fas fa-star star-light submit_star mr-1 org-star" id="submit_star_5" data-rating="5"></i>
	        </h4>
	        <div class="form-group">
                    <textarea name="user_review" id="user_review" class="form-control" placeholder="Share your feelings"></textarea>
	        </div>
	        <div class="form-group text-center mt-4">
                    <button type="button" class="btn btn-primary" id="save_review">Submit</button>
	        </div>
	    </div>
    	</div>
    </div>
</div>

<div id="show-review" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Reviews</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="reviews-list">
                    <div id="all-reviews" class="all-reviews"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    </div>
    </body>
</html>

<script> 
    function editReview(){
        var original_rating = <?php echo $rating; ?>;
        var original_review = '<?php echo $review; ?>'; 
        
        console.log("Test " + original_rating);
        console.log("Test2 " + original_review);            
            
        for(var count = 1; count <= original_rating; count++){
            $('#submit_star_'+count).addClass('text-warning');
        }
        
        $('#user_review').html(original_review);
    }
    
    $(document).ready(function(){
        $('#view-review').click(function(){
            $('#show-review').modal('show');
        });
        
        $('#edit-review').click(function(){
            $('#review_modal').modal('show');
            editReview();
        });   
        
        var rating_data = 0;

        $('#add-review').click(function(){
            $('#review_modal').modal('show');
        });
        
        $(document).on('mouseenter', '.submit_star', function(){

            var rating = $(this).data('rating');

            reset_background();

            for(var count = 1; count <= rating; count++){
                $('#submit_star_'+count).addClass('text-warning');
            }
        });

        function reset_background(){
            for(var count = 1; count <= 5; count++){
                $('#submit_star_'+count).addClass('star-light');

                $('#submit_star_'+count).removeClass('text-warning');
            }
        }
    
        var rating_data = <?php echo $rating; ?>;
        
        function mouseleave(){
            $(document).on('mouseleave', '.submit_star', function(){
                console.log("New1 " + rating_data);
                reset_background();

                for(var count = 1; count <= rating_data; count++){

                    $('#submit_star_'+count).removeClass('star-light');

                    $('#submit_star_'+count).addClass('text-warning');
                }
            });      
        }
              
        mouseleave();

        $(document).on('click', '.submit_star', function(){
            rating_data = $(this).data('rating');
        });
    
//        $(document).on('click', '.close', function(){
//            reset_background();
//        });
        
        var rating_data = <?php echo $rating; ?>;
        
        //$('#add-review').click(function(){
            $('#save_review').click(function(){
                user_review = $('#user_review').val();
        
                // Check user rating or not
                if(rating_data == 0){
                    alert("Please rate this movie!");
                    //$('.display').css('display', 'block');
                    return false;
                }

                else { 
                    var movie_id = "<?php echo $movie_id; ?>";
                    
                    $.ajax({
                        url:"./submit-rating.php",
                        method:"POST",
                        data:{movie_id:movie_id, rating_data:rating_data, user_review:user_review},
                        success:function(data){
                            console.log(data);
                            alert("Successful");
                            $('#review_modal').modal('hide');
                            load_rating();
                            
                            // Update the button
                            var button = document.getElementById('add-review');
                            button.innerHTML = 'Edit Your Rating';
                            button.name = 'edit-review';
                            button.id = 'edit-review';                            
                        }
                    });
                }
            });     
            
        load_rating();
    
        function load_rating(){
            var user_id = "<?php echo $user_id; ?>";
            var movie_id = "<?php echo $movie_id; ?>";
            
            $.ajax({
                url:"submit-rating.php",
                method:"POST",
                data:{action:'load_data', movie_id:movie_id},
                dataType:"JSON",
                success:function(data){
                    $('#average_rating').text(data.avg_rating);
                    $('#total_review').text(data.rating_times);
 
                    var count_star = 0;

                    $('.main_star').each(function(){
                        count_star++;
                        $(this).removeClass('text-warning star-light');
                        
                        if(Math.ceil(data.avg_rating) >= count_star){
                            $(this).addClass('text-warning');
                            $(this).addClass('star-light');
                        }
                    });
                    
                    if(data.rating_times === 0){
                        data.rating_times = 1;
                    }
                    
                    $('#five_star_progress').css('width', (data.five_star/data.rating_times) * 100 + '%');

                    $('#four_star_progress').css('width', (data.four_star/data.rating_times) * 100 + '%');

                    $('#three_star_progress').css('width', (data.three_star/data.rating_times) * 100 + '%');

                    $('#two_star_progress').css('width', (data.two_star/data.rating_times) * 100 + '%');

                    $('#one_star_progress').css('width', (data.one_star/data.rating_times) * 100 + '%');              
                
                    if(data.review.length >= 0){
                        var i = 0;
                        var html = '';

                        for(var count = 0; count < data.review.length; count++){
    
                            if((data.review[count].review && i < 3) || (user_id === String(data.review[count].user_id) && movie_id === String(data.review[count].movie_id))){
                                if(user_id === String(data.review[count].user_id) && movie_id === String(data.review[count].movie_id)){
                                    i--;
                                }
                                
                                html += '<div class="review-container">';
                            
                                html += '<div class="headshot">'+data.review[count].user_name.charAt(0)+'</div>';

                                html += '<div class="user-name"><b>'+data.review[count].user_name+'</b></div>';
                                
                                if(user_id === String(data.review[count].user_id)){ 
                                    html += '<span class="btnBackground"></span><i class="fas fa-ellipsis-v" id="deletebtn"></i>';
                                    html += '<a id="delete-review">Delete Review</a>';                                 
                                }
                                
                                html += '</div>';
                            
                                html += '<div class="rating-info rating-date">';

                                for(var star = 1; star <= 5; star++){
                                    var class_name = '';

                                    if(data.review[count].rating >= star){
                                        class_name = 'text-warning';
                                    }
                                
                                    else{
                                        class_name = 'star-light';
                                    }

                                    html += '<i class="fas fa-star '+class_name+' mr-1"></i>';
                                }
                            
                                html += '&nbsp;&nbsp;'+data.review[count].rating_date+'</div>';

                                html += '<div class="review-info">'+data.review[count].review;

                                html += '</div><br>';   
                            
                                i++;
                            }
                        }

                        $('#review_content').html(html);
                    }
                }
            });
        }
        
        $(document).on('click', '#delete-review', function(){
            var user_id = "<?php echo $user_id; ?>";
            var movie_id = "<?php echo $movie_id; ?>";
            
            $.ajax({
                url:"./submit-rating.php",
                method:"POST",
                data:{del_userId:user_id, del_movieId:movie_id},
                success:function(data){
                    load_rating();
                    alert("Delete Successfully");
                    
                    // Reset button
                    var button = document.getElementById('edit-review');
                    button.innerHTML = 'Write Review';
                    button.name = 'add-review';
                    button.id = 'add-review'; 
                    
                    // Reset user review content
                    var user_review = document.getElementById('user_review');
                    user_review.value = '';
                    user_review.placeholder = "Share your feelings";
                    
                    reset_background();
                    
                    <?php $rating = 0; ?>
                            
                    rating_data = <?php echo $rating; ?>;
                }
            });
        });

        $("#view-review").click(function(){
            var movie_id = "<?php echo $movie_id; ?>";
            
            $.ajax({
                url:"./submit-rating.php",
                method:"POST",
                data:{action:'load_data', movie_id:movie_id},
                dataType:"JSON",
                success:function(data){                              
                
                    if(data.review.length > 0){
                        var html = '';

                        for(var count = 0; count < data.review.length; count++){    
                            if(data.review[count].review){
                            
                                html += '<div class="review-container">';
                            
                                html += '<div class="headshot">'+data.review[count].user_name.charAt(0)+'</div>';

                                html += '<div class="user-name"><b>'+data.review[count].user_name+'</b></div>';

                                html += '</div>';
                            
                                html += '<div class="rating-info rating-date">';
                                
                                for(var star = 1; star <= 5; star++){
                                    var class_name = '';

                                    if(data.review[count].rating >= star){
                                        class_name = 'text-warning';
                                    }
                                
                                    else{
                                        class_name = 'star-light';
                                    }

                                    html += '<i class="fas fa-star '+class_name+' mr-1"></i>';
                                }
                            
                                html += data.review[count].rating_date+'</div>';

                                html += '<div class="review-info">'+data.review[count].review;

                                html += '</div><br>';   
                            }
                        }

                        $('#all-reviews').html(html);
                    }
                }
            });
    
        });      
        
        $(document).on('click', '#deletebtn', function() {
            $("#delete-review").toggle(200);
        });

    });

</script>