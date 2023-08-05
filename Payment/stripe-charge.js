/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

// Create a Stripe client
var stripe = Stripe('pk_test_51MkK28LVFpC4e2xLwjC5p4eG0IS0tUnr88YaVDEPVDvfnyKARbPPkWbt5eRVcS8wRKRGeonYHSmTAbTXHah9FDl100iwnNEFGp');

// Create an instance of Elements
var elements = stripe.elements();

if (!stripe) {
    console.log('Stripe object is not defined.');
}

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
    base: {
        color: 'white',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '15px',
        //color: '#32325d'
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: 'red',
        iconColor: '#fa755a'
        //color: '#fa755a'
    }
};

// Style button with BS
document.querySelector('#payment-form button').classList = 'btn btn-primary btn-block mt-4';

// Create an instance of the card Element
var cardNumber = elements.create('cardNumber', {style: style});
var cardExpiry = elements.create('cardExpiry', {style: style});
var cardCVC = elements.create('cardCvc', {style: style});

// Add an instance of the card Element into the `card-element` <div>
cardNumber.mount('#cardNumber');
cardExpiry.mount('#cardExpiry');
cardCVC.mount('#cardCVC');

if(!cardNumber){
    console.log('card number undefined');
}

// Handle real-time validation errors from the card Element.
cardNumber.addEventListener('change', function(event) {
    var displayError = document.getElementById('cardNumber-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.classList.add('cardNo-error');
    } else {
        displayError.textContent = '';
        displayError.classList.remove('cardNo-error');
    }
});

cardExpiry.addEventListener('change', function(event) {
    var displayError = document.getElementById('cardExpiry-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.classList.add('expiry-error');
    } else {
        displayError.textContent = '';
        displayError.classList.remove('expiry-error');
    }
});

cardCVC.addEventListener('change', function(event) {
    var displayError = document.getElementById('cardCVC-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.classList.add('cardCVC-error');
    } else {
        displayError.textContent = '';
        displayError.classList.remove('cardCVC-error');
    } 
});

// Handle form submission
var submitButton = document.getElementById('payment-form');
submitButton.addEventListener('submit', function(evt) {
    evt.preventDefault();

    stripe.createToken(cardNumber).then(function(result) {
        if(result) {
            // Send the token to your server
            stripeTokenHandler(result.token);
        }
    });
});

function stripeTokenHandler(token) {
    // Insert the token into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
}



