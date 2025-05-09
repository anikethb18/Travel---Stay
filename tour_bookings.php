<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - TOUR BOOKINGS</title>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('index.php');
    }
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 px-4">
        <h2 class="fw-bold">TOUR BOOKINGS</h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">TOUR BOOKINGS</a>
        </div>
      </div>

      <div class="col-12 mb-4 px-4">
        <div class="alert alert-info">
          <i class="bi bi-exclamation-circle-fill me-2"></i>
          <strong>Note:</strong> Cancellation is not available for tour bookings. Please contact customer support for any inquiries.
        </div>
      </div>

      <?php 
        $query = "SELECT tord.*, tbd.* FROM `tour_order` tord
            INNER JOIN `tour_booking_details` tbd ON tord.booking_id = tbd.tour_booking_id
            WHERE ((tord.booking_status='booked') 
            OR (tord.booking_status='cancelled')
            OR (tord.booking_status='payment failed')) 
            AND (tord.user_id=?) 
            ORDER BY tord.booking_id DESC";

        $result = select($query,[$_SESSION['uId']],'i');

        while($data = mysqli_fetch_assoc($result))
        {
          $date = date("d-m-Y",strtotime($data['datentime']));
          $tour_date = date("d-m-Y",strtotime($data['tour_date']));

          $status_bg = "";
          $btn = ""; 
          
          if ($data['booking_status'] == 'booked') {
            $status_bg = "bg-success";
            $btn = "<a href='generate_tour_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
            $btn .= "<button type='button' onclick='review_tour($data[booking_id], $data[package_id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
          } 
          else if ($data['booking_status'] == 'cancelled') {
            $status_bg = "bg-danger";
            if ($data['refund'] == 0) {
              $btn = "<span class='badge bg-primary'>Refund in process!</span>";
            } else {
              $btn = "<a href='generate_tour_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
            }
          } 
          else {
            $status_bg = "bg-warning";
            $btn = "<a href='generate_tour_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
          }

          echo<<<bookings
            <div class='col-md-4 px-4 mb-4'>
              <div class='bg-white p-3 rounded shadow-sm'>
                <h5 class='fw-bold'>$data[package_name]</h5>
                <p>
                  <b>Destination:</b> $data[destination]<br>
                  <b>Duration:</b> $data[duration]
                </p>
                <p>
                  <b>Tour Date:</b> $tour_date<br>
                  <b>Adults:</b> $data[adults]<br>
                  <b>Children:</b> $data[children]
                </p>
                <p>
                  <b>Adult Price:</b> $$data[price_per_adult]<br>
                  <b>Child Price:</b> $$data[price_per_child]<br>
                  <b>Total Amount:</b> $$data[total_pay]
                </p>
                <p>
                  <b>Order ID:</b> $data[order_id]<br>
                  <b>Booking Date:</b> $date
                </p>
                <p>
                  <span class='badge $status_bg'>$data[booking_status]</span>
                </p>
                $btn
              </div>
            </div>
          bookings;
        }
      ?>
    </div>
  </div>

  <!-- Rating & Review Modal -->
  <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="review-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Rate & Review Tour
            </h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Rating</label>
              <select class="form-select shadow-none" name="rating" required>
                <option value="5">Excellent</option>
                <option value="4">Good</option>
                <option value="3">Ok</option>
                <option value="2">Poor</option>
                <option value="1">Bad</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">Review</label>
              <textarea name="review" rows="3" required class="form-control shadow-none"></textarea>
            </div>
            
            <input type="hidden" name="booking_id">

            <div class="text-end">
              <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php 
    if(isset($_GET['review_status'])){
      alert('success','Thank you for rating & review!');
    }  
  ?>

  <?php require('inc/footer.php'); ?>

  <script>
    let review_form = document.getElementById('review-form');

    function review_tour(bid) {
      review_form.elements['booking_id'].value = bid;
    }

    review_form.addEventListener('submit', function(e) {
      e.preventDefault();

      let data = new FormData();
      data.append('review_form', '');
      data.append('rating', review_form.elements['rating'].value);
      data.append('review', review_form.elements['review'].value);
      data.append('booking_id', review_form.elements['booking_id'].value);

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/review_tour.php", true);

      xhr.onload = function() {
        if(this.responseText == 1) {
          window.location.href = 'tour_bookings.php?review_status=true';
        } else {
          var myModal = document.getElementById('reviewModal');
          var modal = bootstrap.Modal.getInstance(myModal);
          modal.hide();
        }
      }

      xhr.send(data);
    })
  </script>

</body>
</html>