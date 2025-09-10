// Booking system JavaScript functionality

let currentStep = 1;
let bookingData = {
    movieId: null,
    movieTitle: '',
    cinemaId: null,
    cinemaName: '',
    date: '',
    showtimeId: null,
    showtime: '',
    price: 0,
    selectedSeats: [],
    totalAmount: 0
};

document.addEventListener('DOMContentLoaded', function() {
    initializeBookingSteps();
    initializeMovieSelection();
    initializeDateSelection();
    initializeSeatSelection();
    initializePayment();
});

// Initialize booking steps navigation
function initializeBookingSteps() {
    const steps = document.querySelectorAll('.step');
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    
    nextBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (validateCurrentStep()) {
                nextStep();
            }
        });
    });
    
    prevBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            previousStep();
        });
    });
}

// Movie selection functionality
function initializeMovieSelection() {
    const movieCards = document.querySelectorAll('.movie-selection-card');
    const nextBtn = document.querySelector('.step-1 .next-step');
    
    movieCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove previous selection
            movieCards.forEach(c => c.classList.remove('selected'));
            
            // Select current movie
            this.classList.add('selected');
            
            // Update booking data
            bookingData.movieId = this.dataset.movieId;
            bookingData.movieTitle = this.querySelector('h3').textContent;
            
            // Enable next button
            nextBtn.disabled = false;
        });
    });
}

// Date selection functionality
function initializeDateSelection() {
    const dateOptions = document.querySelectorAll('.date-option');
    
    dateOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove previous selection
            dateOptions.forEach(o => o.classList.remove('selected'));
            
            // Select current date
            this.classList.add('selected');
            bookingData.date = this.dataset.date;
            
            // Load showtimes for selected date and movie
            loadShowtimes();
        });
    });
}

// Load showtimes based on selected movie and date
function loadShowtimes() {
    if (!bookingData.movieId || !bookingData.date) return;
    
    const container = document.getElementById('showtimes-container');
    container.innerHTML = '<div class="loading"><div class="spinner"></div><span>Loading showtimes...</span></div>';
    
    // Simulate API call - replace with actual AJAX request
    setTimeout(() => {
        const showtimes = generateSampleShowtimes();
        displayShowtimes(showtimes);
    }, 1000);
}

// Generate sample showtimes (replace with actual API call)
function generateSampleShowtimes() {
    return [
        {
            cinemaId: 1,
            cinemaName: 'Mondy Cinema Colombo',
            showtimes: [
                { id: 1, time: '10:00 AM', price: 1500 },
                { id: 2, time: '1:30 PM', price: 1500 },
                { id: 3, time: '5:00 PM', price: 1800 },
                { id: 4, time: '8:30 PM', price: 1800 }
            ]
        },
        {
            cinemaId: 2,
            cinemaName: 'Mondy Cinema Kandy',
            showtimes: [
                { id: 5, time: '11:00 AM', price: 1400 },
                { id: 6, time: '3:00 PM', price: 1400 },
                { id: 7, time: '7:00 PM', price: 1600 }
            ]
        }
    ];
}

// Display showtimes
function displayShowtimes(cinemas) {
    const container = document.getElementById('showtimes-container');
    
    container.innerHTML = cinemas.map(cinema => `
        <div class="cinema-showtime">
            <h4>${cinema.cinemaName}</h4>
            <div class="showtime-slots">
                ${cinema.showtimes.map(showtime => `
                    <div class="showtime-slot" data-showtime-id="${showtime.id}" data-cinema-id="${cinema.cinemaId}" data-cinema-name="${cinema.cinemaName}" data-time="${showtime.time}" data-price="${showtime.price}">
                        <div class="time">${showtime.time}</div>
                        <div class="price">Rs. ${showtime.price}</div>
                    </div>
                `).join('')}
            </div>
        </div>
    `).join('');
    
    // Add click handlers for showtime slots
    const showtimeSlots = container.querySelectorAll('.showtime-slot');
    const nextBtn = document.querySelector('.step-2 .next-step');
    
    showtimeSlots.forEach(slot => {
        slot.addEventListener('click', function() {
            // Remove previous selection
            showtimeSlots.forEach(s => s.classList.remove('selected'));
            
            // Select current showtime
            this.classList.add('selected');
            
            // Update booking data
            bookingData.showtimeId = this.dataset.showtimeId;
            bookingData.cinemaId = this.dataset.cinemaId;
            bookingData.cinemaName = this.dataset.cinemaName;
            bookingData.showtime = this.dataset.time;
            bookingData.price = parseFloat(this.dataset.price);
            
            // Enable next button
            nextBtn.disabled = false;
        });
    });
}

