<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
date_default_timezone_set("America/Chicago");
agencylogin();

$user_id = $_SESSION['agencyId'];
if(isset($_POST['get_flight_bookings']))
{
    $frm_data = filteration($_POST);

    $limit = 100;
    $page = $frm_data['page'];
    $start = ($page-1) * $limit;

    $query = "SELECT fo.*, f.flight_id, uc.name AS user_name, uc.phonenum
              FROM `flight_order` fo
              INNER JOIN `flights` f ON fo.flight_id = f.flight_id
              INNER JOIN `user_cred` uc ON fo.user_id = uc.id
              WHERE (fo.order_id LIKE ? OR uc.phonenum LIKE ? OR uc.name LIKE ?) and  f.travel_id = $user_id

              ORDER BY fo.id DESC";
              

    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');
   
    $limit_query = $query ." LIMIT $start,$limit";
    $limit_res = select($limit_query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');

    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
        $output = json_encode(["table_data"=>"<b>No Flight Booking Data Found!</b>", "pagination"=>'']);
        echo $output;
        exit;
    }

    $i=$start+1;
    $table_data = "";

    while($data = mysqli_fetch_assoc($limit_res))
    {
        $date = date("d-m-Y",strtotime($data['datentime']));

        if($data['booking_status']=='booked'){
            $status_bg = 'bg-success';
        }
        else if($data['booking_status']=='cancelled'){
            $status_bg = 'bg-danger';
        }
        else{
            $status_bg = 'bg-warning text-dark';
        }

        $table_data .="
            <tr>
                <td>$i</td>
                <td>
                    <span class='badge bg-primary'>
                        Order ID: $data[order_id]
                    </span>
                    <br>
                    <b>Name:</b> $data[user_name]
                    <br>
                    <b>Phone No:</b> $data[phonenum]
                </td>
                <td>
                    <b>Flight:</b> $data[flight_id]
                    <br>
                    <b>Tickets:</b> $data[tickets]
                    <br>
                    <b>Total Pay:</b> $$data[total_pay]
                </td>
                <td>
                    <b>Amount:</b> $$data[trans_amount]
                    <br>
                    <b>Date:</b> $date
                </td>
                <td>
                    <b>Travel ID:</b> " . ($data['travel_id'] ?? 'N/A') . "
                    <br>
                    <b>Agency:</b> " . ($data['travel_agency'] ?? 'N/A') . "
                </td>
                <td>
                    <span class='badge $status_bg'>$data[booking_status]</span>
                </td>
                <td>
                    <button type='button' onclick='download_flight_invoice($data[id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                        <i class='bi bi-file-earmark-arrow-down-fill'></i>
                    </button>
                </td>
            </tr>
        ";

        $i++;
    }

    $pagination = "";

    if($total_rows>$limit)
    {
        $total_pages = ceil($total_rows/$limit);

        if($page!=1){
            $pagination .="<li class='page-item'>
                <button onclick='change_flight_page(1)' class='page-link shadow-none'>First</button>
            </li>";
        }

        $disabled = ($page==1) ? "disabled" : "";
        $prev= $page-1;
        $pagination .="<li class='page-item $disabled'>
            <button onclick='change_flight_page($prev)' class='page-link shadow-none'>Prev</button>
        </li>";


        $disabled = ($page==$total_pages) ? "disabled" : "";
        $next = $page+1;
        $pagination .="<li class='page-item $disabled'>
            <button onclick='change_flight_page($next)' class='page-link shadow-none'>Next</button>
        </li>";

        if($page!=$total_pages){
            $pagination .="<li class='page-item'>
                <button onclick='change_flight_page($total_pages)' class='page-link shadow-none'>Last</button>
            </li>";
        }

    }

    $output = json_encode(["table_data"=>$table_data,"pagination"=>$pagination]);

    echo $output;
}

?>