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


    if ((isset($_REQUEST['type']) && ($_REQUEST['type'] == 0) && isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['email']) && !empty($_REQUEST['username']) && !empty($_REQUEST['password']) && !empty($_REQUEST['email']))
        || (isset($_REQUEST['type']) && ($_REQUEST['type'] == 1) && isset($_REQUEST['fb_id']) && !empty($_REQUEST['fb_id']))
    ) {

        $type = mysqli_real_escape_string($db, $_REQUEST['type']);
        $email = mysqli_real_escape_string($db, $_REQUEST['email']);

        if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
            $username = mysqli_real_escape_string($db, $_REQUEST['username']);
            $password = mysqli_real_escape_string($db, $_REQUEST['password']);

            $password = encrypt($password);

        } else {
            $username = "";
            $password = "";
        }

        if (isset($_REQUEST['fb_id'])) {
            $fb_id = mysqli_real_escape_string($db, $_REQUEST['fb_id']);
        }

        $result1 = $db->query("SELECT * FROM `users` WHERE `username` = '$username'");

        if ((isset($_REQUEST['username']) && $result1->num_rows == 0) || isset($_REQUEST['fb_id'])) {

            $result2 = $db->query("SELECT `uid` FROM `users` WHERE `fb_id` = '$fb_id'");
            if ((isset($_REQUEST['fb_id']) && $result2->num_rows == 0) || isset($_REQUEST['username'])) {


                $query = $db->query("INSERT INTO `users`(`login_type`, `username`, `password`, `fb_id`, `email`) VALUES ('$type', '$username', '$password', '$fb_id', '$email')");

                if ($query) {
                    $json = array("status" => 1, "response" => "success");
                } else {
                    $json = array("status" => 0, "response" => "server error");
                }

            } else {
                $json = array("status" => 0, "response" => "facebook id exists");
            }
        } else {
            $json = array("status" => 0, "response" => "username exists");
        }

    } else {
        $json = array("status" => 0, "response" => "not enough user information");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);
