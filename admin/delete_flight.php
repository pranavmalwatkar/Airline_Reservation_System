<?php
require_once '../includes/header.php';

// Check if user is admin
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit;
}

// Check if flight ID is provided
if(!isset($_GET['id'])) {
    header("Location: flights.php");
    exit;
}

$flight_id = $_GET['id'];

// Check if flight has any bookings
$sql = "SELECT COUNT(*) as booking_count FROM bookings WHERE flight_id = ?";
if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $flight_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $booking_count = mysqli_fetch_assoc($result)['booking_count'];
    
    if($booking_count > 0) {
        $_SESSION['error'] = "Cannot delete flight with existing bookings.";
        header("Location: flights.php");
        exit;
    }
    
    mysqli_stmt_close($stmt);
}

// Delete the flight
$sql = "DELETE FROM flights WHERE id = ?";
if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $flight_id);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Flight deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting flight.";
    }
    
    mysqli_stmt_close($stmt);
}

header("Location: flights.php");
exit;
?> 