// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Date Validation
function validateDate(input) {
    const selectedDate = new Date(input.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        input.setCustomValidity('Please select a future date');
        input.classList.add('is-invalid');
    } else {
        input.setCustomValidity('');
        input.classList.remove('is-invalid');
    }
}

// Password Strength Check
function checkPasswordStrength(password) {
    const strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
    const mediumRegex = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.{6,})");

    if (strongRegex.test(password)) {
        return 'strong';
    } else if (mediumRegex.test(password)) {
        return 'medium';
    } else {
        return 'weak';
    }
}

// Password Match Validation
function validatePasswordMatch(password, confirmPassword) {
    if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Passwords do not match');
        confirmPassword.classList.add('is-invalid');
    } else {
        confirmPassword.setCustomValidity('');
        confirmPassword.classList.remove('is-invalid');
    }
}

// Flight Search Form
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            if (!validateForm('searchForm')) {
                e.preventDefault();
            }
        });
    }

    // Date input validation
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateDate(this);
        });
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            const strengthIndicator = document.getElementById('password-strength');
            if (strengthIndicator) {
                strengthIndicator.className = 'password-strength ' + strength;
                strengthIndicator.textContent = 'Password Strength: ' + strength.charAt(0).toUpperCase() + strength.slice(1);
            }
        });
    }

    if (confirmPasswordInput && passwordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordMatch(passwordInput, this);
        });
    }
});

// Dynamic Flight Search
function searchFlights() {
    const origin = document.getElementById('origin').value;
    const destination = document.getElementById('destination').value;
    const date = document.getElementById('date').value;

    if (!origin || !destination || !date) {
        alert('Please fill in all search fields');
        return;
    }

    // AJAX request to search flights
    fetch('search_flights.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `origin=${origin}&destination=${destination}&date=${date}`
    })
    .then(response => response.json())
    .then(data => {
        displayFlights(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while searching for flights');
    });
}

// Display Flight Results
function displayFlights(flights) {
    const resultsContainer = document.getElementById('flight-results');
    if (!resultsContainer) return;

    resultsContainer.innerHTML = '';

    if (flights.length === 0) {
        resultsContainer.innerHTML = '<div class="alert alert-info">No flights found matching your criteria.</div>';
        return;
    }

    flights.forEach(flight => {
        const flightCard = document.createElement('div');
        flightCard.className = 'flight-card';
        flightCard.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <h5>${flight.airline} - ${flight.flight_number}</h5>
                    <p>${flight.origin} → ${flight.destination}</p>
                    <p>Departure: ${flight.departure_time}</p>
                    <p>Arrival: ${flight.arrival_time}</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="flight-price">₹${flight.price}</div>
                    <p>Available Seats: ${flight.available_seats}</p>
                    <a href="booking.php?flight_id=${flight.id}" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        `;
        resultsContainer.appendChild(flightCard);
    });
}

// Admin Panel Functions
function confirmDelete(id, type) {
    if (confirm(`Are you sure you want to delete this ${type}?`)) {
        window.location.href = `admin/delete_${type}.php?id=${id}`;
    }
}

// Booking Confirmation
function confirmBooking(flightId) {
    if (confirm('Are you sure you want to book this flight?')) {
        window.location.href = `confirm_booking.php?flight_id=${flightId}`;
    }
} 