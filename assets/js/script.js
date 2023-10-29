

// Wait for the document to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
    // Your JavaScript code here

    
    var myButton = document.getElementById('my-button');
    if (myButton) {
        myButton.addEventListener('click', function () {
            // Perform an action when the button is clicked
            alert('Button clicked! Implement your functionality here.');
        });
    }


});
