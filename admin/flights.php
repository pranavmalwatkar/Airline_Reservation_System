<?php
require_once '../includes/header.php';

// Check if user is admin
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit;
}

$error = '';
$success = '';

// Process flight addition/update
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'add') {
            $flight_number = trim($_POST['flight_number']);
            $airline_id = $_POST['airline_id'];
            $origin = trim($_POST['origin']);
            $destination = trim($_POST['destination']);
            $departure_time = $_POST['departure_time'];
            $arrival_time = $_POST['arrival_time'];
            $price = $_POST['price'];
            $available_seats = $_POST['available_seats'];
            
            $sql = "INSERT INTO flights (flight_number, airline_id, origin, destination, departure_time, arrival_time, price, available_seats) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sissssdi", $flight_number, $airline_id, $origin, $destination, $departure_time, $arrival_time, $price, $available_seats);
                
                if(mysqli_stmt_execute($stmt)) {
                    $success = "Flight added successfully!";
                } else {
                    $error = "Error adding flight.";
                }
                
                mysqli_stmt_close($stmt);
            }
        } elseif($_POST['action'] == 'update') {
            $flight_id = $_POST['flight_id'];
            $flight_number = trim($_POST['flight_number']);
            $airline_id = $_POST['airline_id'];
            $origin = trim($_POST['origin']);
            $destination = trim($_POST['destination']);
            $departure_time = $_POST['departure_time'];
            $arrival_time = $_POST['arrival_time'];
            $price = $_POST['price'];
            $available_seats = $_POST['available_seats'];
            
            $sql = "UPDATE flights 
                    SET flight_number = ?, airline_id = ?, origin = ?, destination = ?, 
                        departure_time = ?, arrival_time = ?, price = ?, available_seats = ? 
                    WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sissssdi", $flight_number, $airline_id, $origin, $destination, $departure_time, $arrival_time, $price, $available_seats, $flight_id);
                
                if(mysqli_stmt_execute($stmt)) {
                    $success = "Flight updated successfully!";
                } else {
                    $error = "Error updating flight.";
                }
                
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Get all flights
$sql = "SELECT f.*, a.name as airline_name 
        FROM flights f 
        JOIN airlines a ON f.airline_id = a.id 
        ORDER BY f.departure_time DESC";
$flights = array();
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $flights[] = $row;
}

// Get all airlines for dropdown
$sql = "SELECT * FROM airlines ORDER BY name";
$airlines = array();
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    $airlines[] = $row;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Manage Flights</h2>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Flight</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="action" value="add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="flight_number" class="form-label">Flight Number</label>
                                <input type="text" class="form-control" id="flight_number" name="flight_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="airline_id" class="form-label">Airline</label>
                                <select class="form-select" id="airline_id" name="airline_id" required>
                                    <?php foreach($airlines as $airline): ?>
                                        <option value="<?php echo $airline['id']; ?>"><?php echo htmlspecialchars($airline['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origin" class="form-label">Origin</label>
                                <input type="text" class="form-control" id="origin" name="origin" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="destination" class="form-label">Destination</label>
                                <input type="text" class="form-control" id="destination" name="destination" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="departure_time" class="form-label">Departure Time</label>
                                <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="arrival_time" class="form-label">Arrival Time</label>
                                <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="available_seats" class="form-label">Available Seats</label>
                                <input type="number" class="form-control" id="available_seats" name="available_seats" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Flight</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Flights</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Flight Number</th>
                                    <th>Airline</th>
                                    <th>Route</th>
                                    <th>Departure</th>
                                    <th>Arrival</th>
                                    <th>Price</th>
                                    <th>Available Seats</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($flights as $flight): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($flight['flight_number']); ?></td>
                                        <td><?php echo htmlspecialchars($flight['airline_name']); ?></td>
                                        <td><?php echo htmlspecialchars($flight['origin'] . ' → ' . $flight['destination']); ?></td>
                                        <td><?php echo date('M j, Y h:i A', strtotime($flight['departure_time'])); ?></td>
                                        <td><?php echo date('M j, Y h:i A', strtotime($flight['arrival_time'])); ?></td>
                                        <td>₹<?php echo number_format($flight['price'], 2); ?></td>
                                        <td><?php echo $flight['available_seats']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editFlight(<?php echo htmlspecialchars(json_encode($flight)); ?>)">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteFlight(<?php echo $flight['id']; ?>)">
                                                Delete
                                            </button>
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
</div>

<!-- Edit Flight Modal -->
<div class="modal fade" id="editFlightModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Flight</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editFlightForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="flight_id" id="edit_flight_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_flight_number" class="form-label">Flight Number</label>
                            <input type="text" class="form-control" id="edit_flight_number" name="flight_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_airline_id" class="form-label">Airline</label>
                            <select class="form-select" id="edit_airline_id" name="airline_id" required>
                                <?php foreach($airlines as $airline): ?>
                                    <option value="<?php echo $airline['id']; ?>"><?php echo htmlspecialchars($airline['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_origin" class="form-label">Origin</label>
                            <input type="text" class="form-control" id="edit_origin" name="origin" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_destination" class="form-label">Destination</label>
                            <input type="text" class="form-control" id="edit_destination" name="destination" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_departure_time" class="form-label">Departure Time</label>
                            <input type="datetime-local" class="form-control" id="edit_departure_time" name="departure_time" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_arrival_time" class="form-label">Arrival Time</label>
                            <input type="datetime-local" class="form-control" id="edit_arrival_time" name="arrival_time" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_available_seats" class="form-label">Available Seats</label>
                            <input type="number" class="form-control" id="edit_available_seats" name="available_seats" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editFlightForm" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function editFlight(flight) {
    document.getElementById('edit_flight_id').value = flight.id;
    document.getElementById('edit_flight_number').value = flight.flight_number;
    document.getElementById('edit_airline_id').value = flight.airline_id;
    document.getElementById('edit_origin').value = flight.origin;
    document.getElementById('edit_destination').value = flight.destination;
    document.getElementById('edit_departure_time').value = flight.departure_time.slice(0, 16);
    document.getElementById('edit_arrival_time').value = flight.arrival_time.slice(0, 16);
    document.getElementById('edit_price').value = flight.price;
    document.getElementById('edit_available_seats').value = flight.available_seats;
    
    new bootstrap.Modal(document.getElementById('editFlightModal')).show();
}

function deleteFlight(flightId) {
    if(confirm('Are you sure you want to delete this flight?')) {
        window.location.href = 'delete_flight.php?id=' + flightId;
    }
}
</script>

<?php
require_once '../includes/footer.php';
?> 