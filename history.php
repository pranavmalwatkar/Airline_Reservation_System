<?php
require_once 'includes/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user's bookings
$sql = "SELECT b.*, f.flight_number, f.origin, f.destination, f.departure_time, f.arrival_time, f.price, a.name as airline_name 
        FROM bookings b 
        JOIN flights f ON b.flight_id = f.id 
        JOIN airlines a ON f.airline_id = a.id 
        WHERE b.user_id = ? 
        ORDER BY b.booking_date DESC";

$bookings = array();

if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        while($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    }
    
    mysqli_stmt_close($stmt);
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Booking History</h2>
            
            <?php if(empty($bookings)): ?>
                <div class="alert alert-info">
                    You haven't made any bookings yet.
                    <div class="mt-3">
                        <a href="search.php" class="btn btn-primary">Search Flights</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($bookings as $booking): ?>
                    <div class="booking-history">
                        <div class="row">
                            <div class="col-md-8">
                                <h5><?php echo htmlspecialchars($booking['airline_name']); ?> - <?php echo htmlspecialchars($booking['flight_number']); ?></h5>
                                <p><?php echo htmlspecialchars($booking['origin']); ?> → <?php echo htmlspecialchars($booking['destination']); ?></p>
                                <p>Departure: <?php echo date('h:i A', strtotime($booking['departure_time'])); ?></p>
                                <p>Arrival: <?php echo date('h:i A', strtotime($booking['arrival_time'])); ?></p>
                                <p>Booking Date: <?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="flight-price">₹<?php echo number_format($booking['price'], 2); ?></div>
                                <span class="booking-status status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 