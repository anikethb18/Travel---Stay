<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
agencylogin();
$user_id = $_SESSION['agencyId'];

if (isset($_POST['add_tour'])) {
    $frm_data = filteration($_POST);
    
    $q = "INSERT INTO `tour_packages`(`name`, `destination`, `duration`, `price_per_adult`, `description`, `travel_id`, `travel_agency`, `status`) VALUES (?,?,?,?,?,?,?,?)";
    $values = [$frm_data['tour_name'], $frm_data['destination'], $frm_data['duration'],(double) $frm_data['price'], $frm_data['desc'], $user_id, $_SESSION['agencyName'], $frm_data['status']];
    if (insert($q, $values, 'sssdsisi')) {
        echo 1; // Success
        
    } else{
        echo 0;
    }
}

if (isset($_POST['get_all_tours'])) {
    $res = select("SELECT * FROM `tour_packages` WHERE `removed`=? and travel_id = $user_id", [0], 'i');
    $i = 1;
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['status'] == 1) {
            $status = "<button onclick='toggle_status($row[package_id], 0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
        } else {
            $status = "<button onclick='toggle_status($row[package_id], 1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
        }

        $data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['destination']) . "</td>
                <td>" . htmlspecialchars($row['duration'] ?? 'N/A') . "</td>
                <td>$" . htmlspecialchars($row['price_per_adult'] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars(substr($row['description'], 0, 100)) . "...</td>
                
                <td>$status</td>
                <td>
                    <button type='button' onclick='edit_details($row[package_id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-tour'>
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' onclick='remove_tour($row[package_id])' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['get_tour'])) {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `tour_packages` WHERE `package_id`=? and travel_id = $user_id", [$frm_data['get_tour']], 'i');
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
}

if (isset($_POST['edit_tour'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE `tour_packages` SET `name`=?,`destination`=?,`duration`=?,`price`=?,`description`=?,`travel_id`=?,`travel_agency`=?,`status`=? WHERE `package_id`=?";
    $values = [$frm_data['name'], $frm_data['destination'], $frm_data['duration'], $frm_data['travel_id'], $frm_data['desc'], $frm_data['travel_id'], $frm_data['travel_agency'], $frm_data['status'], $frm_data['tour_id']];

    if (update($q, $values, 'sssdsisi')) {
        echo 1; // Success
    } else {
        echo 0; // Failed
    }
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `tour_packages` SET `status`=? WHERE `package_id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];
    if (update($q, $v, 'ii')) {
        echo 1; // Success
    } else {
        echo 0; // Failed
    }
}

if (isset($_POST['remove_tour'])) {
    $frm_data = filteration($_POST);
    $res = update("UPDATE `tour_packages` SET `removed`=? WHERE `package_id`=?", [1, $frm_data['tour_id']], 'ii');
    echo $res;
}

?>