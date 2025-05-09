<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - FLIGHT DETAILS</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <?php 
    if(!isset($_GET['id'])){
      redirect('flights.php');
    }

    $data = filteration($_GET);

    $flight_res = select("SELECT * FROM flights WHERE flight_id=? AND status=? AND removed=?",[$data['flight_id'],1,0],'iii');

    if(mysqli_num_rows($flight_res)==0){
      redirect('flights.php');
    }

    $flight_data = mysqli_fetch_assoc($flight_res);
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold"><?php echo $flight_data['airline_name'] ?> - <?php echo $flight_data['flight_number'] ?></h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="flights.php" class="text-secondary text-decoration-none">FLIGHTS</a>
        </div>
      </div>

      <div class="col-lg-7 col-md-12 px-4">
        <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3 d-flex justify-content-center align-items-center">
                <?php 
                  $airline_img = AIRLINES_IMG_PATH.$flight_data['airline_image'];
                  echo "<img src='$airline_img' class='img-fluid' alt='airline logo'>";
                ?>
              </div>
              <div class="col-md-9">
                <h4 class="mb-3">Flight Details</h4>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <h5><?php echo $flight_data['departure_city'] ?></h5>
                    <p><?php echo $flight_data['departure_time'] ?></p>
                    <p><?php echo $flight_data['departure_airport'] ?></p>
                  </div>
                  <div class="col-md-6">
                    <h5><?php echo $flight_data['arrival_city'] ?></h5>
                    <p><?php echo $flight_data['arrival_time'] ?></p>
                    <p><?php echo $flight_data['arrival_airport'] ?></p>
                  </div>
                </div>
                <p><strong>Duration:</strong> <?php echo $flight_data['duration'] ?></p>
                <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($flight_data['departure_date'])) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5 col-md-12 px-4">
        <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body">
            <?php 

              echo<<<price
                <h4>$$flight_data[price_economy] - Economy</h4>
                <h5>$$flight_data[price_business] - Business</h5>
                <h5>$$flight_data[price_first] - First Class</h5>
              price;

              $rating_q = "SELECT AVG(rating) AS avg_rating FROM flight_rating_review
                WHERE flight_id='$flight_data[id]' ORDER BY sr_no DESC LIMIT 20";
  
              $rating_res = mysqli_query($con,$rating_q);
              $rating_fetch = mysqli_fetch_assoc($rating_res);
    
              $rating_data = "";
    
              if($rating_fetch['avg_rating']!=NULL)
              {
                for($i=0; $i < $rating_fetch['avg_rating']; $i++){
                  $rating_data .="<i class='bi bi-star-fill text-warning'></i> ";
                }
              }

              echo<<<rating
                <div class="mb-3">
                  $rating_data
                </div>
              rating;

              $amen_q = mysqli_query($con,"SELECT a.name FROM amenities a 
                INNER JOIN flight_amenities fa ON a.id = fa.amenity_id 
                WHERE fa.flight_id = '$flight_data[id]'");

              $amenities_data = "";
              while($amen_row = mysqli_fetch_assoc($amen_q)){
                $amenities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $amen_row[name]
                </span>";
              }

              echo<<<amenities
                <div class="mb-3">
                  <h6 class="mb-1">Amenities</h6>
                  $amenities_data
                </div>
              amenities;

              echo<<<details
                <div class="mb-3">
                  <h6 class="mb-1">Flight Information</h6>
                  <span class='badge rounded-pill bg-light text-dark text-wrap'>
                    Flight Type: $flight_data[flight_type]
                  </span>
                  <span class='badge rounded-pill bg-light text-dark text-wrap'>
                    Seats Available: $flight_data[seats_available]
                  </span>
                </div>
              details;

              if(!$settings_r['shutdown']){
                $login=0;
                if(isset($_SESSION['login']) && $_SESSION['login']==true){
                  $login=1;
                }
                echo<<<book
                  <button onclick='checkLoginToBookFlight($login,$flight_data[id])' class="btn w-100 text-white custom-bg shadow-none mb-1">Book Now</button>
                book;
              }

            ?>
          </div>
        </div>
      </div>

      <div class="col-12 mt-4 px-4">
        <div class="mb-5">
          <h5>Additional Information</h5>
          <p>
            <?php echo $flight_data['description'] ?>
          </p>
        </div>

        <div>
          <h5 class="mb-3">Reviews & Ratings</h5>

          <?php
            $review_q = "SELECT frr.*,uc.name AS uname, uc.profile, f.airline_name, f.flight_number FROM flight_rating_review frr
              INNER JOIN user_cred uc ON frr.user_id = uc.id
              INNER JOIN flights f ON frr.flight_id = f.id
              WHERE frr.flight_id = '$flight_data[id]'
              ORDER BY sr_no DESC LIMIT 15";

            $review_res = mysqli_query($con,$review_q);
            $img_path = USERS_IMG_PATH;

            if(mysqli_num_rows($review_res)==0){
              echo 'No reviews yet!';
            }
            else
            {
              while($row = mysqli_fetch_assoc($review_res))
              {
                $stars = "<i class='bi bi-star-fill text-warning'></i> ";
                for($i=1; $i<$row['rating']; $i++){
                  $stars .= " <i class='bi bi-star-fill text-warning'></i>";
                }

                echo<<<reviews
                  <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                      <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                      <h6 class="m-0 ms-2">$row[uname]</h6>
                    </div>
                    <p class="mb-1">
                      $row[review]
                    </p>
                    <div>
                      $stars
                    </div>
                  </div>
                reviews;
              }
            }
          ?>

        </div>
      </div>

    </div>
  </div>

  <?php require('inc/footer.php'); ?>

  <script>
    function checkLoginToBookFlight(loginStatus, flightId) {
      if(loginStatus) {
        window.location.href = 'book_flight.php?id='+flightId;
      } else {
        alert('error', 'Please login to book flight!');
      }
    }
  </script>

</body>
</html>