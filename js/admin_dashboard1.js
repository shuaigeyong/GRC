/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

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
  $(".content-box p").css('margin-left', '100px');
  $(".heading").css('grid-template-columns', 'repeat(4, minmax(294px, auto))');//442
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
  $(".content-box p").css('margin-left', '40px');
  $(".heading").css('grid-template-columns', 'repeat(4, minmax(220px, auto))');//375
}


