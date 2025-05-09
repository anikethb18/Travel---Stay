<?php 
  require('../inc/connection.php');
  if(isset($_POST['review_form'])) {
    $booking_id = $_POST['booking_id'];
    $room_id = $_POST['room_id'];
    $rating = $_POST['rating'];
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $user_id = $_SESSION['uId'];
    $datentime = date("Y-m-d H:i:s");

    $query = "INSERT INTO rate_review (booking_id, room_id, user_id, rating, review, seen, datentime) 
              VALUES (?, ?, ?, ?, ?, 0, ?)";
    
    $result = select($query, [$booking_id, $room_id, $user_id, $rating, $review, $datentime], 'iiis');

    if($result) {
      echo 1; // Success
    } else {
      echo 0; // Failure
    }
  }
?>
