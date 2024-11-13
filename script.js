document.addEventListener("DOMContentLoaded", function() {
    const signInButton = document.getElementById('sign-in-btn');
    const signUpButton = document.getElementById('sign-up-btn');
    const container = document.getElementById('container');

    // Event listener to switch to Sign Up
    signUpButton.addEventListener('click', function() {
        container.classList.add('active');
    });

    // Event listener to switch to Sign In
    signInButton.addEventListener('click', function() {
        container.classList.remove('active');
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Handle Sign-Up form submission via AJAX
    const signUpForm = document.querySelector('#sign-up form');
    signUpForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from refreshing the page
        submitForm('register', new FormData(signUpForm)); // Submit Sign-Up form via AJAX
    });

    // Handle Sign-In form submission via AJAX
    const signInForm = document.querySelector('#sign-in form');
    signInForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from refreshing the page
        submitForm('login', new FormData(signInForm)); // Submit Sign-In form via AJAX
    });

    // General function to submit form via AJAX
    function submitForm(action, formData) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php', true); // Send request to the same PHP file
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Handle response from the server
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = xhr.responseText.trim();
                if (response === "Registration successful!" || response === "Login successful!") {
                    alert(response); // Show success message
                    window.location.reload(); // Reload to update the page state
                } else {
                    alert(response); // Show error message
                }
            }
        };

        // Append the action to differentiate between 'register' and 'login'
        formData.append('action', action);

        // Send the form data to the PHP file
        xhr.send(new URLSearchParams(formData).toString());
    }
});
