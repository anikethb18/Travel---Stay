<?php
  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');
  date_default_timezone_set("America/Chicago");
  
  session_start();
  
  // Function to generate flight card HTML
  function generate_flight_card($flight_data) {
    $book_btn = "";
    
    if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
      $book_btn = "<a href='book_flight.php?flight_id={$flight_data['flight_id']}' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</a>";
    }
    
    return "
      <div class='card mb-4 border-0 shadow'>
        <div class='row g-0 p-3 align-items-center'>
          <div class='col-md-8'>
            <h5 class='mb-2'>{$flight_data['airline']} </h5>
            <p class='mb-1'><strong>From:</strong> {$flight_data['departure_city']}</p>
            <p class='mb-1'><strong>To:</strong> {$flight_data['arrival_city']}</p>
            <p class='mb-1'><strong>Departure:</strong> {$flight_data['departure_time']}</p>
            <p class='mb-1'><strong>Arrival:</strong> {$flight_data['arrival_time']}</p>
            <p class='mb-1'><strong>Seats Available:</strong> {$flight_data['seats_available']}</p>
          </div>
          <div class='col-md-4 text-center mt-3 mt-md-0'>
            <h6 class='mb-3'>\${$flight_data['price']} per ticket</h6>
            $book_btn
            <a href='flight_details.php?id={$flight_data['flight_id']}' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More details</a>
          </div>
        </div>
      </div>
    ";
  }
  
  if (isset($_GET['fetch_flights'])) {
    $dep_date = isset($_GET['dep_date']) ? trim($_GET['dep_date']) : '';
    $dep_city = isset($_GET['dep_city']) ? trim($_GET['dep_city']) : '';
    $arr_city = isset($_GET['arr_city']) ? trim($_GET['arr_city']) : '';
    $airline = isset($_GET['airline']) ? trim($_GET['airline']) : '';
    
    $count_flights = 0;
    $output = "";
    $where_conditions = [];
    $values = [];
    $types = "";
    
    // Common conditions for all queries
    $where_conditions[] = "seats_available > 0";
    $where_conditions[] = "status = 1";
    $where_conditions[] = "removed = 0";
    
    // Add filters if provided
    if (!empty($dep_date)) {
      // Validate departure date
      $today_date = new DateTime(date("Y-m-d"));
      $departure_obj = new DateTime($dep_date);
      
      if ($departure_obj < $today_date) {
        echo "<h3 class='text-center text-danger'>Invalid Departure Date!</h3>";
        exit;
      }
      
      $where_conditions[] = "DATE(departure_time) = ?";
      $values[] = $dep_date;
      $types .= "s";
    }
    
    if (!empty($dep_city)) {
      $where_conditions[] = "departure_city LIKE ?";
      $values[] = "%$dep_city%";
      $types .= "s";
    }
    
    if (!empty($arr_city)) {
      $where_conditions[] = "arrival_city LIKE ?";
      $values[] = "%$arr_city%";
      $types .= "s";
    }
    
    if (!empty($airline)) {
      $where_conditions[] = "airline LIKE ?";
      $values[] = "%$airline%";
      $types .= "s";
    }
    
    // Construct the WHERE clause
    $where_clause = implode(" AND ", $where_conditions);
    
    // Construct the full query
    $query = "SELECT * FROM flights WHERE $where_clause ORDER BY departure_time ASC";
    
    // Execute the query with or without parameters
    if (!empty($values) && !empty($types)) {
      $flight_res = select($query, $values, $types);
    } else {
      // Direct query if no parameters are used
      $flight_res = mysqli_query($GLOBALS['con'], $query);
    }
    
    // Process results
    while ($flight_data = mysqli_fetch_assoc($flight_res)) {
      $output .= generate_flight_card($flight_data);
      $count_flights++;
    }
    
    if ($count_flights > 0) {
      echo $output;
    } else {
      if (!empty($dep_date) || !empty($dep_city) || !empty($arr_city) || !empty($airline)) {
        echo "<h3 class='text-center text-danger'>No flights available for the selected criteria!</h3>";
      } else {
        echo "<h3 class='text-center text-danger'>No flights available at this time!</h3>";
      }
    }
  } else if (isset($_GET['fetch_all_flights'])) {
    // Fetch all available flights with basic conditions (using direct query)
    $query = "SELECT * FROM flights WHERE seats_available > 0 AND status = 1 AND removed = 0 ORDER BY departure_time ASC";
    $flight_res = mysqli_query($GLOBALS['con'], $query);
    
    $count_flights = 0;
    $output = "";
    
    while ($flight_data = mysqli_fetch_assoc($flight_res)) {
      $output .= generate_flight_card($flight_data);
      $count_flights++;
    }
    
    if ($count_flights > 0) {
      echo $output;
    } else {
      echo "<h3 class='text-center text-danger'>No flights available at this time!</h3>";
    }
  }
?>