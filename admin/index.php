<?php
require_once '../includes/header.php';

// Check if user is admin
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit;
}

// Get statistics
$stats = array();

// Total flights
$sql = "SELECT COUNT(*) as total FROM flights";
$result = mysqli_query($conn, $sql);
$stats['total_flights'] = mysqli_fetch_assoc($result)['total'];

// Total bookings
$sql = "SELECT COUNT(*) as total FROM bookings";
$result = mysqli_query($conn, $sql);
$stats['total_bookings'] = mysqli_fetch_assoc($result)['total'];

// Total users
$sql = "SELECT COUNT(*) as total FROM users WHERE is_admin = 0";
$result = mysqli_query($conn, $sql);
$stats['total_users'] = mysqli_fetch_assoc($result)['total'];

// Recent bookings
$sql = "SELECT b.*, f.flight_number, u.username 
        FROM bookings b 
        JOIN flights f ON b.flight_id = f.id 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.booking_date DESC 
        LIMIT 5";
$recent_bookings = array();
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $recent_bookings[] = $row;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Admin Dashboard</h2>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Flights</h5>
                            <p class="card-text display-4"><?php echo $stats['total_flights']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Bookings</h5>
                            <p class="card-text display-4"><?php echo $stats['total_bookings']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-4"><?php echo $stats['total_users']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Bookings</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>User</th>
                                            <th>Flight</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($recent_bookings as $booking): ?>
                                            <tr>
                                                <td><?php echo $booking['id']; ?></td>
                                                <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['flight_number']); ?></td>
                                                <td><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></td>
                                                <td>
                                                    <span class="booking-status status-<?php echo strtolower($booking['status']); ?>">
                                                        <?php echo ucfirst($booking['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Quick Actions</h5>
                            <div class="d-grid gap-2">
                                <a href="flights.php" class="btn btn-primary">Manage Flights</a>
                                <a href="bookings.php" class="btn btn-success">View All Bookings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?> 