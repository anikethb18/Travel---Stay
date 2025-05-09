<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if (isset($_POST['add_flight'])) {
    $frm_data = filteration($_POST);

    $q = "INSERT INTO `flights`(`airline`, `departure_city`, `arrival_city`, `departure_time`, `arrival_time`, `travel_id`, `travel_agency`, `status`) VALUES (?,?,?,?,?,?,?,?)";
    $values = [$frm_data['airline'], $frm_data['departure_city'], $frm_data['arrival_city'], $frm_data['departure_time'], $frm_data['arrival_time'], $frm_data['travel_id'], $frm_data['travel_agency'], $frm_data['status']];

    if (insert($q, $values, 'sssssssi')) {
        echo 1; // Success
    } else {
        echo 0; // Failed
    }
}

if (isset($_POST['get_all_flights'])) {
    $res = select("SELECT * FROM `flights` WHERE `removed`=?", [0], 'i');
    $i = 1;
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['status'] == 1) {
            $status = "<button onclick='toggle_status($row[flight_id], 0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
        } else {
            $status = "<button onclick='toggle_status($row[flight_id], 1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
        }

        $data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td>" . htmlspecialchars($row['airline']) . "</td>
                <td>" . htmlspecialchars($row['departure_city']) . "</td>
                <td>" . htmlspecialchars($row['arrival_city']) . "</td>
                <td>" . htmlspecialchars($row['departure_time']) . "</td>
                <td>" . htmlspecialchars($row['arrival_time']) . "</td>
                <td>" . htmlspecialchars($row['travel_id'] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars($row['travel_agency'] ?? 'N/A') . "</td>
                <td>$status</td>
                <td>
                    <button type='button' onclick='edit_details($row[flight_id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-flight'>
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' onclick='remove_flight($row[flight_id])' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['get_flight'])) {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `flights` WHERE `flight_id`=?", [$frm_data['get_flight']], 'i');
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
}

if (isset($_POST['edit_flight'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE `flights` SET `airline`=?,`departure_city`=?,`arrival_city`=?,`departure_time`=?,`arrival_time`=?,`travel_id`=?,`travel_agency`=?,`status`=? WHERE `id`=?";
    $values = [$frm_data['airline'], $frm_data['departure_city'], $frm_data['arrival_city'], $frm_data['departure_time'], $frm_data['arrival_time'], $frm_data['travel_id'], $frm_data['travel_agency'], $frm_data['status'], $frm_data['flight_id']];

    if (update($q, $values, 'ssssssisi')) {
        echo 1; // Success
    } else {
        echo 0; // Failed
    }
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `flights` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];
    if (update($q, $v, 'ii')) {
        echo 1; // Success
    } else {
        echo 0; // Failed
    }
}

if (isset($_POST['remove_flight'])) {
    $frm_data = filteration($_POST);
    $res = update("UPDATE `flights` SET `removed`=? WHERE `flight_id`=?", [1, $frm_data['flight_id']], 'ii');
    echo $res;
}

?>