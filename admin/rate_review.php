<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

if(isset($_GET['seen']))
{
    $frm_data = filteration($_GET);

    if($frm_data['seen']=='all'){
        $q_room = "UPDATE `rating_review` SET `seen`=?";
        $q_tour = "UPDATE `tour_rating_review` SET `seen`=?";
        $q_flight = "UPDATE `flight_rating_review` SET `seen`=?";
        $values = [1];
        if(update($q_room,$values,'i') && update($q_tour,$values,'i') && update($q_flight,$values,'i')){
            alert('success','Marked all as read!');
        }
        else{
            alert('error','Operation Failed!');
        }
    }
    else{
        $parts = explode('-', $frm_data['seen']);
        $id = $parts[1];
        if (strpos($frm_data['seen'], 'room') === 0) {
            $q = "UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=?";
            $values = [1, $id];
        } elseif (strpos($frm_data['seen'], 'tour') === 0) {
            $q = "UPDATE `tour_rating_review` SET `seen`=? WHERE `sr_no`=?";
            $values = [1, $id];
        } elseif (strpos($frm_data['seen'], 'flight') === 0) {
            $q = "UPDATE `flight_rating_review` SET `seen`=? WHERE `sr_no`=?";
            $values = [1, $id];
        }

        if(update($q,$values,'ii')){
            alert('success','Marked as read!');
        }
        else{
            alert('error','Operation Failed!');
        }
    }
}

