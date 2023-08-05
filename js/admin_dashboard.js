/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */
function checkPassword() {
  var password = prompt("Please Enter Password:", "");
  if (password == "123") {
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].disabled = false;
    }
    var textareas = document.getElementsByTagName('textarea');
    for (var i = 0; i < textareas.length; i++) {
      textareas[i].disabled = false;
    }
    var passwordBtn = document.getElementById("password-btn");
    passwordBtn.removeEventListener("click", checkPassword);
    passwordBtn.disabled = true;
  } else {
      alert("Password incorrect!");
    window.history.back();
  }
  
}

var passwordBtn = document.getElementById("password-btn");
passwordBtn.addEventListener("click", checkPassword);
  
function dashboard(){
  $("#sidebar").css('width','70px');
  $("#main").css('margin-left','70px');
  $(".logo").css('visibility','hidden');
  $(".logo span").css('visibility','visible');
  $(".logo span").css('margin-left','-10px');
  $(".icon-a").css('visibility','hidden');
  $(".icons").css('visibility','visible');
  $(".icons").css('margin-left','-8px');
  $(".nav").css('display','none');
  $(".nav2").css('display','block');
  $("sidebar").css('width','70px');
      $(".pieChart").animate({
    width: '%'
  }, 500);
  
  $("#bChart").animate({
    width: '65%'
  }, 500);
}

function dashboard2(){
  $("#sidebar").css('width','300px');
  $("#main").css('margin-left','300px');
  $(".logo").css('visibility','visible');
  $(".logo span").css('visibility','visible');
  $(".icon-a").css('visibility','visible');
  $(".icons").css('visibility','visible');
  $(".nav").css('display','block');
  $(".nav2").css('display','none');
      $(".pieChart").animate({
    width: '%'
  }, 300);
  
  $("#bChart").animate({
    width: '65%'
  }, 500);
}