// Seat selection functionality
function initializeSeatSelection() {
    // Generate seat layout when step 3 is shown
    const step3 = document.querySelector('.step-3');
    
    if (step3) {
        // Initialize seats when the step becomes active
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.target.classList.contains('active') && mutation.target.classList.contains('step-3')) {
                    updateBookingSummary();
                    generateSeatLayout();
                }
            });
        });
        
        observer.observe(step3, { attributes: true, attributeFilter: ['class'] });
    }
}

// Update booking summary in step 3
function updateBookingSummary() {
    document.getElementById('booking-movie-title').textContent = bookingData.movieTitle;
    document.getElementById('booking-cinema-date').textContent = `${bookingData.cinemaName} • ${formatDate(bookingData.date)}`;
    document.getElementById('booking-showtime').textContent = bookingData.showtime;
}

// Generate seat layout
function generateSeatLayout() {
    const seatsGrid = document.getElementById('seats-grid');
    if (!seatsGrid) return;
    
    const rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    const seatsPerRow = 10;
    
    let seatHTML = '';
    
    rows.forEach(row => {
        for (let i = 1; i <= seatsPerRow; i++) {
            const seatNumber = `${row}${i}`;
            const isOccupied = Math.random() < 0.2; // 20% chance of being occupied
            
            seatHTML += `
                <div class="seat ${isOccupied ? 'occupied' : 'available'}" 
                     data-seat="${seatNumber}" 
                     ${isOccupied ? '' : 'onclick="toggleSeat(this)"'}>
                    ${seatNumber}
                </div>
            `;
        }
    });
    
    seatsGrid.innerHTML = seatHTML;
}

// Toggle seat selection
function toggleSeat(seatElement) {
    const seatNumber = seatElement.dataset.seat;
    
    if (seatElement.classList.contains('selected')) {
        // Deselect seat
        seatElement.classList.remove('selected');
        seatElement.classList.add('available');
        
        // Remove from selected seats
        const index = bookingData.selectedSeats.indexOf(seatNumber);
        if (index > -1) {
            bookingData.selectedSeats.splice(index, 1);
        }
    } else {
        // Select seat (max 8 seats)
        if (bookingData.selectedSeats.length < 8) {
            seatElement.classList.remove('available');
            seatElement.classList.add('selected');
            
            // Add to selected seats
            bookingData.selectedSeats.push(seatNumber);
        } else {
            showAlert('You can select maximum 8 seats', 'error');
            return;
        }
    }
    
    updateSeatSummary();
}

// Update seat selection summary
function updateSeatSummary() {
    const selectedSeatsElement = document.getElementById('selected-seats-list');
    const ticketCountElement = document.getElementById('ticket-count');
    const ticketsTotalElement = document.getElementById('tickets-total');
    const serviceFeeElement = document.getElementById('service-fee');
    const totalAmountElement = document.getElementById('total-amount');
    const nextBtn = document.querySelector('.step-3 .next-step');
    
    const ticketCount = bookingData.selectedSeats.length;
    const ticketsTotal = ticketCount * bookingData.price;
    const serviceFee = ticketCount * 50; // Rs. 50 service fee per ticket
    const totalAmount = ticketsTotal + serviceFee;
    
    // Update display
    selectedSeatsElement.textContent = bookingData.selectedSeats.length > 0 ? bookingData.selectedSeats.join(', ') : 'None';
    ticketCountElement.textContent = ticketCount;
    ticketsTotalElement.textContent = ticketsTotal.toFixed(2);
    serviceFeeElement.textContent = serviceFee.toFixed(2);
    totalAmountElement.textContent = totalAmount.toFixed(2);
    
    // Update booking data
    bookingData.totalAmount = totalAmount;
    
    // Enable/disable next button
    nextBtn.disabled = ticketCount === 0;
}

