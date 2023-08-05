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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="../css/view_profile.css" rel="stylesheet" type="text/css"/>
        <title>GRC Cinema</title>
    </head>
    <body>
        <?php
        include '../Movie_management_module/cust_header.php';
        ?>
        
        <div class="update-profile">
            <?php
            $user_id = $_GET['profileID'];
            $_SESSION['user_id'] = $user_id;
            $sql = $con->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
            $sql->bind_param("s", $user_id);
            $sql->execute();
            $result = $sql->get_result();
            if($result && $result->num_rows > 0){
                $row = $result->fetch_array();
                $_SESSION['username'] = $row['user_name'];
                $_SESSION['old_img'] = $row['profile_img'];
                $_SESSION['password'] = $row['password'];
            }
            ?>
            
            <!-- Update profile form -->
            <form action="" method="post" id="profile-form" enctype="multipart/form-data">
                <?php
                // If NO profile image, display the first word of username as profile image
                // Else display the profile image 
                if($row['profile_img'] == ""){
                    echo '<div class="headshot-1">' . substr($row['user_name'], 0, 1) . '</div>';
                    $row['profile_img'] = "No File Chosen";
                }
                else{
                    echo "<img src='../profile/". $row['profile_img'] . "'>";
                }
                ?>
                <table class="flex">
                    <tr>
                        <td>
                            <span>Username</span>
                        </td>
                        <td>
                            <input type="text" name="update_name" class="box" value="<?php echo $row['user_name']; ?>" />
                            <div id="error-message-1" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Email address</span>
                        </td>
                        <td>
                            <div class="box"><?php echo $row['email']; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Update profile picture</span>
                        </td>
                        <td>
                            <div class="custom-file mb-3">
                                <input type="file" name="update_image" class="custom-file-input" id="customFile" />
                                <input type='hidden' name='MAX_FILE_SIZE' value='2097152'>
                                <label class="custom-file-label" for="customFile"></label>
                                <input type="hidden" name="old_img" class="custom-file-input" id="customFile" />
                                <div id="error-message-2"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Old Password</span>
                        </td>
                        <td>
                            <input type="password" name="update_pass" class="box" placeholder="Enter previous password">
                            <div id="error-message-3" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>New Password</span>
                        </td>
                        <td>
                            <input type="password" name="new_pass" class="box" placeholder="Enter new password">
                            <div id="error-message-4" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Confirm Password</span>
                        </td>
                        <td>
                            <input type="password" name="confirm_pass" class="box" placeholder="Confirm new password">
                            <div id="error-message-5" class="error"></div>
                        </td>
                    </tr>
                </table>
                <div>
                    <a style="text-decoration: none;" href="../Movie_management_module/homepage.php" class="back-btn">Go back</a>
                    <input type="button" name="update_profile" value="Update Profile" class="update-btn" onclick="ajaxFunction();" />
                </div>
            </form>
        </div>
    </body>
    <script>
        // display the file name (image) that user has chosen
        var defaultFileName = '<?php echo $row['profile_img']; ?>';
        $('#customFile').siblings('.custom-file-label').html(defaultFileName);
        
        $('#customFile').on('change', function() {
        // Get the file name
        var fileName = $(this).val().split('\\').pop(); 
        // val() -> value of the selected file (absolute pathname), split('\\') -> divide the string based on '\', pop() -> select the last group of the string
        
        // Display the file name in the box
        $(this).siblings('.custom-file-label').html(fileName); 
        // $(this).siblings('.custom-file-label') -> select the siblings of the selected thing(#customFile) which class name is "custom-file-label"
        });
        
        // ajax
        function ajaxFunction(){
            var xmlhttp;
            var formdata = new FormData(document.getElementById("profile-form"));
            
            if(window.XMLHttpRequest)
                xmlhttp = new XMLHttpRequest();
            else if(ActiveXObjext("Microsoft.XMLHTTP"))
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            else{
                alert("Problem with your browser!");
                return false;
            }
            
            // Create a function that will receive data from the server
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState === 4){
                    var response = JSON.parse(xmlhttp.responseText);
                    
                    if (response.username) {
                        document.getElementById("error-message-1").innerText = response.username;
                    }
                    if (response.img) {
                        document.getElementById("error-message-2").innerText = response.img;
                    }
                    if (response.update_password) {
                        document.getElementById("error-message-3").innerText = response.update_password;
                    }
                    if (response.new_password) {
                        document.getElementById("error-message-4").innerText = response.new_password;
                    }
                    if (response.confirm_password) {
                        document.getElementById("error-message-5").innerText = response.confirm_password;
                    }
                    if (response.url) {
                        window.location.href = response.url;
                    }
                }    
            };
            
            xmlhttp.open("POST", "update_profile_ajax.php", true); // send the request to update_profile_ajax.php in POST method
            xmlhttp.send(formdata);
        }
    </script>
</html>