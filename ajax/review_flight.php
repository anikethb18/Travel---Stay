<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("America/Chicago");
session_start();

if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
}

if(isset($_POST['review_form']))
{
    $frm_data = filteration($_POST);


    // Insert the new review
    $ins_query = "INSERT INTO `flight_rating_review`(`flight_id`, `user_id`, `rating`, `review`, `seen`, `datentime`)
                  VALUES (?,?,?,?,?,?)"; // Corrected: 6 placeholders

    $datentime = date("Y-m-d H:i:s");
    $ins_values = [
        $frm_data['booking_id'], // Corrected: Using the likely correct key
        $_SESSION['uId'],
        $frm_data['rating'],
        $frm_data['review'],
        0,
        $datentime
    ];

    $ins_result = insert($ins_query,$ins_values,'iiissi'); // Corrected: 6 type specifiers

}
?>