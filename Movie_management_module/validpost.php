<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function checkMovieName($mv_name, $con){
    if($mv_name == NULL){
        return "Please enter the <b>movie name</b>.";
    }
    else if(checkDuplicateName($mv_name, $con)){
        return "Same <b>movie name</b> detected";
    }
}

function checkDuplicateName($mv_name, $con){
    $exist = false;
    $sql = $con->prepare("SELECT * FROM movie WHERE mv_name = ? LIMIT 1");
    $sql->bind_param("s", $mv_name);
    $sql->execute();
    $result = $sql->get_result();
    if($result && $result->num_rows > 0){
        $exist = true;
    }
    return $exist;
}

function checkGenreID($genre_id, $con){
    if($genre_id != NULL){
        $sql = $con->prepare("SELECT * FROM genre WHERE genre_id = ? LIMIT 1");
        $sql->bind_param("i", $genre_id);
        $sql->execute();
        $result = $sql->get_result();
        if($result && $result->num_rows == 0){
            return "Please enter a <b>valid genre ID!</b>";
        }
    }
    else{
        return "Please enter the <b>genre ID.</b>";
    }
}

function checkTrailorLink($mv_link1, $mv_link2){
    if($mv_link1 == NULL || $mv_link2 == NULL){
        return "Please enter the <b>trailor's link.</b>";
    }
}

function checkReleaseDate($mv_date){
    $currentDate = date('Y-m-d');
    
    if($mv_date == NULL){
        return "Please select the <b>movie release date.</b>";
    }
    else if($mv_date < $currentDate){
        return "The movie release date <b>cannot be earlier than today.</b>";
    }
}

function checkDuration($mv_duration){
    if($mv_duration == NULL){
        return "Please enter the <b>movie's duration.</b>";
    }
    else if(!preg_match('/^\d{2,3} minutes$/', $mv_duration)){
        return "Please enter a <b>valid duration!</b>";
    }
}

function checkLanguage($mv_lang){
    $languages = array("English", "Chinese", "Japanese", "Korean", "German", "Italian", "Spanish", "Cantonese", "French", "Russian", "Arabic", "Hindi", "Tamil");
    
    if($mv_lang == NULL){
        return "Please enter the <b>movie language.</b>";
    }
    else if(!in_array($mv_lang, $languages)){
        return "The movie language can only be one of <b>" . implode(", ", $languages) . "</b>";
    }
}

function checkDirector($mv_director){
    if($mv_director == NULL){
        return "Please enter the <b>movie director.</b>";
    }
}

function checkWritter($mv_writter){
    if($mv_writter == NULL){
        return "Please enter the <b>movie writter.</b>";
    }
}

function checkStarring($mv_starring){
    if($mv_starring == NULL){
        return "Please enter the <b>movie starring.</b>";
    }
}

function checkMusic($mv_music){
    if($mv_music == NULL){
        return "Please enter the <b>movie's music producer.</b>";
    }
}

function checkCountry($country){
    if($country == NULL){
        return "Please enter where the <b>movie come from.</b>";
    }
}

function checkFileUploaded($img){
    // Check if there is any upload error?
    $file = $img;
    if($file['error'] > 0){
        // This validation is done by PHP
        // WITH ERROR, handle to display error msg
        switch($file['error']){
            case UPLOAD_ERR_NO_FILE:
                return "<b>No file</b> was selected!";
                break;
            
            case UPLOAD_ERR_FROM_SIZE:
                return "File uploaded is too large. Maximum 1MB is allowed!";
                break;
            
            default: // other error
                return "There was an error when uploading the file!";
                break;
        }  
    }
    else if($file['size'] > 2097152){
        // Validate specifically, file size
        // 1MB = 1024 x 1024
        return "File uploaded is too large. <b>Max 1MB</b> is allowed!";
    }
    else{
        // Extract file extension, eg: png,jpg, gif
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check file extension
        if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif'){
            return "Only <b>JPG, JPEG, GIF and PNG</b> images are allowed!";
        }
    }
}

function checkDescription($mv_desc){
    if($mv_desc == NULL){
        return "Please enter the <b>movie description.</b>";
    }
    else if(strpos($mv_desc, "'") !== false){
        return "<b>Apostrophe</b> cannot be contains in the description.";
    }   
}

function checkChildTicketPrice($ticket_price){
    if($ticket_price == NULL){
        return "Please enter the <b>child ticket price.</b>";
    }
    else if(!preg_match('/^\d{1,3}(\.\d{2})$/', $ticket_price)){
        return "Please enter a <b>valid child ticket price!</b>";
    }
}

function checkAdultTicketPrice($ticket_price){
    if($ticket_price == NULL){
        return "Please enter the <b>adult ticket price.</b>";
    }
    else if(!preg_match('/^\d{1,3}(\.\d{2})$/', $ticket_price)){
        return "Please enter a <b>valid adult ticket price!</b>";
    }
}

/* For edit movie only */
function checkEditMovieName($mv_name, $con, $org_mv_name){
    if($mv_name == NULL){
        return "Please enter the <b>movie name.</b>";
    }
    else if(checkEditDuplicateName($mv_name, $con, $org_mv_name)){
        return "Same <b>movie name</b> detected";
    }
}

function checkEditDuplicateName($mv_name, $con, $org_mv_name){
    $exist = false;
    $sql = $con->prepare("SELECT * FROM movie WHERE mv_name = ? LIMIT 1");
    $sql->bind_param("s", $mv_name);
    $sql->execute();
    $result = $sql->get_result();
    if($result && $result->num_rows > 0){
        if($mv_name != $org_mv_name){
            $exist = true;
        }
    }
    return $exist;
}

function checkEditReleaseDate($mv_date, $org_mv_date){
    $currentDate = date('Y-m-d');
    
    if($mv_date == NULL){
        return "Please select the <b>movie release date.</b>";
    }
    else if($mv_date < $currentDate && $mv_date != $org_mv_date){
        return "The movie release date <b>cannot be earlier than today.</b>";
    }
}

function setDefaultFileName($newImg) {
    echo "<script>
              var defaultFileName = '$newImg';
              $('#customFile').siblings('.custom-file-label').html(defaultFileName);
          </script>";
}
       