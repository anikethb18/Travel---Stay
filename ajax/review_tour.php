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


    $booking_id = $frm_data['booking_id'];
    $user_id = $_SESSION['uId'];

    // Fetch the package_id from the tour_order table
    $fetch_package_query = "SELECT `package_id` FROM `tour_order`
                            WHERE `booking_id` = ? AND `user_id` = ?";
    $fetch_package_res = mysqli_prepare($con, $fetch_package_query);
    mysqli_stmt_bind_param($fetch_package_res, 'ii', $booking_id, $user_id);
    mysqli_stmt_execute($fetch_package_res);
    $package_result = mysqli_stmt_get_result($fetch_package_res);

    if(mysqli_num_rows($package_result) == 1) {
        $package_data = mysqli_fetch_assoc($package_result);
        $package_id = $package_data['package_id'];

        // Insert the new review
        $ins_query = "INSERT INTO `tour_rating_review`(`tour_booking_id`, `package_id`, `user_id`, `rating`, `review`, `seen`, `datentime`)
                      VALUES (?,?,?,?,?,?,?)";

        $datentime = date("Y-m-d H:i:s");
        $ins_values = [
            $booking_id,
            $package_id,
            $user_id,
            $frm_data['rating'],
            $frm_data['review'],
            0,
            $datentime
        ];

        $ins_result = insert($ins_query, $ins_values, 'iiiissi'); // Updated type string
        echo $ins_result;

    } else {
        echo 0; // Or some other error indication if package_id couldn't be found
    }

    mysqli_stmt_close($fetch_package_res);
}
?>