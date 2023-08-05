/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

const form = document.querySelector('form');
form.addEventListener('submit', function(e) {
  e.preventDefault();
  const input = this.querySelector('input[type="text"]');
  const query = input.value;
}); 

