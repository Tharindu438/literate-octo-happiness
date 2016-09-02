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

            $favorite1 = (isset($_REQUEST['favorite1']) ? mysqli_real_escape_string($db, $_REQUEST['favorite1']) : "");
            $favorite2 = (isset($_REQUEST['favorite2']) ? mysqli_real_escape_string($db, $_REQUEST['favorite2']) : "");
            $favorite3 = (isset($_REQUEST['favorite3']) ? mysqli_real_escape_string($db, $_REQUEST['favorite3']) : "");

            $query = $db->query(sprintf("UPDATE `users` SET `favorite1`='".$favorite1."', `favorite2`='".$favorite2."', `favorite3`='".$favorite3."'"));

                if ($query) {
                    $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

                    $data = $result->fetch_assoc();

                    $token = $data['uid'];

                    $json = array("status" => 1, "uid" => $token, "user" => $data);

                } else {
                    $json = array("status" => 0, "response" => "server error");
                }

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