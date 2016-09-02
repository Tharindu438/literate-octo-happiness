<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 6/11/2016
 * Time: 7:25 PM
 */
require('db_connect.php');
require('auth_token.php');

if (!empty($_REQUEST['key']) && ($_REQUEST['key'] == APIKEY)) {

    if ((isset($_REQUEST['type']) && ($_REQUEST['type'] == 0) && isset($_REQUEST['username']) && isset($_REQUEST['password']) && !empty($_REQUEST['username']) && !empty($_REQUEST['password']))
        || (isset($_REQUEST['type']) && ($_REQUEST['type'] == 1) && isset($_REQUEST['fb_id']) && !empty($_REQUEST['fb_id']))
    ) {

        if ($_REQUEST['type'] == 0) {

            $username = mysqli_real_escape_string($db, $_REQUEST['username']);
            $password = mysqli_real_escape_string($db, $_REQUEST['password']);
           // $password = encrypt($password);

            $result = $db->query("SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'");

            if ($result->num_rows == 1) {

                $data = $result->fetch_assoc();

                $token = $data['uid'];

                $json = array("status" => 1, "uid" => $token, "user" => $data);

            } else {
                $json = array("status" => 0, "response" => "invalid credentials");
            }
        }else{

            $fb_id = mysqli_real_escape_string($db, $_REQUEST['fb_id']);

            $result = $db->query("SELECT * FROM `users` WHERE `fb_id` = '$fb_id'");

            if ($result->num_rows == 1) {

                $data = $result->fetch_array();

                $token = encrypt($data['uid']);

                $json = array("status" => 1, "token" => $token, "user" => $data);

            } else {
                $json = array("status" => 0, "response" => "invalid facebook id");
            }
        }

    } else {
        $json = array("status" => 0, "response" => "invalid login information");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);
