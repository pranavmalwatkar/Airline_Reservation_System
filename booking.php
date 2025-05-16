<?php
require_once 'includes/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if flight_id is provided
if(!isset($_GET['flight_id'])) {
    header("Location: search.php");
    exit;
}

$flight_id = $_GET['flight_id'];
$error = '';
$success = '';

// Get flight details
$sql = "SELECT f.*, a.name as airline_name 
        FROM flights f 
        JOIN airlines a ON f.airline_id = a.id 
        WHERE f.id = ? AND f.available_seats > 0";

if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $flight_id);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $flight = mysqli_fetch_assoc($result);
        
        if(!$flight) {
            header("Location: search.php");
            exit;
        }
    }
    
    mysqli_stmt_close($stmt);
}

// Process booking
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Check if seats are still available
        $sql = "SELECT available_seats FROM flights WHERE id = ? FOR UPDATE";
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $flight_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $flight_check = mysqli_fetch_assoc($result);
            
            if($flight_check['available_seats'] <= 0) {
                throw new Exception("Sorry, this flight is no longer available.");
            }
            
            // Create booking
            $sql = "INSERT INTO bookings (user_id, flight_id) VALUES (?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $flight_id);
                mysqli_stmt_execute($stmt);
                
                // Update available seats
                $sql = "UPDATE flights SET available_seats = available_seats - 1 WHERE id = ?";
                if($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $flight_id);
                    mysqli_stmt_execute($stmt);
                }
                
                mysqli_commit($conn);
                $success = "Flight booked successfully!";
            }
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="form-container">
                <h2 class="text-center mb-4">Book Flight</h2>
                
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                        <div class="mt-3">
                            <a href="history.php" class="btn btn-primary">View Booking History</a>
                            <a href="search.php" class="btn btn-secondary">Search More Flights</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flight-card mb-4">
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
                            </div>
                        </div>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?flight_id=" . $flight_id); ?>" method="post">
                        <div class="alert alert-info">
                            <h5>Booking Confirmation</h5>
                            <p>Please confirm your booking details:</p>
                            <ul>
                                <li>Flight: <?php echo htmlspecialchars($flight['airline_name'] . ' ' . $flight['flight_number']); ?></li>
                                <li>Route: <?php echo htmlspecialchars($flight['origin'] . ' to ' . $flight['destination']); ?></li>
                                <li>Date: <?php echo date('F j, Y', strtotime($flight['departure_time'])); ?></li>
                                <li>Price: ₹<?php echo number_format($flight['price'], 2); ?></li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure you want to book this flight?')">Confirm Booking</button>
                            <a href="search.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 