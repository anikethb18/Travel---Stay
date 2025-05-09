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

if (isset($_POST['package_id']) && isset($_POST['adults']) && isset($_POST['children']) && isset($_POST['tour_date']) && isset($_POST['amount'])) {
    $package_id = $_POST['package_id'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $tour_date = $_POST['tour_date'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION['uId'];
    
    // Validate inputs
    if (!is_numeric($package_id) || !is_numeric($adults) || !is_numeric($children) || !is_numeric($amount)) {
        echo "invalid_data_format";
        exit;
    }
    
    if ($adults <= 0) {
        echo "invalid_adults_count";
        exit;
    }
    
    // Validate tour date
    $tour_date_obj = date_create($tour_date);
    $today = date_create(date('Y-m-d'));
    $diff = date_diff($today, $tour_date_obj);
    
    if (!$tour_date_obj || $diff->invert == 1) {
        echo "invalid_tour_date";
        exit;
    }
    
    // Check if tour package exists
    $query = "SELECT * FROM tour_packages WHERE package_id=? AND status=1 AND removed=0";
    $values = [$package_id];
    $res = select($query, $values, 'i');
    
    if (mysqli_num_rows($res) == 0) {
        echo "tour_not_available";
        exit;
    }
    
    $tour_data = mysqli_fetch_assoc($res);

    // Generate order ID
    $order_id = 'TOUR_' . $package_id . '_' . $user_id . '_' . time();
    
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
        // 1. Insert into tour_order table
        $to_q = "INSERT INTO `tour_order`(`order_id`, `user_id`, `package_id`, `adults`, 
                `children`, `tour_date`, `total_pay`, `booking_status`, `trans_id`, 
                `trans_amount`, `trans_status`, `trans_resp_msg`, `datentime`) 
                VALUES (?,?,?,?,?,?,?,'pending','','','','',?)";
        
        $datetime = date("Y-m-d H:i:s");
        $to_values = [$order_id, $user_id, $package_id, $adults, $children, $tour_date, $amount, $datetime];
        $to_res = insert($to_q, $to_values, 'siiissds');
        
        if (!$to_res) {
            throw new Exception("Failed to create tour order: " . mysqli_error($con));
        }
        
        // 2. Get the booking ID
        $booking_id = mysqli_insert_id($con);
        
        if (!$booking_id) {
            throw new Exception("Failed to get booking ID");
        }
        
        // 3. Insert into tour_booking_details
        $tbd_q = "INSERT INTO `tour_booking_details`(`tour_booking_id`, `package_name`, 
        `destination`, `duration`, `price_per_adult`, `price_per_child`, 
        `adults`, `children`, `tour_date`, `total_pay`, `user_name`, 
        `phonenum`, `address`) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $tbd_values = [
            (int) $booking_id,                     // i - tour_booking_id (integer)
            (string) $tour_data['name'],           // s - package_name (string)
            (string) $tour_data['destination'],    // s - destination (string)
            (string) $tour_data['duration'],       // s - duration (string)
            (double) $tour_data['price_per_adult'], // d - price_per_adult (double)
            (double) $tour_data['price_per_child'], // d - price_per_child (double)
            (int) $adults,                         // i - adults (integer)
            (int) $children,                       // i - children (integer)
            (string) $tour_date,                   // s - tour_date (string - date)
            (double) $amount,                       // d - total_pay (double)
            (string) $user_data['name'],           // s - user_name (string)
            (string) $user_data['phonenum'],       // s - phonenum (string)
            (string) $user_data['address']          // s - address (string)
        ];
        // Type string for prepared statement with correct number of parameters
        $tbd_res = insert($tbd_q, $tbd_values, 'isssddiisdsss');
        
        // if (!$tbd_res) {
           // throw new Exception("Failed to create tour booking details: " . mysqli_error($con));
        // }
        
        // Commit transaction
        mysqli_commit($con);
        
        // Set session variables for mock payment
        $_SESSION['mock_order_id'] = $order_id;
        $_SESSION['mock_amount'] = $amount;
        $_SESSION['tour_booking_id'] = $booking_id;
        
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