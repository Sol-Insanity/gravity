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


// Simulate network delay for local testing
const simulateNetworkDelay = () => new Promise(resolve => setTimeout(resolve, 2000));

// Helper function to toggle loading state
function toggleLoadingState(button, isLoading) {
    const buttonText = button.querySelector('.button-text');
    const spinner = button.querySelector('.spinner');
    
    if (isLoading) {
        button.disabled = true;
        buttonText.style.visibility = 'hidden'; // Hide text but maintain space
        spinner.style.display = 'block';
    } else {
        button.disabled = false;
        buttonText.style.visibility = 'visible';
        spinner.style.display = 'none';
    }
}

// Signup form handler
document.getElementById('signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const button = form.querySelector('button');
    const messageDiv = document.getElementById('message');
    
    // Get form data
    const email = form.email.value.trim();
    const password = form.password.value;

    // Show loading state
    toggleLoadingState(button, true);
    messageDiv.style.display = 'none';

    try {
        // Add artificial delay when testing locally
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            await simulateNetworkDelay();
        }

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
        // Hide loading state
        toggleLoadingState(button, false);
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

    // Show loading state
    toggleLoadingState(button, true);
    messageDiv.style.display = 'none';

    try {
        // Add artificial delay when testing locally
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            await simulateNetworkDelay();
        }

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
        // Hide loading state
        toggleLoadingState(button, false);
    }
});