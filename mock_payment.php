<?php
session_start();
if (!isset($_SESSION['mock_order_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mock Payment Gateway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f7f7;
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 600px;
      margin-top: 50px;
    }
    .form-control {
      border-radius: 5px;
      box-shadow: none;
    }
    .btn-submit {
      background-color: #28a745;
      color: white;
      width: 100%;
      padding: 12px;
      border-radius: 5px;
    }
    .btn-submit:hover {
      background-color: #218838;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .form-label {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="container bg-white p-5 rounded shadow">
    <div class="header">
      <h2>Mock Payment Gateway</h2>
      <p>Amount to be paid: $<?php echo $_SESSION['mock_amount']; ?></p>
    </div>

    <form action="pay_response.php" method="post">
      <div class="mb-4">
        <label class="form-label">Card Number</label>
        <input type="text" name="card_number" class="form-control" required>
      </div>

      <div class="mb-4">
        <label class="form-label">Name on Card</label>
        <input type="text" name="card_name" class="form-control" required>
      </div>

      <div class="mb-4">
        <label class="form-label">CVV</label>
        <input type="password" name="cvv" class="form-control" required>
      </div>
 
      <div class="mb-4">
        <label class="form-label">Expiry Date</label>
        <input type="month" name="expiry" class="form-control" required>
      </div>

      <input type="hidden" name="ORDERID" value="<?php echo $_SESSION['mock_order_id']; ?>">
      <input type="hidden" name="TXNAMOUNT" value="<?php echo $_SESSION['mock_amount']; ?>">
      <input type="hidden" name="TXNID" value="<?php echo "TXN" . rand(1000000, 9999999); ?>">
      <input type="hidden" name="STATUS" value="TXN_SUCCESS">
      <input type="hidden" name="RESPMSG" value="Mock Payment Successful">
      <input type="hidden" name="CHECKSUMHASH" value="MOCKCHECKSUM">

      <button type="submit" class="btn-submit">Pay Now</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
