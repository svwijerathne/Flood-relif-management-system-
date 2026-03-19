document.addEventListener('DOMContentLoaded', function() {
    
    
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            errorMessage.innerText = "";

            
            const formData = new FormData(this);

            
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