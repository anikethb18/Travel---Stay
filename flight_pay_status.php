<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - FLIGHT BOOKING STATUS</title>
</head>
<body class="bg-light">
  <?php require('inc/header.php'); ?>
  
  <div class="container">
    <div class="row">
      <div class="col-12 my-5 mb-3 px-4">
        <h2 class="fw-bold">FLIGHT PAYMENT STATUS</h2>
      </div>
      
      <?php
        // Use $_GET directly instead of calling filteration() again
        // since it's already declared in the included files
        $order_id = isset($_GET['order']) ? $_GET['order'] : '';
        
        if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
          redirect('index.php');
        }
        
        // Modified query to use only flight_order table
        $booking_q = "SELECT * FROM `flight_order` 
          WHERE `order_id`=? AND `user_id`=? AND `booking_status`!=?";
          
        $booking_res = select($booking_q,[$order_id,$_SESSION['uId'],'pending'],'sis');
        
        if(mysqli_num_rows($booking_res)==0){
          redirect('index.php');
        }
        
        $booking_fetch = mysqli_fetch_assoc($booking_res);
        
        if($booking_fetch['trans_status']=="TXN_SUCCESS")
        {
          echo<<<data
            <div class="col-12 px-4">
              <p class="fw-bold alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                Payment done! Flight booking successful.
                <br><br>
                <a href='flight_bookings.php'>Go to Flight Bookings</a>
              </p>
            </div>
          data;
        }
        else
        {
          echo<<<data
            <div class="col-12 px-4">
              <p class="fw-bold alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Payment failed! $booking_fetch[trans_resp_msg]
                <br><br>
                <a href='flight_bookings.php'>Go to Flight Bookings</a>
              </p>
            </div>
          data;
        }
      ?>
    </div>
  </div>
  
  <?php require('inc/footer.php'); ?>
</body>
</html>