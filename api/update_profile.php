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

            $email = (isset($_REQUEST['email']) ? mysqli_real_escape_string($db, $_REQUEST['email']) : "");
            $nick_name = (isset($_REQUEST['nick_name']) ? mysqli_real_escape_string($db, $_REQUEST['nick_name']) : "");
            $first_name = (isset($_REQUEST['first_name']) ? mysqli_real_escape_string($db, $_REQUEST['first_name']) : "");
            $last_name = (isset($_REQUEST['last_name']) ? mysqli_real_escape_string($db, $_REQUEST['last_name']) : "");
            $dob = (isset($_REQUEST['dob']) ? mysqli_real_escape_string($db, $_REQUEST['dob']) : "");
            $email = (isset($_REQUEST['email']) ? mysqli_real_escape_string($db, $_REQUEST['email']) : "");
            $phone = (isset($_REQUEST['phone']) ? mysqli_real_escape_string($db, $_REQUEST['phone']) : "");
            $mother = (isset($_REQUEST['mother']) ? mysqli_real_escape_string($db, $_REQUEST['mother']) : "");
            $father = (isset($_REQUEST['father']) ? mysqli_real_escape_string($db, $_REQUEST['father']) : "");
            $city = (isset($_REQUEST['city']) ? mysqli_real_escape_string($db, $_REQUEST['city']) : "");
            $state = (isset($_REQUEST['state']) ? mysqli_real_escape_string($db, $_REQUEST['state']) : "");
            $country = (isset($_REQUEST['country']) ? mysqli_real_escape_string($db, $_REQUEST['country']) : "");
            $sport = (isset($_REQUEST['sport']) ? mysqli_real_escape_string($db, $_REQUEST['sport']) : "");
            $started_year = (isset($_REQUEST['started_year']) ? mysqli_real_escape_string($db, $_REQUEST['started_year']) : "");

            $query = $db->query(sprintf("UPDATE `users` SET `email`='" . $email . "', `nick_name`='" . $nick_name . "', `first_name`='" . $first_name . "',
             `last_name`='" . $last_name . "', `dob`='" . $dob . "', `email`='" . $email . "', `phone`='" . $phone . "',
              `mother`='" . $mother . "', `father`='" . $father . "', `city`='" . $city . "', `state`='" . $state . "',
               `country`='" . $country . "', `sport`='" . $sport . "', `started_year`='" . $started_year . "' WHERE `uid` = '$uid'"));

            if ((isset($_REQUEST['password']) && !empty($_REQUEST['password']))) {

                $password = (isset($_REQUEST['password']) ? mysqli_real_escape_string($db, $_REQUEST['password']) : "");

                $db->query(sprintf("UPDATE `users` SET `password`='" . $password . "' WHERE `uid` = '$uid'"));
            }

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