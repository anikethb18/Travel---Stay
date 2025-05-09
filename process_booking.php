<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
session_start();

if (isset($_POST['flight_id']) && isset($_POST['num_tickets'])) {
    $flight_id = $_POST['flight_id'];
    $num_tickets = $_POST['num_tickets'];

    // Check if seats are available
    $query = "SELECT seats_available, price FROM flights WHERE flight_id = ?";
    $values = [$flight_id];
    $flight_res = select($query, $values, "i");
    $flight_data = mysqli_fetch_assoc($flight_res);

    if ($flight_data['seats_available'] >= $num_tickets) {
        // Process booking (e.g., update seats, add booking record, etc.)
        // Assuming you have a 'bookings' table

        $total_price = $flight_data['price'] * $num_tickets;
        $query = "INSERT INTO bookings (flight_id, user_id, num_tickets, total_price, booking_date) VALUES (?, ?, ?, ?, NOW())";
        $values = [$flight_id, $_SESSION['user_id'], $num_tickets, $total_price];
        $result = select($query, $values, "iiii");

        // Update available seats
        $query = "UPDATE flights SET seats_available = seats_available - ? WHERE flight_id = ?";
        $values = [$num_tickets, $flight_id];
        select($query, $values, "ii");

        // Redirect to confirmation page
        echo "<h3 class='text-center text-success'>Booking successful! Your total is \$${total_price}. Please check your email for the booking details.</h3>";
    } else {
        echo "<h3 class='text-center text-danger'>Not enough seats available!</h3>";
    }
} else {
    echo "<h3 class='text-center text-danger'>Invalid booking data!</h3>";
}
?>
