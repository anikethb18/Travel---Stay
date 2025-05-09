<?php
    require('inc/essentials.php');
    require('inc/db_config.php');

    session_start();
    if((isset($_SESSION['agencyLogin']) && $_SESSION['agencyLogin']==true)){
        redirect('dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agency Login Panel</title>
    <?php require('inc/links.php'); ?>
    <style>
        div.login-form{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            width: 400px;
        }
    </style>
</head>
<body class="bg-light">

    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="POST">
            <h4 class="bg-dark text-white py-3">TRAVEL AGENCY LOGIN</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="agency_name" required type="text" class="form-control shadow-none text-center" placeholder="Agency Name">
                </div>
                <div class="mb-4">
                    <input name="agency_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <button name="login" type="submit" class="btn text-white custom-bg shadow-none">LOGIN</button>
            </div>
        </form>
    </div>


    <?php

        if(isset($_POST['login']))
        {
            $frm_data = filteration($_POST);

            $query = "SELECT * FROM  `travel_agency_cred` WHERE `agency_name`=? AND `agency_pass`=?";
            $values = [$frm_data['agency_name'],$frm_data['agency_pass']];

            $res = select($query,$values,"ss");
            if($res->num_rows==1){
                $row = mysqli_fetch_assoc($res);
                $_SESSION['agencyLogin'] = true;
                $_SESSION['agencyId'] = $row['id']; // Changed to 'id'
                $_SESSION['agencyName'] = $row['agency_name']; // Store agency name if needed
                redirect('dashboard.php'); // Redirect to agency dashboard
            }
            else{
                alert('error','Login failed - Invalid Credentials!');
            }
        }

    ?>


    <?php require('inc/scripts.php') ?>
</body>
</html>