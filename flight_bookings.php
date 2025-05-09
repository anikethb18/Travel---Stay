<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - FLIGHT BOOKINGS</title>
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
        <h2 class="fw-bold">FLIGHT BOOKINGS</h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">FLIGHT BOOKINGS</a>
        </div>
      </div>

      <?php 
        $query = "SELECT fo.*, fbd.* FROM `flight_order` fo
                  INNER JOIN `flight_booking_details` fbd ON fo.id = fbd.flight_booking_id
                  WHERE ((fo.booking_status='booked') 
                  OR (fo.booking_status='cancelled')
                  OR (fo.booking_status='payment failed')) 
                  AND (fo.user_id=?) 
                  ORDER BY fo.id DESC";

        $result = select($query,[$_SESSION['uId']],'i');

        while($data = mysqli_fetch_assoc($result))
        {
          $date = date("d-m-Y",strtotime($data['datentime']));
          $departure = date("d-m-Y h:i A",strtotime($data['departure_time']));
          $arrival = date("d-m-Y h:i A",strtotime($data['arrival_time']));

          $status_bg = "";
          $btn = "";
          $note = ""; // Added variable for cancellation note
           
          if ($data['booking_status'] == 'booked') {
            $status_bg = "bg-success";
            $btn = "<a href='generate_flight_pdf.php?gen_pdf&id=$data[id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
            $btn .= "<button type='button' onclick='review_flight($data[id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
            $note = "<p class='text-danger mt-2 mb-0'><small><i class='bi bi-exclamation-circle'></i> Cancellation not available</small></p>"; // Added cancellation notice
          } 
          else if ($data['booking_status'] == 'cancelled') {
            $status_bg = "bg-danger";
            if ($data['refund'] == 0) {
              $btn = "<span class='badge bg-primary'>Refund in process!</span>";
            } else {
              $btn = "<a href='generate_flight_pdf.php?gen_pdf&id=$data[id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
            }
          } 
          else {
            $status_bg = "bg-warning";
            $btn = "<a href='generate_flight_pdf.php?gen_pdf&id=$data[id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
          }

          echo<<<bookings
            <div class='col-md-4 px-4 mb-4'>
              <div class='bg-white p-3 rounded shadow-sm'>
                <h5 class='fw-bold'>$data[airline]</h5>
                <p>
                  <b>From:</b> $data[departure_city]
                  <b>To:</b> $data[arrival_city]
                </p>
                <p>
                  <b>Departure:</b> $departure <br>
                  <b>Arrival:</b> $arrival
                </p>
                <p>
                  <b>Number of Tickets:</b> $data[tickets] <br>
                  <b>Price per Ticket:</b> $$data[price_per_adult] <br>
                  <b>Total Amount:</b> $$data[total_pay]
                </p>
                <p>
                  <b>Order ID:</b> $data[order_id] <br>
                  <b>Booking Date:</b> $date
                </p>
                <p>
                  <span class='badge $status_bg'>$data[booking_status]</span>
                </p>
                $btn
                $note
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
              <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Rate & Review Flight
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
    if(isset($_GET['cancel_status'])){
      alert('success','Flight Booking Cancelled!');
    }  
    else if(isset($_GET['review_status'])){
      alert('success','Thank you for rating & review!');
    }  
  ?>

  <?php require('inc/footer.php'); ?>

  <script>
    function cancel_flight_booking(id) {
      if(confirm('Are you sure to cancel flight booking?')) {        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/cancel_flight_booking.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
          if(this.responseText == 1) {
            window.location.href = "flight_bookings.php?cancel_status=true";
          } else {
            alert('error', 'Cancellation Failed!');
          }
        }

        xhr.send('cancel_booking&id=' + id);
      }
    }

    let review_form = document.getElementById('review-form');

    function review_flight(bid) {
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
      xhr.open("POST", "ajax/review_flight.php", true);

      xhr.onload = function() {
        if(this.responseText == 1) {
          window.location.href = 'flight_bookings.php?review_status=true';
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