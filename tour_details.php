<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - TOUR DETAILS</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <?php 
    if(!isset($_GET['id'])){
      redirect('tours.php');
    }

    $data = filteration($_GET);

    $tour_res = select("SELECT * FROM tour_packages WHERE id=? AND status=? AND removed=?",[$data['id'],1,0],'iii');

    if(mysqli_num_rows($tour_res)==0){
      redirect('tours.php');
    }

    $tour_data = mysqli_fetch_assoc($tour_res);
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold"><?php echo $tour_data['package_name'] ?></h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="tours.php" class="text-secondary text-decoration-none">TOURS</a>
        </div>
      </div>

      <div class="col-lg-7 col-md-12 px-4">
        <div id="tourCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php 

              $tour_img = TOURS_IMG_PATH."thumbnail.jpg";
              $img_q = mysqli_query($con,"SELECT * FROM tour_images 
                WHERE tour_id='$tour_data[id]'");

              if(mysqli_num_rows($img_q)>0)
              {
                $active_class = 'active';

                while($img_res = mysqli_fetch_assoc($img_q))
                {
                  echo"
                    <div class='carousel-item $active_class'>
                      <img src='".TOURS_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                    </div>
                  ";
                  $active_class=''; 
                }

              }
              else{
                echo"<div class='carousel-item active'>
                  <img src='$tour_img' class='d-block w-100'>
                </div>";
              }

            ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>

      <div class="col-lg-5 col-md-12 px-4">
        <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body">
            <?php 

              echo<<<price
                <h4>$$tour_data[price_per_adult] per adult</h4>
                <h5>$$tour_data[price_per_child] per child</h5>
              price;

              $rating_q = "SELECT AVG(rating) AS avg_rating FROM tour_rating_review
                WHERE package_id='$tour_data[id]' ORDER BY sr_no DESC LIMIT 20";
  
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

              $act_q = mysqli_query($con,"SELECT a.name FROM activities a 
                INNER JOIN tour_activities tact ON a.id = tact.activity_id 
                WHERE tact.tour_id = '$tour_data[id]'");

              $activities_data = "";
              while($act_row = mysqli_fetch_assoc($act_q)){
                $activities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $act_row[name]
                </span>";
              }

              echo<<<activities
                <div class="mb-3">
                  <h6 class="mb-1">Activities</h6>
                  $activities_data
                </div>
              activities;

              $inc_q = mysqli_query($con,"SELECT i.name FROM inclusions i 
                INNER JOIN tour_inclusions tinc ON i.id = tinc.inclusion_id 
                WHERE tinc.tour_id = '$tour_data[id]'");

              $inclusions_data = "";
              while($inc_row = mysqli_fetch_assoc($inc_q)){
                $inclusions_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $inc_row[name]
                </span>";
              }
              
              echo<<<inclusions
                <div class="mb-3">
                  <h6 class="mb-1">Inclusions</h6>
                  $inclusions_data
                </div>
              inclusions;

              echo<<<details
                <div class="mb-3">
                  <h6 class="mb-1">Tour Details</h6>
                  <span class='badge rounded-pill bg-light text-dark text-wrap'>
                    Destination: $tour_data[destination]
                  </span>
                  <span class='badge rounded-pill bg-light text-dark text-wrap'>
                    Duration: $tour_data[duration]
                  </span>
                </div>
              details;

              if(!$settings_r['shutdown']){
                $login=0;
                if(isset($_SESSION['login']) && $_SESSION['login']==true){
                  $login=1;
                }
                echo<<<book
                  <button onclick='checkLoginToBookTour($login,$tour_data[id])' class="btn w-100 text-white custom-bg shadow-none mb-1">Book Now</button>
                book;
              }

            ?>
          </div>
        </div>
      </div>

      <div class="col-12 mt-4 px-4">
        <div class="mb-5">
          <h5>Description</h5>
          <p>
            <?php echo $tour_data['description'] ?>
          </p>
        </div>

        <div>
          <h5 class="mb-3">Reviews & Ratings</h5>

          <?php
            $review_q = "SELECT trr.*,uc.name AS uname, uc.profile, tp.package_name AS tname FROM tour_rating_review trr
              INNER JOIN user_cred uc ON trr.user_id = uc.id
              INNER JOIN tour_packages tp ON trr.package_id = tp.id
              WHERE trr.package_id = '$tour_data[id]'
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
    function checkLoginToBookTour(loginStatus, tourId) {
      if(loginStatus) {
        window.location.href = 'book_tour.php?id='+tourId;
      } else {
        alert('error', 'Please login to book tour!');
      }
    }
  </script>

</body>
</html>