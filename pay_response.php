<?php
  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');
  date_default_timezone_set("America/Chicago");
  
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  unset($_SESSION['room']);  // Optional cleanup
  unset($_SESSION['tour']);  // Optional cleanup for tours
  
  function regenrate_session($uid) {
    $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$uid],'i');
    $user_fetch = mysqli_fetch_assoc($user_q);
    
    $_SESSION['login'] = true;
    $_SESSION['uId'] = $user_fetch['id'];
    $_SESSION['uName'] = $user_fetch['name'];
    $_SESSION['uPic'] = $user_fetch['profile'];
    $_SESSION['uPhone'] = $user_fetch['phonenum'];
  }

  // Skip real checksum validation in mock mode
  $order_id = $_POST['ORDERID'];
  
  // First check if this is a hotel booking
  $hotel_query = "SELECT `booking_id`, `user_id` FROM `booking_order` 
                 WHERE `order_id`='$order_id'";
  $hotel_res = mysqli_query($con, $hotel_query);
  
  // Then check if this is a flight booking
  $flight_query = "SELECT `id` as booking_id, `user_id` FROM `flight_order` 
                  WHERE `order_id`='$order_id'";
  $flight_res = mysqli_query($con, $flight_query);
  
  // Also check if this is a tour booking
  $tour_query = "SELECT `booking_id`, `user_id` FROM `tour_order` 
                WHERE `order_id`='$order_id'";
  $tour_res = mysqli_query($con, $tour_query);
  
  $is_hotel_booking = mysqli_num_rows($hotel_res) > 0;
  $is_flight_booking = mysqli_num_rows($flight_res) > 0;
  $is_tour_booking = mysqli_num_rows($tour_res) > 0;
  
  // If no valid booking found, redirect
  if (!$is_hotel_booking && !$is_flight_booking && !$is_tour_booking) {
    redirect('index.php');
  }
  
  // Get booking details based on type
  if ($is_hotel_booking) {
    $booking_fetch = mysqli_fetch_assoc($hotel_res);
    $booking_type = 'hotel';
  } elseif ($is_flight_booking) {
    $booking_fetch = mysqli_fetch_assoc($flight_res);
    $booking_type = 'flight';
  } else {
    $booking_fetch = mysqli_fetch_assoc($tour_res);
    $booking_type = 'tour';
  }
  
  // Regenerate session if user not logged in
  if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    regenrate_session($booking_fetch['user_id']);
  }
  
  // Update transaction status based on payment result
  if ($_POST["STATUS"] == "TXN_SUCCESS") {
    if ($booking_type == 'hotel') {
      $upd_query = "UPDATE `booking_order` SET 
                    `booking_status`='booked',
                    `trans_id`='$_POST[TXNID]',
                    `trans_amt`='$_POST[TXNAMOUNT]',
                    `trans_status`='$_POST[STATUS]',
                    `trans_resp_msg`='$_POST[RESPMSG]'
                    WHERE `booking_id`='$booking_fetch[booking_id]'";
      mysqli_query($con, $upd_query);
      
      // Redirect to hotel payment status page
      redirect('pay_status.php?order=' . $_POST['ORDERID']);
    } 
    elseif ($booking_type == 'flight') {
      // For flight bookings
      $upd_query = "UPDATE `flight_order` SET 
                    `booking_status`='booked',
                    `trans_id`='$_POST[TXNID]',
                    `trans_amount`='$_POST[TXNAMOUNT]',
                    `trans_status`='$_POST[STATUS]',
                    `trans_resp_msg`='$_POST[RESPMSG]'
                    WHERE `id`='$booking_fetch[booking_id]'";
      mysqli_query($con, $upd_query);
      
      // Redirect to flight payment status page
      redirect('flight_pay_status.php?order=' . $_POST['ORDERID']);
    }
    else {
      // For tour bookings
      $upd_query = "UPDATE `tour_order` SET 
                    `booking_status`='booked',
                    `trans_id`='$_POST[TXNID]',
                    `trans_amount`='$_POST[TXNAMOUNT]',
                    `trans_status`='$_POST[STATUS]',
                    `trans_resp_msg`='$_POST[RESPMSG]'
                    WHERE `booking_id`='$booking_fetch[booking_id]'";
      mysqli_query($con, $upd_query);
      
      // Redirect to tour payment status page
      redirect('tour_pay_status.php?order=' . $_POST['ORDERID']);
    }
  } 
  else {
    // Payment failed
    if ($booking_type == 'hotel') {
      $upd_query = "UPDATE `booking_order` SET 
                    `booking_status`='payment failed',
                    `trans_id`='$_POST[TXNID]',
                    `trans_amt`='$_POST[TXNAMOUNT]',
                    `trans_status`='$_POST[STATUS]',
                    `trans_resp_msg`='$_POST[RESPMSG]'
                    WHERE `booking_id`='$booking_fetch[booking_id]'";
      mysqli_query($con, $upd_query);
      
      // Redirect to hotel payment status page
      redirect('pay_status.php?order=' . $_POST['ORDERID']);
    } 
    elseif ($booking_type == 'flight') {
      // For flight bookings
      $upd_query = "UPDATE `flight_order` SET 
                    `booking_status`='payment failed',
                    `trans_id`='$_POST[TXNID]',
                    `trans_amount`='$_POST[TXNAMOUNT]',
                    `trans_status`='$_POST[STATUS]',
                    `trans_resp_msg`='$_POST[RESPMSG]'
                    WHERE `id`='$booking_fetch[booking_id]'";
      mysqli_query($con, $upd_query);
      
      // Redirect to flight payment status page
      redirect('flight_pay_status.php?order=' . $_POST['ORDERID']);
    }
    else {
      // For tour bookings
      $upd_query = "UPDATE `tour_order` SET 
                    `booking_status`='payment failed',
                    `trans_id`='$_POST[TXNID]',
                    `trans_amount`='$_POST[TXNAMOUNT]',
                    `trans_status`='$_POST[STATUS]',
                    `trans_resp_msg`='$_POST[RESPMSG]'
                    WHERE `booking_id`='$booking_fetch[booking_id]'";
      mysqli_query($con, $upd_query);
      
      // Redirect to tour payment status page
      redirect('tour_pay_status.php?order=' . $_POST['ORDERID']);
    }
  }
?>