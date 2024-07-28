// auth.js

document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    
    // Collect form data
    let formData = new FormData(this);
    
    // Send form data as JSON using fetch API
    fetch('/login', {
        method: 'POST',
        body: JSON.stringify({
            username: formData.get('username'),
            password: formData.get('password')
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(res => {
        // Handle success or failure based on the response
        if (res.ok) {
            // Redirect or show success message
            window.location.href = '/'; // Redirect to home page
        } else {
            // Show error message
            document.getElementById('error-message').textContent = 'Invalid username or password';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message
        document.getElementById('error-message').textContent = 'An error occurred, please try again later';
    });
});
