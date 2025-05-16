<?php
require_once 'includes/header.php';

// Get search parameters
$origin = isset($_GET['origin']) ? trim($_GET['origin']) : '';
$destination = isset($_GET['destination']) ? trim($_GET['destination']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

// Initialize flights array
$flights = array();

// If search parameters are provided
if(!empty($origin) && !empty($destination) && !empty($date)) {
    // Prepare the SQL query
    $sql = "SELECT f.*, a.name as airline_name 
            FROM flights f 
            JOIN airlines a ON f.airline_id = a.id 
            WHERE f.origin = ? 
            AND f.destination = ? 
            AND DATE(f.departure_time) = ? 
            AND f.available_seats > 0 
            ORDER BY f.departure_time";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $origin, $destination, $date);
        
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            while($row = mysqli_fetch_assoc($result)) {
                $flights[] = $row;
            }
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="search-form mb-4">
                <h3 class="text-center mb-4">Search Flights</h3>
                <form id="searchForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="origin" class="form-label">From</label>
                            <select class="form-select" id="origin" name="origin" required>
                                <option value="">Select Origin</option>
                                <option value="Delhi" <?php echo ($origin == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                                <option value="Mumbai" <?php echo ($origin == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                                <option value="Bangalore" <?php echo ($origin == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
                                <option value="Chennai" <?php echo ($origin == 'Chennai') ? 'selected' : ''; ?>>Chennai</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="destination" class="form-label">To</label>
                            <select class="form-select" id="destination" name="destination" required>
                                <option value="">Select Destination</option>
                                <option value="Delhi" <?php echo ($destination == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                                <option value="Mumbai" <?php echo ($destination == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                                <option value="Bangalore" <?php echo ($destination == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
                                <option value="Chennai" <?php echo ($destination == 'Chennai') ? 'selected' : ''; ?>>Chennai</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo $date; ?>" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Search Flights</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if(!empty($origin) && !empty($destination) && !empty($date)): ?>
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-4">Available Flights</h3>
                
                <?php if(empty($flights)): ?>
                    <div class="alert alert-info">
                        No flights found for the selected route and date.
                    </div>
                <?php else: ?>
                    <?php foreach($flights as $flight): ?>
                        <div class="flight-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5><?php echo htmlspecialchars($flight['airline_name']); ?> - <?php echo htmlspecialchars($flight['flight_number']); ?></h5>
                                    <p><?php echo htmlspecialchars($flight['origin']); ?> → <?php echo htmlspecialchars($flight['destination']); ?></p>
                                    <p>Departure: <?php echo date('h:i A', strtotime($flight['departure_time'])); ?></p>
                                    <p>Arrival: <?php echo date('h:i A', strtotime($flight['arrival_time'])); ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="flight-price">₹<?php echo number_format($flight['price'], 2); ?></div>
                                    <p>Available Seats: <?php echo $flight['available_seats']; ?></p>
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <a href="booking.php?flight_id=<?php echo $flight['id']; ?>" class="btn btn-primary">Book Now</a>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-primary">Login to Book</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?> 