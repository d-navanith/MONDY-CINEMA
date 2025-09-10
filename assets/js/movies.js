// Movies management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeMovieManagement();
});

function initializeMovieManagement() {
    // Initialize poster preview
    const posterInput = document.getElementById('movie-poster');
    if (posterInput) {
        posterInput.addEventListener('change', function() {
            previewPoster(this.value);
        });
    }
    
    // Initialize trailer URL validation
    const trailerInput = document.getElementById('movie-trailer');
    if (trailerInput) {
        trailerInput.addEventListener('blur', function() {
            validateTrailerURL(this.value);
        });
    }
    
    // Initialize duration formatting
    const durationInput = document.getElementById('movie-duration');
    if (durationInput) {
        durationInput.addEventListener('input', function() {
            formatDuration(this);
        });
    }
}

// Preview poster image
function previewPoster(imageName) {
    if (imageName) {
        const previewContainer = document.querySelector('.poster-preview');
        if (!previewContainer) {
            const container = document.createElement('div');
            container.className = 'poster-preview';
            document.getElementById('movie-poster').parentNode.appendChild(container);
        }
        
        const preview = document.querySelector('.poster-preview');
        preview.innerHTML = `
            <img src="../assets/images/movies/${imageName}" 
                 alt="Poster Preview" 
                 style="max-width: 100px; height: auto; border-radius: 5px; margin-top: 10px;"
                 onerror="this.style.display='none'">
        `;
    }
}

// Validate YouTube trailer URL
function validateTrailerURL(url) {
    if (url && !isValidYouTubeURL(url)) {
        showFieldError(document.getElementById('movie-trailer'), 'Please enter a valid YouTube URL');
        return false;
    }
    return true;
}

// Check if URL is a valid YouTube URL
function isValidYouTubeURL(url) {
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/;
    return youtubeRegex.test(url);
}

// Format duration input
function formatDuration(input) {
    const value = parseInt(input.value);
    if (value) {
        const hours = Math.floor(value / 60);
        const minutes = value % 60;
        
        let displayText = '';
        if (hours > 0) {
            displayText += `${hours}h `;
        }
        if (minutes > 0) {
            displayText += `${minutes}m`;
        }
        
        // Show formatted duration hint
        let hint = input.parentNode.querySelector('.duration-hint');
        if (!hint) {
            hint = document.createElement('small');
            hint.className = 'duration-hint';
            hint.style.color = '#ccc';
            hint.style.display = 'block';
            hint.style.marginTop = '5px';
            input.parentNode.appendChild(hint);
        }
        hint.textContent = displayText || '';
    }
}

// Show field error (reuse from admin.js)
function showFieldError(field, message) {
    // Remove existing error
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    field.classList.add('error');
    
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    errorElement.style.color = '#f44336';
    errorElement.style.fontSize = '0.8rem';
    errorElement.style.marginTop = '0.25rem';
    
    field.parentNode.appendChild(errorElement);
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('error');
    const error = field.parentNode.querySelector('.error-message');
    if (error) {
        error.remove();
    }
}

// Movie form submission handler
function handleMovieSubmit(form) {
    // Additional validation before submission
    const trailerURL = document.getElementById('movie-trailer').value;
    if (trailerURL && !validateTrailerURL(trailerURL)) {
        return false;
    }
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<div class="spinner"></div> Saving...';
    submitBtn.disabled = true;
    
    // Reset button after some time (in case form submission fails)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
    
    return true;
}

// Initialize form submission
document.addEventListener('DOMContentLoaded', function() {
    const movieForm = document.getElementById('movie-form');
    if (movieForm) {
        movieForm.addEventListener('submit', function(e) {
            if (!handleMovieSubmit(this)) {
                e.preventDefault();
            }
        });
    }
});