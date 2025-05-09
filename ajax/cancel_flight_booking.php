<?php 
  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');
  
  date_default_timezone_set("America/Chicago");


  if(isset($_POST['cancel_booking']))
  {
    $frm_data = filteration($_POST);
    
    $query = "UPDATE `flight_order` SET `booking_status`=?, `refund`=? WHERE `id`=?";
    $values = ['cancelled',0,$frm_data['id']];
    $res = update($query,$values,'sii');
    
    echo $res;
  }
?>