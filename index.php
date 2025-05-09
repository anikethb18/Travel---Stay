<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - HOME</title>
  <style>
    .hero-section {
      background-image: url('images/carousel/2.png');
      background-size: cover;
      background-position: center;
      height: 60vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      position: relative;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .hero-content {
      position: relative;
      z-index: 1;
      padding: 2rem;
    }

    .hero-title {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .hero-subtitle {
      font-size: 1.5rem;
      margin-bottom: 2rem;
    }

    .cta-button {
      background-color: var(--teal);
      color: white;
      border: none;
      padding: 1rem 2rem;
      font-size: 1.2rem;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .cta-button:hover {
      background-color: #007bff;
      transform: translateY(-5px);
    }

    .service-card {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      height: 300px;
    }

    .service-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .service-card:hover img {
      transform: scale(1.1);
    }

    .service-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.7);
      padding: 1.5rem;
      color: white;
      transform: translateY(100%);
      transition: transform 0.3s ease;
    }

    .service-card:hover .service-overlay {
      transform: translateY(0);
    }

    .service-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .experience-section {
      background-color: #f8f9fa;
      padding: 5rem 0;
    }

    .stat-item {
      text-align: center;
      padding: 2rem;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
    }

    .stat-item:hover {
      transform: translateY(-10px);
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: bold;
      color: var(--teal);
      margin-bottom: 0.5rem;
    }

    .stat-text {
      font-size: 1.2rem;
      color: #6c757d;
    }

    .testimonial-section {
      background-color: var(--teal);
      color: white;
      padding: 5rem 0;
    }

    .footer {
      background-color: #343a40;
      color: white;
      padding: 3rem 0;
    }

    .social-icons a {
      color: white;
      font-size: 1.5rem;
      margin-right: 1rem;
    }

    .h-font {
      font-family: 'Merienda', cursive;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h1 class="hero-title">Your Dream Stay Awaits</h1>
      <p class="hero-subtitle">Exceptional accommodations and experiences that create lasting memories.</p>
      <button class="cta-button">Explore Our Services</button>
    </div>
  </section>

  <!-- Our Services Section -->
  <section class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold fs-1 h-font">Our Premium Services</h2>
      <p class="text-muted fs-5">Everything you need for your perfect stay, all in one place</p>
    </div>
    <div class="row g-4">
      <!-- Accommodation Service -->
      <div class="col-md-4">
        <div class="service-card">
          <img src="images/services/service1.jpg" alt="Luxury Rooms">
          <div class="service-overlay">
            <h3 class="service-title">Luxury Rooms</h3>
            <p class="service-text">Experience comfort like never before in our specially designed luxury rooms.</p>
          </div>
        </div>
      </div>
      <!-- Flight Bookings -->
      <div class="col-md-4">
        <div class="service-card">
          <img src="images/services/service4.jpg" alt="Flight Bookings">
          <div class="service-overlay">
            <h3 class="service-title">Flight Bookings</h3>
            <p class="service-text">Book your flights easily with our reliable and affordable flight booking services.</p>
         </div>
        </div>
      </div>

      <!-- Tour Bookings -->
      <div class="col-md-4">
        <div class="service-card">
          <img src="images/services/service5.jpg" alt="Tour Bookings">
          <div class="service-overlay">
            <h3 class="service-title">Tour Bookings</h3>
            <p class="service-text">Explore curated tours and unforgettable experiences tailored just for you.</p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="experience-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold fs-1 h-font">Why Choose Us</h2>
        <p class="text-muted fs-5">Stay with confidence knowing you're in good hands</p>
      </div>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="stat-item">
            <div class="stat-number">10+</div>
            <div class="stat-text">Years of Service</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-item">
            <div class="stat-number">200+</div>
            <div class="stat-text">Premium Rooms</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-item">
            <div class="stat-number">30K+</div>
            <div class="stat-text">Happy Guests</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-item">
            <div class="stat-number">24/7</div>
            <div class="stat-text">Customer Support</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  

  <!-- Password reset modal and code -->
  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="recovery-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-shield-lock fs-3 me-2"></i> Set up New Password
            </h5>
          </div>
          <div class="modal-body">
            <div class="mb-4">
              <label class="form-label">New Password</label>
              <input type="password" name="pass" required class="form-control shadow-none">
              <input type="hidden" name="email">
              <input type="hidden" name="token">
            </div>
            <div class="mb-2 text-end">
              <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">CANCEL</button>
              <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require('inc/footer.php'); ?>

  <?php
  
    if(isset($_GET['account_recovery']))
    {
      $data = filteration($_GET);

      $t_date = date("Y-m-d");

      $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
        [$data['email'],$data['token'],$t_date],'sss');

      if(mysqli_num_rows($query)==1)
      {
        echo<<<showModal
          <script>
            var myModal = document.getElementById('recoveryModal');

            myModal.querySelector("input[name='email']").value = '$data[email]';
            myModal.querySelector("input[name='token']").value = '$data[token]';

            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
            modal.show();
          </script>
        showModal;
      }
      else{
        alert("error","Invalid or Expired Link !");
      }
    }
  ?>
  
  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

  <script>
    // recover account
    let recovery_form = document.getElementById('recovery-form');

    recovery_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('email',recovery_form.elements['email'].value);
      data.append('token',recovery_form.elements['token'].value);
      data.append('pass',recovery_form.elements['pass'].value);
      data.append('recover_user','');

      var myModal = document.getElementById('recoveryModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'failed'){
          alert('error',"Account reset failed!");
        }
        else{
          alert('success',"Account Reset Successful !");
          recovery_form.reset();
        }
      }

      xhr.send(data);
    });
  </script>

</body>
</html>