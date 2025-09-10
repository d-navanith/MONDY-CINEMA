// Main JavaScript functionality for Mondy Cinema

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializeNavigation();
    initializeTrailerModal();
    initializeScrollAnimations();
    initializeFormValidation();
});

// Navigation functionality
function initializeNavigation() {
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            
            // Animate hamburger menu
            const bars = navToggle.querySelectorAll('.bar');
            bars.forEach((bar, index) => {
                if (navMenu.classList.contains('active')) {
                    if (index === 0) bar.style.transform = 'rotate(45deg) translate(6px, 6px)';
                    if (index === 1) bar.style.opacity = '0';
                    if (index === 2) bar.style.transform = 'rotate(-45deg) translate(6px, -6px)';
                } else {
                    bar.style.transform = 'none';
                    bar.style.opacity = '1';
                }
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                const bars = navToggle.querySelectorAll('.bar');
                bars.forEach(bar => {
                    bar.style.transform = 'none';
                    bar.style.opacity = '1';
                });
            }
        });
    }
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Header scroll effect
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                header.style.background = 'rgba(0, 0, 0, 0.98)';
            } else {
                header.style.background = 'rgba(0, 0, 0, 0.95)';
            }
        });
    }
}

// Trailer modal functionality
function initializeTrailerModal() {
    const modal = document.getElementById('trailer-modal');
    const trailerIframe = document.getElementById('trailer-iframe');
    const closeBtn = document.querySelector('.close');
    const trailerButtons = document.querySelectorAll('.play-trailer');
    
    if (modal && trailerIframe && closeBtn) {
        // Open trailer modal
        trailerButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const trailerUrl = this.getAttribute('data-trailer');
                if (trailerUrl) {
                    // Convert YouTube URL to embed format
                    const videoId = extractYouTubeVideoId(trailerUrl);
                    if (videoId) {
                        trailerIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    }
                }
            });
        });
        
        // Close trailer modal
        function closeModal() {
            modal.style.display = 'none';
            trailerIframe.src = '';
            document.body.style.overflow = 'auto';
        }
        
        closeBtn.addEventListener('click', closeModal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                closeModal();
            }
        });
    }
    
    // Trailer thumbnails
    const trailerItems = document.querySelectorAll('.trailer-item');
    trailerItems.forEach(item => {
        item.addEventListener('click', function() {
            // You can add specific trailer URLs for each item
            const trailerUrl = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // Example
            const videoId = extractYouTubeVideoId(trailerUrl);
            if (videoId && trailerIframe) {
                trailerIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        });
    });
}

// Extract YouTube video ID from URL
function extractYouTubeVideoId(url) {
    const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[7].length === 11) ? match[7] : null;
}

// Scroll animations
function initializeScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe movie cards and other elements
    const animatedElements = document.querySelectorAll('.movie-card, .offer-card, .cinema-card, .trailer-item');
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
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
                    
                    if (field.type === 'tel' && !isValidPhone(field.value)) {
                        isValid = false;
                        showFieldError(field, 'Please enter a valid phone number');
                    }
                    
                    if (field.name === 'confirm-password') {
                        const passwordField = form.querySelector('[name="password"]');
                        if (passwordField && field.value !== passwordField.value) {
                            isValid = false;
                            showFieldError(field, 'Passwords do not match');
                        }
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
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

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

// Utility functions
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

// Add custom CSS for error states and alerts
const style = document.createElement('style');
style.textContent = `
    .error {
        border-color: #f44336 !important;
        background-color: rgba(244, 67, 54, 0.05) !important;
    }
    
    .alert {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(10px);
        border-left: 4px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 400px;
        animation: slideIn 0.3s ease;
    }
    
    .alert-success {
        border-color: #4caf50;
        color: #4caf50;
    }
    
    .alert-error {
        border-color: #f44336;
        color: #f44336;
    }
    
    .alert-info {
        border-color: #2196f3;
        color: #2196f3;
    }
    
    .alert-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .alert-close:hover {
        opacity: 1;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 2rem;
        color: #ccc;
    }
    
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #ff6b6b;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
`;
document.head.appendChild(style);