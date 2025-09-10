// Profile page JavaScript functionality

document.addEventListener('DOMContentLoaded', function() {
    initializeProfileTabs();
    initializePasswordValidation();
    initializeBookingDetails();
});

// Tab switching functionality
function initializeProfileTabs() {
    const navItems = document.querySelectorAll('.nav-item[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all nav items and tab contents
            navItems.forEach(nav => nav.classList.remove('active'));
            tabContents.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to clicked nav item and corresponding tab
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
}

// Password validation
function initializePasswordValidation() {
    const passwordForm = document.querySelector('.password-form');
    if (!passwordForm) return;
    
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    // Real-time password validation
    if (newPassword) {
        newPassword.addEventListener('input', function() {
            const password = this.value;
            const feedback = this.parentNode.querySelector('.password-feedback') || createPasswordFeedback(this.parentNode);
            
            updatePasswordStrength(password, feedback);
        });
    }
    
    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            const password = newPassword.value;
            const confirm = this.value;
            const feedback = this.parentNode.querySelector('.confirm-feedback') || createConfirmFeedback(this.parentNode);
            
            if (confirm && password !== confirm) {
                feedback.textContent = 'Passwords do not match';
                feedback.className = 'confirm-feedback error';
                this.classList.add('error');
            } else if (confirm) {
                feedback.textContent = 'Passwords match';
                feedback.className = 'confirm-feedback success';
                this.classList.remove('error');
            } else {
                feedback.textContent = '';
                feedback.className = 'confirm-feedback';
                this.classList.remove('error');
            }
        });
    }
    
    // Form submission validation
    passwordForm.addEventListener('submit', function(e) {
        const current = currentPassword.value;
        const newPass = newPassword.value;
        const confirm = confirmPassword.value;
        
        let isValid = true;
        
        // Clear previous errors
        passwordForm.querySelectorAll('.error-message').forEach(error => error.remove());
        passwordForm.querySelectorAll('.error').forEach(field => field.classList.remove('error'));
        
        if (!current) {
            showFieldError(currentPassword, 'Current password is required');
            isValid = false;
        }
        
        if (!newPass) {
            showFieldError(newPassword, 'New password is required');
            isValid = false;
        } else if (newPass.length < 6) {
            showFieldError(newPassword, 'Password must be at least 6 characters long');
            isValid = false;
        }
        
        if (!confirm) {
            showFieldError(confirmPassword, 'Please confirm your new password');
            isValid = false;
        } else if (newPass !== confirm) {
            showFieldError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
}

// Create password strength feedback element
function createPasswordFeedback(parent) {
    const feedback = document.createElement('div');
    feedback.className = 'password-feedback';
    parent.appendChild(feedback);
    return feedback;
}

// Create confirm password feedback element
function createConfirmFeedback(parent) {
    const feedback = document.createElement('div');
    feedback.className = 'confirm-feedback';
    parent.appendChild(feedback);
    return feedback;
}

// Update password strength indicator
function updatePasswordStrength(password, feedback) {
    if (!password) {
        feedback.textContent = '';
        feedback.className = 'password-feedback';
        return;
    }
    
    let strength = 0;
    let messages = [];
    
    // Length check
    if (password.length >= 6) strength++;
    else messages.push('At least 6 characters');
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength++;
    else messages.push('One uppercase letter');
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength++;
    else messages.push('One lowercase letter');
    
    // Number check
    if (/\d/.test(password)) strength++;
    else messages.push('One number');
    
    // Special character check
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
    else messages.push('One special character');
    
    // Update feedback
    if (strength < 2) {
        feedback.textContent = 'Weak password. Add: ' + messages.slice(0, 2).join(', ');
        feedback.className = 'password-feedback weak';
    } else if (strength < 4) {
        feedback.textContent = 'Medium strength. Consider adding: ' + messages.slice(0, 1).join(', ');
        feedback.className = 'password-feedback medium';
    } else {
        feedback.textContent = 'Strong password!';
        feedback.className = 'password-feedback strong';
    }
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('error');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
}

// Booking details functionality
function initializeBookingDetails() {
    // This function can be expanded to handle booking detail modals
    window.viewBookingDetails = function(bookingRef) {
        // For now, just show an alert with the booking reference
        // In a full implementation, this would open a modal with detailed booking information
        showAlert(`Viewing details for booking: ${bookingRef}`, 'info');
        
        // You can expand this to fetch and display detailed booking information
        // via AJAX or show a modal with more booking details
    };
}

// Profile form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.querySelector('.profile-form');
    if (profileForm) {
        // Add form submission feedback
        profileForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                submitBtn.disabled = true;
                
                // Re-enable button after form submission (in case of validation errors)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    }
});

// Utility function for showing alerts (reused from main.js)
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