<?php
    include 'con_db.php';
    //get the id from btn update
    $id = $_GET['updateid'];
    $sql = "SELECT * FROM users WHERE user_id=$id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    
    $name = $row['user_name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $joinDate = $row['join_date'];
    $usertype = $row['user_type'];
    $password = $row['password'];
    
    //check if the btn submit clicked
    if(isset($_POST['submit'])){
  
        $name= trim($_POST['name']);
        $email= trim($_POST['email']);
        $phone= trim($_POST['phone']);
        $joindate = trim($_POST['joinDate']);
        $userType = trim($_POST['userType']);
        $password = $_POST['password'];
        
        $errors = array();
        if (strlen($password) < 8 && strlen($newpassword) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
         }

        $phone_pattern = '/^01[0-9]{1}-[0-9]{4}-[0-9]{3,4}$/';
       
        if (!preg_match($phone_pattern, $phone)) {
            $errors['phone'] = 'Invalid phone format';
        }

        $email_pattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        if (!preg_match($email_pattern, $email)) {
            $errors['email'] = 'Invalid email format';
    }

        if (strlen($name) > 20) {
            $errors['name'] = 'Name must not longer than 20 characters.';
    }
    
    
        if (count($errors) == 0) {  
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
        
    $sql = "UPDATE users SET user_name='$name', email='$email',password='$passwordhash',phone='$phone',user_type='$userType',join_date='$joindate' WHERE user_id=$id ";
    
    $result = mysqli_query($con, $sql);
    
     if($result){
        header("location:staff_detail.php");
    }else{
        die(mysqli_error($con));
    }
       
    
        }else{
            print_r($errors);
        }
    
    
 }
 
    
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRC Cinema</title>
  <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="../js/admin_dashboard.js" type="text/javascript"></script>
  <link href="../css/staff_update.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    
    <?php
      include'../Admin_Account_Module/sidebar_detail.php'
      ?>
<script> 
  checkPassword();
</script>
      <div id="main">
        <div class="head"> 
          <div class="col-div-6"></div>
            <span onclick="dashboard()" class="nav" style="font-size: 30px;cursor: pointer; color: white;">&#9776; Update Account</span>
            <span onclick="dashboard2()" class="nav2" style="font-size: 30px;cursor: pointer; color: white;">&#9776; Update Account</span>

          <div class="col-div-6"></div>
          <div class="profile">
              <img src="../img/baby.jpg" class="pro-img"  alt="">
            <p >Baby Boss <span>President</span></p>
          </div>

          <div class="clearfix"></div>

          
          <div class="col-div-8">
            <div class="box-8">
              <div class="content-box">
                  <p>Updating Staff Account </p>
                <br>
                
                
                <form method="POST" >
                <table>
                    <tr>      
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter your name" autocomplete="off" required value=<?php echo $name;?>>
                    <span class="text-danger" id="name-message"></span>
                    </tr>
                  <br>
                  <tr>
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Enter your email" autocomplete="off" required value=<?php echo $email; ?>>
                  <span class="text-danger" id="email-message"></span>
                  </tr>
                  <br>
                  <tr>
                  <label>Mobile number</label>
                  <input type="text" name="phone" class="form-control" placeholder="Enter your mobile number" autocomplete="off" required value=<?php echo $phone; ?>>
                  <span class="text-danger" id="phone-message"></span>

                  </tr>
                  <br>
                  <tr>
                  <label>Join Date</label>
                  <input type="date" name="joinDate" class="form-control" readonly value=<?php echo $joinDate; ?>>
                  </tr>
                  <br>
                  <tr>
                  <label>User Type</label>
                  <input type="text" name="userType" class="form-control" placeholder="Enter user type"  required value=<?php echo $usertype; ?>>
                  </tr>
                  <br>
                  <tr>
                  <label>Password</label>
                  <input type="password" id="password" name="password" class="form-control" required placeholder="Enter Password " minlength="8">
                  <div><span style='float: right; cursor: pointer; width: auto;' class='text-light' id="togglePassword">
                  Show Password
                  </span>
                  <span style='width: 50px;' class="text-danger" id="password-message"></span>
                  </div>
                  </tr>
                  <tr>
                  <br>
                </table>
                    <button type="submit" id="submit-btn" disabled class="btn btn-primary" name="submit">Update</button>
            </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <script>
        
        // Get the input and the validation element
const passwordInput = document.querySelector('input[name="password"]');
const validationMessage = document.querySelector('#password-message');

passwordInput.addEventListener('input', () => {
  if (passwordInput.value.length < 8) {
    validationMessage.textContent = 'Password must be at least 8 characters long.';
  } else {
    validationMessage.textContent = '';
  }
});

  //get submit btn
  const submitBtn = document.getElementById("submit-btn");
  passwordInput.addEventListener("keyup", function() {
    if (passwordInput.value.length >= 8) {

      submitBtn.disabled = false;
    } else {
      
      submitBtn.disabled = true;
    }
  });
  
  const phoneInput = document.querySelector('input[name="phone"]');
const phoneMessage = document.querySelector('#phone-message');

phoneInput.addEventListener('input', function() {
  
  const phoneNumber = phoneInput.value.trim();
  var pattern = /^01[0-9]{1}-[0-9]{4}-[0-9]{3}$/;
  var pattern2 = /^01[0-9]{1}-[0-9]{4}-[0-9]{4}$/;
  

  if (pattern.test(phoneNumber) || pattern2.test(phoneNumber)) {
    phoneMessage.textContent = '';
  } else {
    phoneMessage.textContent = 'Invalid phone format';
    phoneInput.setAttribute('maxlength', '13');
  }
});
  
  const emailInput = document.querySelector('input[name="email"]');
  const emailMessage = document.querySelector('#email-message');

  emailInput.addEventListener('input', function() {
  const email = emailInput.value.trim();
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
  if (pattern.test(email)) {
    emailMessage.textContent = '';
  } else {
    emailMessage.textContent = 'Invalid email format';
  }
});

  const nameInput = document.querySelector('input[name="name"]');
  const nameMessage = document.querySelector('#name-message');
  nameInput.addEventListener('input', function() {
      
  if (nameInput.value.length < 20) {
    nameMessage.textContent = '';
  } else {
    nameMessage.textContent = 'Name must not longer than 20 characters.';
    nameInput.setAttribute('maxlength', '20');
  }
});

$('#togglePassword').click(function() {
    var passwordInput = $('#password');
    var showhide =document.querySelector('#togglePassword');
    var passwordFieldType = passwordInput.attr('type');

    if (passwordFieldType === 'password') {
      passwordInput.attr('type', 'text');
      showhide.textContent ='Hide Password';
    } else {
      passwordInput.attr('type', 'password');
      showhide.textContent ='Show Password';

    }
  });

</script>
  
</body>
</html>