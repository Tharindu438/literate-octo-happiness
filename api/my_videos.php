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

            $result = $db->query("SELECT * FROM `videos` WHERE `uid`='$uid'");

            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $line = array();
                $line['id'] = $row['id'];
                $line['video'] = $row['video'];
                $line['uid'] = $row['uid'];
                $line['category'] = $row['category'];
                $line['title'] = $row['title'];
                $line['specialty'] = $row['specialty'];
                $line['eventtype'] = $row['eventtype'];
                $line['organizer'] = $row['organizer'];
                $line['place'] = $row['place'];
                $line['date'] = $row['date'];
                $line['thumb'] = $row['thumb'];

                $rows[] = $line;

            }
            $json = array("status" => 1, "videos" => $rows);

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
