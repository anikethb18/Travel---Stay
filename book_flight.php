<?php
  // First check if session is already active before starting
  

  require('inc/links.php');
  
  // Conditional includes to prevent function redeclaration
  if (!function_exists('filteration')) {
    require('admin/inc/db_config.php');
  }
  
  if (!function_exists('adminLogin')) {
    require('admin/inc/essentials.php');
  }

  if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    redirect('index.php');
  }

  if(!isset($_GET['flight_id'])) {
    redirect('flights.php');
  }

  $flight_id = $_GET['flight_id'];
  
  // Fetch flight details
  $query = "SELECT * FROM flights WHERE flight_id=? AND seats_available>0 AND status=1 AND removed=0";
  $values = [$flight_id];
  $res = select($query, $values, 's');
  
  if(mysqli_num_rows($res)==0){
    redirect('flights.php');
  }
  
  $flight_data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($settings_r['site_title']) ? $settings_r['site_title'] : 'Airline Booking'; ?> - BOOK FLIGHT</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">BOOK YOUR FLIGHT</h2>
    <div class="h-line bg-dark"></div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-12 mx-auto">
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5 class="mb-4">Flight Details</h5>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Airline:</strong> <?php echo htmlspecialchars($flight_data['airline']); ?></p>
                <p><strong>From:</strong> <?php echo htmlspecialchars($flight_data['departure_city']); ?></p>
                <p><strong>To:</strong> <?php echo htmlspecialchars($flight_data['arrival_city']); ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Departure:</strong> <?php echo htmlspecialchars($flight_data['departure_time']); ?></p>
                <p><strong>Arrival:</strong> <?php echo htmlspecialchars($flight_data['arrival_time']); ?></p>
                <p><strong>Price per ticket:</strong> $<?php echo htmlspecialchars($flight_data['price']); ?></p>
              </div>
            </div>
            
            <div class="border-top mt-4 pt-4">
              <form id="booking-form">
                <div class="mb-3">
                  <label class="form-label fw-bold">Select Number of Tickets</label>
                  <input type="number" name="tickets" id="tickets" class="form-control shadow-none" 
                    min="1" max="<?php echo $flight_data['seats_available']; ?>" value="1" required
                    oninput="calculateTotal()">
                  <div class="form-text">Maximum <?php echo $flight_data['seats_available']; ?> tickets available</div>
                </div>
                
                <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
                <input type="hidden" name="price" value="<?php echo $flight_data['price']; ?>">
                
                <div class="mb-4">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Total Amount:</h5>
                    </div>
                    <div class="col-md-6 text-end">
                      <h5 id="total-amount">$<?php echo $flight_data['price']; ?></h5>
                    </div>
                  </div>
                </div>
                
                <button type="submit" class="btn text-white custom-bg shadow-none w-100">Proceed to Payment</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="error-msg" class="alert alert-danger mt-3 d-none"></div>

  <script>
    function calculateTotal() {
      let tickets = document.getElementById('tickets').value;
      let price = <?php echo $flight_data['price']; ?>;
      let total = tickets * price;
      
      document.getElementById('total-amount').innerHTML = '$' + total;
    }
    
    document.getElementById('booking-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      let tickets = document.getElementById('tickets').value;
      let flight_id = <?php echo $flight_id; ?>;
      let price = <?php echo $flight_data['price']; ?>;
      let total = tickets * price;
      
      // Validate input
      if (tickets <= 0 || tickets > <?php echo $flight_data['seats_available']; ?>) {
        showError("Please select a valid number of tickets");
        return;
      }
      
      // Add loading indicator
      let submitBtn = this.querySelector('button[type="submit"]');
      let originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Processing...';
      
      // Send data to create order in the database
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/flight_booking.php", true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      
      xhr.onload = function() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Debug response
        console.log("Server response:", this.responseText);
        
        let response = this.responseText.trim();
        
        if(response === 'success') {
          window.location.href = "mock_payment.php";
        }
        else {
          showError('Error: ' + response);
        }
      }
      
      xhr.onerror = function() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        showError('Network error occurred. Please try again.');
      }
      
      xhr.send(`flight_id=${flight_id}&tickets=${tickets}&amount=${total}`);
    });
    
    function showError(message) {
      let errorDiv = document.getElementById('error-msg');
      errorDiv.textContent = message;
      errorDiv.classList.remove('d-none');
      setTimeout(() => {
        errorDiv.classList.add('d-none');
      }, 5000);
    }
  </script>

  <?php require('inc/footer.php'); ?>
</body>
</html>