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

        $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

        if ($result->num_rows == 1) {


            $result = $db->query("SELECT * FROM `shares` WHERE `owner_id`='$uid'");

            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $line = array();
                $line['video_id'] = $row['vid'];
                $line['video_title'] = $row['video_title'];
                $line['shared_by_id'] = $row['shared_uid'];
                $line['shared_by_uname'] = $row['shared_name'];
                $line['shared_date'] = $row['date'];

                $rows[] = $line;

            }
            $json = array("status" => 1, "activities" => $rows);


        } else {
            $json = array("status" => 0, "response" => "user not exists");
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