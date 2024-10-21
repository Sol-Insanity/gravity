// Modal functions
function openEditModal(userData) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('edit_user_id').value = userData.user_id;
    document.getElementById('edit_first_name').value = userData.first_name || '';
    document.getElementById('edit_last_name').value = userData.last_name || '';
    document.getElementById('edit_contact_number').value = userData.contact_number || '';
    document.getElementById('edit_address').value = userData.address || '';
    document.getElementById('edit_city').value = userData.city || '';
    document.getElementById('edit_zip_code').value = userData.zip_code || '';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
    const firstName = document.getElementById('edit_first_name').value.trim();
    const lastName = document.getElementById('edit_last_name').value.trim();
    
    if (!firstName || !lastName) {
        e.preventDefault();
        alert('First name and last name are required!');
    }
});

// Refresh data
function refreshData() {
    location.reload();
}

// Add event listeners when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            document.querySelectorAll('.nav-item').forEach(i => {
                i.classList.remove('active');
            });
            // Add active class to clicked item
            this.classList.add('active');
        });
    });

    // Add refresh handler
    const refreshButton = document.querySelector('.action-button i.fa-sync').parentElement;
    refreshButton.addEventListener('click', refreshData);
});


document.querySelector('.quick-actions button:first-child').onclick = openAddModal;

function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    let addModal = document.getElementById('addModal');
    let editModal = document.getElementById('editModal');
    if (event.target === addModal) {
        addModal.style.display = 'none';
    }
    if (event.target === editModal) {
        editModal.style.display = 'none';
    }
}