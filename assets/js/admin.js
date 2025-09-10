// Admin Panel JavaScript functionality

document.addEventListener('DOMContentLoaded', function() {
    initializeModals();
    initializeDataTables();
    initializeFormValidation();
});

// Modal functionality
function initializeModals() {
    const modals = document.querySelectorAll('.modal');
    const closeBtns = document.querySelectorAll('.close, .modal .btn-secondary');
    
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Close modal when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modals.forEach(modal => {
                if (modal.style.display === 'block') {
                    closeModal(modal.id);
                }
            });
        }
    });
}

// Open modal
function openModal(action, data = null) {
    const modal = document.getElementById('movie-modal');
    const form = document.getElementById('movie-form');
    const modalTitle = document.getElementById('modal-title');
    const formAction = document.getElementById('form-action');
    const movieId = document.getElementById('movie-id');
    
    if (action === 'add') {
        modalTitle.textContent = 'Add New Movie';
        formAction.value = 'add';
        form.reset();
        movieId.value = '';
    } else if (action === 'edit' && data) {
        modalTitle.textContent = 'Edit Movie';
        formAction.value = 'update';
        movieId.value = data.id;
        
        // Populate form fields
        document.getElementById('movie-title').value = data.title || '';
        document.getElementById('movie-genre').value = data.genre || '';
        document.getElementById('movie-description').value = data.description || '';
        document.getElementById('movie-duration').value = data.duration || '';
        document.getElementById('movie-rating').value = data.rating || '';
        document.getElementById('movie-director').value = data.director || '';
        document.getElementById('movie-cast').value = data.cast || '';
        document.getElementById('movie-release-date').value = data.release_date || '';
        document.getElementById('movie-poster').value = data.poster_image || '';
        document.getElementById('movie-trailer').value = data.trailer_url || '';
        document.getElementById('movie-status').value = data.status || '';
    }
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Close modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Edit movie function
function editMovie(movieData) {
    openModal('edit', movieData);
}

// Delete movie function
function deleteMovie(movieId, movieTitle) {
    if (confirm(`Are you sure you want to delete "${movieTitle}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="movie_id" value="${movieId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Data tables functionality
function initializeDataTables() {
    // Add search functionality
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.table-container').querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Add sorting functionality
    const sortableHeaders = document.querySelectorAll('.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const columnIndex = Array.from(this.parentElement.children).indexOf(this);
            const isAscending = this.classList.contains('sort-asc');
            
            // Clear all sort classes
            sortableHeaders.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
            
            // Add appropriate sort class
            this.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
            
            // Sort rows
            rows.sort((a, b) => {
                const aValue = a.children[columnIndex].textContent.trim();
                const bValue = b.children[columnIndex].textContent.trim();
                
                if (isAscending) {
                    return bValue.localeCompare(aValue);
                } else {
                    return aValue.localeCompare(bValue);
                }
            });
            
            // Reorder rows in DOM
            rows.forEach(row => tbody.appendChild(row));
        });
    });
}

// Form validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            // Clear previous errors
            form.querySelectorAll('.error-message').forEach(error => error.remove());
            form.querySelectorAll('.error').forEach(field => field.classList.remove('error'));
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    showFieldError(field, 'This field is required');
                } else {
                    // Specific validation
                    if (field.type === 'email' && !isValidEmail(field.value)) {
                        isValid = false;
                        showFieldError(field, 'Please enter a valid email address');
                    }
                    
                    if (field.type === 'url' && field.value && !isValidURL(field.value)) {
                        isValid = false;
                        showFieldError(field, 'Please enter a valid URL');
                    }
                    
                    if (field.type === 'number' && field.value && isNaN(field.value)) {
                        isValid = false;
                        showFieldError(field, 'Please enter a valid number');
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showAlert('Please fix the errors in the form', 'error');
            }
        });
    });
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('error');
    
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    errorElement.style.color = '#f44336';
    errorElement.style.fontSize = '0.8rem';
    errorElement.style.marginTop = '0.25rem';
    
    field.parentNode.appendChild(errorElement);
}

// Validation helpers
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidURL(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

// Alert function
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.insertBefore(alert, document.body.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Confirmation dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Loading state management
function showLoading(element, text = 'Loading...') {
    element.innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            <span>${text}</span>
        </div>
    `;
}

function hideLoading(element, originalContent) {
    element.innerHTML = originalContent;
}

// AJAX helper for admin operations
function makeAjaxRequest(url, options = {}) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...options.headers
        },
        ...options
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .catch(error => {
        console.error('Request failed:', error);
        showAlert('Something went wrong. Please try again.', 'error');
        throw error;
    });
}

// Export functions for use in other scripts
window.adminUtils = {
    openModal,
    closeModal,
    editMovie,
    deleteMovie,
    showAlert,
    confirmAction,
    showLoading,
    hideLoading,
    makeAjaxRequest
};