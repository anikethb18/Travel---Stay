<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - FLIGHTS</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">AVAILABLE FLIGHTS</h2>
    <div class="h-line bg-dark"></div>
  </div>

  <div class="container-fluid">
    <div class="row">

      <!-- Filters Section -->
      <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
          <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2">FILTERS</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flightFilters" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="flightFilters">

              <!-- Departure Date -->
              <div class="border bg-light p-3 rounded mb-3">
                <label class="form-label">Departure Date</label>
                <input type="date" id="dep_date" class="form-control shadow-none" onchange="fetch_flights()">
              </div>

              <!-- Cities -->
              <div class="border bg-light p-3 rounded mb-3">
                <label class="form-label">From (Departure City)</label>
                <input type="text" id="dep_city" class="form-control shadow-none" oninput="fetch_flights()">
                <label class="form-label mt-3">To (Arrival City)</label>
                <input type="text" id="arr_city" class="form-control shadow-none" oninput="fetch_flights()">
              </div>

              <!-- Airline -->
              <div class="border bg-light p-3 rounded mb-3">
                <label class="form-label">Airline</label>
                <input type="text" id="airline" class="form-control shadow-none" oninput="fetch_flights()">
              </div>

            </div>
          </div>
        </nav>
      </div>

      <!-- Flights Display -->
      <div class="col-lg-9 col-md-12 px-4" id="flights-data">
      </div>

    </div>
  </div>

  <script>
    function fetch_flights() {
      let dep_date = document.getElementById('dep_date').value;
      let dep_city = document.getElementById('dep_city').value;
      let arr_city = document.getElementById('arr_city').value;
      let airline = document.getElementById('airline').value;

      let xhr = new XMLHttpRequest();
      xhr.open("GET", `ajax/flights.php?fetch_flights&dep_date=${dep_date}&dep_city=${dep_city}&arr_city=${arr_city}&airline=${airline}`, true);

      xhr.onprogress = function(){
        document.getElementById('flights-data').innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>`;
      }

      xhr.onload = function(){
        document.getElementById('flights-data').innerHTML = this.responseText;
      }

      xhr.send();
    }

    window.onload = fetch_flights;
  </script>

  <?php require('inc/footer.php'); ?>

</body>
</html>