if(isset($_GET['del']))
{
    $frm_data = filteration($_GET);

    if($frm_data['del']=='all'){
        $q_room = "DELETE FROM `rating_review`";
        $q_tour = "DELETE FROM `tour_rating_review`";
        $q_flight = "DELETE FROM `flight_rating_review`";
        if(mysqli_query($con,$q_room) && mysqli_query($con,$q_tour) && mysqli_query($con,$q_flight)){
            alert('success','All data deleted!');
        }
        else{
            alert('error','Operation failed!');
        }
    }
    else{
        $parts = explode('-', $frm_data['del']);
        $id = $parts[1];
        if (strpos($frm_data['del'], 'room') === 0) {
            $q = "DELETE FROM `rating_review` WHERE `sr_no`=?";
            $values = [$id];
        } elseif (strpos($frm_data['del'], 'tour') === 0) {
            $q = "DELETE FROM `tour_rating_review` WHERE `sr_no`=?";
            $values = [$id];
        } elseif (strpos($frm_data['del'], 'flight') === 0) {
            $q = "DELETE FROM `flight_rating_review` WHERE `sr_no`=?";
            $values = [$id];
        }
        if(delete($q,$values,'i')){
            alert('success','Data deleted!');
        }
        else{
            alert('error','Operation failed!');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ratings & Reviews</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">RATINGS & REVIEWS</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check-all"></i> Mark all read
                            </a>
                            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                                <i class="bi bi-trash"></i> Delete all
                            </a>
                        </div>

                        <div class="table-responsive-md">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Rating</th>
                                        <th scope="col" width="30%">Review</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Travel Info</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Query for Room Reviews
                                        $q_room = "SELECT rr.*, uc.name AS uname, r.name AS rname
                                                  FROM `rating_review` rr
                                                  INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                                                  INNER JOIN `rooms` r ON rr.room_id = r.id
                                                  ORDER BY `sr_no` DESC";
                                        $data_room = mysqli_query($con,$q_room);
                                        $i=1;
                                        while($row_room = mysqli_fetch_assoc($data_room))
                                        {
                                            $date = date('d-m-Y',strtotime($row_room['datentime']));
                                            $seen_link = "<a href='?seen=room-$row_room[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2'>Mark as read</a> <br>";
                                            $delete_link = "<a href='?del=room-$row_room[sr_no]' class='btn btn-sm rounded-pill btn-danger'>Delete</a>";
                                            $seen = ($row_room['seen']!=1) ? $seen_link . $delete_link : $delete_link;

                                            echo<<<query
                                                <tr>
                                                    <td>$i</td>
                                                    <td>Room</td>
                                                    <td>$row_room[rname]</td>
                                                    <td>$row_room[uname]</td>
                                                    <td>$row_room[rating]</td>
                                                    <td>$row_room[review]</td>
                                                    <td>$date</td>
                                                    <td></td>
                                                    <td>$seen</td>
                                                </tr>
                                            query;
                                            $i++;
                                        }

                                        // Query for Tour Reviews
                                        $q_tour = "SELECT trr.*, uc.name AS uname, t.name AS tname, bo.travel_id, bo.travel_agency
                                                  FROM `tour_rating_review` trr
                                                  INNER JOIN `user_cred` uc ON trr.user_id = uc.id
                                                  INNER JOIN `tour_packages` t ON trr.package_id = t.package_id
                                                  LEFT JOIN `booking_order` bo ON trr.tour_booking_id = bo.booking_id
                                                  ORDER BY `sr_no` DESC";
                                        $data_tour = mysqli_query($con,$q_tour);
                                        while($row_tour = mysqli_fetch_assoc($data_tour))
                                        {
                                            $date = date('d-m-Y',strtotime($row_tour['datentime']));
                                            $seen_link = "<a href='?seen=tour-$row_tour[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2'>Mark as read</a> <br>";
                                            $delete_link = "<a href='?del=tour-$row_tour[sr_no]' class='btn btn-sm rounded-pill btn-danger'>Delete</a>";
                                            $seen = ($row_tour['seen']!=1) ? $seen_link . $delete_link : $delete_link;
                                            $travel_info = ($row_tour['travel_id'] && $row_tour['travel_agency']) ? "<b>ID:</b> {$row_tour['travel_id']}<br><b>Agency:</b> {$row_tour['travel_agency']}" : '';

                                            echo<<<query
                                                <tr>
                                                    <td>$i</td>
                                                    <td>Tour</td>
                                                    <td>$row_tour[tname]</td>
                                                    <td>$row_tour[uname]</td>
                                                    <td>$row_tour[rating]</td>
                                                    <td>$row_tour[review]</td>
                                                    <td>$date</td>
                                                    <td>$travel_info</td>
                                                    <td>$seen</td>
                                                </tr>
                                            query;
                                            $i++;
                                        }

                                        // Query for Flight Reviews
                                        $q_flight = "SELECT frr.*, uc.name AS uname, f.flight_id AS fname, bo.travel_id, bo.travel_agency
                                                    FROM `flight_rating_review` frr
                                                    INNER JOIN `user_cred` uc ON frr.user_id = uc.id
                                                    INNER JOIN `flights` f ON frr.flight_id = f.flight_id
                                                    LEFT JOIN `booking_order` bo ON frr.flight_booking_id = bo.booking_id
                                                    ORDER BY `sr_no` DESC";
                                        $data_flight = mysqli_query($con,$q_flight);
                                        while($row_flight = mysqli_fetch_assoc($data_flight))
                                        {
                                            $date = date('d-m-Y',strtotime($row_flight['datentime']));
                                            $seen_link = "<a href='?seen=flight-$row_flight[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2'>Mark as read</a> <br>";
                                            $delete_link = "<a href='?del=flight-$row_flight[sr_no]' class='btn btn-sm rounded-pill btn-danger'>Delete</a>";
                                            $seen = ($row_flight['seen']!=1) ? $seen_link . $delete_link : $delete_link;
                                            $travel_info = ($row_flight['travel_id'] && $row_flight['travel_agency']) ? "<b>ID:</b> {$row_flight['travel_id']}<br><b>Agency:</b> {$row_flight['travel_agency']}" : '';

                                            echo<<<query
                                                <tr>
                                                    <td>$i</td>
                                                    <td>Flight</td>
                                                    <td>$row_flight[fname]</td>
                                                    <td>$row_flight[uname]</td>
                                                    <td>$row_flight[rating]</td>
                                                    <td>$row_flight[review]</td>
                                                    <td>$date</td>
                                                    <td>$travel_info</td>
                                                    <td>$seen</td>
                                                </tr>
                                            query;
                                            $i++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>

</body>
</html>