// Payment functionality
function initializePayment() {
    const paymentForm = document.getElementById('payment-form');
    const completeBookingBtn = document.getElementById('complete-booking');
    
    if (completeBookingBtn) {
        completeBookingBtn.addEventListener('click', function() {
            if (validatePaymentForm()) {
                processBooking();
            }
        });
    }
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('input[name="payment-method"]');
    const cardDetails = document.querySelector('.card-details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });
}

// Update final booking summary in step 4
function updateFinalSummary() {
    document.getElementById('final-movie-title').textContent = bookingData.movieTitle;
    document.getElementById('final-cinema').textContent = bookingData.cinemaName;
    document.getElementById('final-datetime').textContent = `${formatDate(bookingData.date)} at ${bookingData.showtime}`;
    document.getElementById('final-seats').textContent = bookingData.selectedSeats.join(', ');
    document.getElementById('final-total').textContent = bookingData.totalAmount.toFixed(2);
}

// Validate current step
function validateCurrentStep() {
    switch (currentStep) {
        case 1:
            return bookingData.movieId !== null;
        case 2:
            return bookingData.showtimeId !== null;
        case 3:
            return bookingData.selectedSeats.length > 0;
        default:
            return true;
    }
}

// Validate payment form
function validatePaymentForm() {
    const requiredFields = document.querySelectorAll('#payment-form [required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('error');
        } else {
            field.classList.remove('error');
        }
    });
    
    if (!isValid) {
        showAlert('Please fill in all required fields', 'error');
    }
    
    return isValid;
}

// Process booking
function processBooking() {
    const completeBtn = document.getElementById('complete-booking');
    completeBtn.disabled = true;
    completeBtn.innerHTML = '<div class="spinner"></div> Processing...';
    
    // Simulate booking process
    setTimeout(() => {
        // Generate booking reference
        const bookingRef = 'MBC' + Date.now().toString().slice(-8);
        
        // Show success message
        showAlert(`Booking confirmed! Your reference number is ${bookingRef}`, 'success');
        
        // Redirect to booking confirmation page (you can implement this)
        setTimeout(() => {
            window.location.href = 'booking-confirmation.php?ref=' + bookingRef;
        }, 2000);
    }, 3000);
}

// Navigation functions
function nextStep() {
    if (currentStep < 4) {
        // Hide current step
        document.querySelector(`.step-${currentStep}`).classList.remove('active');
        document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
        
        currentStep++;
        
        // Show next step
        document.querySelector(`.step-${currentStep}`).classList.add('active');
        document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
        
        // Update step-specific content
        if (currentStep === 2) {
            updateSelectedMovieInfo();
        } else if (currentStep === 4) {
            updateFinalSummary();
        }
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function previousStep() {
    if (currentStep > 1) {
        // Hide current step
        document.querySelector(`.step-${currentStep}`).classList.remove('active');
        document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
        
        currentStep--;
        
        // Show previous step
        document.querySelector(`.step-${currentStep}`).classList.add('active');
        document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Update selected movie info in step 2
function updateSelectedMovieInfo() {
    const selectedCard = document.querySelector('.movie-selection-card.selected');
    if (selectedCard) {
        const poster = selectedCard.querySelector('img').src;
        const title = selectedCard.querySelector('h3').textContent;
        const details = selectedCard.querySelector('.movie-info').textContent;
        
        document.getElementById('selected-movie-poster').src = poster;
        document.getElementById('selected-movie-title').textContent = title;
        document.getElementById('selected-movie-details').textContent = details;
    }
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function showAlert(message, type) {
    // Use the same alert function from main.js
    if (window.showAlert) {
        window.showAlert(message, type);
    } else {
        alert(message);
    }
}

// Initialize booking data from URL parameters if available
function initializeFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('movie');
    const cinemaId = urlParams.get('cinema');
    
    if (movieId) {
        // Pre-select movie if provided in URL
        const movieCard = document.querySelector(`[data-movie-id="${movieId}"]`);
        if (movieCard) {
            movieCard.click();
        }
    }
    
    if (cinemaId) {
        bookingData.cinemaId = cinemaId;
    }
}

// Call initialization from URL on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeFromURL();
});