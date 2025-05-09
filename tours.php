<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - TOURS</title>
</head>
<body class="bg-light">
  
  <?php require('inc/header.php'); ?>
  
  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">AVAILABLE TOURS</h2>
    <div class="h-line bg-dark"></div>
  </div>
  
  <div class="container-fluid">
    <div class="row">
      
      <!-- Filters Section -->
      <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
          <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2">FILTERS</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#tourFilters" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="tourFilters">
              
              <!-- Destination -->
              <div class="border bg-light p-3 rounded mb-3">
                <label class="form-label">Destination</label>
                <input type="text" id="destination" class="form-control shadow-none" oninput="fetch_tours()">
              </div>
              
              <!-- Duration -->
              <div class="border bg-light p-3 rounded mb-3">
                <label class="form-label">Duration</label>
                <input type="text" id="duration" class="form-control shadow-none" oninput="fetch_tours()">
              </div>
              
              <!-- Price Range -->
              <div class="border bg-light p-3 rounded mb-3">
                <label class="form-label">Max Price (per adult)</label>
                <input type="number" id="max_price" min="0" class="form-control shadow-none" oninput="fetch_tours()">
              </div>
              
            </div>
          </div>
        </nav>
      </div>
      
      <!-- Tours Display -->
      <div class="col-lg-9 col-md-12 px-4" id="tours-data">
      </div>
      
    </div>
  </div>
  
  <script>
    function fetch_tours() {
      let destination = document.getElementById('destination').value;
      let duration = document.getElementById('duration').value;
      let max_price = document.getElementById('max_price').value;
      
      let xhr = new XMLHttpRequest();
      xhr.open("GET", `ajax/tours.php?fetch_tours&destination=${destination}&duration=${duration}&max_price=${max_price}`, true);
      
      xhr.onprogress = function(){
        document.getElementById('tours-data').innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>`;
      }
      
      xhr.onload = function(){
        document.getElementById('tours-data').innerHTML = this.responseText;
      }
      
      xhr.send();
    }
    
    window.onload = fetch_tours;
  </script>
  
  <?php require('inc/footer.php'); ?>
  
</body>
</html>