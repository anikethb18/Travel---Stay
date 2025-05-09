<?php
// Start session first
session_start();

// Include required files
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

// Set proper content type for AJAX response
header('Content-Type: text/plain');

// Check for login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    echo "login_required";
    exit;
}

if (isset($_POST['flight_id']) && isset($_POST['tickets']) && isset($_POST['amount'])) {
    $flight_id = $_POST['flight_id'];
    $tickets = $_POST['tickets'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION['uId'];
    
    // Validate inputs
    if (!is_numeric($flight_id) || !is_numeric($tickets) || !is_numeric($amount)) {
        echo "invalid_data_format";
        exit;
    }
    
    if ($tickets <= 0) {
        echo "invalid_tickets";
        exit;
    }
    
    // Check if flight exists and has enough seats
    // Modified to explicitly include travel_id and travel_agency (although * would include them anyway)
    $query = "SELECT *, travel_id, travel_agency FROM flights WHERE flight_id=? AND seats_available>=? AND status=1 AND removed=0";
    $values = [$flight_id, $tickets];
    $res = select($query, $values, 'si');
    
    if (mysqli_num_rows($res) == 0) {
        echo "flight_not_available";
        exit;
    }
    
    $flight_data = mysqli_fetch_assoc($res);
    
    // Generate order ID
    $order_id = 'FLGT_' . $flight_id . '_' . $user_id . '_' . time();
    
    // Get user data
    $user_q = "SELECT * FROM user_cred WHERE id=?";
    $user_res = select($user_q, [$user_id], 'i');
    
    if (mysqli_num_rows($user_res) == 0) {
        echo "user_not_found";
        exit;
    }
    
    $user_data = mysqli_fetch_assoc($user_res);
    
    // Begin transaction
    mysqli_begin_transaction($con); 
    
    try {
        // 1. Insert into flight_order table
        $fb_q = "INSERT INTO `flight_order`(`order_id`, `user_id`, `flight_id`, `tickets`, 
        `total_pay`, `booking_status`, `trans_id`, `trans_amount`, 
        `trans_status`, `trans_resp_msg`, `datentime`, `travel_id`, `travel_agency`) 
        VALUES (?,?,?,?,?,'pending','','','','',?,?,?)";

        $datetime = date("Y-m-d H:i:s");
        $fb_values = [
        $order_id, 
        $user_id, 
        $flight_id, 
        $tickets, 
        $amount, 
        $datetime,
        $flight_data['travel_id'],
        $flight_data['travel_agency']
        ];

        // Updated parameter type string to include travel_id and travel_agency
        $fb_res = insert($fb_q, $fb_values, 'siiidsis');
        
        if (!$fb_res) {
            throw new Exception("Failed to create flight order: " . mysqli_error($con));
        }
        
        // 2. Get the booking ID
        $booking_id = mysqli_insert_id($con);
        
        if (!$booking_id) {
            throw new Exception("Failed to get booking ID");
        }
        
        // 3. Insert into flight_booking_details - Modified to include travel_id and travel_agency
        $fbd_q = "INSERT INTO `flight_booking_details`(`flight_booking_id`, `airline`, 
                `departure_city`, `arrival_city`, `departure_airport`, `arrival_airport`, 
                `departure_time`, `arrival_time`, `price_per_adult`, `price_per_child`, 
                `total_pay`, `user_name`, `phonenum`, `address`, `travel_id`, `travel_agency`) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
        $fbd_values = [
            $booking_id,                       // i - flight_booking_id (integer)
            $flight_data['airline'],           // s - airline (string)
            $flight_data['departure_city'],    // s - departure_city (string)
            $flight_data['arrival_city'],      // s - arrival_city (string)
            $flight_data['departure_airport'] ?? '', // s - departure_airport (string)
            $flight_data['arrival_airport'] ?? '',   // s - arrival_airport (string)
            $flight_data['departure_time'],    // s - departure_time (string - datetime)
            $flight_data['arrival_time'],      // s - arrival_time (string - datetime)
            $flight_data['price'],             // d - price_per_adult (double)
            0.00,                              // d - price_per_child (double)
            $amount,                           // d - total_pay (double)
            $user_data['name'],                // s - user_name (string)
            $user_data['phonenum'],            // s - phonenum (string)
            $user_data['address'],             // s - address (string)
            $flight_data['travel_id'],         // i - travel_id (assuming integer)
            $flight_data['travel_agency']      // s - travel_agency (string)
        ];
        
        // Updated type string to match the 16 parameters (added 'is' for travel_id and travel_agency)
        $fbd_res = insert($fbd_q, $fbd_values, 'issssssdddssssis');
        
        if (!$fbd_res) {
            throw new Exception("Failed to create flight booking details: " . mysqli_error($con));
        }
        
        // Commit transaction
        mysqli_commit($con);
        
        // Set session variables for mock payment
        $_SESSION['mock_order_id'] = $order_id;
        $_SESSION['mock_amount'] = $amount;
        $_SESSION['flight_booking_id'] = $booking_id;
        
        // Send success response
        echo "success";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($con);
        echo "error: " . $e->getMessage();
    }
} else {
    echo "invalid_request";
}
?>