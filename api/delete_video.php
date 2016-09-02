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


    if ((isset($_REQUEST['vid']) && !empty($_REQUEST['vid']))) {

        if ((isset($_REQUEST['uid']) && !empty($_REQUEST['uid']))) {

            $uid = mysqli_real_escape_string($db, $_REQUEST['uid']);
            $vid = mysqli_real_escape_string($db, $_REQUEST['vid']);

            $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

            if ($result->num_rows == 1) {

                $result = $db->query("SELECT * FROM `videos` WHERE `uid`='$uid' AND `id`='$vid'");

                if ($result->num_rows == 1) {

                    $query = $db->query("DELETE FROM `videos` WHERE `uid`='$uid' AND `id`='$vid'");

                    if($query) {
                        $json = array("status" => 1, "response" => "success");
                    }else{
                        $json = array("status" => 0, "response" => "error deleting video");
                    }

                } else {
                    $json = array("status" => 0, "response" => "video not exists or not own");
                }

            } else {
                $json = array("status" => 0, "response" => "user not exists");
            }

        } else {
            $json = array("status" => 0, "response" => "invalid user token");
        }

    } else {
        $json = array("status" => 0, "response" => "invalid video id");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);