<?php
require_once 'includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center mb-5">
            <h1 class="display-4">Welcome to Airline Reservation System</h1>
            <p class="lead">Book your flights with ease and comfort</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="search-form">
                <h3 class="text-center mb-4">Search Flights</h3>
                <form id="searchForm" action="search.php" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="origin" class="form-label">From</label>
                            <select class="form-select" id="origin" name="origin" required>
                                <option value="">Select Origin</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Bangalore">Bangalore</option>
                                <option value="Chennai">Chennai</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="destination" class="form-label">To</label>
                            <select class="form-select" id="destination" name="destination" required>
                                <option value="">Select Destination</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Bangalore">Bangalore</option>
                                <option value="Chennai">Chennai</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Search Flights</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Easy Booking</h3>
                    <p class="card-text">Book your flights in just a few clicks with our user-friendly interface.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Best Prices</h3>
                    <p class="card-text">Find the best deals and competitive prices for your journey.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">24/7 Support</h3>
                    <p class="card-text">Our customer support team is always ready to help you.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 