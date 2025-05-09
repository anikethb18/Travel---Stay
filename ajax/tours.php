<?php
  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');
  date_default_timezone_set("America/Chicago");
  
  session_start();
  
  // Function to generate tour package card HTML
  function generate_tour_card($tour_data) {
    $book_btn = "";
    if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
      $book_btn = "<a href='book_tour.php?package_id={$tour_data['package_id']}' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</a>";
    } else {
      $book_btn = "<button onclick=\"alert('Please login to book this tour!')\" class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";
    }
    
    return "
      <div class='card mb-4 border-0 shadow'>
        <div class='row g-0 p-3 align-items-center'>
          <div class='col-md-4'>
            
            <img src='images/tours/{$tour_data['image']}' class='img-fluid rounded' alt='{$tour_data['name']}'>
          </div>
          <div class='col-md-5'>
            <h5 class='mb-3'>{$tour_data['name']}</h5>
            <p class='mb-1'><strong>Destination:</strong> {$tour_data['destination']}</p>
            <p class='mb-1'><strong>Duration:</strong> {$tour_data['duration']}</p>
            <div class='mb-3'>
              <p class='mb-1'><strong>Description:</strong></p>
              <p>" . substr($tour_data['description'], 0, 100) . "...</p>
            </div>
          </div>
          <div class='col-md-3 text-center'>
            <h6 class='mb-2'>Price per adult: \${$tour_data['price_per_adult']}</h6>
            <h6 class='mb-3'>Price per child: \${$tour_data['price_per_adult']}</h6>
            $book_btn
            <a href='tour_details.php?id={$tour_data['package_id']}' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More details</a>
          </div>
        </div>
      </div>
    ";
  }
  
  if (isset($_GET['fetch_tours'])) {
    $destination = isset($_GET['destination']) ? trim($_GET['destination']) : '';
    $duration = isset($_GET['duration']) ? trim($_GET['duration']) : '';
    $max_price = isset($_GET['max_price']) ? trim($_GET['max_price']) : '';
    
    $count_tours = 0;
    $output = "";
    $where_conditions = [];
    $values = [];
    $types = "";
    
    // Common conditions for all queries
    $where_conditions[] = "status = 1";
    $where_conditions[] = "removed = 0";
    
    // Add filters if provided
    if (!empty($destination)) {
      $where_conditions[] = "destination LIKE ?";
      $values[] = "%$destination%";
      $types .= "s";
    }
    
    if (!empty($duration)) {
      $where_conditions[] = "duration LIKE ?";
      $values[] = "%$duration%";
      $types .= "s";
    }
    
    if (!empty($max_price) && is_numeric($max_price)) {
      $where_conditions[] = "price_per_adult <= ?";
      $values[] = $max_price;
      $types .= "d";
    }
    
    // Construct the WHERE clause
    $where_clause = implode(" AND ", $where_conditions);
    
    // Construct the full query
    $query = "SELECT * FROM tour_packages WHERE $where_clause ORDER BY price_per_adult ASC";
    
    // Execute the query with or without parameters
    if (!empty($values) && !empty($types)) {
      $tour_res = select($query, $values, $types);
    } else {
      // Direct query if no parameters are used
      $tour_res = mysqli_query($GLOBALS['con'], $query);
    }
    
    // Process results
    while ($tour_data = mysqli_fetch_assoc($tour_res)) {
      $output .= generate_tour_card($tour_data);
      $count_tours++;
    }
    
    if ($count_tours > 0) {
      echo $output;
    } else {
      if (!empty($destination) || !empty($duration) || !empty($max_price)) {
        echo "<h3 class='text-center text-danger'>No tours available for the selected criteria!</h3>";
      } else {
        echo "<h3 class='text-center text-danger'>No tours available at this time!</h3>";
      }
    }
  } else if (isset($_GET['fetch_all_tours'])) {
    // Fetch all available tours with basic conditions (using direct query)
    $query = "SELECT * FROM tour_packages WHERE status = 1 AND removed = 0 ORDER BY price_per_adult ASC";
    $tour_res = mysqli_query($GLOBALS['con'], $query);
    
    $count_tours = 0;
    $output = "";
    
    while ($tour_data = mysqli_fetch_assoc($tour_res)) {
      $output .= generate_tour_card($tour_data);
      $count_tours++;
    }
    
    if ($count_tours > 0) {
      echo $output;
    } else {
      echo "<h3 class='text-center text-danger'>No tours available at this time!</h3>";
    }
  }
?>