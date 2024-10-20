// login.js

// Get DOM elements
const modal = document.getElementById('loginModal');
const loginBtn = document.getElementById('loginBtn');
const closeBtn = document.querySelector('.close-modal');
const loginForm = document.getElementById('loginForm');
const messageEl = document.getElementById('loginMessage');

// Function to toggle modal
function toggleModal(show = true) {
    if (show) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Event Listeners
loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    toggleModal(true);
});

closeBtn.addEventListener('click', () => toggleModal(false));

// Close modal when clicking outside
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        toggleModal(false);
    }
});

// Handle form submission
loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!email || !password) {
        messageEl.style.backgroundColor = '#ffe6e6';
        messageEl.style.color = '#dc3545';
        messageEl.textContent = "Please enter both email and password.";
        return;
    }

    fetch('api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            messageEl.style.backgroundColor = '#d4edda';
            messageEl.style.color = '#155724';
            messageEl.textContent = data.message;

            localStorage.setItem('sessionToken', data.token);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            messageEl.style.backgroundColor = '#ffe6e6';
            messageEl.style.color = '#dc3545';
            messageEl.textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageEl.style.backgroundColor = '#ffe6e6';
        messageEl.style.color = '#dc3545';
        messageEl.textContent = "An error occurred. Please try again.";
    });

});

// Close modal on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('show')) {
        toggleModal(false);
    }
});
