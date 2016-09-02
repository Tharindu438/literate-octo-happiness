<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 7/25/2016
 * Time: 2:50 PM
 */
require('db_connect.php');
require('auth_token.php');

if (!empty($_REQUEST['key']) && ($_REQUEST['key'] == APIKEY)) {


    if ((isset($_REQUEST['uid']) && !empty($_REQUEST['uid']))) {

        $uid = mysqli_real_escape_string($db, $_REQUEST['uid']);
        $user = mysqli_real_escape_string($db, $_REQUEST['user']);

        $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

        if ($result->num_rows == 1) {

            $result = $db->query("SELECT `uid`, `login_type`, `username`, `fb_id`, `nick_name`, `first_name`, `last_name`, `dob`, `email`, `phone`, `mother`, `father`, `city`, `state`, `country`, `sport`, `started_year`, `registered_date`, `favorite1`, `favorite2`, `favorite3` FROM `users` WHERE `uid` = '$user'");
            if ($result->num_rows == 1) {

                $data = $result->fetch_assoc();

                $json = array("status" => 1, "user" => $data);


            } else {
                $json = array("status" => 0, "response" => "user id exists");
            }

        } else {
            $json = array("status" => 0, "response" => "invalid user token");
        }

    } else {
        $json = array("status" => 0, "response" => "invalid user token");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);