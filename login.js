document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Handle the Login Submission
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop the page from reloading

            // Clear any old error messages
            errorMessage.innerText = "";

            // Gather the data from the inputs (using their 'name' attributes)
            const formData = new FormData(this);

            // Send data to PHP securely
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Read the PHP response
            .then(data => {
                if (data.status === 'success') {
                    // Redirect to the correct page based on admin/user
                    window.location.href = data.redirect;
                } else {
                    // Show error (e.g., "Invalid credentials")
                    errorMessage.innerText = data.message;
                }
            })
            .catch(error => {
    // This will show you the ACTUAL system error on the webpage
    errorMessage.innerText = "JS Error: " + error.message;
});
        });
    }

    // 2. Make the Password Eye Icon Work
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye / eye-slash icon class
            this.classList.toggle('fa-eye-slash');
        });
    }
});