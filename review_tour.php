<?php
    require('../inc/connection.php');
    
    if(isset($_POST['review_form'])) {
        $tour_booking_id = $_POST['booking_id'];
        $package_id = $_POST['package_id'];
        $rating = $_POST['rating'];
        $review = mysqli_real_escape_string($conn, $_POST['review']);
        $user_id = $_SESSION['uId'];
        $datentime = date("Y-m-d H:i:s");
        
        // Get travel_id and travel_agency from the package
        $package_query = "SELECT travel_id, travel_agency FROM tour_packages WHERE id = ?";
        $package_result = select($package_query, [$package_id], 'i');
        
        if($package_result) {
            $travel_id = $package_result[0]['travel_id'];
            $travel_agency = $package_result[0]['travel_agency'];
            
            $query = "INSERT INTO tour_rating_review (tour_booking_id, package_id, user_id, rating, review, seen, datentime, travel_id, travel_agency)
                      VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)";

            $result = select($query, [$tour_booking_id, $package_id, $user_id, $rating, $review, $datentime, $travel_id, $travel_agency], 'iiisssis');
            
            if($result) {
                echo 1; // Success
            } else {
                echo 0; // Failure
            }
        } else {
            echo 0; // Failed to fetch package details
        }
    }
?>