// Open modal functions
function openModal(type = 'signup') {
    if (type === 'signup') {
        document.getElementById('signupModal').classList.add('show');
        document.getElementById('loginModal').classList.remove('show');
    } else {
        document.getElementById('loginModal').classList.add('show');
        document.getElementById('signupModal').classList.remove('show');
    }
}

// Close modal functions
function closeModal(type = 'signup') {
    if (type === 'signup') {
        document.getElementById('signupModal').classList.remove('show');
    } else {
        document.getElementById('loginModal').classList.remove('show');
    }
}

// Switch between modals
function switchToLogin() {
    document.getElementById('signupModal').classList.remove('show');
    document.getElementById('loginModal').classList.add('show');
}

function switchToSignup() {
    document.getElementById('loginModal').classList.remove('show');
    document.getElementById('signupModal').classList.add('show');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.id === 'signupModal') {
        closeModal('signup');
    }
    if (event.target.id === 'loginModal') {
        closeModal('login');
    }
}

// Add click handler for login button
document.getElementById('loginBtn').addEventListener('click', function(e) {
    e.preventDefault();
    openModal('login');
});

// Signup form handler
document.getElementById('signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const button = form.querySelector('button');
    const buttonText = form.querySelector('.button-text');
    const spinner = form.querySelector('.spinner');
    const messageDiv = document.getElementById('message');
    
    // Get form data
    const email = form.email.value.trim();
    const password = form.password.value;

    // Disable button and show spinner
    button.disabled = true;
    buttonText.style.display = 'none';
    spinner.style.display = 'block';
    messageDiv.style.display = 'none';

    try {
        const response = await fetch('api/signup.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        messageDiv.style.display = 'block';
        if (data.status === 'success') {
            messageDiv.className = 'message success';
            messageDiv.textContent = 'Account created successfully! Redirecting...';
            setTimeout(() => {
                closeModal('signup');
                switchToLogin();
            }, 2000);
        } else {
            messageDiv.className = 'message error';
            messageDiv.textContent = data.message || 'An error occurred. Please try again.';
        }
    } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'message error';
        messageDiv.textContent = 'Network error. Please check your connection and try again.';
    } finally {
        // Re-enable button and hide spinner
        button.disabled = false;
        buttonText.style.display = 'block';
        spinner.style.display = 'none';
    }
});

// Login form handler
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const button = form.querySelector('button');
    const messageDiv = document.getElementById('loginMessage');
    
    const email = form.email.value.trim();
    const password = form.password.value;

    // Disable button during submission
    button.disabled = true;
    button.innerHTML = 'Logging in...';

    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        messageDiv.style.display = 'block';
        if (data.status === 'success') {
            messageDiv.className = 'message success';
            messageDiv.textContent = 'Login successful! Redirecting...';
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            messageDiv.className = 'message error';
            messageDiv.textContent = data.message || 'Invalid email or password';
        }
    } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'message error';
        messageDiv.textContent = 'Network error. Please try again.';
    } finally {
        // Re-enable button
        button.disabled = false;
        button.innerHTML = 'Login';
    }
});