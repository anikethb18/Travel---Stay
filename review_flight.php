<?php    
require('../inc/connection.php');
  
if(isset($_POST['review_form'])) {
    $flight_booking_id = $_POST['flight_booking_id'];
    $flight_id = $_POST['flight_id'];
    $rating = $_POST['rating'];
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $user_id = $_SESSION['uId'];
    $datentime = date("Y-m-d H:i:s");
    
    $query = "INSERT INTO flight_rating_review (flight_booking_id, flight_id, user_id, rating, review, seen, datentime)
              VALUES (?, ?, ?, ?, ?, 0, ?)";
    
    $result = select($query, [$flight_booking_id, $flight_id, $user_id, $rating, $review, $datentime], 'iiisss');
    
    if($result) {
        echo 1; // Success
    } else {
        echo 0; // Failure
    }
}
?>