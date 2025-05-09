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

  if(!isset($_GET['package_id'])) {
    redirect('tours.php');
  }

  $package_id = $_GET['package_id'];
  
  // Fetch tour package details
  $query = "SELECT * FROM tour_packages WHERE package_id=? AND status=1 AND removed=0";
  $values = [$package_id];
  $res = select($query, $values, 'i');
  
  if(mysqli_num_rows($res)==0){
    redirect('tours.php');
  }
  
  $tour_data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($settings_r['site_title']) ? $settings_r['site_title'] : 'Tour Booking'; ?> - BOOK TOUR</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">BOOK YOUR TOUR</h2>
    <div class="h-line bg-dark"></div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-12 mx-auto">
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5 class="mb-4">Tour Package Details</h5>
            <div class="row">
              <div class="col-md-4">
                <img src="images/tours/<?php echo htmlspecialchars($tour_data['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($tour_data['name']); ?>">
              </div>
              <div class="col-md-8">
                <h4><?php echo htmlspecialchars($tour_data['name']); ?></h4>
                <p><strong>Destination:</strong> <?php echo htmlspecialchars($tour_data['destination']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($tour_data['duration']); ?></p>
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Adult Price:</strong> $<?php echo htmlspecialchars($tour_data['price_per_adult']); ?></p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Child Price:</strong> $<?php echo htmlspecialchars($tour_data['price_per_adult']); ?></p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="border-top mt-4 pt-4">
              <form id="booking-form">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label fw-bold">Number of Adults</label>
                    <input type="number" name="adults" id="adults" class="form-control shadow-none" 
                      min="1" value="1" required oninput="calculateTotal()">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-bold">Number of Children</label>
                    <input type="number" name="children" id="children" class="form-control shadow-none" 
                      min="0" value="0" oninput="calculateTotal()">
                  </div>
                </div>
                
                <div class="mb-3">
                  <label class="form-label fw-bold">Preferred Date</label>
                  <input type="date" name="tour_date" id="tour_date" class="form-control shadow-none" 
                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                
                <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
                
                <div class="mb-4">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Total Amount:</h5>
                    </div>
                    <div class="col-md-6 text-end">
                      <h5 id="total-amount">$<?php echo $tour_data['price_per_adult']; ?></h5>
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
      let adults = parseInt(document.getElementById('adults').value) || 0;
      let children = parseInt(document.getElementById('children').value) || 0;
      let adultPrice = <?php echo $tour_data['price_per_adult']; ?>;
      let childPrice = <?php echo $tour_data['price_per_adult']; ?>;
      if (adults < 1) {
        adults = 1;
        document.getElementById('adults').value = 1;
      }
      
      if (children < 0) {
        children = 0;
        document.getElementById('children').value = 0;
      }
      
      let total = (adults * adultPrice) + (children * childPrice);
      document.getElementById('total-amount').innerHTML = '$' + total.toFixed(2);
    }
    
    document.getElementById('booking-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      let adults = parseInt(document.getElementById('adults').value) || 0;
      let children = parseInt(document.getElementById('children').value) || 0;
      let tour_date = document.getElementById('tour_date').value;
      let package_id = <?php echo $package_id; ?>;
      let adultPrice = <?php echo $tour_data['price_per_adult']; ?>;
      let childPrice = <?php echo $tour_data['price_per_adult']; ?>;
      
      let total = (adults * adultPrice) + (children * childPrice);
      
      // Validate input
      if (adults < 1) {
        showError("At least one adult is required");
        return;
      }
      
      if (!tour_date) {
        showError("Please select a preferred date for your tour");
        return;
      }
      
      // Validate date is in the future
      let selectedDate = new Date(tour_date);
      let tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      tomorrow.setHours(0, 0, 0, 0);
      
      if (selectedDate < tomorrow) {
        showError("Please select a future date for your tour");
        return;
      }
      
      // Add loading indicator
      let submitBtn = this.querySelector('button[type="submit"]');
      let originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Processing...';
      
      // Send data to create order in the database
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/tour_booking.php", true);
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
      
      xhr.send(`package_id=${package_id}&adults=${adults}&children=${children}&tour_date=${tour_date}&amount=${total.toFixed(2)}`);
    });
    
    function showError(message) {
      let errorDiv = document.getElementById('error-msg');
      errorDiv.textContent = message;
      errorDiv.classList.remove('d-none');
      setTimeout(() => {
        errorDiv.classList.add('d-none');
      }, 5000);
    }
    
    // Calculate total on page load
    window.onload = calculateTotal;
  </script>

  <?php require('inc/footer.php'); ?>
</body>
</html>