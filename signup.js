function openModal() {
    document.getElementById('signupModal').classList.add('show');
}

function closeModal() {
    document.getElementById('signupModal').classList.remove('show');
}

function switchToLogin() {
    // Add your login modal switching logic here
    console.log('Switching to login modal');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('signupModal');
    if (event.target === modal) {
        closeModal();
    }
}

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
                closeModal();
                // Add your post-signup logic here
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